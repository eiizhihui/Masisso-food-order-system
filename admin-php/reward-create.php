<?php
require_once("../config.php");
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$sql = "INSERT INTO rewards (title, bowls_required, image_url) VALUES (";
$sql .= "'" . mysqli_real_escape_string($conn, $data['title']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['bowls_required']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['image_url']) . "', ";
$sql = rtrim($sql, ", ") . ")";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true, "id"=>mysqli_insert_id($conn)]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>