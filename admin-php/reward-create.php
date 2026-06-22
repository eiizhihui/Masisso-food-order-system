<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$sql = "INSERT INTO rewards (title, bowls_required, image_url) VALUES (";
$sql .= "'" . $conn->real_escape_string($data['title']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['bowls_required']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['image_url']) . "', ";
$sql = rtrim($sql, ", ") . ")";
if($conn->query($sql)) echo json_encode(["success"=>true, "id"=>$conn->insert_id]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>