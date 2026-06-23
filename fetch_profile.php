<?php
include 'db_connect.php';
header('Content-Type: application/json');

// For now, we hardcode user_id 1111 (Joey). 
// Once you add a Login page, this should come from $_SESSION['user_id']
$user_id = 1111;

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
