<?php
include 'db_connect.php';

// Grab all offers from the database (and only show ones that haven't expired!)
$sql = "SELECT * FROM offers WHERE valid_until >= CURRENT_DATE";

$result = $conn->query($sql);

$offers = array(); 

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $offers[] = $row;
    }
}

// Convert the data into JSON so JavaScript can read it
header('Content-Type: application/json');
echo json_encode($offers);

$conn->close();
?>