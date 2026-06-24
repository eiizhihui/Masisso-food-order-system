<?php
require_once("../config.php");
// Staff can only read from the staff table (not customers)
$query = "SELECT staff_id as user_id, name, email, phone, gender, branch, position as role FROM staff";
$result = mysqli_query($conn, $query);
$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}
echo json_encode($data);
?>