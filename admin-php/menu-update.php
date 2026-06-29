<?php
require_once("../config.php");

session_start();
if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ["admin", "super admin"])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}
$data = getPostData();
if(!$data || !isset($data['item_id'])) { echo json_encode(["success"=>false]); exit; }
$id = mysqli_real_escape_string($conn, $data['item_id']);
$sql = "UPDATE menu_items SET ";
if(isset($data['name'])) $sql .= "name='" . mysqli_real_escape_string($conn, $data['name']) . "', ";
if(isset($data['price'])) $sql .= "price='" . mysqli_real_escape_string($conn, $data['price']) . "', ";
if(isset($data['description'])) $sql .= "description='" . mysqli_real_escape_string($conn, $data['description']) . "', ";
if(isset($data['category'])) $sql .= "category='" . mysqli_real_escape_string($conn, $data['category']) . "', ";
if(isset($data['image_url'])) $sql .= "image_url='" . mysqli_real_escape_string($conn, $data['image_url']) . "', ";
$sql = rtrim($sql, ", ") . " WHERE item_id='$id'";
if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>