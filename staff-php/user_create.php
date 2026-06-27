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

$role = mysqli_real_escape_string($conn, $data['role']);
$name = mysqli_real_escape_string($conn, $data['name']);
$email = mysqli_real_escape_string($conn, $data['email']);
$phone = isset($data['phone']) ? mysqli_real_escape_string($conn, $data['phone']) : '';
$password = isset($data['password']) ? mysqli_real_escape_string($conn, $data['password']) : '123456';

if ($role === 'Customer') {
    $bowls = isset($data['bowls']) ? (int) $data['bowls'] : 0;
    $address = isset($data['address']) ? mysqli_real_escape_string($conn, $data['address']) : '';
    $sql = "INSERT INTO customer (name, email, phone, address, points, password) VALUES ('$name', '$email', '$phone', '$address', $bowls, '$password')";
} else {
    $gender = isset($data['gender']) ? mysqli_real_escape_string($conn, $data['gender']) : 'Other';
    $branch = isset($data['branch']) ? mysqli_real_escape_string($conn, $data['branch']) : 'Masisso JB City Square';
    $sql = "INSERT INTO staff (name, email, phone, gender, branch, position, password) VALUES ('$name', '$email', '$phone', '$gender', '$branch', '$role', '$password')";
}

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "id" => mysqli_insert_id($conn)]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
