<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.php");
  exit();
}

if (!isset($_POST['admin_id']) || !ctype_digit($_POST['admin_id'])) {
  $_SESSION['error'] = "Invalid admin ID.";
  header("Location: dashboard.php?page=admin_management");
  exit();
}

$admin_id = (int) $_POST['admin_id'];

// Prevent deleting default admin
$check = $conn->prepare("SELECT role_id FROM admins WHERE admin_id = ?");
$check->bind_param("i", $admin_id);
$check->execute();
$row = $check->get_result()->fetch_assoc();

if ($row['role_id'] == 1) {
  $_SESSION['error'] = "Default admin cannot be deleted.";
  header("Location: dashboard.php?page=admin_management");
  exit();
}

$del = $conn->prepare("DELETE FROM admins WHERE admin_id = ?");
$del->bind_param("i", $admin_id);
$del->execute();

$_SESSION['success'] = "Admin deleted successfully.";
header("Location: dashboard.php?page=admin_management");
exit();
