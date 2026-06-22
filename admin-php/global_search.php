<?php
require_once 'db_connect.php';
$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
if(!$q) { echo json_encode([]); exit; }

$results = [];

// Search users
$res = $conn->query("SELECT * FROM users WHERE name LIKE '%$q%' OR email LIKE '%$q%' LIMIT 5");
if($res) while($row = $res->fetch_assoc()) { $row['type']='user'; $results[] = $row; }

// Search menu
$res = $conn->query("SELECT * FROM menu_items WHERE name LIKE '%$q%' OR category LIKE '%$q%' LIMIT 5");
if($res) while($row = $res->fetch_assoc()) { $row['type']='menu'; $results[] = $row; }

// Search offers
$res = $conn->query("SELECT * FROM offers WHERE code LIKE '%$q%' OR title LIKE '%$q%' LIMIT 5");
if($res) while($row = $res->fetch_assoc()) { $row['type']='offer'; $results[] = $row; }

// Search rewards
$res = $conn->query("SELECT * FROM rewards WHERE title LIKE '%$q%' LIMIT 5");
if($res) while($row = $res->fetch_assoc()) { $row['type']='reward'; $results[] = $row; }

echo json_encode($results);
?>