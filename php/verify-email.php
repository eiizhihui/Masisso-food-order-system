<?php
header('Content-Type: application/json');
include "../db_connect.php";

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        echo json_encode(["success" => false, "message" => "Email is required."]);
        exit();
    }
    
    // Check if email exists in customer or staff table
    $email_exists = false;
    
    $stmt_cust = $conn->prepare("SELECT user_id FROM customer WHERE email = ?");
    if ($stmt_cust) {
        $stmt_cust->bind_param("s", $email);
        $stmt_cust->execute();
        $stmt_cust->store_result();
        if ($stmt_cust->num_rows > 0) {
            $email_exists = true;
        }
        $stmt_cust->close();
    }
    
    if (!$email_exists) {
        $stmt_staff = $conn->prepare("SELECT staff_id FROM staff WHERE email = ?");
        if ($stmt_staff) {
            $stmt_staff->bind_param("s", $email);
            $stmt_staff->execute();
            $stmt_staff->store_result();
            if ($stmt_staff->num_rows > 0) {
                $email_exists = true;
            }
            $stmt_staff->close();
        }
    }
    
    echo json_encode(["success" => true, "exists" => $email_exists]);
} else {
    echo json_encode(["success" => false, "message" => "No email provided."]);
}
$conn->close();
?>
