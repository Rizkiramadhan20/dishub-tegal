<?php
session_start();
require_once 'config/db.php';

// Validate input
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];
    sendResponse($response);
}

// Get and sanitize input
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
    $response = [
        'success' => false,
        'message' => 'All fields are required'
    ];
    sendResponse($response);
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = [
        'success' => false,
        'message' => 'Invalid email format'
    ];
    sendResponse($response);
}

try {
    // Insert into database with status 'unread'
    $stmt = $db->prepare("INSERT INTO contacts (first_name, last_name, email, message, status) VALUES (?, ?, ?, ?, 'unread')");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $message);
    
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Thank you for your message. We will get back to you soon!'
        ];
    } else {
        throw new Exception($stmt->error);
    }
    
    $stmt->close();
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Failed to send message. Please try again later.'
    ];
}

sendResponse($response);

function sendResponse($response) {
    // Check if the request is AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Regular form submission
        $_SESSION['toast'] = [
            'message' => $response['message'],
            'type' => $response['success'] ? 'success' : 'error'
        ];
        header('Location: index.php#contact');
    }
    exit;
} 