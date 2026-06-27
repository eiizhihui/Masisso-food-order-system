<?php  
session_start();
include "../db_connect.php";

// 1. Added $_POST['username'] to the check
if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role']) && isset($_POST['phone']) && isset($_POST['address'])) {

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = test_input($_POST['name']);
    $username = test_input($_POST['username']); // 2. Capture the username input
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);
    $phone = test_input($_POST['phone']);
    $address = test_input($_POST['address']);

    // 3. Added all fields to basic validation
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($phone) || empty($address)) {
        header("Location: ../register.php?error=All fields are required");
        exit();
    } else {
        $hashed_password = md5($password);

        // 4. Check if EITHER the username OR the email is already taken
        $stmt_check = $conn->prepare("SELECT user_id FROM customer WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            header("Location: ../register.php?error=Username or Email is already taken");
            exit();
        } else {
            // 5. Insert all fields into the database including the username, phone, address and starting points (0)
            $stmt_insert = $conn->prepare("INSERT INTO customer (name, username, email, password, role, phone, address, points) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt_insert->bind_param("sssssss", $name, $username, $email, $hashed_password, $role, $phone, $address);
            
            if ($stmt_insert->execute()) {
                header("Location: ../login.php?success=Account created successfully! Please log in.");
                exit();
            } else {
                header("Location: ../register.php?error=An unknown error occurred. Please try again.");
                exit();
            }
        }

        $stmt_check->close();
        $stmt_insert->close();
    }
} else {
    header("Location: ../register.php");
    exit();
}
?>