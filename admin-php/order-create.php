<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$sql = "INSERT INTO orders (user_id, order_type, total_price, order_status) VALUES (";
$sql .= "'" . $conn->real_escape_string($data['user_id']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['order_type']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['total_price']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['order_status']) . "', ";
$sql = rtrim($sql, ", ") . ")";
if($conn->query($sql)) echo json_encode(["success"=>true, "id"=>$conn->insert_id]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>