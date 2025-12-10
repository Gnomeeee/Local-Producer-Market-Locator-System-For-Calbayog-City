<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

// CHECK IF FORM SUBMITTED
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $title = $conn->real_escape_string($_POST['title']);
  $message = $conn->real_escape_string($_POST['message']);
  $audience = $conn->real_escape_string($_POST['audience']);

  // INSERT INTO DATABASE
  $query = "
        INSERT INTO announcements (title, message, audience, status, created_at)
        VALUES ('$title', '$message', '$audience', 'Active', NOW())
    ";

  if ($conn->query($query)) {
    $_SESSION['success'] = "Announcement created successfully!";
    header("Location: dashboard.php?page=announcements");
    exit();
  } else {
    $_SESSION['error'] = "Failed to create announcement";
    header("Location: dashboard.php?page=announcements");
    exit();
  }
}
