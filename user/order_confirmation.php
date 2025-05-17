<?php
session_start();

// Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("../includes/db_connection.php");

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's orders sorted by newest first
$sql = "SELECT o.*, i.name AS item_name 
        FROM orders o
        JOIN inventory i ON o.item_id = i.id
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f2f2f2; }
        header { background: #333; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 24px; }
        .nav-icons a { color: white; margin-left: 15px; text-decoration: none; }
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
        td.date { text-align: center; }
        .back-btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; display: inline-block; }
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
    <h2>My Orders (Newest First)</h2>
    <a href="home.php" class="back-btn">← Back to Home</a>
    <hr>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['item_name']); ?></td>
                    <td><?= (int)$row['quantity']; ?></td>
                    <td>₱<?= number_format($row['total'], 2); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td class="date"><?= date('F j, Y, g:i a', strtotime($row['created_at'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No orders yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
