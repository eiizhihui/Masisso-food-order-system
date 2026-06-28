<?php  
session_start();
include "../db_connect.php"; // Make sure this matches your actual DB file name!

// 1. Changed to look for 'username' which contains either the username or the email
if (isset($_POST['username']) && isset($_POST['password'])) {

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $login_input = test_input($_POST['username']); // Holds either email or username
    $password = test_input($_POST['password']);
    $role = isset($_POST['role']) ? test_input($_POST['role']) : 'customer';

    if (empty($login_input)) {
        header("Location: ../login.php?error=Username or Email is Required");
        exit(); 
    } else if (empty($password)) {
        header("Location: ../login.php?error=Password is Required");
        exit();
    } else {

        $plain_password = $password;
        
        $user_found = false;
        $row = null;
        $escaped_input = mysqli_real_escape_string($conn, $login_input);
        
        if ($role === 'customer') {
            // 1. Try customer table first
            $sql = "SELECT * FROM customer WHERE (email = '$escaped_input' OR username = '$escaped_input')";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $row['role'] = 'Customer';
                if ($row['password'] === $plain_password) {
                    $user_found = true;
                }
            }
            
            // 2. Try staff table as fallback if not found
            if (!$user_found) {
                $sql = "SELECT staff_id as user_id, name, username, email, password, position as role FROM staff WHERE (email = '$escaped_input' OR username = '$escaped_input' OR name = '$escaped_input')";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) === 1) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row['password'] === $plain_password) {
                        $user_found = true;
                    }
                }
            }
        } else {
            // 1. Try staff table first (for staff, admin, super admin)
            $sql = "SELECT staff_id as user_id, name, username, email, password, position as role FROM staff WHERE (email = '$escaped_input' OR username = '$escaped_input' OR name = '$escaped_input')";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if ($row['password'] === $plain_password) {
                    $user_found = true;
                }
            }
            
            // 2. Try customer table as fallback if not found
            if (!$user_found) {
                $sql = "SELECT * FROM customer WHERE (email = '$escaped_input' OR username = '$escaped_input')";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) === 1) {
                    $row = mysqli_fetch_assoc($result);
                    $row['role'] = 'Customer';
                    if ($row['password'] === $plain_password) {
                        $user_found = true;
                    }
                }
            }
        }
        
        if ($user_found) {
            // Save to session
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id']; 
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = isset($row['username']) ? $row['username'] : $row['name'];

            $role_lower = strtolower($row['role']);
            if ($role_lower === 'admin' || $role_lower === 'super admin') {
                header("Location: ../admin-dashboard.php");
            } elseif ($role_lower === 'staff') {
                header("Location: ../staff_dashboard.php");
            } else {
                header("Location: ../home.php");
            }
            exit();
        } else {
            header("Location: ../login.php?error=Incorrect username/email or password");
            exit();
        }
    }
    
} else {
    header("Location: ../login.php");
    exit();
}
?>