<?php
session_start();
include '../Database/dbconnect.php';

$producer_id = (int)$_SESSION['producer_id'];

// CHECK IF HE'S CURRENTLY LOGGED IN 

if (!isset($_SESSION['producer_id'])) {
  $_SESSION['help_request_error'] = 'Unauthorized access';
  header("location: dashboard.php?page=help_request");
  exit();
}

if (isset($_POST['send_help_request'])) {
  $producer_id = (int)$_POST['producer_id'];
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);

  // BASIC ERROR AND VALIDATION

  if (empty($subject) || empty($message)) {
    $_SESSION['help_request_error'] = 'Please put in all fields';
    header("location: dashboard.php?page=help_request");
    exit();
  }

  // GET FARM_ID OF PRODUCER IF EXISTING 

  $farmStmt = $conn->prepare("SELECT farm_id FROM farms WHERE producer_id = ?");
  $farmStmt->bind_param('i', $producer_id);
  $farmStmt->execute();
  $farmResult = $farmStmt->get_result();

  if ($farmResult->num_rows === 0) {
    $_SESSION['help_request_error'] = 'No farm found for this producer';
    header("Location: dashboard.php?page=help_request");
    exit();
  }

  // GET THE MESSAGE AND SUBJECT IN THE DATABASE TO PREVENT DUPLICATION

  $checkStmt = $conn->prepare(
    "SELECT * FROM admin_help_requests 
         WHERE producer_id = ? AND subject = ? AND message_text = ? 
         ORDER BY request_id DESC LIMIT 1"
  );
  $checkStmt->bind_param("iss", $producer_id, $subject, $message);
  $checkStmt->execute();
  $duplicate = $checkStmt->get_result();

  if ($duplicate->num_rows > 0) {
    $_SESSION['help_request_error'] = "You already submitted this issue.";
    header("location: dashboard.php?page=help_request");
    exit();
  } else {
    // INSERT IF NO DUPLICATION

    $helpRequestStmt = $conn->prepare("INSERT INTO admin_help_requests(producer_id, subject, message_text, request_date, status)VALUES(?,?,?, NOW(), 'Open');");
    $helpRequestStmt->bind_param('iss', $producer_id, $subject, $message);

    // EXECUTION 

    if ($helpRequestStmt->execute()) {
      $_SESSION['help_request_success'] = 'Your request has been submitted successfully';
      header("location: dashboard.php?page=help_request");
      exit();
    } else {
      $_SESSION['help_request_error'] = 'Something went wrong. Please try again.';
      header("location: dashboard.php?page=help_request");
      exit();
    }
  }
}
