<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate required fields
if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_FILES['chunk'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Get upload parameters
$chunk = $_FILES['chunk'];
$chunkIndex = $_POST['chunkIndex'];
$totalChunks = $_POST['totalChunks'];
$uploadId = $_POST['uploadId'];
$fileName = $_POST['fileName'];

// Create temporary directory for chunks
$tempDir = __DIR__ . '/../../uploads/temp/' . $uploadId;
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Move chunk to temporary directory
$chunkPath = $tempDir . '/' . $chunkIndex;
if (!move_uploaded_file($chunk['tmp_name'], $chunkPath)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error saving chunk']);
    exit;
}

// If this is the last chunk, combine all chunks
if ($chunkIndex == $totalChunks - 1) {
    $finalPath = __DIR__ . '/../../uploads/education/' . $fileName;
    $out = fopen($finalPath, "wb");

    if ($out) {
        for ($i = 0; $i < $totalChunks; $i++) {
            $in = fopen($tempDir . '/' . $i, "rb");
            if ($in) {
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                fclose($in);
                unlink($tempDir . '/' . $i);
            }
        }
        fclose($out);
        rmdir($tempDir);

        // Database connection
        require_once '../../../config/db.php';
        $db = getDBConnection();

        if ($db->connect_error) {
            unlink($finalPath);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Database connection failed']);
            exit;
        }

        // Prepare and execute the query
        $stmt = $db->prepare("INSERT INTO education (title, description, video) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['title'], $_POST['description'], $fileName);

        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Content created successfully']);
        } else {
            unlink($finalPath);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error creating content: ' . $stmt->error]);
        }

        $stmt->close();
        $db->close();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error combining chunks']);
    }
} else {
    // Return success for intermediate chunks
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} 