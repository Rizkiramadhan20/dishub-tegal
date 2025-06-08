<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate required fields
if (!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['description'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
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

// Get current video filename
$stmt = $db->prepare("SELECT video FROM education WHERE id = ?");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_video = $row['video'];
$stmt->close();

$video_filename = $current_video;

// Handle new video upload if provided
if (isset($_FILES['video']) && $_FILES['video']['size'] > 0) {
    $video = $_FILES['video'];
    $allowed_types = ['video/mp4', 'video/webm', 'video/quicktime'];
    $max_size = 100 * 1024 * 1024; // 100MB

    if (!in_array($video['type'], $allowed_types)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid video type. Only MP4, WebM and MOV are allowed']);
        exit;
    }

    if ($video['size'] > $max_size) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Video size too large. Maximum size is 100MB']);
        exit;
    }

    // Create uploads directory if it doesn't exist
    $upload_dir = __DIR__ . '/../../uploads/education/';
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
    $filename = uniqid() . '_' . basename($video['name']);
    $target_path = $upload_dir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($video['tmp_name'], $target_path)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error uploading video']);
        exit;
    }

    // Delete old video if exists
    if ($current_video && file_exists($upload_dir . $current_video)) {
        unlink($upload_dir . $current_video);
    }

    $video_filename = $filename;
}

// Prepare and execute the update query
$stmt = $db->prepare("UPDATE education SET title = ?, description = ?, video = ? WHERE id = ?");
$stmt->bind_param("sssi", $_POST['title'], $_POST['description'], $video_filename, $_POST['id']);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Content updated successfully']);
} else {
    // Delete new video if database update fails
    if ($video_filename !== $current_video) {
        unlink($upload_dir . $video_filename);
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error updating content: ' . $stmt->error]);
}

$stmt->close();
$db->close(); 