<?php
include '../Database/dbconnect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = 'Unauthorized access.';
  header("Location: ../login.php");
  exit();
}

$id = intval($_POST['producer_id']);

$stmt = $conn->prepare("UPDATE producers SET is_verified = 1 WHERE producer_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  $_SESSION['producer_approve_success'] = 'Producer has been approved successfully.';
  header("Location: dashboard.php?page=producers");
  exit();
}


header("Location: dashboard.php?page=producers");
exit();
