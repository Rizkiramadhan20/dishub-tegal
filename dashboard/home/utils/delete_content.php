<?php
session_start();
header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

// Get JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate required fields
if (!isset($data['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID is required'
    ]);
    exit;
}

// Database connection
require_once '../../../config/db.php';
$db = getDBConnection();

// Get image filename before deleting
$stmt = $db->prepare("SELECT image FROM home WHERE id = ?");
$stmt->bind_param("i", $data['id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode([
        'success' => false,
        'message' => 'Content not found'
    ]);
    exit;
}

// Prepare and execute delete query
$stmt = $db->prepare("DELETE FROM home WHERE id = ?");
$stmt->bind_param("i", $data['id']);

if ($stmt->execute()) {
    // Delete image file if exists
    $imagePath = __DIR__ . '/../../uploads/home/' . $row['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
    echo json_encode([
        'success' => true,
        'message' => 'Content deleted successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting content: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close(); 