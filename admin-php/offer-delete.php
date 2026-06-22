<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['offer_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['offer_id']);
$sql = "DELETE FROM offers WHERE offer_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>