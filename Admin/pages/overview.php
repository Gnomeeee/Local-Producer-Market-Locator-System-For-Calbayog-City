<?php
include '../Database/dbconnect.php';

if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
  header("Location: ../login.php");
  exit();
}

$admin_id = (int) $_SESSION['admin_id'];

// TOTAL USERS
$users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$consumers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role_id = 3")->fetch_assoc()['total'];
$producers = $conn->query("SELECT COUNT(*) AS total FROM producers WHERE role_id = 2")->fetch_assoc()['total'];

// VERIFIED FARMS
$verified_farms = $conn->query("SELECT COUNT(*) AS total FROM farms WHERE approval_status = 'Approved'")->fetch_assoc()['total'];
$total_farms = $conn->query("SELECT COUNT(*) AS total FROM farms")->fetch_assoc()['total'];

// PRODUCTS
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

// REVIEWS
$total_reviews = $conn->query("SELECT COUNT(*) AS total FROM consumer_reviews")->fetch_assoc()['total'];

// PENDING VERIFICATION
$pending_verifications = $conn->query("SELECT COUNT(*) AS total FROM producers WHERE is_verified = 0")->fetch_assoc()['total'];

// OPEN HELP REQUESTS
$open_help = $conn->query("SELECT COUNT(*) AS total FROM admin_help_requests WHERE status='In Progress'")->fetch_assoc()['total'];

// TOTAL HELP REQUESTS
$total_help = $conn->query("SELECT COUNT(*) AS total FROM admin_help_requests")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/admin-overview.css">
  <title>Document</title>
</head>

<body>
  <div class="overview-layout">
    <div class="top-cards-row">

      <div class="stat-card">
        <div class="icon-circle">
          <img src="../Assets/svg/users-svgrepo-com.svg">
          <h2>Total Users</h2>
        </div>

        <p class="value"><?= $users ?></p>
        <span><?= $consumers ?> consumers, <?= $producers ?> producers</span>
      </div>

      <div class="stat-card">
        <div class="icon-circle">
          <img src="../Assets/svg/farm-svgrepo-com (1).svg">
          <h2>Verified Farms</h2>
        </div>

        <p class="value"><?= $verified_farms ?></p>
        <span>of <?= $total_farms ?> total</span>
      </div>

      <div class="stat-card">
        <div class="icon-circle">
          <img src="../Assets/svg/box-svgrepo-com.svg">
          <h2>Total Products</h2>
        </div>

        <p class="value"><?= $total_products ?></p>
        <span>Listed by producers</span>
      </div>

      <div class="stat-card">
        <div class="icon-circle">
          <img src="../Assets/svg/message-square-01-svgrepo-com.svg">
          <h2>Customer Reviews</h2>
        </div>

        <p class="value"><?= $total_reviews ?></p>
        <span>Submitted reviews</span>
      </div>

    </div>

    <div class="bottom-grid">

      <div class="pending-box">
        <div class="title">
          <img src="../Assets/svg/alert-error-svgrepo-com.svg" alt="">
          <h3>Pending Actions</h3>
        </div>

        <div class="pending-item">
          <div>
            <strong>Producer Verifications</strong>
            <p><?= $pending_verifications ?> pending approval</p>
          </div>
          <span class="badge"><?= $pending_verifications ?></span>
        </div>

        <div class="pending-item">
          <div>
            <strong>Open Help Requests</strong>
            <p><?= $open_help ?> awaiting response</p>
          </div>
          <span class="badge"><?= $open_help ?></span>
        </div>

      </div>

      <!-- ===== PLATFORM ACTIVITY ===== -->
      <div class="activity-box">
        <div class="act-title">
          <img src="../Assets/svg/trending-up-svgrepo-com.svg" alt="">
          <h3>Platform Activity</h3>
        </div>

        <div class="activity">
          <div>
            <strong>Total Help Requests</strong>
            <p>All time</p>
          </div>
          <span><?= $total_help ?></span>
        </div>

        <div class="activity">
          <div>
            <strong>System Health</strong>
            <p>All systems operational</p>
          </div>
          <span class="health-icon">✔</span>
        </div>

      </div>

    </div>

  </div>
</body>

</html>