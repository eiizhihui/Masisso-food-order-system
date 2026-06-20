<?php
include 'db_connect.php';

// Get the keyword that the user typed in 
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Protect the database from bad characters
$keyword = $conn->real_escape_string($keyword);

// Search for the keyword in BOTH the name AND description
$sql = "SELECT * FROM Menu_Items WHERE name LIKE '%$keyword%' OR description LIKE '%$keyword%'";

// Run the query and collect results
$result = $conn->query($sql);
$menu_items = array(); 

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}

// Send the results back as JSON
header('Content-Type: application/json');
echo json_encode($menu_items);

$conn->close();
?>