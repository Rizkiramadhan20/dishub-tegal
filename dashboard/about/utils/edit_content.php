<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if required fields are present
if (!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['text']) || !isset($_POST['description'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Include database configuration
require_once '../../../config/db.php';

try {
    // Get current image filename
    $stmt = $db->prepare("SELECT image FROM about WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Content not found']);
        exit;
    }
    
    $currentImage = $row['image'];
    $stmt->close();

    $filename = $currentImage;

    // Handle new image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
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
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
        }

        // Delete old image if exists
        if ($currentImage && file_exists($uploadDir . $currentImage)) {
            unlink($uploadDir . $currentImage);
        }
    }

    // Update content in database
    $stmt = $db->prepare("UPDATE about SET title = ?, text = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $_POST['title'], $_POST['text'], $_POST['description'], $filename, $_POST['id']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Content updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update content: ' . $stmt->error]);
    }
    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$db->close(); 