<?php
session_start();
include '../Database/dbconnect.php';

// Get POST data
$rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$farm_id = isset($_POST['farm_id']) ? (int)$_POST['farm_id'] : 0;
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

$redirect_url = "dashboard.php?id=$farm_id"; 

// Validate
if ($rating < 1 || $rating > 5) {
    $_SESSION['review_error'] = '<span style="display: inline-flex; align-items: center; color: #d4183d;">
    <img src="../Assets/svg/alert-error-svgrepo-com.svg" alt="" 
         style="width:20px;height:20px;margin-right:10px;
                filter: invert(50%) sepia(93%) saturate(7470%) hue-rotate(5059deg) brightness(95%) contrast(105%);"> 
    Invalid rating!
</span>';
    header("Location: $redirect_url");
    exit;
}

if (empty($comment)) {
    $_SESSION['review_error'] = '<span style="display: inline-flex; align-items: center; color: #d4183d;">
    <img src="../Assets/svg/alert-error-svgrepo-com.svg" alt="" 
         style="width:20px;height:20px;margin-right:10px;
                filter: invert(50%) sepia(93%) saturate(7470%) hue-rotate(5059deg) brightness(95%) contrast(105%);"> 
    Comment is required!
</span>';
    header("Location: $redirect_url");
    exit;
}

if ($farm_id <= 0) {
    $_SESSION['review_error'] = '<span style="display: inline-flex; align-items: center; color: #d4183d;">
    <img src="../Assets/svg/alert-error-svgrepo-com.svg" alt="" 
         style="width:20px;height:20px;margin-right:10px;
                filter: invert(50%) sepia(93%) saturate(7470%) hue-rotate(5059deg) brightness(95%) contrast(105%);"> 
    Invalid farm!
</span>';
    header("Location: $redirect_url");
    exit;
}

if ($user_id <= 0) {
    $_SESSION['review_error'] = '<span style="display: inline-flex; align-items: center; color: #d4183d;">
    <img src="../Assets/svg/alert-error-svgrepo-com.svg" alt="" 
         style="width:20px;height:20px;margin-right:10px;
                filter: invert(50%) sepia(93%) saturate(7470%) hue-rotate(5059deg) brightness(95%) contrast(105%);"> 
    You must be logged in to send a review!
</span>';
    header("Location: $redirect_url");
    exit;
}

// Insert review
$stmt = $conn->prepare("
    INSERT INTO consumer_reviews (farm_id, user_id, rating, comment_text, review_date)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->bind_param('iiis', $farm_id, $user_id, $rating, $comment);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: $redirect_url");
    $_SESSION['review_success'] = '<span style="display: inline-flex; align-items: center; color: green;">
    <img src="../Assets/svg/check-circle-svgrepo-com.svg" alt=" review" 
         style="width:20px;height:20px;margin-right:10px;
                filter: invert(48%) sepia(95%) saturate(3940%) hue-rotate(84deg) brightness(95%) contrast(93%);"> 
    Review submitted successfully!
</span>';
    exit;
} else {
    $_SESSION['review_error'] = '<span style="display: inline-flex; align-items: center; color: #d4183d;">
    <img src="../Assets/svg/alert-error-svgrepo-com.svg" alt="" 
         style="width:20px;height:20px;margin-right:10px;
                filter: invert(50%) sepia(93%) saturate(7470%) hue-rotate(5059deg) brightness(95%) contrast(105%);"> 
    Failed to send review!
</span>';
    header("Location: $redirect_url");
    exit;
}
?>
