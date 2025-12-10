<?php
session_start();
include '../Database/dbconnect.php';

// BLOCK UNAUTHORIZED
if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = 'Unauthorized access.';
  header("location: ../login.php");
  exit();
}

if (isset($_POST['save_profile'])) {

  // Validate admin ID
  if (!isset($_POST['admin_id']) || !ctype_digit($_POST['admin_id'])) {
    $_SESSION['error'] = "Invalid admin ID.";
    header("location: dashboard.php?page=account");
    exit();
  }

  $admin_id = (int) $_POST['admin_id'];
  $fullname = trim($_POST['full_name']);
  $username = trim($_POST['username']);

  // Username validation
  if (!preg_match("/^[a-zA-Z0-9]{6,}$/", $username)) {
    $_SESSION['error'] = 'Username must be at least 6 characters long and contain only letters and numbers.';
    header("location: dashboard.php?page=account");
    exit();
  }

  // CHECK IF USERNAME EXISTS (FIXED)
  $check = $conn->prepare("
        SELECT admin_id 
        FROM admins 
        WHERE BINARY username = ? AND admin_id != ?
    ");
  $check->bind_param('si', $username, $admin_id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $_SESSION['error'] = 'Username already exists. Please choose another.';
    header("location: dashboard.php?page=account");
    exit();
  }

  // UPDATE PROFILE (FIXED)
  $updStmt = $conn->prepare("
        UPDATE admins 
        SET username = ?, full_name = ?
        WHERE admin_id = ?
    ");
  $updStmt->bind_param('ssi', $username, $fullname, $admin_id);

  if ($updStmt->execute()) {
    $_SESSION['username'] = $username;
    $_SESSION['success'] = 'Profile updated successfully.';
    header("location: dashboard.php?page=account");
    exit();
  } else {
    $_SESSION['error'] = 'Failed to update profile.';
    header("location: dashboard.php?page=account");
    exit();
  }
}
