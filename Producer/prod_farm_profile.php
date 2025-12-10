<?php 
session_start();
include '../Database/dbconnect.php';

if(!isset($_SESSION['producer_id'])){
    $_SESSION['farm_profile_error'] = 'Unauthorized access';
    header("location: dashboard.php?page=farm_profile");
    exit();
}

$producer_id = (int)$_SESSION['producer_id'];

// Check if producer exists in the producers table
$checkProducerStmt = $conn->prepare("SELECT producer_id, is_verified FROM producers WHERE producer_id = ?");
$checkProducerStmt->bind_param('i', $producer_id);
$checkProducerStmt->execute();
$producer = $checkProducerStmt->get_result()->fetch_assoc();

if(!$producer){
    $_SESSION['farm_profile_error'] = 'Producer not found. Cannot update farm profile.';
    header("location: dashboard.php?page=farm_profile");
    exit();
}

// Check verification status
$is_verified = (int)$producer['is_verified'];
if($is_verified === 0){
    $_SESSION['farm_profile_error'] = 'You cannot update your farm profile until your account is verified.';
    header("location: dashboard.php?page=farm_profile");
    exit();
}

// approval status
if($is_verified === 1){
    $approval_status = 'Approved';
} elseif ($is_verified === 2){
    $approval_status = 'Rejected';
} else {
    $approval_status = 'Pending';
}

if(isset($_POST['save_farm_profile'])){
    $farm_name = trim($_POST['farm_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $phone_number = trim($_POST['phone_number']);
    $description = trim($_POST['description']);

    // Validation
    if(empty($farm_name) || empty($address) || empty($city) || empty($phone_number) || empty($description)){
        $_SESSION['farm_profile_error'] = 'Please fill in all fields';
        header("location: dashboard.php?page=farm_profile");
        exit();
    }

    if(!preg_match('/^\d{11}$/', $phone_number)){
        $_SESSION['farm_profile_error'] = 'Invalid phone number. Must be exactly 11 digits.';
        header("location: dashboard.php?page=farm_profile");
        exit();
    }

    // Check if farm profile already exists
    $checkFarmStmt = $conn->prepare("SELECT farm_id FROM farms WHERE producer_id = ?");
    $checkFarmStmt->bind_param('i', $producer_id);
    $checkFarmStmt->execute();
    $existingFarm = $checkFarmStmt->get_result()->fetch_assoc();

    if($existingFarm){
        // Update existing farm
        $updateStmt = $conn->prepare("
            UPDATE farms 
            SET farm_name = ?, address = ?, city = ?, phone_number = ?, description = ?, approval_status = ? 
            WHERE producer_id = ?
        ");
        $updateStmt->bind_param('ssssssi', $farm_name, $address, $city, $phone_number, $description, $approval_status, $producer_id);

        if($updateStmt->execute()){
            $_SESSION['farm_profile_success'] = 'Farm profile updated successfully';
            header("location: dashboard.php?page=farm_profile");
            exit();
        } else {
            $_SESSION['farm_profile_error'] = 'Error updating farm profile';
            header("location: dashboard.php?page=farm_profile");
            exit();
        }

    } else {
        // Insert new farm
        $insertStmt = $conn->prepare("
            INSERT INTO farms (producer_id, farm_name, address, city, phone_number, description, approval_status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insertStmt->bind_param('issssss', $producer_id, $farm_name, $address, $city, $phone_number, $description, $approval_status);

        if($insertStmt->execute()){
            $_SESSION['farm_profile_success'] = 'Farm profile saved successfully';
            header("location: dashboard.php?page=farm_profile");
            exit();
        } else {
            $_SESSION['farm_profile_error'] = 'Error saving farm profile';
            header("location: dashboard.php?page=farm_profile");
            exit();
        }
    }
}
?>
