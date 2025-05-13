<?php
include '../includes/db_connection.php'; // or 'db_connection.php' if same folder
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js library -->
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
            border-radius: 30px;
            font-size: 16px;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #d084ff, #9a3cff);
            transform: scale(1.02);
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

        .icon-container a img {
            width: 25px;
            height: auto;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            grid-gap: 25px;
            margin-top: 20px;
        }

        .card {
            background: linear-gradient(to right, #fbe0ff, #ecd0ff);
            padding: 15px;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            height: 230px; /* Make height consistent */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease;
            overflow: hidden; /* Prevent overflow issues */
        }

        .card h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #5e2484;
            line-height: 1.4;
        }

        .card canvas {
            width: 100%;
            height: 150px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="asset/logo.png" alt="Logo" class="sidebar-logo">
    <a href="#">Home</a>
    <a href="shipments.php">Shipments</a>
    <a href="inventory.php">Inventory</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="header">
        <div class="header-text">
            “HANDLED WITH CARE, DELIVERED WITH LOVE”
        </div>
        <div class="icon-container">
            <img src="asset/icon-email.png" alt="Messages">
            <img src="asset/icon-bell.png" alt="Notifications">
            <a href="index.php" title="Logout">
                <img src="asset/icon-logout.png" alt="Logout">
            </a>
        </div>
    </div>

    <div class="card-container">
        <div class="card">
            <h3>Order Status</h3>
            <canvas id="orderStatusChart"></canvas>
        </div>
        <div class="card">
            <h3>Top Selling Product</h3>
            <canvas id="topProductChart"></canvas>
        </div>
        <div class="card">
            <h3>Total Inventory Value</h3>
            <canvas id="inventoryValueChart"></canvas>
        </div>
        <div class="card">
            <h3>Stock Level</h3>
            <canvas id="stockLevelChart"></canvas>
        </div>
        <div class="card">
            <h3>Recent Activity</h3>
            <canvas id="recentActivityChart"></canvas>
        </div>
        <div class="card">
            <h3>Recent Shipments</h3>
            <canvas id="recentShipmentsChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Example charts
    const ctxOrder = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(ctxOrder, {
        type: 'doughnut',
        data: {
            labels: ['Delivered', 'Pending', 'Cancelled'],
            datasets: [{
                label: 'Orders',
                data: [60, 25, 15],
                backgroundColor: ['#4CAF50', '#FF9800', '#F44336'],
            }]
        }
    });

    const ctxTopProduct = document.getElementById('topProductChart').getContext('2d');
    new Chart(ctxTopProduct, {
        type: 'bar',
        data: {
            labels: ['Product A', 'Product B', 'Product C'],
            datasets: [{
                label: 'Sales',
                data: [120, 90, 70],
                backgroundColor: ['#3f51b5', '#2196f3', '#00bcd4'],
            }]
        }
    });

    const ctxInventory = document.getElementById('inventoryValueChart').getContext('2d');
    new Chart(ctxInventory, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr'],
            datasets: [{
                label: 'Inventory Value',
                data: [5000, 6000, 5500, 7000],
                borderColor: '#ff5722',
                fill: false
            }]
        }
    });

    const ctxStock = document.getElementById('stockLevelChart').getContext('2d');
    new Chart(ctxStock, {
        type: 'bar',
        data: {
            labels: ['Warehouse 1', 'Warehouse 2', 'Warehouse 3'],
            datasets: [{
                label: 'Stock',
                data: [200, 150, 300],
                backgroundColor: ['#8bc34a', '#cddc39', '#ffc107'],
            }]
        }
    });

    const ctxActivity = document.getElementById('recentActivityChart').getContext('2d');
    new Chart(ctxActivity, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
            datasets: [{
                label: 'Activity',
                data: [10, 20, 15, 25, 30],
                borderColor: '#9c27b0',
                fill: false
            }]
        }
    });

    const ctxShipments = document.getElementById('recentShipmentsChart').getContext('2d');
    new Chart(ctxShipments, {
        type: 'doughnut',
        data: {
            labels: ['On Time', 'Delayed'],
            datasets: [{
                label: 'Shipments',
                data: [80, 20],
                backgroundColor: ['#00e676', '#ff1744'],
            }]
        }
    });
</script>

</body>
</html>
