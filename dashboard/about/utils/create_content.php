<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if required fields are present
if (!isset($_POST['title']) || !isset($_POST['text']) || !isset($_POST['description']) || !isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Include database configuration
require_once '../../../config/db.php';

// Validate image
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

if (!in_array($_FILES['image']['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid image type. Only JPEG, PNG, and GIF are allowed']);
    exit;
}

if ($_FILES['image']['size'] > $maxFileSize) {
    echo json_encode(['success' => false, 'message' => 'Image size exceeds 5MB limit']);
    exit;
}

// Create uploads directory if it doesn't exist
$uploadDir = '../../uploads/about/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit;
    }
}

// Check if directory is writable
if (!is_writable($uploadDir)) {
    echo json_encode(['success' => false, 'message' => 'Upload directory is not writable']);
    exit;
}

// Generate unique filename
$fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '.' . $fileExtension;
$targetPath = $uploadDir . $filename;

// Move uploaded file
if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    try {
        // Insert into database
        $stmt = $db->prepare("INSERT INTO about (title, text, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['title'], $_POST['text'], $_POST['description'], $filename);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Content created successfully']);
        } else {
            // Delete uploaded file if database insert fails
            unlink($targetPath);
            echo json_encode(['success' => false, 'message' => 'Failed to create content: ' . $stmt->error]);
        }
        $stmt->close();
    } catch (Exception $e) {
        // Delete uploaded file if database operation fails
        unlink($targetPath);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
}

$db->close(); 