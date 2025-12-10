<?php
  include '../Database/dbconnect.php';

  if(!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Producer'){
  header("Location: ../login.php");
  exit();
}

  $producer_id = $_SESSION['producer_id'];

  $farmStmt = $conn->prepare("SELECT * FROM farms WHERE producer_id = ?");
  $farmStmt->bind_param('i', $producer_id);
  $farmStmt->execute();
  $profile = $farmStmt->get_result()->fetch_assoc();
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/farm-profile.css">
  <title>Document</title>
</head>
<body>
  <div class="farm-profile-container">
    <div class="farm-profile-header">
      <h3>Farm Information</h3>
      <p>Update your farm details visible to consumers</p>
    </div>
    <div class="farm-profile-body">
      <div class="form-container">
        <form action="./prod_farm_profile.php" method="post">
          <input type="hidden" name="producer_id">
          <div class="input-box">
            <label for="farm_name">Farm Name<span>*</span></label>
          <input type="text" name="farm_name" placeholder="e.g., Green Valley Farm">
          </div>
          <div class="input-box">
            <label for="address">Address<span>*</span></label>
            <input type="text" name="address" placeholder="e.g., Baranggay Matobato">
          </div>
          <div class="input-box">
            <label for="city">City<span>*</span></label>
            <input type="text" name="city" placeholder="e.g., Calbayog City...">
          </div>
           <div class="input-box">
            <label for="phone_number">Phone Number<span>*</span></label>
            <input type="text" name="phone_number" placeholder="e.g., 09123456789">
            <sub>This number will be displayed to consumers so they can contact you for inquiries.</sub>
          </div>
           <div class="input-box">
            <label for="description">Description<span>*</span></label>
            <input type="text" name="description" placeholder="Describe your farm, what you grow, and what makes your produce special">
          </div>

          <button type="submit" name="save_farm_profile"  class="farm-profile-btn">
            <img src="../Assets/svg/check-circle-svgrepo-com.svg" alt="Check">Save Farm Profile
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>