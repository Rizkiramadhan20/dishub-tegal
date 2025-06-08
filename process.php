<?php
session_start();
require 'config/db.php';

// Set headers to allow POST method
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle AJAX requests
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'edit_profile':
            if (!isset($_SESSION['user'])) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
                exit;
            }

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
            exit;

        case 'change_password':
            if (!isset($_SESSION['user'])) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
                exit;
            }

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
                // Destroy session
                session_destroy();
                echo json_encode(['success' => true, 'message' => 'Password changed successfully. Please login again.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to change password']);
            }
            exit;
    }
}

// REGISTER
if (isset($_POST['register'])) {
    $name = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        header("Location: register.php?error=All fields are required");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=Invalid email format");
        exit;
    }

    if (strlen($password) < 6) {
        header("Location: register.php?error=Password must be at least 6 characters");
        exit;
    }

    // Check if email exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=Email already registered");
        exit;
    }

    // Hash password and insert user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    
    if ($stmt->execute()) {
        header("Location: login.php?success=Registration successful! You can now login with your account.");
    } else {
        header("Location: register.php?error=Registration failed. Please try again.");
    }
    exit;
}

// LOGIN
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=All fields are required");
        exit;
    }

    // Get user
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] === 'admin') {
                header("Location: dashboard/dashboard.php");
            } else {
                header("Location: index.php");
            }
        } else {
            header("Location: login.php?error=Invalid password");
        }
    } else {
        header("Location: login.php?error=Email not found");
    }
    exit;
}

// If no valid action, redirect to login
header("Location: login.php");
exit;
?>