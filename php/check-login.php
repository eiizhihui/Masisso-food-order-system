<?php  
session_start();
include "../db_connect.php"; // Make sure this matches your actual DB file name!

$log_file = __DIR__ . "/../login_debug.log";
function write_log($msg) {
    global $log_file;
    file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] " . $msg . "\n", FILE_APPEND);
}

// 1. Changed to look for 'username' which contains either the username or the email
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $login_input = test_input($_POST['username']); // Holds either email or username
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);

    write_log("Login attempt - Input: '$login_input', Role: '$role'");

    if (empty($login_input)) {
        write_log("  Failed: Username/Email empty");
        header("Location: ../login.php?error=Username or Email is Required");
        exit(); 
    } else if (empty($password)) {
        write_log("  Failed: Password empty");
        header("Location: ../login.php?error=Password is Required");
        exit();
    } else {

        $plain_password = $password;
        $hashed_password = md5($password);
        
        $user_found = false;
        $row = null;
        
        // 1. Try customer table
        $sql = "SELECT * FROM customer WHERE (email = '$login_input' OR username = '$login_input') AND role = '$role'";
        write_log("  Customer query: $sql");
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            write_log("    Row found in customer. Hashed DB pass: '{$row['password']}', Input MD5: '$hashed_password'");
            if ($row['password'] === $hashed_password || $row['password'] === $plain_password) {
                $user_found = true;
                write_log("    Password MATCH in customer table!");
            } else {
                write_log("    Password mismatch in customer table.");
            }
        } else {
            write_log("    No match or multiple matches in customer table. Rows: " . ($result ? mysqli_num_rows($result) : 0));
        }
        
        // 2. If not found and role is admin/staff/super admin, try staff table
        if (!$user_found && ($role === 'admin' || $role === 'staff' || $role === 'super admin')) {
            if ($role === 'admin' || $role === 'super admin') {
                $sql = "SELECT staff_id as user_id, name, username, email, password, position as role FROM staff WHERE (email = '$login_input' OR username = '$login_input' OR name = '$login_input') AND (position = 'admin' OR position = 'super admin')";
            } else {
                $sql = "SELECT staff_id as user_id, name, username, email, password, position as role FROM staff WHERE (email = '$login_input' OR username = '$login_input' OR name = '$login_input') AND position = 'staff'";
            }
            write_log("  Staff query: $sql");
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                write_log("    Row found in staff. DB pass: '{$row['password']}', Input MD5: '$hashed_password', Input Plain: '$plain_password'");
                if ($row['password'] === $hashed_password || $row['password'] === $plain_password) {
                    $user_found = true;
                    write_log("    Password MATCH in staff table!");
                } else {
                    write_log("    Password mismatch in staff table.");
                }
            } else {
                write_log("    No match or multiple matches in staff table. Rows: " . ($result ? mysqli_num_rows($result) : 0));
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
            write_log("  Success! User ID: {$_SESSION['user_id']}, Role: {$_SESSION['role']}. Redirecting based on role: $role_lower");
            if ($role_lower === 'admin' || $role_lower === 'super admin') {
                header("Location: ../admin-dashboard.php");
            } elseif ($role_lower === 'staff') {
                header("Location: ../staff_dashboard.php");
            } else {
                header("Location: ../home.php");
            }
            exit();
        } else {
            write_log("  Failed: Incorrect credentials");
            header("Location: ../login.php?error=Incorrect username/email or password");
            exit();
        }
    }
    
} else {
    write_log("Failed: POST data missing");
    header("Location: ../login.php");
    exit();
}
?>