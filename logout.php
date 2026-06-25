<?php  
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
    <script>
        localStorage.clear();
        window.location.href = "login.php";
    </script>
</head>
<body>
</body>
</html>