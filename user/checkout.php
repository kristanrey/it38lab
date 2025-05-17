<?php
session_start();
include("../includes/db_connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $quantity = intval($_POST['quantity']);
    $address = trim($_POST['address']);
    $payment_method = $_POST['payment_method'];
    $status = 'Pending';  // default status
    $created_at = date('Y-m-d H:i:s');

    // Validate quantity positive
    if ($quantity <= 0) {
        echo "Invalid quantity.";
        exit();
    }

    // Get item price, name, and current stock
    $stmt = $conn->prepare("SELECT price, name, quantity FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->bind_result($price, $item_name, $stock);
    $stmt->fetch();
    $stmt->close();

    if ($price === null) {
        echo "Error: Item not found.";
        exit();
    }

    // Check if enough stock
    if ($quantity > $stock) {
        echo "Error: Not enough stock available. Current stock: " . $stock;
        exit();
    }

    $total_price = $price * $quantity;

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, item_id, quantity, total, address, status, created_at, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidssss", $user_id, $item_id, $quantity, $total_price, $address, $status, $created_at, $payment_method);

        if (!$stmt->execute()) {
            throw new Exception("Error placing order: " . $stmt->error);
        }
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert into shipments table
        $stmt2 = $conn->prepare("INSERT INTO shipments (order_id, name, quantity, price, address, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("isidss", $order_id, $item_name, $quantity, $total_price, $address, $created_at);

        if (!$stmt2->execute()) {
            throw new Exception("Error creating shipment: " . $stmt2->error);
        }
        $stmt2->close();

        // Update inventory stock
        $new_stock = $stock - $quantity;
        $stmt3 = $conn->prepare("UPDATE inventory SET quantity = ? WHERE id = ?");
        $stmt3->bind_param("ii", $new_stock, $item_id);

        if (!$stmt3->execute()) {
            throw new Exception("Error updating stock: " . $stmt3->error);
        }
        $stmt3->close();

        // Commit transaction
        $conn->commit();

        // Redirect to confirmation page
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
        exit();
    }
}

// GET request - show checkout form

$item_id = $_GET['item_id'] ?? null;
$quantity = $_GET['quantity'] ?? 1;

if (!$item_id) {
    die("No item selected.");
}

$sql = "SELECT * FROM inventory WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item_result = $stmt->get_result();

if ($item_result->num_rows === 0) {
    die("Item not found.");
}

$item = $item_result->fetch_assoc();
$total_price = $item['price'] * $quantity;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Checkout - ShoeShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0; padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .checkout-container {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        p {
            margin: 5px 0;
            font-size: 16px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #444;
        }
        textarea, select, input[type=number] {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            resize: vertical;
        }
        button {
            margin-top: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout Details</h2>
    <p><strong>Item:</strong> <?= htmlspecialchars($item['name']) ?></p>
    <p><strong>Quantity:</strong> <?= (int)$quantity ?></p>
    <p><strong>Total Price:</strong> ₱<?= number_format($total_price, 2) ?></p>

    <form action="checkout.php" method="POST">
        <input type="hidden" name="item_id" value="<?= (int)$item['id'] ?>" />
        <input type="hidden" name="quantity" value="<?= (int)$quantity ?>" />

        <label for="address">Shipping Address</label>
        <textarea name="address" id="address" required></textarea>

        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">Bank Transfer</option>
        </select>

        <button type="submit">Confirm Order</button>
    </form>
</div>

</body>
</html>
