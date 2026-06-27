<?php
require_once("../config.php");
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$sql = "INSERT INTO orders (user_id, order_type, total_price, order_status) VALUES (";
$sql .= "'" . mysqli_real_escape_string($conn, $data['user_id']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['order_type']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['total_price']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['order_status']) . "', ";
$sql = rtrim($sql, ", ") . ")";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true, "id"=>mysqli_insert_id($conn)]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>