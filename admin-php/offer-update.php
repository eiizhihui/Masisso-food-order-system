<?php
require_once 'db_connect.php';
$data = getPostData();
if(!$data || !isset($data['offer_id'])) { echo json_encode(["success"=>false]); exit; }
$id = $conn->real_escape_string($data['offer_id']);
$sql = "UPDATE offers SET ";
if(isset($data['code'])) $sql .= "code='" . $conn->real_escape_string($data['code']) . "', ";
if(isset($data['title'])) $sql .= "title='" . $conn->real_escape_string($data['title']) . "', ";
if(isset($data['description'])) $sql .= "description='" . $conn->real_escape_string($data['description']) . "', ";
if(isset($data['discount_type'])) $sql .= "discount_type='" . $conn->real_escape_string($data['discount_type']) . "', ";
if(isset($data['discount_value'])) $sql .= "discount_value='" . $conn->real_escape_string($data['discount_value']) . "', ";
if(isset($data['min_spend'])) $sql .= "min_spend='" . $conn->real_escape_string($data['min_spend']) . "', ";
if(isset($data['valid_until'])) $sql .= "valid_until='" . $conn->real_escape_string($data['valid_until']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE offer_id='$id'";
if($conn->query($sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>$conn->error]);
?>