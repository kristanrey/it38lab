<?php
session_start();
require_once '../includes/db_connection.php';

// Fetch inventory
$inventoryResult = $conn->query("SELECT * FROM inventory");

// Fetch orders (highest order_id first)
$orderResult = $conn->query("
    SELECT 
        o.id AS order_id,
        CONCAT(u.firstname, ' ', u.lastname) AS customer_name,
        i.name AS item_name,
        o.quantity,
        (o.quantity * i.price) AS total_amount,  
        o.status
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN inventory i ON o.item_id = i.id
    ORDER BY o.id DESC
");

// Sales Reports
$salesResult = $conn->query("
    SELECT 
        SUM(o.quantity * i.price) AS total_sales,
        SUM(o.quantity) AS total_items_sold,
        COUNT(CASE WHEN o.status = 'Cancelled' THEN 1 END) AS total_cancelled_orders
    FROM orders o
    JOIN inventory i ON o.item_id = i.id
    WHERE o.status = 'Completed'
");
$salesData = $salesResult->fetch_assoc() ?? [];

// Monthly Sales
$monthlySalesResult = $conn->query("
    SELECT 
        MONTH(o.created_at) AS month,  
        SUM(o.quantity * i.price) AS monthly_sales
    FROM orders o
    JOIN inventory i ON o.item_id = i.id
    WHERE YEAR(o.created_at) = YEAR(CURDATE())  
        AND o.status = 'Completed'
    GROUP BY MONTH(o.created_at)
");

// Yearly Sales
$yearlySalesResult = $conn->query("
    SELECT 
        YEAR(o.created_at) AS year,  
        SUM(o.quantity * i.price) AS yearly_sales
    FROM orders o
    JOIN inventory i ON o.item_id = i.id
    WHERE o.status = 'Completed'
    GROUP BY YEAR(o.created_at)
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management</title>
    <style>
        body { font-family: Arial; background-color: #f5f5f5; margin: 0; }
        .sidebar { width: 220px; height: 100vh; float: left; background: #f5f5f5; box-shadow: 2px 0 5px rgba(0,0,0,0.1); padding-top: 20px; }
        .sidebar a { display: block; padding: 15px; margin: 10px auto; width: 80%; background: linear-gradient(to right, #e3b4ff, #b966ff); color: white; text-align: center; border-radius: 25px; text-decoration: none; }
        .sidebar a:hover { background: linear-gradient(to right, #d084ff, #9a3cff); }
        .sidebar-logo { width: 70px; margin: 0 auto 10px auto; display: block; }

        .content { margin-left: 220px; padding: 20px; background-color: #FAC99B; min-height: 100vh; }
        .header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 30px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header-text { font-size: 20px; font-weight: bold; color: #333; text-align: center; flex: 1; margin-right: 50px; }
        .icon-container img { width: 25px; margin-left: 10px; cursor: pointer; }

        table { width: 100%; background: white; border-collapse: collapse; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        th, td { padding: 12px 15px; text-align: left; }
        thead { background: linear-gradient(to right, #e3b4ff, #b966ff); color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        img { max-width: 100px; }
        .status-available { color: green; font-weight: bold; }
        .status-out { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="asset/logo.png" alt="Logo" class="sidebar-logo">
    <a href="dashboard.php">Home</a>
    <a href="shipments.php">Shipments</a>
    <a href="inventory.php">Inventory</a>
</div>

<div class="content">
    <div class="header">
        <div class="header-text">INVENTORY MANAGEMENT</div>
        <div class="icon-container">
            <img src="asset/icon-email.png" alt="Messages">
            <img src="asset/icon-bell.png" alt="Notifications">
            <a href="index.php"><img src="asset/icon-logout.png" alt="Logout"></a>
        </div>
    </div>

    <h3>Inventory List</h3>
    <table>
        <thead>
            <tr>
                <th>Item Image</th>
                <th>ID</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Stock</th>
                <th>Availability</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $inventoryResult->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_url'] ?? '') ?>" alt="Item Image"></td>
                    <td><?= $row['id'] ?? '' ?></td>
                    <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                    <td>$<?= number_format($row['price'] ?? 0, 2) ?></td>
                    <td><?= htmlspecialchars($row['description'] ?? '') ?></td>
                    <td><?= $row['quantity'] ?? 0 ?></td>
                    <td>
                        <?php if (($row['quantity'] ?? 0) > 0): ?>
                            <span class="status-available">Available</span>
                        <?php else: ?>
                            <span class="status-out">Out of Stock</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Order List</h3>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orderResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $order['order_id'] ?? '' ?></td>
                    <td><?= htmlspecialchars($order['customer_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($order['item_name'] ?? '') ?></td>
                    <td><?= $order['quantity'] ?? 0 ?></td>
                    <td>$<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                    <td><?= htmlspecialchars($order['status'] ?? 'Pending') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Sales Report</h3>
    <p><strong>Total Sales:</strong> $<?= number_format($salesData['total_sales'] ?? 0, 2) ?></p>
    <p><strong>Total Items Sold:</strong> <?= $salesData['total_items_sold'] ?? 0 ?></p>
    <p><strong>Total Cancelled Orders:</strong> <?= $salesData['total_cancelled_orders'] ?? 0 ?></p>

    <h3>Monthly Sales Report</h3>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Sales Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($month = $monthlySalesResult->fetch_assoc()): ?>
                <tr>
                    <td><?= date("F", mktime(0, 0, 0, $month['month'], 1)) ?></td>
                    <td>$<?= number_format($month['monthly_sales'] ?? 0, 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Yearly Sales Report</h3>
    <table>
        <thead>
            <tr>
                <th>Year</th>
                <th>Sales Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($year = $yearlySalesResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $year['year'] ?? '' ?></td>
                    <td>$<?= number_format($year['yearly_sales'] ?? 0, 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
