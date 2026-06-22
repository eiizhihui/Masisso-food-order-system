<?php
require_once 'db_connect.php';
$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$sql = "SELECT * FROM offers WHERE code LIKE '%$q%' OR title LIKE '%$q%' OR description LIKE '%$q%'";
$result = $conn->query($sql);
$data = [];
if($result) while($row = $result->fetch_assoc()) $data[] = $row;
echo json_encode($data);
?>