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

        // 4. Check if the username or email is already taken in customer or staff table
        $username_exists = false;
        $email_exists = false;

        // Check customer table for username
        $stmt_user_cust = $conn->prepare("SELECT user_id FROM customer WHERE username = ?");
        $stmt_user_cust->bind_param("s", $username);
        $stmt_user_cust->execute();
        $stmt_user_cust->store_result();
        if ($stmt_user_cust->num_rows > 0) {
            $username_exists = true;
        }
        $stmt_user_cust->close();

        // Check staff table for username
        if (!$username_exists) {
            $stmt_user_staff = $conn->prepare("SELECT staff_id FROM staff WHERE username = ?");
            $stmt_user_staff->bind_param("s", $username);
            $stmt_user_staff->execute();
            $stmt_user_staff->store_result();
            if ($stmt_user_staff->num_rows > 0) {
                $username_exists = true;
            }
            $stmt_user_staff->close();
        }

        // Check customer table for email
        $stmt_email_cust = $conn->prepare("SELECT user_id FROM customer WHERE email = ?");
        $stmt_email_cust->bind_param("s", $email);
        $stmt_email_cust->execute();
        $stmt_email_cust->store_result();
        if ($stmt_email_cust->num_rows > 0) {
            $email_exists = true;
        }
        $stmt_email_cust->close();

        // Check staff table for email
        if (!$email_exists) {
            $stmt_email_staff = $conn->prepare("SELECT staff_id FROM staff WHERE email = ?");
            $stmt_email_staff->bind_param("s", $email);
            $stmt_email_staff->execute();
            $stmt_email_staff->store_result();
            if ($stmt_email_staff->num_rows > 0) {
                $email_exists = true;
            }
            $stmt_email_staff->close();
        }

        if ($username_exists && $email_exists) {
            header("Location: ../register.php?error=Username and Email are already taken");
            exit();
        } else if ($username_exists) {
            header("Location: ../register.php?error=Username is already taken");
            exit();
        } else if ($email_exists) {
            header("Location: ../register.php?error=Email is already taken");
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