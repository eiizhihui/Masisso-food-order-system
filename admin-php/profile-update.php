<?php
require_once("config.php");
$data = getPostData();
if(!$data || !isset($data['user_id'])) { echo json_encode(["success"=>false, "error"=>"Missing user_id"]); exit; }
$id = mysqli_real_escape_string($conn, $data['user_id']);

if ((int)$id >= 2000) {
    // Staff/Admin table
    $sql = "UPDATE staff SET ";
    if(isset($data['name'])) $sql .= "name='" . mysqli_real_escape_string($conn, $data['name']) . "', ";
    if(isset($data['username'])) $sql .= "username='" . mysqli_real_escape_string($conn, $data['username']) . "', ";
    if(isset($data['email'])) $sql .= "email='" . mysqli_real_escape_string($conn, $data['email']) . "', ";
    if(isset($data['phone'])) $sql .= "phone='" . mysqli_real_escape_string($conn, $data['phone']) . "', ";
    if(isset($data['branch'])) $sql .= "branch='" . mysqli_real_escape_string($conn, $data['branch']) . "', ";
    if(isset($data['password']) && !empty($data['password'])) {
        $hashed = md5($data['password']);
        $sql .= "password='" . mysqli_real_escape_string($conn, $hashed) . "', ";
    }
    $sql = rtrim($sql, ", ") . " WHERE staff_id='$id'";
} else {
    // Customer table
    $sql = "UPDATE customer SET ";
    if(isset($data['name'])) $sql .= "name='" . mysqli_real_escape_string($conn, $data['name']) . "', ";
    if(isset($data['username'])) $sql .= "username='" . mysqli_real_escape_string($conn, $data['username']) . "', ";
    if(isset($data['email'])) $sql .= "email='" . mysqli_real_escape_string($conn, $data['email']) . "', ";
    if(isset($data['phone'])) $sql .= "phone='" . mysqli_real_escape_string($conn, $data['phone']) . "', ";
    if(isset($data['address'])) $sql .= "address='" . mysqli_real_escape_string($conn, $data['address']) . "', ";
    if(isset($data['password']) && !empty($data['password'])) {
        $hashed = md5($data['password']);
        $sql .= "password='" . mysqli_real_escape_string($conn, $hashed) . "', ";
    }
    $sql = rtrim($sql, ", ") . " WHERE user_id='$id'";
}

if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>
