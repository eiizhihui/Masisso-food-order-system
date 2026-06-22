<?php
include 'db_connect.php';
header('Content-Type: application/json');

// Check if the JavaScript sent a code
if(isset($_GET['code'])) {
    // Protect against SQL injection
    $code = $conn->real_escape_string($_GET['code']);
    
    // Look for the code in the database, ONLY if it hasn't expired!
    $sql = "SELECT * FROM offers WHERE code = '$code' AND valid_until >= CURRENT_DATE";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        // Code exists and is valid! Send the discount details back.
        $offer = $result->fetch_assoc();
        echo json_encode(['success' => true, 'offer' => $offer]);
    } else {
        // Code doesn't exist or is expired.
        echo json_encode(['success' => false, 'message' => 'Invalid or expired voucher code.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No code provided.']);
}

$conn->close();
?>