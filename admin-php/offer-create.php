<?php
require_once("../config.php");
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$sql = "INSERT INTO offers (code, title, description, discount_type, discount_value, min_spend, valid_until) VALUES (";
$sql .= "'" . mysqli_real_escape_string($conn, $data['code']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['title']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['description']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['discount_type']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['discount_value']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['min_spend']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['valid_until']) . "', ";
$sql = rtrim($sql, ", ") . ")";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true, "id"=>mysqli_insert_id($conn)]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>