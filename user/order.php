<?php
session_start();
require_once '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle order deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $delete_order_id = (int)$_POST['delete_order_id'];

    // Verify the order belongs to the logged-in user
    $stmt_check = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $stmt_check->bind_param("ii", $delete_order_id, $user_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_delete = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt_delete->bind_param("i", $delete_order_id);
        $stmt_delete->execute();
        $stmt_delete->close();
    }
    $stmt_check->close();

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch orders for the logged-in user
$orderResult = $conn->prepare("SELECT 
        o.id AS order_id,
        i.name AS item_name,
        o.quantity,
        (o.quantity * i.price) AS total_amount,
        o.status,
        o.created_at
    FROM orders o
    JOIN inventory i ON o.item_id = i.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC");

$orderResult->bind_param("i", $user_id);
$orderResult->execute();
$result = $orderResult->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <style>
        body { font-family: Arial; background-color: #f5f5f5; margin: 0; padding: 20px; }
        h2 { text-align: center; color: #444; }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        thead {
            background: linear-gradient(to right, #e3b4ff, #b966ff);
            color: white;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 8px;
            display: inline-block;
        }
        .status.Completed { background-color: #c8f7c5; color: green; }
        .status.Pending { background-color: #fff3cd; color: #856404; }
        .status.Cancelled { background-color: #f8d7da; color: #721c24; }
        /* Delete button styling */
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        form.delete-form {
            margin: 0;
        }
        td.action-cell {
            width: 100px;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>My Orders</h2>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Date Ordered</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= htmlspecialchars($order['item_name']) ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td>$<?= number_format($order['total_amount'], 2) ?></td>
                    <td><span class="status <?= $order['status'] ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                    <td><?= date("F j, Y, g:i a", strtotime($order['created_at'])) ?></td>
                    <td class="action-cell">
                        <form method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this order?');">
                            <input type="hidden" name="delete_order_id" value="<?= (int)$order['order_id'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align: center;">No orders found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
