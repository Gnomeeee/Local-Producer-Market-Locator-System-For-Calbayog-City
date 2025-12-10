<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
  $id = $conn->real_escape_string($_GET['id']);
  $status = $conn->real_escape_string($_GET['status']);

  $query = "UPDATE announcements SET status='$status' WHERE id='$id'";

  if ($conn->query($query)) {
    $_SESSION['success'] = "Announcement has been " . ($status === 'Active' ? 'activated' : 'deactivated') . " successfully!";
    header("Location: dashboard.php?page=announcements");
    exit();
  } else {
    $_SESSION['error'] = "Failed to update announcement status.";
    header("Location: dashboard.php?page=announcements");
    exit();
  }
}
