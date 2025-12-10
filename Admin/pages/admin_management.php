<?php
include '../Database/dbconnect.php';

// BLOCK UNAUTHORIZED
if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = 'Unauthorized access.';
  header("Location: ../login.php");
  exit();
}

// Fetch all admins
$stmt = $conn->prepare("
    SELECT admin_id, username, email, full_name, created_at, role_id
    FROM admins
    ORDER BY created_at ASC
");
$stmt->execute();
$admins = $stmt->get_result();

// Get current logged-in admin info
$currentAdminId = $_SESSION['admin_id'];
$currentAdminStmt = $conn->prepare("SELECT full_name, role_id FROM admins WHERE admin_id = ?");
$currentAdminStmt->bind_param("i", $currentAdminId);
$currentAdminStmt->execute();
$currentAdmin = $currentAdminStmt->get_result()->fetch_assoc();

// Fetch system/default admin dynamically
$systemAdminStmt = $conn->prepare("SELECT full_name, email FROM admins WHERE role_id = 1 LIMIT 1");
$systemAdminStmt->execute();
$systemAdmin = $systemAdminStmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/admin-management.css">
  <title>Administrator Management</title>
</head>

<body>

  <div class="admin-container">

    <div class="admin-header">
      <div>
        <h2>Administrator Management</h2>
        <p>Manage system administrators</p>
      </div>
      <button class="add-admin-btn" onclick="openAddAdmin()"><img src="../Assets/svg/add-svgrepo-com.svg" alt="">Add Admin</button>
    </div>

    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Created</th>
            <th>Created By</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $admins->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= date("m/d/Y", strtotime($row['created_at'])) ?></td>
              <td>
                <?php
                if ($row['role_id'] == 1) {
                  echo "System Admin";
                } elseif ($row['admin_id'] == $currentAdminId) {
                  echo "You";
                } else {
                  echo "Admin";
                }
                ?>
              </td>
              <td>
                <?php if ($row['admin_id'] == 0): ?>
                  <button class="default-admin-btn">Default Admin</button>
                <?php elseif ($row['admin_id'] == $currentAdminId): ?>
                  <span class="you-pill">You</span>
                <?php else: ?>
                  <form action="delete_admin.php" method="POST" onsubmit="return confirm('Delete this admin?');">
                    <input type="hidden" name="admin_id" value="<?= $row['admin_id'] ?>">
                    <button type="submit" class="delete-btn">
                      <img src="../Assets/svg/trash-alt-svgrepo-com.svg" alt="">Delete
                    </button>
                  </form>
                <?php endif; ?>
              </td>

            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="default-box">
    <div class="credentials">
      <img src="../Assets/svg/shield-check-svgrepo-com.svg" alt="">
      <h3> Default Administrator Credentials:</h3>
    </div>
    <p class="admin"><img src="../Assets/svg/admin-svgrepo-com.svg" alt=""> Name: <strong><?= htmlspecialchars($systemAdmin['full_name']) ?></strong></p>
    <p class="email"><img src="../Assets/svg/email-svgrepo-com.svg" alt=""> Email: <strong><?= htmlspecialchars($systemAdmin['email']) ?></strong></p>
    <p class="note">
      The default admin account cannot be deleted. Please change the password after first login.
    </p>
  </div>

  <div id="addAdminModal" class="modal-overlay">
    <div class="modal-box">
      <button class="modal-close" onclick="closeAddAdmin()">×</button>
      <h2>Add New Administrator</h2>
      <p class="subtitle">Create a new admin account with full system access</p>
      <form id="addAdminForm" method="POST" action="add_admin.php">

        <label>Username *</label>
        <input type="text" name="username" required placeholder="adminsample">

        <label>Full Name *</label>
        <input type="text" name="full_name" required placeholder="admin sample">

        <label>Email *</label>
        <input type="email" name="email" required placeholder="admin@example.com">

        <label>Password *</label>
        <input type="password" name="password" required placeholder="At least 8 characters">

        <div class="modal-actions">
          <button type="button" class="cancel-btn" onclick="closeAddAdmin()">Cancel</button>
          <button type="submit" name="add_admin" class="create-btn">
            <img src="../Assets/svg/shield-check-svgrepo-com.svg" alt=""> Create Admin
          </button>
        </div>
      </form>
    </div>
  </div>


  <script>
    function openAddAdmin() {
      document.getElementById("addAdminModal").style.display = "flex";
    }

    function closeAddAdmin() {
      document.getElementById("addAdminModal").style.display = "none";
    }
  </script>

</body>

</html>