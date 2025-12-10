<?php
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch users
$usersQuery = $conn->query("
    SELECT 
        user_id AS id,
        username AS name,
        email,
        role_id,
        reg_date AS joined,
        account_status AS status,
        'user' AS source
    FROM users
");

// Fetch producers
$producersQuery = $conn->query("
    SELECT
        producer_id AS id,
        username AS name,
        email,
        role_id,
        reg_date AS joined,
        status,
        'producer' AS source
    FROM producers
");

$allUsers = [];

// Add users
while ($u = $usersQuery->fetch_assoc()) {
  $allUsers[] = $u;
}

// Add producers (use `status` column)
while ($p = $producersQuery->fetch_assoc()) {
  $allUsers[] = $p;
}

// Sort alphabetically
usort($allUsers, function ($a, $b) {
  return strcmp(strtolower($a['name']), strtolower($b['name']));
});

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/admin-users.css">
  <title>User Management</title>
</head>

<body>

  <div class="user-management-container">
    <div class="user-header">
      <h2>User Management</h2>
      <p>Manage all platform users</p>
    </div>
    <div class="user-body">
      <table class="user-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($allUsers as $usr): ?>

            <?php
            // Determine role pill
            $rolePill = "";
            if ($usr['role_id'] == 1) $rolePill = "<span class='pill role-admin'>admin</span>";
            if ($usr['role_id'] == 2) $rolePill = "<span class='pill role-producer'>producer</span>";
            if ($usr['role_id'] == 3) $rolePill = "<span class='pill role-consumer'>consumer</span>";

            // Status pill
            $statusPill = ($usr['status'] === "Inactive")
              ? "<span class='pill status-inactive'>Inactive</span>"
              : "<span class='pill status-active'>Active</span>";

            // Check if it's the logged-in admin
            $isYou = ($usr['id'] == $admin_id && $usr['role_id'] == 1);
            ?>

            <tr>
              <td><?= htmlspecialchars($usr['name']) ?></td>
              <td><?= htmlspecialchars($usr['email']) ?></td>
              <td><?= $rolePill ?></td>
              <td><?= $statusPill ?></td>
              <td><?= date("m/d/Y", strtotime($usr['joined'])) ?></td>

              <td>
                <?php if ($isYou): ?>
                  <span class="you-pill">You</span>
                <?php else: ?>

                  <?php if ($usr['status'] === "Active"): ?>
                    <form action="toggle_user_status.php" method="POST" style="display:inline-block;">
                      <input type="hidden" name="id" value="<?= $usr['id'] ?>">
                      <input type="hidden" name="source" value="<?= $usr['source'] ?>">
                      <input type="hidden" name="action" value="deactivate">
                      <button class="action-btn deactivate">
                        <img src="../Assets/svg/user-x-svgrepo-com.svg">Deactivate
                      </button>
                    </form>
                  <?php else: ?>
                    <form action="toggle_user_status.php" method="POST" style="display:inline-block;">
                      <input type="hidden" name="id" value="<?= $usr['id'] ?>">
                      <input type="hidden" name="source" value="<?= $usr['source'] ?>">
                      <input type="hidden" name="action" value="activate">
                      <button class="action-btn activate">
                        <img src="../Assets/svg/user-check-svgrepo-com.svg">Activate
                      </button>
                    </form>
                  <?php endif; ?>

                <?php endif; ?>

              </td>

            </tr>

          <?php endforeach; ?>
        </tbody>

      </table>
    </div>
  </div>

</body>

</html>