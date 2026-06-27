<?php
require_once("../config.php");

session_start();

if (!isset($_SESSION['role'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$role = strtolower($_SESSION['role']);
$data = getPostData();

if (!$data || !isset($data['order_id'])) {
    echo json_encode(["success" => false, "error" => "Missing order ID."]);
    exit;
}

$id = (int) $data['order_id'];
$updates = [];

if ($role === 'staff') {
    // Staff can only update order_status
    if (isset($data['order_status'])) {
        $updates[] = "order_status='" . mysqli_real_escape_string($conn, $data['order_status']) . "'";
    }
} else if ($role === 'admin' || $role === 'super admin') {
    // Admin can update all fields
    if (isset($data['user_id'])) {
        $updates[] = "user_id='" . mysqli_real_escape_string($conn, $data['user_id']) . "'";
    }
    if (isset($data['order_type'])) {
        $updates[] = "order_type='" . mysqli_real_escape_string($conn, $data['order_type']) . "'";
    }
    if (isset($data['total_price'])) {
        $updates[] = "total_price='" . mysqli_real_escape_string($conn, $data['total_price']) . "'";
    }
    if (isset($data['order_status'])) {
        $updates[] = "order_status='" . mysqli_real_escape_string($conn, $data['order_status']) . "'";
    }
} else {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

if (empty($updates)) {
    echo json_encode(["success" => false, "error" => "No valid fields to update."]);
    exit;
}

$sql = "UPDATE orders SET " . implode(", ", $updates) . " WHERE order_id=$id";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>