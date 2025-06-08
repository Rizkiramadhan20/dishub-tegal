<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate required fields
if (!isset($_POST['id']) || !isset($_POST['title']) || trim($_POST['title']) === '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Title cannot be empty']);
    exit;
}

// Database connection
require_once '../../../config/db.php';
$db = getDBConnection();

if ($db->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get current image filename
$stmt = $db->prepare("SELECT image FROM gallery WHERE id = ?");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_image = $row['image'];
$stmt->close();

$image_filename = $current_image;

// Handle new image upload if provided
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $image = $_FILES['image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

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
    $upload_dir = __DIR__ . '/../../uploads/gallery/';
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
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error uploading image']);
        exit;
    }

    // Delete old image if exists
    if ($current_image && file_exists($upload_dir . $current_image)) {
        unlink($upload_dir . $current_image);
    }

    $image_filename = $filename;
}

// Prepare and execute the update query
$stmt = $db->prepare("UPDATE gallery SET title = ?, image = ? WHERE id = ?");
$stmt->bind_param("ssi", $_POST['title'], $image_filename, $_POST['id']);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Content updated successfully']);
} else {
    // Delete new image if database update fails
    if ($image_filename !== $current_image) {
        unlink($upload_dir . $image_filename);
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error updating content: ' . $stmt->error]);
}

$stmt->close();
$db->close(); 