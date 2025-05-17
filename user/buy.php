<?php
session_start();
include("../includes/db_connection.php");

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get item_id and quantity from the POST request
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // Fetch the item from the inventory
    $sql = "SELECT * FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    // Check if the item exists and if there's enough stock
    if ($item && $item['quantity'] >= $quantity) {
        // Calculate the total price
        $total = $item['price'] * $quantity;

        // Insert the order into the orders table
        $sql = "INSERT INTO orders (customer_name, item_id, quantity, total, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siidi', $_SESSION['username'], $item_id, $quantity, $total, $user_id);
        $stmt->execute();

        // Reduce the quantity of the item in the inventory
        $new_quantity = $item['quantity'] - $quantity;
        $sql = "UPDATE inventory SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $new_quantity, $item_id);
        $stmt->execute();

        // Redirect to the order confirmation page
        header("Location: order_tracking.php");
        exit();
    } else {
        echo "Not enough stock available.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Item</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f2f2f2; }
        header { background: #333; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 24px; }
        .nav-icons a { color: white; margin-left: 15px; text-decoration: none; }
        .container { padding: 20px; }
        .greeting { font-size: 18px; margin-bottom: 15px; }
        .form-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 300px; margin: 0 auto; }
        .form-container input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 4px; border: 1px solid #ddd; }
        .form-container button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        footer { margin-top: 30px; text-align: center; padding: 15px; background: #333; color: white; }
    </style>
</head>
<body>

<header>
    <div class="brand">ShoeShop</div>
    <div class="nav-icons">
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <div class="greeting">Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👟</div>

    <!-- Buy item form -->
    <?php
    if (isset($_GET['id'])) {
        $item_id = $_GET['id'];

        // Fetch the item details
        $sql = "SELECT * FROM inventory WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $item_id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();

        if ($item) {
            ?>
            <div class="form-container">
                <h2>Buy <?= htmlspecialchars($item['name']) ?></h2>
                <img src="<?= !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : 'https://via.placeholder.com/300x200?text=No+Image' ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 100%; height: auto;">
                <p>Price: ₱<?= number_format($item['price'], 2) ?></p>
                <p>Stock: <?= $item['quantity'] ?> pcs</p>

                <!-- Form to handle purchase -->
                <form method="POST" action="buy.php">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" min="1" max="<?= $item['quantity'] ?>" required>
                    <button type="submit">Buy</button>
                </form>
            </div>
            <?php
        } else {
            echo "Item not found.";
        }
    } else {
        echo "No item selected.";
    }
    ?>
</div>

<footer>
    &copy; <?= date('Y') ?> ShoeShop. All rights reserved.
</footer>

</body>
</html>
