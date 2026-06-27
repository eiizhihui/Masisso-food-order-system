<?php
require_once("../config.php");

session_start();

if (!isset($_SESSION['role'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$role = strtolower($_SESSION['role']);

if ($role === 'admin' || $role === 'super admin') {
    $query = "
        SELECT user_id, name, username, email, phone, address, points as bowls, 'Customer' as role, NULL as gender, NULL as branch FROM customer
        UNION ALL
        SELECT staff_id as user_id, name, username, email, phone, NULL as address, 0 as bowls, position as role, gender, branch FROM staff
        ORDER BY user_id DESC
    ";
} else if ($role === 'staff') {
    $query = "SELECT staff_id as user_id, name, username, email, phone, gender, branch, position as role FROM staff ORDER BY staff_id DESC";
} else {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$result = mysqli_query($conn, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>