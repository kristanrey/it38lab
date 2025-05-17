<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $address = trim($_POST['address']);
    $payment_method = $_POST['payment_method'];
    $status = 'pending'; // Default status
    $created_at = date("Y-m-d H:i:s");

    // Get item price
    $stmt = $conn->prepare("SELECT price FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    $total = $price * $quantity;

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, item_id, quantity, total, address, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiidsss", $user_id, $item_id, $quantity, $total, $address, $status, $created_at);

    if ($stmt->execute()) {
        header("Location: order_success.php");
        exit();
    } else {
        echo "Error placing order: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
