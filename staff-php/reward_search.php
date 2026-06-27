<?php
require_once("../config.php");

$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$sql = "SELECT id as reward_id, reward_name as title, points_required as bowls_required, image_url FROM rewards WHERE reward_name LIKE '%$q%'";
$result = mysqli_query($conn, $sql);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
