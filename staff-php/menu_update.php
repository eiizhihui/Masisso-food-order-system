<?php
require_once("../config.php");
$data = getPostData();
if (!$data || !isset($data['item_id']) || !isset($data['is_available'])) {
    echo json_encode(["success" => false, "error" => "Missing item_id or is_available"]);
    exit;
}
$id = (int) $data['item_id'];
$is_available = (int) $data['is_available'] === 1 ? 1 : 0; // sanitize to 0 or 1 only

$sql = "UPDATE menu_items SET is_available='$is_available' WHERE item_id='$id'";
if (mysqli_query($conn, $sql)) echo json_encode(["success" => true]);
else echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
?>