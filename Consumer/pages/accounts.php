<?php
include '../Database/dbconnect.php';

$user_id = $_SESSION['user_id'];

// GET USER FROM DATABASE 

$userStmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$userStmt->bind_param('i', $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

// FOR REVIEW COUNTER 

$revStmt = $conn->prepare("SELECT COUNT(*) AS total_review
FROM consumer_reviews WHERE user_id = ?");

$revStmt->bind_param('i', $user_id);
$revStmt->execute();
$revCounter = $revStmt->get_result()->fetch_assoc()['total_review'];

// FOR FAVORITES COUNTER

$favStmt = $conn->prepare("SELECT COUNT(*) AS total_favorites
FROM favorites WHERE user_id = ?");
$favStmt->bind_param('i', $user_id);
$favStmt->execute();
$favCounter = $favStmt->get_result()->fetch_assoc()['total_favorites'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/accountss.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="../Assets/Javascript/edit-user-info.js"></script>
  <script src="../Assets/Javascript/user-toggle-password.js"></script>
  <title>Accounts</title>
</head>
<body>
  <div class="accounts-container">
    <div class="user-details">
      <div class="user-func">
        <div class="favorite-farms-content">
          <div class="title">
            <h3>Favorite Farms</h3>
          </div>
          <div class="par">
            <p><?= (int)$favCounter ?></p>
          </div>
        </div>
      </div>
      <div class="user-func">
        <div class="review-written-content">
          <div class="title">
            <h3>Reviews Written</h3>
          </div>
          <div class="par">
            <p><?= (int)$revCounter ?></p>
          </div>
        </div>
      </div>
      <div class="user-func">
        <div class="member-since-content">
         <div class="title">
            <h3>Member Since</h3>
          </div>
          <div class="par">
            <p><?= date("M Y", strtotime($user['reg_date'])) ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="account-settings">
      <div class="account-header">
        <h3>Account Settings</h3>
        <p>Manage your profile and security</p>
      </div>
    <div class="account-body">
      <div class="profile-information">
        <div class="profile-content">
          <div class="left-information">
            <div class="account-icon">
              <img src="../Assets/svg/user-svgrepo-com.svg" alt="User">
            </div> 
            <div class="user-information">
              <h3>Profile Information</h3>
              <p>Update your name and phone</p>
            </div>   
          </div>
          <div class="right-information">
            <button onclick="openModal('editProfileModal')">Edit Profile</button>
          </div>
        </div>
      </div>
      <div class="profile-information">
        <div class="profile-content">
          <div class="left-information">
            <div class="account-icon">
              <img src="../Assets/svg/lock-alt-svgrepo-com.svg" alt="Lock">
            </div> 
            <div class="user-information">
              <h3>Password & Security</h3>
              <p>Change your password</p>
            </div>   
          </div>
          <div class="right-information">
            <button onclick="openModal('changePassModal')">Change Password</button>
          </div>
        </div>
      </div>
        <div class="user-email-address">
          <div class="envelope-svg">
            <img src="../Assets/svg/email-svgrepo-com.svg" alt="email">
          </div>
          <div class="email-address">
            <h3>Email Address</h3>
            <p><?= htmlspecialchars($user['email']) ?></p>
            <p>Email cannot be changed</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <!-- EDIT PROFILE MODAL -->
<div id="editProfileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editProfileModal')">&times;</span>
    <h2>Edit Profile</h2>
    <p class="subtitle">Update your personal information</p>
    <form action="update_profile.php" method="POST">
    <input type="hidden" name="user_id" value="<?= $user_id ?>">
      <label>Username</label>
      <input type="text" name="username" value="<?= $user['username'] ?>" class="input-field" required>

      <label>Phone Number</label>
      <input type="text" name="phone_number" value="<?= $user['phone_number'] ?>" class="input-field" required>

      <div class="actions">
        <button type="button" class="cancel-btn" onclick="closeModal('editProfileModal')">Cancel</button>
        <button type="submit" class="save-btn" name="save_profile">Save Changes</button>
      </div>

    </form>

  </div>
</div>
<!-- CHANGE PASSWORD MODAL -->
<div id="changePassModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('changePassModal')">&times;</span>

    <h2>Change Password</h2>
    <p class="subtitle">Update your account password</p>

    <form action="update_password.php" method="POST">
      <input type="hidden" name="user_id" value="<?= $user_id ?>">
      <label>Current Password</label>
      <div class="input-box">
        <input type="password" id="current_password" name="current_password" class="input-field" placeholder="Enter your current password" required>
      <i id="toggleCurrent" class="fa-solid fa-eye"></i>
      </div>
      
      <label>New Password</label>
      <div class="input-box">
        <input type="password" id="password" name="new_password" placeholder="Enter new password" class="input-field" required>
      <i id="togglePassword" class="fa-solid fa-eye"></i>
      </div>
      
      <label>Confirm New Password</label>
      <div class="input-box">
        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Re-enter your password" class="input-field" required>
      <i id="toggleConfirm" class="fa-solid fa-eye"></i>
      </div>
      
      <div class="actions">
        <button type="button" class="cancel-btn" onclick="closeModal('changePassModal')">Cancel</button>
        <button type="submit" class="save-btn" name="save_password">Save Password</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>