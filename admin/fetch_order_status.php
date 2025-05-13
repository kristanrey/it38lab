<?php
include '../includes/db_connection.php'; // adjust path if needed

header('Content-Type: application/json');

// Query to count orders by status
$sql = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$result = $conn->query($sql);

$data = [
    'labels' => [],
    'data' => []
];

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['status'];
    $data['data'][] = (int)$row['count'];
}

echo json_encode($data);
?>
