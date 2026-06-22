<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['order_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['order_id']);
$sql = "DELETE FROM orders WHERE order_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>