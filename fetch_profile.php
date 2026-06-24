<?php
include 'db_connect.php';
header('Content-Type: application/json');

session_start();

// Retrieve user_id from session with fallback
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1111;

$sql = "SELECT name, email, phone, address, points FROM Customer WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'profile' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
}

$stmt->close();
$conn->close();
?>
