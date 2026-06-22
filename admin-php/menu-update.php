<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['item_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['item_id']);
$sql = "UPDATE menu_items SET ";
if(isset($data['name'])) $sql .= "name='" . $conn->real_escape_string($data['name']) . "', ";
if(isset($data['price'])) $sql .= "price='" . $conn->real_escape_string($data['price']) . "', ";
if(isset($data['description'])) $sql .= "description='" . $conn->real_escape_string($data['description']) . "', ";
if(isset($data['category'])) $sql .= "category='" . $conn->real_escape_string($data['category']) . "', ";
if(isset($data['image_url'])) $sql .= "image_url='" . $conn->real_escape_string($data['image_url']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE item_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>