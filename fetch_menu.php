<?php
//R-read : read database to display the food
include 'db_connect.php';

$sql = "SELECT * FROM Menu_Items";

$result = $conn->query($sql);

$menu_items = array(); // Create an empty array to hold our food

// Check if we found any food items
if ($result->num_rows > 0) {
    // Loop through each row and add it to our array
    while($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}

// Convert the array into JSON 
header('Content-Type: application/json');
echo json_encode($menu_items);

//Close the connection
$conn->close();
?>