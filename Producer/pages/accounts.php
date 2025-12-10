<?php
include '../Database/dbconnect.php';

$producer_id = $_SESSION['producer_id'];

// GETTING PRODUCERS IN DATABASE 

$prodStmt = $conn->prepare("SELECT * FROM producers WHERE producer_id = ?");
$prodStmt->bind_param('i', $producer_id);
$prodStmt->execute();
$producer = $prodStmt->get_result()->fetch_assoc();

// GETTING COUNTER OF THE PRODUCT LISTED BY THE PRODUCER

$prodStmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products p INNER JOIN farms f ON p.farm_id = f.farm_id WHERE producer_id = ?");
$prodStmt->bind_param('i', $producer_id);
$prodStmt->execute();
$prodCounter = $prodStmt->get_result()->fetch_assoc()['total_products'];

// GET THE STATUS OF THE PRODUCER 
$statusStmt = $conn->prepare("SELECT account_status FROM producers WHERE producer_id = ?");
$statusStmt->bind_param('i', $producer_id);
$statusStmt->execute();
$status = $statusStmt->get_result()->fetch_assoc()['account_status'];

// SWITCH PARA SA COLOR NGAN ICON SA STATUS 

$statusColor = '';
$statusText = '';
$icon = '';

switch ($status) {
  case 'pending':
    $statusColor = '#D97706';
    $statusText = 'Pending';
    $icon = '../Assets/svg/time-svgrepo-com.svg';
    break;
  case 'verified':
    $statusColor = 'rgba(6, 105, 6, 1)';
    $statusText = 'Verified';
    $icon = '../Assets/svg/check-circle-svgrepo-com.svg';
    break;
  case 'cancel':
    $statusColor = '#ff4e72ff';
    $statusText = 'Rejected';
    $icon = '../Assets/svg/shield-xmark-svgrepo-com.svg';
  default:
    $statusColor = '#6B7280';
    $statusText = 'Unknown';
    $icon = 'Assets/svg/circle-question-svgrepo-com.svg';
}

function hex2filter($hex)
{
  switch (strtolower($hex)) {
    case 'rgba(6, 105, 6, 1)':
      return 'invert(38%) sepia(81%) saturate(341%) hue-rotate(80deg) brightness(96%) contrast(92%)'; // green
    case '#d97706':
      return 'invert(45%) sepia(71%) saturate(577%) hue-rotate(3deg) brightness(96%) contrast(97%)'; // orange
    case '#ff4e72':
      return 'invert(47%) sepia(75%) saturate(4370%) hue-rotate(340deg) brightness(95%) contrast(102%)'; // red
    default:
      return 'invert(33%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(83%) contrast(88%)'; // gray
  }
}

$iconFilter = hex2filter($statusColor);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/accounts.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="../Assets/Javascript/edit-user-info.js"></script>
  <script src="../Assets/Javascript/user-toggle-password.js"></script>
  <title>Accounts</title>
</head>

<body>
  <div class="accounts-container">
    <div class="producer-details">
      <div class="producer-func">
        <div class="account-status-content">
          <div class="title">
            <h3>Account Status</h3>
          </div>
          <div class="par">
            <p style="font-size: 17px;">
              <?php
              echo '<span style="display:inline-flex; align-items:center;">
                      <img src="' . $icon . '" alt="' . $statusText . '" style="width:22px;height:22px;margin-right:8px; filter:' . $iconFilter . ';">
                      <span style="color:' . $statusColor . ';">' . $statusText . '</span>
                    </span>';
              ?>
            </p>
          </div>
        </div>
      </div>
      <div class="producer-func">
        <div class="product-listed-content">
          <div class="title">
            <h3>Products Listed</h3>
          </div>
          <div class="par">
            <p><?= (int)$prodCounter ?></p>
          </div>
        </div>
      </div>
      <div class="producer-func">
        <div class="member-since-content">
          <div class="title">
            <h3>Member Since</h3>
          </div>
          <div class="par">
            <p><?= date("M Y", strtotime($producer['reg_date'])) ?></p>
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
              <div class="producer-information">
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
              <div class="producer-information">
                <h3>Password & Security</h3>
                <p>Change your password</p>
              </div>
            </div>
            <div class="right-information">
              <button onclick="openModal('changePassModal')">Change Password</button>
            </div>
          </div>
        </div>
        <div class="producer-email-address">
          <div class="envelope-svg">
            <img src="../Assets/svg/email-svgrepo-com.svg" alt="email">
          </div>
          <div class="email-address">
            <h3>Email Address</h3>
            <p><?= htmlspecialchars($producer['email']) ?></p>
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
        <input type="hidden" name="producer_id" value="<?= $producer_id ?>">
        <label>Username</label>
        <input type="text" name="username" value="<?= $producer['username'] ?>" class="input-field" required>

        <label>Phone Number</label>
        <input type="text" name="phone_number" value="<?= $producer['phone_number'] ?>" class="input-field" required>

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
        <input type="hidden" name="producer_id" value="<?= $producer_id ?>">
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