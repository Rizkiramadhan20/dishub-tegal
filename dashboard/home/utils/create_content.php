<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate required fields
if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_FILES['image'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Handle image upload
$image = $_FILES['image'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 5 * 1024 * 1024; // 5MB

// Debug information
error_log("Upload attempt - File info: " . print_r($image, true));

if ($image['error'] !== UPLOAD_ERR_OK) {
    $error_message = match($image['error']) {
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
        default => 'Unknown upload error'
    };
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Upload error: ' . $error_message]);
    exit;
}

if (!in_array($image['type'], $allowed_types)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid image type. Only JPG, PNG and GIF are allowed']);
    exit;
}

if ($image['size'] > $max_size) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Image size too large. Maximum size is 5MB']);
    exit;
}

// Create uploads directory if it doesn't exist
$upload_dir = __DIR__ . '/../../uploads/home/';
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit;
    }
}

// Ensure directory is writable
if (!is_writable($upload_dir)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Upload directory is not writable']);
    exit;
}

// Generate unique filename
$filename = uniqid() . '_' . basename($image['name']);
$target_path = $upload_dir . $filename;

// Move uploaded file
if (!move_uploaded_file($image['tmp_name'], $target_path)) {
    error_log("Failed to move uploaded file to: " . $target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error uploading image']);
    exit;
}

// Verify the file was actually moved and is readable
if (!file_exists($target_path) || !is_readable($target_path)) {
    error_log("File not found or not readable after move: " . $target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error verifying uploaded image']);
    exit;
}

// Database connection
require_once '../../../config/db.php';
$db = getDBConnection();

if ($db->connect_error) {
    // Delete uploaded file if database connection fails
    unlink($target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Prepare and execute the query
$stmt = $db->prepare("INSERT INTO home (title, description, image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_POST['title'], $_POST['description'], $filename);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Content created successfully']);
} else {
    // Delete uploaded file if database insert fails
    unlink($target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error creating content: ' . $stmt->error]);
}

$stmt->close();
$db->close(); 