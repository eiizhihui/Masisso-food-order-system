<?php
session_start();
include 'db_connect.php'; 

header('Content-Type: application/json');

$staff_id = '';

// Check A: If looking for the latest active profile, look in session first
if (isset($_GET['latest']) && $_GET['latest'] === 'true') {
    if (isset($_SESSION['last_viewed_id']) && !empty($_SESSION['last_viewed_id'])) {
        $staff_id = $_SESSION['last_viewed_id'];
    } else {
        // Ultimate fallback if session is completely empty
        $staff_id = 'STF001'; 
    }
} 
// Check B: Otherwise use the specific target ID from the URL string
else if (isset($_GET['id']) && !empty($_GET['id'])) {
    $staff_id = trim($_GET['id']);
    // Update session tracker so it stays aligned
    $_SESSION['last_viewed_id'] = $staff_id;
} else {
    $staff_id = 'STF001'; 
}

$query = "SELECT staff_id, name, gender, email FROM staff_profiles WHERE staff_id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No staff rows found matching: ' . $staff_id]);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Database query preparation failure']);
}

$conn->close();
?>