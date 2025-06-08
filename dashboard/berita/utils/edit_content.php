<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate required fields
if (!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['slug']) || !isset($_POST['description']) || !isset($_POST['content'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
    exit;
}

// Sanitize and validate description
$description = trim($_POST['description']);
if (empty($description)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Deskripsi tidak boleh kosong']);
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

// Get current image filename
$stmt = $db->prepare("SELECT image FROM berita WHERE id = ?");
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
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error saat mengupload gambar']);
        exit;
    }

    // Delete old image if exists
    if ($current_image && file_exists($upload_dir . $current_image)) {
        unlink($upload_dir . $current_image);
    }

    $image_filename = $filename;
}

// Prepare and execute the update query
$stmt = $db->prepare("UPDATE berita SET title = ?, slug = ?, description = ?, content = ?, image = ? WHERE id = ?");
$stmt->bind_param("sssssi", $_POST['title'], $_POST['slug'], $_POST['description'], $_POST['content'], $image_filename, $_POST['id']);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Berita berhasil diperbarui']);
} else {
    // Delete new image if database update fails
    if ($image_filename !== $current_image) {
        unlink($upload_dir . $image_filename);
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error saat memperbarui berita: ' . $stmt->error]);
}

$stmt->close();
$db->close(); 