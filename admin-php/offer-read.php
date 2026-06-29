<?php
require_once("../config.php");

session_start();
if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ["admin", "super admin"])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}
$result = mysqli_query($conn, "SELECT * FROM offers");
$data = [];
while($row = mysqli_fetch_assoc($result)) $data[] = $row;
echo json_encode($data);
?>