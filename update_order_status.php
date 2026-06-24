<?php
session_start();

// Database Engine Connector Configuration
$conn = new mysqli("localhost", "root", "", "masisso_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate if the status is one of the allowed options
    $allowed_statuses = ['Pending', 'Preparing', 'Completed'];
    if (!in_array($new_status, $allowed_statuses)) {
        die("Security violation: Invalid order status.");
    }

    // Database Operation: UPDATE query execution
    $sql = "UPDATE orders SET status = '$new_status' WHERE order_id = $order_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Success: Order status updated to " . $new_status . "!'); window.location.href='view_orders.html';</script>";
    } else {
        echo "Error mutating active database records: " . $conn->error;
    }
}

$conn->close();
?>