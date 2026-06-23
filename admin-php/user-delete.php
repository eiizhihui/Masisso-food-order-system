<?php
require_once("config.php");
$data = getPostData();
if(!$data || !isset($data['user_id'])) { echo json_encode(["success"=>false]); exit; }
$id = mysqli_real_escape_string($conn, $data['user_id']);
$role = isset($data['role']) ? mysqli_real_escape_string($conn, $data['role']) : 'Customer';

if ($role === 'Customer') {
    $sql = "DELETE FROM customer WHERE user_id='$id'";
} else {
    $sql = "DELETE FROM staff WHERE staff_id='$id'";
}

if(mysqli_query($conn, $sql)) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false, "error"=>mysqli_error($conn)]);
?>