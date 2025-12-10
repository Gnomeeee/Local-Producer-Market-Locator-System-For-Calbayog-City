<?php
session_start();
include '../Database/dbconnect.php';

// BLOCK UNAUTHORIZED ACCESS
if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

// PROCESS ADD ADMIN
if (isset($_POST['add_admin'])) {

  // SANITIZE INPUTS
  $full_name = trim($_POST['full_name']);
  $username  = trim($_POST['username']); // new field for username
  $email     = trim($_POST['email']);
  $password  = $_POST['password'];

  // -------- VALIDATION -------- //

  // Empty fields
  if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: dashboard.php?page=admin_management");
    exit();
  }

  if (strlen($password) < 8) {
    $_SESSION['error'] = "Password must be atleast 8 characters.";
    header("Location: dashboard.php?page=admin_management");
    exit();
  }

  // Email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: dashboard.php?page=admin_management");
    exit();
  }

  // CHECK USERNAME DUPLICATE
  $checkUsername = $conn->prepare("SELECT admin_id FROM admins WHERE username = ?");
  $checkUsername->bind_param("s", $username);
  $checkUsername->execute();
  $resultUsername = $checkUsername->get_result();

  if ($resultUsername->num_rows > 0) {
    $_SESSION['error'] = "Username already exists.";
    header("Location: dashboard.php?page=admin_management");
    exit();
  }

  // CHECK EMAIL DUPLICATE
  $checkEmail = $conn->prepare("SELECT admin_id FROM admins WHERE email = ?");
  $checkEmail->bind_param("s", $email);
  $checkEmail->execute();
  $resultEmail = $checkEmail->get_result();

  if ($resultEmail->num_rows > 0) {
    $_SESSION['error'] = "Email already exists.";
    header("Location: dashboard.php?page=admin_management");
    exit();
  }

  // HASH PASSWORD
  $hashed = password_hash($password, PASSWORD_DEFAULT);

  // INSERT ADMIN
  // INSERT ADMIN
  $insert = $conn->prepare("
    INSERT INTO admins (username, full_name, email, password, created_at, role_id)
    VALUES (?, ?, ?, ?, NOW(), 2)
");


  if (!$insert) {
    $_SESSION['error'] = "Database error: " . $conn->error;
    header("Location: dashboard.php?page=admin_management");
    exit();
  }

  $insert->bind_param("ssss", $username, $full_name, $email, $hashed);

  if ($insert->execute()) {
    $_SESSION['success'] = "Admin added successfully!";
    header("Location: dashboard.php?page=admin_management");
    exit();
  } else {
    $_SESSION['error'] = "Failed to add admin. Please try again.";
    header("Location: dashboard.php?page=admin_management");
    exit();
  }
}
