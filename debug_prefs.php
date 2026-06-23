<?php
include 'db_connect.php';
$sql = "SELECT item_id, name, preferences FROM menu_items";
$result = $conn->query($sql);
if (!$result) {
    echo "Error: " . $conn->error;
} else {
    while($row = $result->fetch_assoc()) {
        echo $row['item_id'] . " | " . $row['name'] . " | Prefs: " . $row['preferences'] . "\n";
    }
}
$conn->close();
?>
