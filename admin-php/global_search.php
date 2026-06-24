<?php
require_once("../config.php");
$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
if(!$q) { echo json_encode([]); exit; }

$results = [];

// Search users
$res = mysqli_query($conn, "SELECT * FROM users WHERE name LIKE '%$q%' OR email LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='user'; $results[] = $row; }

// Search menu
$res = mysqli_query($conn, "SELECT * FROM menu_items WHERE name LIKE '%$q%' OR category LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='menu'; $results[] = $row; }

// Search offers
$res = mysqli_query($conn, "SELECT * FROM offers WHERE code LIKE '%$q%' OR title LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='offer'; $results[] = $row; }

// Search rewards
$res = mysqli_query($conn, "SELECT * FROM rewards WHERE title LIKE '%$q%' LIMIT 5");
if($res) while($row = mysqli_fetch_assoc($res)) { $row['type']='reward'; $results[] = $row; }

echo json_encode($results);
?>