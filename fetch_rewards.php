<?php
include 'db_connect.php';

// Grab all rewards from the database, ordered from cheapest to most expensive
$sql = "SELECT * FROM rewards ORDER BY points_required ASC";
$result = $conn->query($sql);

$rewards = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rewards[] = $row;
    }
}

// Convert to JSON for JavaScript
header('Content-Type: application/json');
echo json_encode($rewards);

$conn->close();
?>