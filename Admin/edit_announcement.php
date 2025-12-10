<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $conn->real_escape_string($_POST['id']);
  $title = $conn->real_escape_string($_POST['title']);
  $message = $conn->real_escape_string($_POST['message']);
  $audience = $conn->real_escape_string($_POST['audience']);

  $query = "UPDATE announcements 
              SET title='$title', message='$message', audience='$audience' 
              WHERE id='$id'";

  if ($conn->query($query)) {
    $_SESSION['success'] = "Announcement updated successfully!";
    header("Location: dashboard.php?page=announcements");
    exit();
  } else {
    $_SESSION['error'] = "Failed to update announcement.";
    header("Location: dashboard.php?page=announcements");
    exit();
  }
}
