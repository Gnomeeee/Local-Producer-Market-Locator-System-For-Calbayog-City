<?php
session_start();
include '../Database/dbconnect.php';

if (isset($_POST['save_password'])) {

    // Validate user_id
    if (!isset($_POST['user_id']) || !ctype_digit($_POST['user_id'])) {
        $_SESSION['pro_password_error'] = 'Invalid user ID.';
        header("location: dashboard.php?page=account");
        exit();
    }

    $user_id = (int) $_POST['user_id'];
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // VALIDATION 

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['pro_password_error'] = 'Please fill in all fields.';
        header("location: dashboard.php?page=account");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['pro_password_error'] = "New passwords don't match!";
        header("location: dashboard.php?page=account");
        exit();
    }

    if (strlen($new_password) < 8) {
        $_SESSION['pro_password_error'] = 'Password must be at least 8 characters long.';
        header("location: dashboard.php?page=account");
        exit();
    }

    // GET PASSWORD FROM THE DATABASE
    $passStmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $passStmt->bind_param('i', $user_id);
    $passStmt->execute();
    $passStmt->store_result();

    if ($passStmt->num_rows === 0) {
        $_SESSION['pro_password_error'] = 'User not found.';
        header("location: dashboard.php?page=account");
        exit();
    }

    $passStmt->bind_result($stored_password);
    $passStmt->fetch();
    $passStmt->close();

    // VERIFY OLD PASSWORD

    if (!password_verify($current_password, $stored_password)) {
        $_SESSION['pro_password_error'] = 'Current password is incorrect.';
        header("location: dashboard.php?page=account");
        exit();
    }

    // PREVENT REUSING OLD PASSWORD
    
    if (password_verify($new_password, $stored_password)) {
        $_SESSION['pro_password_error'] = 'New password cannot be the same as the current password.';
        header("location: dashboard.php?page=account");
        exit();
    }

    // HASHING
    $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    // UPDATE PASSWORD
    $updPassStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $updPassStmt->bind_param('si', $hash_new_password, $user_id);

    if ($updPassStmt->execute()) {
        $_SESSION['update_password_successful'] = 'Password updated successfully.';
    } else {
        $_SESSION['pro_password_error'] = 'Something went wrong while updating your password.';
    }

    $updPassStmt->close();
    $conn->close();

    header("location: dashboard.php?page=account");
    exit();
    
} else {
    $_SESSION['pro_password_error'] = 'Invalid Request!';
    header("location: dashboard.php?page=account");
    exit();
}
?>
