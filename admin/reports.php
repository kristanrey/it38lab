<?php
session_start();
require_once 'db_connection.php';

// Fetching reports data
$shipment_count = $conn->query("SELECT COUNT(*) AS count FROM shipments")->fetch_assoc()['count'];
$delivered_count = $conn->query("SELECT COUNT(*) AS count FROM shipments WHERE status = 'Delivered'")->fetch_assoc()['count'];
$shipped_count = $conn->query("SELECT COUNT(*) AS count FROM shipments WHERE status = 'Shipped'")->fetch_assoc()['count'];
$pending_count = $conn->query("SELECT COUNT(*) AS count FROM shipments WHERE status = 'Pending'")->fetch_assoc()['count'];
$in_transit_count = $conn->query("SELECT COUNT(*) AS count FROM shipments WHERE status = 'In Transit'")->fetch_assoc()['count'];

// Sample shipment data for chart (this could be more complex based on your actual reporting needs)
$shipment_data = $conn->query("SELECT status, COUNT(*) AS count FROM shipments GROUP BY status");
$shipment_statuses = [];
$shipment_counts = [];
while ($row = $shipment_data->fetch_assoc()) {
    $shipment_statuses[] = $row['status'];
    $shipment_counts[] = $row['count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
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
            background-color: #f5f5f5;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .header-text {
            flex: 1;
            text-align: center;
            margin-right: 50px;
            color: #333;
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
        }
        .statistics {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 25%;
            text-align: center;
        }
        .stat-card h3 {
            margin-bottom: 15px;
        }
        .stat-card p {
            font-size: 24px;
            font-weight: bold;
        }
        .chart-container {
            width: 80%;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="asset/logo.png" alt="Logo" class="sidebar-logo">
    <a href="dashboard.php">Home</a>
    <a href="order.php">Orders</a>
    <a href="order_history.php">Order History</a>
    <a href="inventory.php">Inventory</a>
    <a href="shipments.php">Shipments</a>
    <a href="reports.php">Reports</a>
</div>

<div class="content">
    <div class="header">
        <div class="header-text">REPORTS</div>
        <div class="icon-container">
            <img src="asset/icon-email.png" alt="Messages">
            <img src="asset/icon-bell.png" alt="Notifications">
            <a href="index.php" title="Logout">
                <img src="asset/icon-logout.png" alt="Logout">
            </a>
        </div>
    </div>

    <div class="statistics">
        <div class="stat-card">
            <h3>Total Shipments</h3>
            <p><?= $shipment_count ?></p>
        </div>
        <div class="stat-card">
            <h3>Delivered Shipments</h3>
            <p><?= $delivered_count ?></p>
        </div>
        <div class="stat-card">
            <h3>Shipped Shipments</h3>
            <p><?= $shipped_count ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Shipments</h3>
            <p><?= $pending_count ?></p>
        </div>
    </div>

    <div class="chart-container">
        <h3>Shipment Status Distribution</h3>
        <canvas id="shipmentStatusChart"></canvas>
        <script>
            var ctx = document.getElementById('shipmentStatusChart').getContext('2d');
            var shipmentStatusChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($shipment_statuses); ?>,
                    datasets: [{
                        label: 'Shipment Status Distribution',
                        data: <?php echo json_encode($shipment_counts); ?>,
                        backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99'],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw + ' shipments';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</div>

</body>
</html>
