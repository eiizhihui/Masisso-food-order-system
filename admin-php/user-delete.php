<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['user_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['user_id']);
$sql = "DELETE FROM users WHERE user_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>