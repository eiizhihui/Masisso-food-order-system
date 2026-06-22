<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data) { echo json_encode(["success"=>false]); exit; }
$password = password_hash('123456', PASSWORD_DEFAULT);
$sql = "INSERT INTO users (name, email, role, bowls, password) VALUES (";
$sql .= "'" . $conn->real_escape_string($data['name']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['email']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['role']) . "', ";
$sql .= "'" . $conn->real_escape_string($data['bowls']) . "', ";
$sql .= "'" . $conn->real_escape_string($password) . "'";
$sql .= ")";
if($conn->query($sql)) echo json_encode(["success"=>true, "id"=>$conn->insert_id]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>