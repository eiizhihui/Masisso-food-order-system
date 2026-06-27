<?php
require_once("../config.php");

session_start();

if (!isset($_SESSION['role'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$role = strtolower($_SESSION['role']);
$data = getPostData();

if (!$data || !isset($data['item_id'])) {
    echo json_encode(["success" => false, "error" => "Missing item ID."]);
    exit;
}

$id = (int) $data['item_id'];
$updates = [];

if ($role === 'staff') {
    // Staff can only update is_available
    if (isset($data['is_available'])) {
        $updates[] = "is_available='" . ((int)$data['is_available'] === 1 ? 1 : 0) . "'";
    }
} else if ($role === 'admin' || $role === 'super admin') {
    // Admin can update all fields
    if (isset($data['name'])) {
        $updates[] = "name='" . mysqli_real_escape_string($conn, $data['name']) . "'";
    }
    if (isset($data['price'])) {
        $updates[] = "price='" . mysqli_real_escape_string($conn, $data['price']) . "'";
    }
    if (isset($data['description'])) {
        $updates[] = "description='" . mysqli_real_escape_string($conn, $data['description']) . "'";
    }
    if (isset($data['category'])) {
        $updates[] = "category='" . mysqli_real_escape_string($conn, $data['category']) . "'";
    }
    if (isset($data['image_url'])) {
        $updates[] = "image_url='" . mysqli_real_escape_string($conn, $data['image_url']) . "'";
    }
    if (isset($data['is_available'])) {
        $updates[] = "is_available='" . ((int)$data['is_available'] === 1 ? 1 : 0) . "'";
    }
} else {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

if (empty($updates)) {
    echo json_encode(["success" => false, "error" => "No valid fields to update."]);
    exit;
}

$sql = "UPDATE menu_items SET " . implode(", ", $updates) . " WHERE item_id='$id'";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>