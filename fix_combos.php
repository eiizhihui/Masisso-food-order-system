<?php
include 'db_connect.php';

$queries = [
    "UPDATE menu_items SET preferences = '[\"No Coriander\", \"No Shrimp Sauce\", \"Extra Sambal\", \"Less Ice\", \"Less Sugar\"]' WHERE item_id = 3",
    "UPDATE menu_items SET preferences = '[\"No Coriander\", \"No Shrimp Sauce\", \"Extra Sambal\", \"No Spicy\", \"More Spicy\"]' WHERE item_id = 4"
];

foreach ($queries as $q) {
    if ($conn->query($q) === TRUE) {
        echo "Success\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}
$conn->close();
?>
