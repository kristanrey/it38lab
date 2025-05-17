<?php
session_start();
include("../includes/db_connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM inventory";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shoe Shop - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f2f2f2; }
        header { background: #333; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 24px; }
        .nav-icons a { color: white; margin-left: 15px; text-decoration: none; }
        .nav-icons .orders-btn { background-color: #28a745; padding: 10px 15px; border-radius: 5px; color: white; text-decoration: none; }
        .container { padding: 20px; }
        .greeting { font-size: 18px; margin-bottom: 15px; }
        .product-grid { display: flex; flex-wrap: wrap; gap: 20px; }
        .card { background: white; border-radius: 8px; width: 250px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); overflow: hidden; }
        .card img { width: 100%; height: 200px; object-fit: cover; }
        .card-body { padding: 15px; }
        .card-title { font-size: 18px; font-weight: bold; }
        .price, .stock { margin-top: 5px; }
        .buy-btn { margin-top: 10px; display: inline-block; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        footer { margin-top: 30px; text-align: center; padding: 15px; background: #333; color: white; }
    </style>
</head>
<body>

<header>
    <div class="brand">Welcome</div>
    <div class="nav-icons">
        <a href="logout.php">Logout</a>
        <a href="orders.php" class="orders-btn">View My Orders</a>
    </div>
</header>

<div class="container">
    <div class="greeting">Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👟</div>

    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($item['image_url'] ?: 'https://via.placeholder.com/300x200?text=No+Image') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="card-body">
                        <div class="card-title"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="price">₱<?= number_format($item['price'], 2) ?></div>
                        <div class="stock">Stock: <?= $item['quantity'] ?> pcs</div>
                        <form action="checkout.php" method="GET">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" min="1" max="<?= $item['quantity'] ?>" required>
                            <button type="submit" class="buy-btn">Buy</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No items found in inventory.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> ShoeShop. All rights reserved.
</footer>

</body>
</html>
