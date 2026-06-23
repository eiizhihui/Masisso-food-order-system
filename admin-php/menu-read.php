<?php
require_once("config.php");
$result = mysqli_query($conn, "SELECT * FROM menu_items");
$data = [];
while($row = mysqli_fetch_assoc($result)) $data[] = $row;
echo json_encode($data);
?>