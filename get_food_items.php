<?php
header('Content-Type: application/json');
session_start();

// Connect to your working phpMyAdmin local database
$conn = new mysqli("localhost", "root", "", "masisso_db");
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Extract all active catalog variations - using name as item_name, and no status column
$sql = "SELECT name as item_name, description, price FROM menu_items ORDER BY item_id DESC";
$result = $conn->query($sql);

$menu_items = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu_items[] = [
            'item_name' => htmlspecialchars($row['item_name']),
            'description' => htmlspecialchars($row['description']),
            'price' => number_format((float)$row['price'], 2)
        ];
    }
}

// Send array dataset back to the HTML layout screen
echo json_encode($menu_items);
$conn->close();
?>