<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['reward_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['reward_id']);
$sql = "UPDATE rewards SET ";
if(isset($data['title'])) $sql .= "title='" . $conn->real_escape_string($data['title']) . "', ";
if(isset($data['bowls_required'])) $sql .= "bowls_required='" . $conn->real_escape_string($data['bowls_required']) . "', ";
if(isset($data['image_url'])) $sql .= "image_url='" . $conn->real_escape_string($data['image_url']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE reward_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>