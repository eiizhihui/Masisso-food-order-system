<?php
require_once("../config.php");

$result = mysqli_query($conn, "SELECT id as reward_id, reward_name as title, points_required as bowls_required, image_url FROM rewards");

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
