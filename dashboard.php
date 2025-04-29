<?php
session_start();
// Optional: Protect dashboard page (redirect if not logged in)
// if (!isset($_SESSION['username'])) {
//     header('Location: login.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #f5f5f5;
            float: left;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar-logo {
            width: 70px;
            height: auto;
            margin: 0 auto 10px auto;
            display: block;
        }
        .sidebar a {
            display: block;
            padding: 15px;
            margin: 10px auto;
            width: 80%;
            background: linear-gradient(to right, #e3b4ff, #b966ff);
            text-decoration: none;
            color: white;
            text-align: center;
            border-radius: 25px;
            font-size: 16px;
        }
        .sidebar a:hover {
            background: linear-gradient(to right, #d084ff, #9a3cff);
        }
        .content {
            margin-left: 220px;
            padding: 20px;
            background-color: #FAC99B;
            min-height: 100vh;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            font-size: 20px;
            font-weight: bold;
            background-color: #F5F5F5;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header-text {
            flex: 1;
            text-align: center;
            margin-right: 50px;
        }
        .icon-container {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .icon-container img {
            width: 25px;
            cursor: pointer;
        }
        /* Make the logout icon a link */
        .icon-container a img {
            width: 25px;
            height: auto;
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .card img {
            width: 90%;
            height: 130px;
            border-radius: 8px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="logo.png" alt="Logo" class="sidebar-logo">
    <a href="#">Home</a>
    <a href="order.php">Orders</a>
    <a href="inventory.php">Inventory</a>
    <a href="shipments.php">Shipments</a>
    <a href="reports.php">Reports</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="header">
        <div class="header-text">
            “HANDLED WITH CARE, DELIVERED WITH LOVE”
        </div>
        <div class="icon-container">
            <img src="icon-email.png" alt="Messages">
            <img src="icon-bell.png" alt="Notifications">
            <!-- Logout Icon Link -->
            <a href="logout.php" title="Logout">
                <img src="icon-logout.png" alt="Logout">
            </a>
        </div>
    </div>

    <div class="card-container">
        <div class="card">
            <h3>Order status</h3>
            <img src="chart-order.png" alt="Order Chart">
        </div>
        <div class="card">
            <h3>Top selling product</h3>
            <img src="chart-top-product.png" alt="Top Product">
        </div>
        <div class="card">
            <h3>Total inventory value</h3>
            <img src="chart-inventory.png" alt="Inventory Value">
        </div>
        <div class="card">
            <h3>Stock level</h3>
            <img src="chart-stock.png" alt="Stock Level">
        </div>
        <div class="card">
            <h3>Recent activity</h3>
            <img src="chart-activity.png" alt="Recent Activity">
        </div>
        <div class="card">
            <h3>Recent shipments</h3>
            <img src="chart-shipments.png" alt="Recent Shipments">
        </div>
    </div>
</div>

</body>
</html>
