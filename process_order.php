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
    $delivery_fee = isset($_POST['delivery_fee']) ? floatval($_POST['delivery_fee']) : 0.00;
    $items = isset($_POST['items']) ? $_POST['items'] : '';
    $escaped_items = mysqli_real_escape_string($conn, $items);
    $is_ajax = isset($_POST['ajax']) && $_POST['ajax'] === 'true';
    
    $is_logged_in = isset($_SESSION['user_id']);
    $earned_points = 0;
    if ($is_logged_in) {
        $customer_id = $_SESSION['user_id'];
        $earned_points = floor($order_total);
    } else {
        // Guest user falls back to customer 1111 for foreign key constraint, but earns no points.
        $customer_id = 1111;
    }
    
    // 3. Prepare the SQL query to insert the order into the database
    $sql = "INSERT INTO Orders (user_id, order_type, total_price, items, delivery_fee, order_status) 
            VALUES ('$customer_id', '$order_type', '$order_total', '$escaped_items', '$delivery_fee', 'Pending')";

    // 4. Run the query and check if it worked
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;
        
        // Add points if logged in
        if ($is_logged_in && $earned_points > 0) {
            $update_sql = "UPDATE customer SET points = points + $earned_points WHERE user_id = $customer_id";
            $conn->query($update_sql);
        }

        // Order saved successfully! 
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'order_id' => $order_id,
                'earned_points' => $earned_points,
                'is_logged_in' => $is_logged_in
            ]);
        } else {
            echo "<script>
                    alert('Success! Your $order_type order for RM $order_total has been placed. You earned $earned_points points!');
                    window.location.href = 'home.php';
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
    header("Location: home.php");
    exit();
}
?>