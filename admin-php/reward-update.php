<?php
require_once("../config.php");
$data = getPostData();
if(!$data || !isset($data['reward_id'])) { echo json_encode(["success"=>false]); exit; }
$id = mysqli_real_escape_string($conn, $data['reward_id']);
$sql = "UPDATE rewards SET ";
if(isset($data['title'])) $sql .= "title='" . mysqli_real_escape_string($conn, $data['title']) . "', ";
if(isset($data['bowls_required'])) $sql .= "bowls_required='" . mysqli_real_escape_string($conn, $data['bowls_required']) . "', ";
if(isset($data['image_url'])) $sql .= "image_url='" . mysqli_real_escape_string($conn, $data['image_url']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE reward_id='$id'";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>