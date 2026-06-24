<?php
require_once("../config.php");
$query = "
    SELECT user_id, name, username, email, phone, address, points as bowls, 'Customer' as role, NULL as gender, NULL as branch FROM customer
    UNION ALL
    SELECT staff_id as user_id, name, username, email, phone, NULL as address, 0 as bowls, position as role, gender, branch FROM staff
";
$result = mysqli_query($conn, $query);
$data = [];
if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}
echo json_encode($data);
?>