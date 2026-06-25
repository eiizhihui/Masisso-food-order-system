<?php
require_once("../config.php");

$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$sql = "SELECT * FROM offers WHERE code LIKE '%$q%' OR title LIKE '%$q%' OR description LIKE '%$q%'";
$result = mysqli_query($conn, $sql);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
