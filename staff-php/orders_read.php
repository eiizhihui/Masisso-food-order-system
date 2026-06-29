<?php
require_once("../config.php");

session_start();
if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

// Join the orders table with the customer table to get the customer's name
$query = "SELECT o.order_id, o.order_type, o.total_price, o.order_status, o.order_date, o.items, c.name AS customer_name 
          FROM orders o 
          LEFT JOIN customer c ON o.user_id = c.user_id 
          ORDER BY o.order_date DESC";

$result = mysqli_query($conn, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>