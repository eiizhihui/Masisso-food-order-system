<?php

include 'db_connect.php';

// Start session to get the user's ID 
session_start();

// Check if the form was actually submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Grab the data sent from your cart.html form
    // Using default values if something is missing
    $order_total = isset($_POST['order_total']) ? floatval($_POST['order_total']) : 0.00;
    $order_type = isset($_POST['order_type']) ? $_POST['order_type'] : 'Delivery';
    $is_ajax = isset($_POST['ajax']) && $_POST['ajax'] === 'true';
    
    // Retrieve user_id from session with fallback
    $customer_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1111; 
    
    // 3. Prepare the SQL query to insert the order into the database
    $sql = "INSERT INTO Orders (user_id, order_type, total_price, order_status) 
            VALUES ('$customer_id', '$order_type', '$order_total', 'Pending')";

    // 4. Run the query and check if it worked
    if ($conn->query($sql) === TRUE) {
        // Order saved successfully! 
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            echo "<script>
                    alert('Success! Your $order_type order for RM $order_total has been placed.');
                    window.location.href = 'index.html';
                  </script>";
        }
    } else {
        // There was an error saving the order
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $conn->error]);
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
} else {
    // If someone tries to access this file directly without checking out, send them away.
    header("Location: cart.html");
    exit();
}
?>