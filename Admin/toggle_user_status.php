<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $id = $_POST['id'];
  $source = $_POST['source'];
  $action = $_POST['action'];

  if ($source !== "user" && $source !== "producer") {
    $_SESSION['error'] = "Invalid account source.";
    header("Location: dashboard.php?page=users");
    exit();
  }

  if ($source === "user" && $id == $admin_id) {
    $_SESSION['error'] = "You cannot deactivate your own admin account.";
    header("Location: dashboard.php?page=users");
    exit();
  }

  $newStatus = ($action === "deactivate") ? "Inactive" : "Active";

  if ($source === "user") {
    // Users table
    $stmt = $conn->prepare("
            UPDATE users 
            SET account_status = ? 
            WHERE user_id = ?
        ");
    $stmt->bind_param("si", $newStatus, $id);
  } else {
    // Producers table
    $stmt = $conn->prepare("
            UPDATE producers 
            SET status = ? 
            WHERE producer_id = ?
        ");
    $stmt->bind_param("si", $newStatus, $id);
  }

  if ($stmt->execute()) {
    $_SESSION['success'] = "Account status updated successfully!";
    header("Location: dashboard.php?page=users");
    exit();
  } else {
    $_SESSION['error'] = "Database error: " . $conn->error;
    header("Location: dashboard.php?page=users");
    exit();
  }
}
