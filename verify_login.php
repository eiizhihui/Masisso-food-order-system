<?php
include "db_connect.php";

function test_login($login_input, $password, $role) {
    global $conn;
    echo "Testing login with Input: '$login_input', Password: '$password', Role: '$role'\n";
    
    $plain_password = $password;
    $hashed_password = md5($password);
    
    $user_found = false;
    $row = null;
    $escaped_input = mysqli_real_escape_string($conn, $login_input);
    
    if ($role === 'customer') {
        // 1. Try customer table first
        $sql = "SELECT * FROM customer WHERE (email = '$escaped_input' OR username = '$escaped_input')";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['password'] === $hashed_password || $row['password'] === $plain_password) {
                $user_found = true;
            }
        }
        
        // 2. Try staff table as fallback if not found
        if (!$user_found) {
            $sql = "SELECT staff_id as user_id, name, username, email, password, position as role FROM staff WHERE (email = '$escaped_input' OR username = '$escaped_input' OR name = '$escaped_input')";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if ($row['password'] === $hashed_password || $row['password'] === $plain_password) {
                    $user_found = true;
                }
            }
        }
    } else {
        // 1. Try staff table first
        $sql = "SELECT staff_id as user_id, name, username, email, password, position as role FROM staff WHERE (email = '$escaped_input' OR username = '$escaped_input' OR name = '$escaped_input')";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['password'] === $hashed_password || $row['password'] === $plain_password) {
                $user_found = true;
            }
        }
        
        // 2. Try customer table as fallback if not found
        if (!$user_found) {
            $sql = "SELECT * FROM customer WHERE (email = '$escaped_input' OR username = '$escaped_input')";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if ($row['password'] === $hashed_password || $row['password'] === $plain_password) {
                    $user_found = true;
                }
            }
        }
    }
    
    if ($user_found) {
        echo "  [SUCCESS] Match found: Name = '{$row['name']}', Role = '{$row['role']}', Email = '{$row['email']}'\n";
    } else {
        echo "  [FAILED] Incorrect username/email or password\n";
    }
    echo "\n";
}

// Case 1: Customer username login
test_login("joey", "1223334444", "customer");

// Case 2: Customer email login
test_login("joeybaobei@gmail.com", "1223334444", "customer");

// Case 3: Staff username login
test_login("jinxuan", "staffPass1", "staff");

// Case 4: Staff email login with fallback (selected role: customer)
test_login("jinxuan@masisso.com", "staffPass1", "customer");

// Case 5: Staff email login with priority (selected role: staff)
test_login("jinxuan@masisso.com", "staffPass1", "staff");

// Case 6: Password mismatch
test_login("joey", "wrongpass", "customer");

// Case 7: Non-existent user
test_login("nonexistent", "somepass", "customer");
?>
