<?php
require_once("../config.php");
$data = getPostData();
if(!$data || !isset($data['order_id'])) { echo json_encode(["success"=>false]); exit; }
$id = mysqli_real_escape_string($conn, $data['order_id']);
$sql = "DELETE FROM orders WHERE order_id='$id'";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>