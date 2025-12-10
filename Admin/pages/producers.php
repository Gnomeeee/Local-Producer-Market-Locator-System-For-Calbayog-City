<?php
include '../Database/dbconnect.php';

// REQUIRE ADMIN LOGIN
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
  header("Location: ../login.php");
  exit();
}

$admin_id = (int) $_SESSION['admin_id'];

// FETCH ALL PRODUCERS ORDERED BY STATUS
$query = $conn->prepare("
    SELECT producer_id, username, email, reg_date, is_verified
    FROM producers 
    ORDER BY 
        CASE is_verified
            WHEN 0 THEN 1  -- Pending first
            WHEN 1 THEN 2  -- Verified next
            WHEN 2 THEN 3  -- Cancelled last
        END,
        reg_date ASC
");
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Producers</title>
  <link rel="stylesheet" href="./Styles/producer.css">
</head>

<body>

  <div class="page-container">
    <div class="page-header">
      <div class="page-title">Producer Accounts</div>
      <div class="page-subtitle">Review all producer accounts</div>
    </div>
    <div class="page-body">
      <table>
        <thead>
          <tr>
            <th>Producer Name</th>
            <th>Email</th>
            <th>Registered</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= date("m/d/Y", strtotime($row['reg_date'])) ?></td>

              <!-- Dynamic Status -->
              <td>
                <?php
                if ($row['is_verified'] == 1) {
                  echo '<span class="verified-status">Verified</span>';
                } elseif ($row['is_verified'] == 2) {
                  echo '<span class="cancelled-status">Cancelled</span>';
                } else {
                  echo '<span class="pending-status">Pending</span>';
                }
                ?>
              </td>

              <td>
                <div class="action-buttons" style="justify-content:right;">
                  <?php if ($row['is_verified'] == 0): ?>
                    <form action="producer_approve.php" method="POST" style="display:inline-block;">
                      <input type="hidden" name="producer_id" value="<?= $row['producer_id']; ?>">
                      <button class="btn-approve">
                        <img src="../Assets/svg/check-circle-svgrepo-com.svg" alt="">
                        Approve</button>
                    </form>

                    <form action="producer_reject.php" method="POST" style="display:inline-block;">
                      <input type="hidden" name="producer_id" value="<?= $row['producer_id']; ?>">
                      <button class="btn-reject">
                        <img src="../Assets/svg/circle-xmark-svgrepo-com.svg" alt="">
                        Reject</button>
                    </form>
                  <?php else: ?>
                    <span class="no-action">—</span>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>

</body>

</html>