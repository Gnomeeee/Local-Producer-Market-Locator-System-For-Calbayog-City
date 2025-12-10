<?php
session_start();
include '../Database/dbconnect.php';

if(!isset($_SESSION['user_id'])){
  echo "You are currently not logged in!";
  exit();
}

$user_id = $_SESSION['user_id'];
$farm_id = $_POST['farm_id'];

// CHECK IF ITS CURRENTLY IN THE FAVORITES NA 

$check = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND farm_id = ? ");
$check->bind_param('ii', $user_id, $farm_id);
$check->execute();
$result = $check->get_result();

if($result->num_rows > 0){
  // IF FOUND DELETE IT 

  $del = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND farm_id = ?");
  $del->bind_param('ii', $user_id, $farm_id);
  $del->execute();
  $_SESSION['fav_deleted'] = 'Favorites removed!';
  echo "removed"; 
  exit();

}else{
  // ELSE ADD IF DIDN'T EXIST
  
  $add = $conn->prepare("INSERT INTO favorites(user_id, farm_id) VALUES(?,?);");
  $add->bind_param('ii', $user_id, $farm_id);
  $add->execute();
  $_SESSION['fav_added'] = 'Favorites added!';
  echo "added";
  exit();
} 