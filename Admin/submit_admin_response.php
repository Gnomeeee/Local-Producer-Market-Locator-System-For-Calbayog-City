<?php
session_start();
include '../Database/dbconnect.php';

// ONLY ADMIN CAN SUBMIT A RESPONSE
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $request_id = $_POST['request_id'];
  $response = trim($_POST['response']);

  // Determine the correct status based on which button was clicked
  if (isset($_POST['mark_progress'])) {
    $status = "In Progress";
  } elseif (isset($_POST['resolve_and_send'])) {
    $status = "Resolved";
  } else {
    $_SESSION['error'] = "No valid action selected.";
    header("Location: dashboard.php?page=help_requests");
    exit();
  }

  // Validation
  if (empty($request_id) || empty($status) || empty($response)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: dashboard.php?page=help_requests");
    exit();
  }

  // Update query
  $sql = $conn->prepare("
        UPDATE admin_help_requests
        SET 
            status = ?, 
            admin_response = ?, 
            date_responded = NOW()
        WHERE request_id = ?
    ");

  $sql->bind_param("ssi", $status, $response, $request_id);

  if ($sql->execute()) {
    $_SESSION['success'] = "Response sent successfully!";
    header("Location: dashboard.php?page=help_requests");
    exit();
  } else {
    $_SESSION['error'] = "Error Sending Response";
    header("Location: dashboard.php?page=help_requests");
    exit();
  }
}
