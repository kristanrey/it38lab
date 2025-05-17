<?php
session_start();
require_once '../includes/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the order form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $address = trim($_POST['address']);
    $payment_method = trim($_POST['payment_method']);

    // Get item price from inventory
    $stmt = $conn->prepare("SELECT price FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->bind_result($price);
    if ($stmt->fetch()) {
        $total = $price * $quantity;
        $stmt->close();

        // Insert order into `orders` table
        $status = "pending";
        $insert = $conn->prepare("INSERT INTO orders (user_id, item_id, quantity, total, address, payment_method, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $insert->bind_param("iiissss", $user_id, $item_id, $quantity, $total, $address, $payment_method, $status);
        if ($insert->execute()) {
            // Redirect to user's order history
            header("Location: order.php?success=1");
            exit();
        } else {
            echo "Error: Could not place order.";
        }
    } else {
        echo "Invalid item selected.";
    }
} else {
    echo "Invalid request method.";
}
?>
