<?php  
session_start();
include "../db_connect.php"; // Make sure this matches your actual DB file name!

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

    if (empty($login_input)) {
        header("Location: ../login.php?error=Username or Email is Required");
        exit(); 
    } else if (empty($password)) {
        header("Location: ../login.php?error=Password is Required");
        exit();
    } else {

        $password = md5($password);
        
        // 2. Query both the email AND username columns
        $sql = "SELECT * FROM customer WHERE (email = '$login_input' OR username = '$login_input') AND password = '$password' AND role = '$role'";
        
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            
            if ($row['password'] === $password && strcasecmp($row['role'], $role) === 0) {
                // 3. Save everything to the session
                $_SESSION['name'] = $row['name'];
                $_SESSION['user_id'] = $row['user_id']; 
                $_SESSION['role'] = $row['role'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username']; // Saving the newly added username

                header("Location: ../home.php");
                exit(); 

            } else {
                header("Location: ../login.php?error=Incorrect credentials or role");
                exit();
            }
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