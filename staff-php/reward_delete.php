<?php
require_once("../config.php");

session_start();

if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['admin', 'super admin'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$data = getPostData();

if (!$data || !isset($data['reward_id'])) {
    echo json_encode(["success" => false]);
    exit;
}

$id = (int) $data['reward_id'];
$sql = "DELETE FROM rewards WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
