<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$sql = "INSERT INTO offers (code, title, description, discount_type, discount_value, min_spend, valid_until) VALUES (";
$sql .= "'" . $conn->real_escape_string($data['code']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['title']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['description']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['discount_type']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['discount_value']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['min_spend']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['valid_until']) . "', ";
$sql = rtrim($sql, ", ") . ")";
if($conn->query($sql)) echo json_encode(["success"=>true, "id"=>$conn->insert_id]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>