<?php
require_once("../config.php");
$data = getPostData(); // Uses the helper function from your config.php

if (!$data || !isset($data['order_id']) || !isset($data['order_status'])) {
    echo json_encode(["success" => false, "error" => "Missing order ID or status."]);
    exit;
}

$order_id = (int) $data['order_id'];
$status = mysqli_real_escape_string($conn, $data['order_status']);

$sql = "UPDATE orders SET order_status='$status' WHERE order_id=$order_id";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>