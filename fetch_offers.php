<?php
include 'db_connect.php';

$sql = "SELECT * FROM Offers";
$result = $conn->query($sql);
$offers = array(); 

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $offers[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($offers);
$conn->close();
?>