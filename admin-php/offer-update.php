<?php
require_once("../config.php");
$data = getPostData();
if(!$data || !isset($data['offer_id'])) { echo json_encode(["success"=>false]); exit; }
$id = mysqli_real_escape_string($conn, $data['offer_id']);
$sql = "UPDATE offers SET ";
if(isset($data['code'])) $sql .= "code='" . mysqli_real_escape_string($conn, $data['code']) . "', ";
if(isset($data['title'])) $sql .= "title='" . mysqli_real_escape_string($conn, $data['title']) . "', ";
if(isset($data['description'])) $sql .= "description='" . mysqli_real_escape_string($conn, $data['description']) . "', ";
if(isset($data['discount_type'])) $sql .= "discount_type='" . mysqli_real_escape_string($conn, $data['discount_type']) . "', ";
if(isset($data['discount_value'])) $sql .= "discount_value='" . mysqli_real_escape_string($conn, $data['discount_value']) . "', ";
if(isset($data['min_spend'])) $sql .= "min_spend='" . mysqli_real_escape_string($conn, $data['min_spend']) . "', ";
if(isset($data['valid_until'])) $sql .= "valid_until='" . mysqli_real_escape_string($conn, $data['valid_until']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE offer_id='$id'";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>