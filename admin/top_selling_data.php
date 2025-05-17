<?php
include '../includes/db_connection.php';

$sql = "SELECT p.product_name, SUM(oi.quantity) AS total_sales
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        GROUP BY oi.product_id
        ORDER BY total_sales DESC
        LIMIT 5";

$result = $conn->query($sql);

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['product_name'];
    $data[] = $row['total_sales'];
}

echo json_encode(['labels' => $labels, 'data' => $data]);
?>
