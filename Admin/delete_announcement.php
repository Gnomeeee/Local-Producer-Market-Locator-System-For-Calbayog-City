<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

if (isset($_GET['id'])) {
  $id = $conn->real_escape_string($_GET['id']);

  $query = "DELETE FROM announcements WHERE id='$id'";

  if ($conn->query($query)) {
    $_SESSION['success'] = "Announcement deleted successfully!";
    header("Location: dashboard.php?page=announcements");
    exit();
  } else {
    $_SESSION['error'] = "Failed to delete announcement.";
    header("Location: dashboard.php?page=announcements");
    exit();
  }
}
