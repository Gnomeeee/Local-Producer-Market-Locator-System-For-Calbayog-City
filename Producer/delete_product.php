<?php 
session_start();
include '../Database/dbconnect.php';

if(!isset($_SESSION['producer_id'])){
  $_SESSION['product_error'] = 'Unauthorized access';
  header("location: dashboard.php?page=products");
  exit();
}

$producer_id = (int) $_SESSION['producer_id'];

if(isset($_POST['delete_product'])){
  $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

  if($product_id <= 0){
    $_SESSION['product_error'] = 'Invalid product';
    header("location: dashboard.php?page=products");
    exit();
  }

  $checkStmt = $conn->prepare("SELECT p.product_id 
  FROM products p 
  JOIN farms f ON p.farm_id = f.farm_id
  WHERE p.product_id = ? AND f.producer_id = ?
  ");
  $checkStmt->bind_param('ii', $product_id, $producer_id);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result();

  if($checkResult->num_rows === 0){
    $_SESSION['product_error'] = 'Product not found or unauthorized';
        header("Location: dashboard.php?page=products");
        exit();
  }

  $deleteStmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
  $deleteStmt->bind_param('i', $product_id);

  if($deleteStmt->execute()){
    $_SESSION['product_success'] = 'Product deleted successfully';
    header("location: dashboard.php?page=products");
    exit();
  }else{
    $_SESSION['product_error'] = 'Error deleting product';
    header("location: dashboard.php?page=products");
    exit();
  }
}
?>