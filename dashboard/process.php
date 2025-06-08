<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once '../config/db.php';

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'edit_profile':
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $user_id = $_SESSION['user']['id'];

        // Validate input
        if (empty($fullname) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        // Check if email is already taken by another user
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email is already taken']);
            exit;
        }

        // Update user profile
        $stmt = $db->prepare("UPDATE users SET fullname = ?, email = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $fullname, $email, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
        }
        break;

    case 'change_password':
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $user_id = $_SESSION['user']['id'];

        // Validate input
        if (empty($current_password) || empty($new_password)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        // Get current user data
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            exit;
        }

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $stmt = $db->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to change password']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
} 