<?php
session_start();

include '../Database/dbconnect.php';

if(isset($_POST['save_profile'])){

  // VALIDATE USER_ID

  if (!isset($_POST['user_id']) || !ctype_digit($_POST['user_id'])) {
        $_SESSION['profile_error'] = "Invalid user ID.";
        header("location: dashboard.php?page=account");
        exit();
  }

  $user_id = (int) $_POST['user_id'];
  $username = trim($_POST['username']);
  $phone_number = trim($_POST['phone_number']);

  // FOR USERNAME VALIDATION

 if(!preg_match("/^[a-zA-Z0-9]{6,}$/", $username)){
    $_SESSION['profile_error'] = 'Username must be at least 6 characters long and contain only letters and numbers.';
    header("location: dashboard.php?page=account");
    exit();
}

  // FOR PHONE NUMBER VALIDATION

  if(!preg_match("/^\d{11}$/", $phone_number)){
    $_SESSION['profile_error'] = 'Invalid phone number. Must be exactly 11 digits.';
    header("location: dashboard.php?page=account");
    exit();
}else{

      // FOR CHECKING 
    $check = $conn->prepare("SELECT username FROM users WHERE BINARY username = ? AND user_id != ?
    UNION 
    SELECT username FROM producers WHERE BINARY username = ?
    ");

      $check->bind_param('sis', $username, $user_id, $username);
      $check->execute();
      $result = $check->get_result();

  if($result->num_rows > 0){
    $_SESSION['profile_error'] = 'Username already exist. Please try another username';
    header("location: dashboard.php?page=account");
    exit();
  }else{
     // FOR UPDATING USER PROFILE

      $updStmt = $conn->prepare("UPDATE users SET username = ?, phone_number = ? WHERE user_id = ? "); 
      $updStmt->bind_param('ssi', $username, $phone_number, $user_id);
      
        if($updStmt->execute()){
          $_SESSION['username'] = $username;
          $_SESSION['profile_successful'] = 'Profile updated successfully';
          header("location: dashboard.php?page=account");
          exit();
        }else{
          $_SESSION['profile_error'] = 'Failed to update profile';
          header("location: dashboard.php?page=account");
          exit();
        }
      }
    }
  }
?>