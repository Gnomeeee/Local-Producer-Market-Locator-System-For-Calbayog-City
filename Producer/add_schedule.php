<?php
session_start();
include '../Database/dbconnect.php';

if(!isset($_SESSION['producer_id'])){
  $_SESSION['schedule_error'] = 'Unauthorized access';
  header("location: dashboard.php?page=schedule");
  exit();
}

$producer_id = (int)$_SESSION['producer_id'];

if(isset($_POST['add_schedule'])){
  $producer_id = (int) $_POST['producer_id'];
  $day = trim($_POST['day']);
  $start_time_input = trim($_POST['start_time']);
  $end_time_input = trim($_POST['end_time']);
  $location = trim($_POST['location']);

  // PROPER VALIDATION

  $start_time = date("H:i:s", strtotime($start_time_input));
  $end_time = date("H:i:s", strtotime($end_time_input));

if(empty($day) || empty($start_time) || empty($end_time) || empty($location)){
  $_SESSION['schedule_error'] = 'Please put in all fields';
  header("location: dashboard.php?page=schedule");
}

  $valid_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

  if(!in_array($day, $valid_days)){
    $_SESSION['schedule_error'] = 'Invalid day selected';
    header("location: dashboard.php?page=schedule");
    exit();
  }
  
  if(empty($start_time) || empty($end_time)){
    $_SESSION['schedule_error'] = 'Start time and End time is required';
    header("location: dashboard.php?page=schedule");
    exit();
  }

  // GET THE FARM ID IN FARMS BASED ON THE LOGIN PRODUCER

  $farmStmt = $conn->prepare("SELECT farm_id FROM farms WHERE producer_id = ?");
  $farmStmt->bind_param('i', $producer_id);
  $farmStmt->execute();
  $farmResult = $farmStmt->get_result();

  if($farmResult->num_rows === 0){
    $_SESSION['schedule_error'] = 'No farm found for this producer';
    header("location: dashboard.php?page=schedule");
    exit();
  }

  $farm_id = $farmResult->fetch_assoc()['farm_id'];

  // INSERT INTO THE TABLE 

  $schedStmt = $conn->prepare("INSERT INTO market_schedules(farm_id, day_of_week, start_time, end_time, location)VALUES(?,?,?,?,?);");
  $schedStmt->bind_param('issss', $farm_id, $day, $start_time, $end_time, $location);

  if($schedStmt->execute()){
    $_SESSION['schedule_success'] = 'Schedule added successfully';
    header("location: dashboard.php?page=schedule");
    exit();
  }else{
    $_SESSION['schedule_error'] = 'Error adding schedule';
    header("location: dashboard.php?page=schedule");
    exit();
  }

}
?>