<?php
require_once("../config.php");
$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
if(!$q) { echo json_encode([]); exit; }

$results = [];

// Search users (customer + staff)
$user_query = "
    SELECT user_id, name, email, 'Customer' as role FROM customer WHERE name LIKE '%$q%' OR email LIKE '%$q%'
    UNION ALL
    SELECT staff_id as user_id, name, email, position as role FROM staff WHERE name LIKE '%$q%' OR email LIKE '%$q%'
    LIMIT 5
";
$res = mysqli_query($conn, $user_query);
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='user'; $results[] = $row; }

// Search menu
$res = mysqli_query($conn, "SELECT * FROM menu_items WHERE name LIKE '%$q%' OR category LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='menu'; $results[] = $row; }

// Search offers
$res = mysqli_query($conn, "SELECT * FROM offers WHERE code LIKE '%$q%' OR title LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='offer'; $results[] = $row; }

// Search rewards
$res = mysqli_query($conn, "SELECT * FROM rewards WHERE reward_name LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { 
    $row['type']='reward'; 
    $row['title'] = $row['reward_name'];
    $row['bowls_required'] = $row['points_required'];
    $results[] = $row; 
}

// Search orders
$res = mysqli_query($conn, "SELECT * FROM orders WHERE order_id LIKE '%$q%' OR order_type LIKE '%$q%' OR order_status LIKE '%$q%' OR items LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='order'; $results[] = $row; }

echo json_encode($results);
?>