<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password (usually empty)
$dbname = "masisso_db"; // The name of your database

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

// Helper function to read JSON POST data
function getPostData() {
    return json_decode(file_get_contents('php://input'), true);
}
?>