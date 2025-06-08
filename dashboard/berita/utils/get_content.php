<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate required fields
if (!isset($_GET['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
    exit;
}

// Database connection
require_once '../../../config/db.php';
$db = getDBConnection();

if ($db->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

// Prepare and execute the query
$stmt = $db->prepare("SELECT id, title, slug, description, content, image FROM berita WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $row['id'],
            'title' => $row['title'],
            'slug' => $row['slug'],
            'description' => $row['description'],
            'content' => $row['content'],
            'image' => $row['image']
        ]
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Berita tidak ditemukan']);
}

$stmt->close();
$db->close(); 