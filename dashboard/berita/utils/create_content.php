<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate required fields
if (!isset($_POST['title']) || !isset($_POST['slug']) || !isset($_POST['description']) || !isset($_POST['content']) || !isset($_FILES['image'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
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
        UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi batas maksimum yang diizinkan',
        UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas yang ditentukan dalam form',
        UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
        UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
        UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP',
        default => 'Error upload tidak diketahui'
    };
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error upload: ' . $error_message]);
    exit;
}

if (!in_array($image['type'], $allowed_types)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tipe file tidak valid. Hanya JPG, PNG dan GIF yang diizinkan']);
    exit;
}

if ($image['size'] > $max_size) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB']);
    exit;
}

// Create uploads directory if it doesn't exist
$upload_dir = __DIR__ . '/../../uploads/berita/';
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Gagal membuat direktori upload']);
        exit;
    }
}

// Ensure directory is writable
if (!is_writable($upload_dir)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Direktori upload tidak dapat ditulis']);
    exit;
}

// Generate unique filename
$filename = uniqid() . '_' . basename($image['name']);
$target_path = $upload_dir . $filename;

// Move uploaded file
if (!move_uploaded_file($image['tmp_name'], $target_path)) {
    error_log("Failed to move uploaded file to: " . $target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error saat mengupload gambar']);
    exit;
}

// Verify the file was actually moved and is readable
if (!file_exists($target_path) || !is_readable($target_path)) {
    error_log("File not found or not readable after move: " . $target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error saat memverifikasi gambar yang diupload']);
    exit;
}

// Database connection
require_once '../../../config/db.php';
$db = getDBConnection();

if ($db->connect_error) {
    // Delete uploaded file if database connection fails
    unlink($target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

// Prepare and execute the query
$stmt = $db->prepare("INSERT INTO berita (title, slug, description, content, image) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $_POST['title'], $_POST['slug'], $_POST['description'], $_POST['content'], $filename);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Berita berhasil dibuat']);
} else {
    // Delete uploaded file if database insert fails
    unlink($target_path);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error saat membuat berita: ' . $stmt->error]);
}

$stmt->close();
$db->close(); 