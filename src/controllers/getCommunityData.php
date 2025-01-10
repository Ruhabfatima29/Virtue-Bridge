<?php
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$query = "SELECT community_id, name, literacy_rate, area, city 
          FROM local_communities 
          WHERE literacy_rate IS NOT NULL 
          ORDER BY name";

$result = $conn->query($query);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'community_id' => $row['community_id'],
            'name' => $row['name'],
            'literacy_rate' => floatval($row['literacy_rate']),
            'area' => $row['area'],
            'city' => $row['city']
        ];
    }
}

echo json_encode($data);
$conn->close();
?>