<?php
session_start();
include 'db_connect.php'; 

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = trim($_POST['staff_id']);
    $name     = trim($_POST['name']);
    $gender   = trim($_POST['gender']);
    $email    = trim($_POST['email']);

    if (empty($staff_id) || empty($name) || empty($gender) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill out all fields.']);
        exit();
    }

    $query = "INSERT INTO staff_profiles (staff_id, name, gender, email) 
              VALUES (?, ?, ?, ?) 
              ON DUPLICATE KEY UPDATE name = ?, gender = ?, email = ?";
              
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("sssssss", $staff_id, $name, $gender, $email, $name, $gender, $email);
        
        if ($stmt->execute()) {
            // Save this exact ID into the session memory!
            $_SESSION['last_viewed_id'] = $staff_id;
            echo json_encode(['status' => 'success', 'staff_id' => $staff_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database Execution Failed: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database Preparation Failed: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method']);
}

$conn->close();
?>