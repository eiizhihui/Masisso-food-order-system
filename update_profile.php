<?php
include 'db_connect.php';
header('Content-Type: application/json');

// Get JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    // For now, hardcode user_id 1111
    $user_id = 1111;
    
    // Protect against SQL injection
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);
    $address = $conn->real_escape_string($data['address']);

    $sql = "UPDATE users SET name=?, email=?, phone=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update database.']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data received.']);
}

$conn->close();
?>
