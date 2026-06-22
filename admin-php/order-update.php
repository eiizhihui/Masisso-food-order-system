<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['order_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['order_id']);
$sql = "UPDATE orders SET ";
if(isset($data['user_id'])) $sql .= "user_id='" . $conn->real_escape_string($data['user_id']) . "', ";
if(isset($data['order_type'])) $sql .= "order_type='" . $conn->real_escape_string($data['order_type']) . "', ";
if(isset($data['total_price'])) $sql .= "total_price='" . $conn->real_escape_string($data['total_price']) . "', ";
if(isset($data['order_status'])) $sql .= "order_status='" . $conn->real_escape_string($data['order_status']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE order_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>