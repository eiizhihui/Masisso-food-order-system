<?php
require_once("../config.php");

session_start();

if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['admin', 'super admin'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

$data = getPostData();

if (!$data || !isset($data['user_id']) || !isset($data['role'])) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

$id = mysqli_real_escape_string($conn, $data['user_id']);
$role = mysqli_real_escape_string($conn, $data['role']);

if ($role === 'Customer') {
    $sql = "UPDATE customer SET ";
    
    if (isset($data['name'])) {
        $sql .= "name='" . mysqli_real_escape_string($conn, $data['name']) . "', ";
    }
    if (isset($data['email'])) {
        $sql .= "email='" . mysqli_real_escape_string($conn, $data['email']) . "', ";
    }
    if (isset($data['phone'])) {
        $sql .= "phone='" . mysqli_real_escape_string($conn, $data['phone']) . "', ";
    }
    if (isset($data['address'])) {
        $sql .= "address='" . mysqli_real_escape_string($conn, $data['address']) . "', ";
    }
    if (isset($data['bowls'])) {
        $sql .= "points='" . (int)$data['bowls'] . "', ";
    }
    if (isset($data['password']) && !empty($data['password'])) {
        $sql .= "password='" . mysqli_real_escape_string($conn, $data['password']) . "', ";
    }
    
    $sql = rtrim($sql, ", ") . " WHERE user_id='$id'";
} else {
    $sql = "UPDATE staff SET ";
    
    if (isset($data['name'])) {
        $sql .= "name='" . mysqli_real_escape_string($conn, $data['name']) . "', ";
    }
    if (isset($data['email'])) {
        $sql .= "email='" . mysqli_real_escape_string($conn, $data['email']) . "', ";
    }
    if (isset($data['phone'])) {
        $sql .= "phone='" . mysqli_real_escape_string($conn, $data['phone']) . "', ";
    }
    if (isset($data['gender'])) {
        $sql .= "gender='" . mysqli_real_escape_string($conn, $data['gender']) . "', ";
    }
    if (isset($data['branch'])) {
        $sql .= "branch='" . mysqli_real_escape_string($conn, $data['branch']) . "', ";
    }
    if (isset($data['role'])) {
        $sql .= "position='" . mysqli_real_escape_string($conn, $data['role']) . "', ";
    }
    if (isset($data['password']) && !empty($data['password'])) {
        $sql .= "password='" . mysqli_real_escape_string($conn, $data['password']) . "', ";
    }
    
    $sql = rtrim($sql, ", ") . " WHERE staff_id='$id'";
}

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
