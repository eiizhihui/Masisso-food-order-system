<?php
require_once("../config.php");

session_start();

if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['admin', 'super admin'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$data = getPostData();

if (!$data) {
    echo json_encode(["success" => false]);
    exit;
}

$sql = "INSERT INTO menu_items (name, price, description, category, image_url) VALUES (";
$sql .= "'" . mysqli_real_escape_string($conn, $data['name']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['price']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['description']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['category']) . "', ";
$sql .= "'" . mysqli_real_escape_string($conn, $data['image_url']) . "'";
$sql .= ")";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "id" => mysqli_insert_id($conn)]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
