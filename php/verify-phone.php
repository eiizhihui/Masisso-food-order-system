<?php
header('Content-Type: application/json');
include "../db_connect.php";

if (isset($_POST['phone'])) {
    $phone = trim($_POST['phone']);
    
    if (empty($phone)) {
        echo json_encode(["success" => false, "message" => "Phone number is required."]);
        exit();
    }
    
    // Check if phone exists in the customer table
    $stmt = $conn->prepare("SELECT user_id FROM customer WHERE phone = ?");
    if ($stmt) {
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo json_encode(["success" => true, "exists" => true]);
        } else {
            echo json_encode(["success" => true, "exists" => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Database query preparation failure."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No phone number provided."]);
}
$conn->close();
?>
