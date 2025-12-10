<?php 
session_start();
include '../Database/dbconnect.php';


if(!isset($_SESSION['producer_id'])){
  $_SESSION['schedule_error'] = 'Unauthorized access';
  header("location: dashboard.php?page=schedule");
  exit();
}

$producer_id = (int) $_SESSION['producer_id'];

if(isset($_POST['delete_schedule'])){
  $schedule_id = (int) $_POST['schedule_id'];

  if($schedule_id <= 0 ){
    $_SESSION['schedule_error'] = 'Invalid market schedule';
    header("location: dashboard.php?page=schedule");
    exit();
  }

  // GET THE SCHEDULE_ID FROM MARKET SCHEDULE 
  $checkStmt = $conn->prepare("SELECT ms.schedule_id FROM market_schedules ms
  JOIN farms f ON ms.farm_id = f.farm_id 
  WHERE schedule_id = ? AND producer_id = ?");
  $checkStmt->bind_param('ii', $schedule_id, $producer_id);
  $checkStmt->execute();
  $checkResult =  $checkStmt->get_result();
  
  if($checkResult->num_rows === 0){
    $_SESSION['schedule_error'] = 'Schedule not found or unauthorized';
    header("location: dashboard.php?page=schedule");
    exit();
  }else{
    // DELETE 

    $deleteStmt = $conn->prepare("DELETE FROM market_schedules WHERE schedule_id = ?");
    $deleteStmt->bind_param('i', $schedule_id);
    

    if($deleteStmt->execute()){
      $_SESSION['schedule_success'] = 'Schedule deleted successfully';
      header("location: dashboard.php?page=schedule");
      exit();
    }else{
      $_SESSION['schedule_error'] = 'Error while deleting schedule';
      header("location: dashboard.php?page=schedule");
      exit();
    }
  }
}