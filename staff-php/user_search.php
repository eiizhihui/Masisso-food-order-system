<?php
require_once("../config.php");

session_start();

if (!isset($_SESSION['role'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$role = strtolower($_SESSION['role']);
$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

if ($role === 'admin' || $role === 'super admin') {
    $sql = "
        SELECT user_id, name, username, email, phone, address, points as bowls, 'Customer' as role, NULL as gender, NULL as branch FROM customer
        WHERE name LIKE '%$q%' OR email LIKE '%$q%'
        UNION ALL
        SELECT staff_id as user_id, name, username, email, phone, NULL as address, 0 as bowls, position as role, gender, branch FROM staff
        WHERE name LIKE '%$q%' OR email LIKE '%$q%' OR position LIKE '%$q%'
    ";
} else if ($role === 'staff') {
    $sql = "
        SELECT staff_id as user_id, name, email, phone, gender, branch, position as role FROM staff
        WHERE name LIKE '%$q%' OR email LIKE '%$q%'
    ";
} else {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$result = mysqli_query($conn, $sql);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
