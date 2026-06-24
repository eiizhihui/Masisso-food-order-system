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

$title = mysqli_real_escape_string($conn, $data['title']);
$bowls = (int) $data['bowls_required'];
$image_url = mysqli_real_escape_string($conn, $data['image_url']);

$sql = "INSERT INTO rewards (reward_name, points_required, image_url) VALUES ('$title', $bowls, '$image_url')";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "id" => mysqli_insert_id($conn)]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
