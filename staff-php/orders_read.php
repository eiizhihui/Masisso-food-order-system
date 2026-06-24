<?php
require_once("../config.php");

// Join the orders table with the customer table to get the customer's name
$query = "SELECT o.order_id, o.order_type, o.total_price, o.order_status, o.order_date, c.name AS customer_name 
          FROM orders o 
          LEFT JOIN customer c ON o.user_id = c.user_id 
          ORDER BY o.order_id DESC";

$result = mysqli_query($conn, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>