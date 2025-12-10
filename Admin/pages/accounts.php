<?php
include '../Database/dbconnect.php';

// BLOCK UNAUTHORIZED
if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = 'Unauthorized access.';
  header("Location: ../login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

$pendingVerifications = $conn->query("SELECT COUNT(*) AS total FROM producers WHERE is_verified = 0")
  ->fetch_assoc()['total'];

// OPEN HELP REQUESTS
$openHelp = $conn->query("SELECT COUNT(*) AS total FROM admin_help_requests WHERE status='In Progress'")
  ->fetch_assoc()['total'];

$totalPending = $pendingVerifications + $openHelp;

// Fetch admin info
$stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

// Determine status
$statusColor = '#6B7280';
$statusText = 'Unknown';
$icon = '../Assets/svg/circle-question-svgrepo-com.svg';

if (isset($admin['role_id'])) {
  switch ($admin['role_id']) {
    case 1:
      $statusColor = 'rgba(6, 105, 6, 1)';
      $statusText = 'System Admin';
      $icon = '../Assets/svg/shield-check-svgrepo-com.svg';
      break;
    case 2:
      $statusColor = '#D97706';
      $statusText = 'Admin';
      $icon = '../Assets/svg/time-svgrepo-com.svg';
      break;
  }
}

function hex2filter($hex)
{
  switch (strtolower($hex)) {
    case 'rgba(6, 105, 6, 1)':
      return 'invert(38%) sepia(81%) saturate(341%) hue-rotate(80deg) brightness(96%) contrast(92%)'; // green
    case '#d97706':
      return 'invert(45%) sepia(71%) saturate(577%) hue-rotate(3deg) brightness(96%) contrast(97%)'; // orange
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
  <link rel="stylesheet" href="./Styles/admin-accounts.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <script src="../Assets/Javascript/edit-user-info.js"></script>
  <script src="../Assets/Javascript/admin-toggle-password.js"></script>
  <title>Admin Account</title>
</head>

<body>
  <div class="accounts-container">

    <!-- Top Dashboard Info -->
    <div class="user-details">
      <div class="user-func">
        <h3>Platform Oversight</h3>
        <p style="font-size: 20px;"> <?= $statusText ?></p>
      </div>
      <div class="user-func">
        <h3>Pending Actions</h3>
        <p class="count"><?= (int)$totalPending ?></p>
      </div>
      <div class="user-func">
        <h3>Member Since</h3>
        <p style="font-size: 20px;"><?= date("M Y", strtotime($admin['created_at'])) ?></p>
      </div>
    </div>

    <!-- Account Settings -->
    <div class="account-settings">
      <div class="account-header">
        <h3>Account Settings</h3>
        <p>Manage your admin profile and security</p>
      </div>
      <div class="account-body">

        <!-- Profile Information -->
        <div class="profile-information">
          <div class="profile-content">
            <div class="left-information">
              <div class="account-icon">
                <img src="../Assets/svg/user-svgrepo-com.svg" alt="User">
              </div>
              <div class="user-information">
                <h3>Profile Information</h3>
                <p>Update your full name and username</p>
              </div>
            </div>
            <div class="right-information">
              <button onclick="openModal('editProfileModal')">Edit Profile</button>
            </div>
          </div>
        </div>

        <!-- Password & Security -->
        <div class="profile-information">
          <div class="profile-content">
            <div class="left-information">
              <div class="account-icon">
                <img src="../Assets/svg/lock-alt-svgrepo-com.svg" alt="Lock">
              </div>
              <div class="user-information">
                <h3>Password & Security</h3>
                <p>Change your account password</p>
              </div>
            </div>
            <div class="right-information">
              <button onclick="openModal('changePassModal')">Change Password</button>
            </div>
          </div>
        </div>

        <!-- Email Display -->
        <div class="user-email-address">
          <div class="envelope-svg">
            <img src="../Assets/svg/email-svgrepo-com.svg" alt="email">
          </div>
          <div class="email-address">
            <h3>Email Address</h3>
            <p><?= htmlspecialchars($admin['email']) ?></p>
            <p>Email cannot be changed</p>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Edit Profile Modal -->
  <div id="editProfileModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('editProfileModal')">&times;</span>

      <h2>Edit Profile</h2>
      <p class="subtitle">Update your personal information</p>

      <form action="update_admin_profile.php" method="POST">
        <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin['admin_id']) ?>">

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($admin['full_name']) ?>"
          class="input-field" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>"
          class="input-field" required>

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

      <!-- Close Button -->
      <span class="close" onclick="closeModal('changePassModal')">&times;</span>

      <h2 class="modal-title">Change Password</h2>
      <p class="subtitle">Update your account password securely</p>

      <form action="update_admin_password.php" method="POST">

        <!-- Hidden admin ID -->
        <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin['admin_id']) ?>">

        <!-- Current Password -->
        <label for="current_password">Current Password</label>
        <div class="input-box">
          <input type="password" id="current_password" name="current_password" class="input-field" placeholder="Enter current password" required>
          <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword(this)"></i>
        </div>

        <!-- New Password -->
        <label for="new_password">New Password</label>
        <div class="input-box">
          <input type="password" id="new_password" name="new_password" class="input-field" placeholder="Enter new password" required>
          <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword(this)"></i>
        </div>

        <!-- Confirm Password -->
        <label for="confirm_password">Confirm New Password</label>
        <div class="input-box">
          <input type="password" id="confirm_password" name="confirm_password" class="input-field" placeholder="Confirm new password" required>
          <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword(this)"></i>
        </div>

        <!-- Buttons -->
        <div class="actions">
          <button type="button" class="cancel-btn" onclick="closeModal('changePassModal')">Cancel</button>
          <button type="submit" class="save-btn" name="save_password">Save Password</button>
        </div>

      </form>
    </div>
  </div>

  <script>
  </script>
</body>

</html>