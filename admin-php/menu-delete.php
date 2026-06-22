<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['item_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['item_id']);
$sql = "DELETE FROM menu_items WHERE item_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>