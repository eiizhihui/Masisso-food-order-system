<?php
require_once("config.php");
$data = getPostData();
if(!$data || !isset($data['item_id'])) { echo json_encode(["success"=>false]); exit; }
$id = mysqli_real_escape_string($conn, $data['item_id']);
$sql = "DELETE FROM menu_items WHERE item_id='$id'";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>