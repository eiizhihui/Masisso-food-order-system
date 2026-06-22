<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$sql = "INSERT INTO menu_items (name, price, description, category, image_url) VALUES (";
$sql .= "'" . $conn->real_escape_string($data['name']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['price']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['description']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['category']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['image_url']) . "', ";
$sql = rtrim($sql, ", ") . ")";
if($conn->query($sql)) echo json_encode(["success"=>true, "id"=>$conn->insert_id]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>