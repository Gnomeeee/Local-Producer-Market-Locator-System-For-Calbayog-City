<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'local_producers_market_locator';

  $conn = new mysqli($servername, $username, $password, $dbname);

  // ERROR HANDLER INI

  if($conn->connect_error){
    die('Connection Failed' . $conn->connect_error);
  }


