<?php
session_start();

// Enable absolute error displaying for troubleshooting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli("localhost", "root", "", "masisso_db");
} catch (Exception $e) {
    echo "CONNECTION_ERROR: " . $e->getMessage();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $status = 'Available';

    if (empty($item_name) || empty($price) || !is_numeric($price) || floatval($price) <= 0) {
        echo "VALIDATION_ERROR: Please verify item name and ensure price is a positive number.";
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO menu_items (item_name, description, price, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $item_name, $description, $price, $status);
        $stmt->execute();
        $stmt->close();
        
        // Output keyword needed by fetch script
        echo "SUCCESS";
    } catch (Exception $e) {
        // Pushes the exact SQL database reason to your top banner alert
        echo "DATABASE_ERROR: " . $e->getMessage();
    }
}
$conn->close();
?>