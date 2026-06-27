<?php
require_once("../config.php");

session_start();

// Only admin and super admin can upload images for menu items
if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['admin', 'super admin'])) {
    echo json_encode(["success" => false, "error" => "Access denied."]);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode(["success" => false, "error" => "No file uploaded."]);
    exit;
}

$file = $_FILES['image'];

// Check for PHP upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "error" => "Upload failed with error code: " . $file['error']]);
    exit;
}

// Validate file type (image check)
$check = getimagesize($file['tmp_name']);
if ($check === false) {
    echo json_encode(["success" => false, "error" => "File is not an image."]);
    exit;
}

// Allow only certain image file formats
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($file_ext, $allowed_extensions)) {
    echo json_encode(["success" => false, "error" => "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed."]);
    exit;
}

// Max file size: 5MB
if ($file['size'] > 5000000) {
    echo json_encode(["success" => false, "error" => "File size exceeds the limit of 5MB."]);
    exit;
}

// Generate a unique safe name for the file to prevent conflicts
$new_filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
$target_dir = "../images/";
$target_file = $target_dir . $new_filename;

// Check if directory exists, if not create it
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (move_uploaded_file($file['tmp_name'], $target_file)) {
    echo json_encode(["success" => true, "image_url" => $new_filename]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to move uploaded file."]);
}
?>
