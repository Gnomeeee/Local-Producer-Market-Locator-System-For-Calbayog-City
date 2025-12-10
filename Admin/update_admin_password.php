<?php
session_start();
include '../Database/dbconnect.php';

// BLOCK UNAUTHORIZED
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error'] = 'Unauthorized access.';
    header("location: ../login.php");
    exit();
}

if (isset($_POST['save_password'])) {

    // Validate admin_id
    if (!isset($_POST['admin_id']) || !ctype_digit($_POST['admin_id'])) {
        $_SESSION['error'] = 'Invalid admin ID.';
        header("location: dashboard.php?page=account");
        exit();
    }

    $admin_id = (int) $_POST['admin_id'];
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Required fields
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Please fill in all fields.';
        header("location: dashboard.php?page=account");
        exit();
    }

    // Password match check
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords don't match!";
        header("location: dashboard.php?page=account");
        exit();
    }

    // Minimum length
    if (strlen($new_password) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters long.';
        header("location: dashboard.php?page=account");
        exit();
    }

    // GET CURRENT PASSWORD
    $stmt = $conn->prepare("SELECT password FROM admins WHERE admin_id = ?");
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['error'] = 'Admin not found.';
        header("location: dashboard.php?page=account");
        exit();
    }

    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Check current password
    if (!password_verify($current_password, $stored_password)) {
        $_SESSION['error'] = 'Current password is incorrect.';
        header("location: dashboard.php?page=account");
        exit();
    }

    // Prevent same password reuse
    if (password_verify($new_password, $stored_password)) {
        $_SESSION['error'] = 'New password cannot be the same as your current password.';
        header("location: dashboard.php?page=account");
        exit();
    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // UPDATE PASSWORD
    $updateStmt = $conn->prepare("UPDATE admins SET password = ? WHERE admin_id = ?");
    $updateStmt->bind_param('si', $hashed_password, $admin_id);

    if ($updateStmt->execute()) {
        $_SESSION['success'] = 'Password updated successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong while updating your password.';
    }

    $updateStmt->close();
    $conn->close();

    header("location: dashboard.php?page=account");
    exit();
}

// Fallback
$_SESSION['error'] = 'Invalid request.';
header("location: dashboard.php?page=account");
exit();
