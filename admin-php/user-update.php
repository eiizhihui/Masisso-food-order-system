<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['user_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['user_id']);
$sql = "UPDATE users SET ";
if(isset($data['name'])) $sql .= "name='" . $conn->real_escape_string($data['name']) . "', ";
if(isset($data['email'])) $sql .= "email='" . $conn->real_escape_string($data['email']) . "', ";
if(isset($data['role'])) $sql .= "role='" . $conn->real_escape_string($data['role']) . "', ";
if(isset($data['bowls'])) $sql .= "bowls='" . $conn->real_escape_string($data['bowls']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE user_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>