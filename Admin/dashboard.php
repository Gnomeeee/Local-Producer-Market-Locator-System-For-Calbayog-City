<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
  header("Location: ../login.php");
  exit();
}

$admin_id = (int) $_SESSION['admin_id'];

// COUNTS 

$totalUsers = 0;

// COUNT FOR CONSUMERS (users)
$consumerCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role_id = 3")
  ->fetch_assoc()['total'];

// COUNT FOR PRODUCERS
$producerCount = $conn->query("SELECT COUNT(*) AS total FROM producers WHERE role_id = 2")
  ->fetch_assoc()['total'];

// TOTAL OF PRODUCERS AND CONSUMER OR USERS
$totalUsers = (int)$consumerCount + (int)$producerCount;


$consumerCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role_id = 3")
  ->fetch_assoc()['total'];

$producerCount = $conn->query("SELECT COUNT(*) AS total FROM producers WHERE role_id = 2")
  ->fetch_assoc()['total'];

// VERIFIED FARMS
$verifiedFarms = $conn->query("SELECT COUNT(*) AS total FROM farms WHERE approval_status = 'Approved'")
  ->fetch_assoc()['total'];

$totalFarms = $conn->query("SELECT COUNT(*) AS total FROM farms")
  ->fetch_assoc()['total'];

// TOTAL PRODUCTS
$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM products")
  ->fetch_assoc()['total'];

// CUSTOMER REVIEWS
$totalReviews = $conn->query("SELECT COUNT(*) AS total FROM consumer_reviews")
  ->fetch_assoc()['total'];

// PENDING PRODUCER VERIFICATION
$pendingVerifications = $conn->query("SELECT COUNT(*) AS total FROM producers WHERE is_verified = 0")
  ->fetch_assoc()['total'];

// OPEN HELP REQUESTS
$openHelp = $conn->query("SELECT COUNT(*) AS total FROM admin_help_requests WHERE status='In Progress'")
  ->fetch_assoc()['total'];

// TOTAL HELP REQUESTS
$totalHelp = $conn->query("SELECT COUNT(*) AS total FROM admin_help_requests")
  ->fetch_assoc()['total'];

// TOTAL ADMINS
$totalAdmins = $conn->query("SELECT COUNT(*) AS total FROM admins")
  ->fetch_assoc()['total'];

// TOTAL ANNOUNCEMENTS
$totalAnnouncements = $conn->query("SELECT COUNT(*) AS total FROM announcements")
  ->fetch_assoc()['total'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/admin-dashboards.css">
  <link rel="icon" href="../Assets/svg/sprout-svgrepo-com.svg" type="icon/svg">
  <script src="../Assets/Javascript/admin-active-button.js" defer></script>
  <script src="../Assets/Javascript/message.js" defer></script>
  <title>Admin | Dashboard</title>
</head>

<body>
  <div class="lpmls-header">
    <div class="content">
      <div class="left-content">
        <div class="header-svg">
          <img class="sprout" src="../Assets/svg/shield-check-svgrepo-com.svg" alt="sprout">
        </div>
        <div class="lpml">
          <h1>Admin Dashboard</h1>
          <?php
          if (isset($_SESSION['admin_id'], $_SESSION['login_type'])) {
            $stmt = $conn->prepare("SELECT username, email FROM admins WHERE admin_id = ?");
            $stmt->bind_param('i', $_SESSION['admin_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              $admin = $result->fetch_assoc();

              if ($_SESSION['login_type'] === 'username') {
                echo "<p> Welcome, " . htmlspecialchars($admin['username']) . "</p>";
              } else {
                echo "<p> Welcome, " . htmlspecialchars($admin['email']) . "</p>";
              }
            }
          }
          ?>
        </div>
      </div>
      <nav>
        <div class="right-content">
          <div class="mess">

            <!-- FOR MESSAGES -->
            <?php if (isset($_SESSION['error'])): ?>
              <div class="message-error" id="reviewMessage">
                <?= $_SESSION['error']; ?>
              </div>
              <?php unset($_SESSION['error']); ?>
            <?php elseif (isset($_SESSION['success'])): ?>
              <div class="message-success" id="reviewMessage">
                <?= $_SESSION['success']; ?>
              </div>
              <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- FOR ADDING SCHEDULE -->

            <?php if (isset($_SESSION['schedule_error'])): ?>
              <div class="message-error" id="reviewMessage">
                <?= $_SESSION['schedule_error']; ?>
              </div>
              <?php unset($_SESSION['schedule_error']); ?>
            <?php elseif (isset($_SESSION['schedule_success'])): ?> <div class="message-success" id="reviewMessage">
                <?= $_SESSION['schedule_success']; ?>
              </div>
              <?php unset($_SESSION['schedule_success']) ?>
            <?php endif; ?>

            <!-- FOR SUBMITTING HELP REQUEST -->

            <?php if (isset($_SESSION['help_request_error'])): ?>
              <div class="message-error" id="reviewMessage">
                <?= $_SESSION['help_request_error']; ?>
              </div>
              <?php unset($_SESSION['help_request_error']); ?>
            <?php elseif (isset($_SESSION['help_request_success'])): ?>
              <div class="message-success" id="reviewMessage">
                <?= $_SESSION['help_request_success']; ?>
              </div>
              <?php unset($_SESSION['help_request_success']); ?>
            <?php endif; ?>

          </div>

          <!-- FOR UPDATING PROFILE -->
          <?php if (isset($_SESSION['profile_error'])): ?>
            <div class="message-error" id="reviewMessage">
              <?= $_SESSION['profile_error']; ?>
            </div>
            <?php unset($_SESSION['profile_error']); ?>

          <?php elseif (isset($_SESSION['profile_successful'])): ?>
            <div class="message-success" id="reviewMessage">
              <?= $_SESSION['profile_successful']; ?>
            </div>
            <?php unset($_SESSION['profile_successful']); ?>
          <?php endif; ?>

          <!-- FOR UPDATING PASSWORD -->

          <?php if (isset($_SESSION['pro_password_error'])): ?>
            <div class="message-error" id="reviewMessage">
              <?= $_SESSION['pro_password_error']; ?>
            </div>
            <?php unset($_SESSION['pro_password_error']); ?>

          <?php elseif (isset($_SESSION['update_password_successful'])): ?>
            <div class="message-success" id="reviewMessage">
              <?= $_SESSION['update_password_successful']; ?>
            </div>
            <?php unset($_SESSION['update_password_successful']); ?>
          <?php endif; ?>

          <div class="btn">
            <img class="log-icon" src="../Assets/svg/logout-svgrepo-com.svg" alt="">
            <button class="log-btn" onclick="window.location.href='logout.php'">Logout</button>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="container">
    <div class="buttons">
      <button data-page="overview" onclick="navigatePage(this)">
        <img src="../Assets/svg/statistics-in-bars-graphic-svgrepo-com.svg" alt="search">Overview
      </button>
      <button data-page="producers" onclick="navigatePage(this)">
        <img src="../Assets/svg/user-check-svgrepo-com.svg" alt="favorites">Producers(<?= (int)$producerCount ?>)
      </button>
      <button data-page="help_requests" onclick="navigatePage(this)">
        <img src="../Assets/svg/message-square-01-svgrepo-com.svg" alt="reviews">Help Requests(<?= (int) $totalHelp ?>)
      </button>
      <button data-page="farms" onclick="navigatePage(this)">
        <img src="../Assets/svg/farm-svgrepo-com (1).svg" alt="Account">Farms (<?= (int) $totalFarms ?>)
      </button>
      <button data-page="users" onclick="navigatePage(this)">
        <img src="../Assets/svg/users-svgrepo-com.svg" alt="Account">Users(<?= (int) $totalUsers ?>)
      </button>
      <button data-page="announcements" onclick="navigatePage(this)">
        <img src="../Assets/svg/message-square-01-svgrepo-com.svg" alt="Account">Announcements (<?= (int) $totalAnnouncements ?>)
      </button>
      <button data-page="admin_management" onclick="navigatePage(this)">
        <img src="../Assets/svg/shield-check-svgrepo-com.svg" alt="Account">Admin Management (<?= (int) $totalAdmins ?>)
      </button>
      <button data-page="account" onclick="navigatePage(this)">
        <img src="../Assets/svg/settings-svgrepo-com.svg" alt="Account">Account
      </button>
      <div class="mess">
        <!-- for messages -->
      </div>
    </div>
    <div class="page-content">
      <?php
      if (isset($_GET['page'])) {
        $page = $_GET['page'];

        switch ($page) {
          case 'overview':
            include './pages/overview.php';
            break;
          case 'producers':
            include './pages/producers.php';
            break;
          case 'help_requests':
            include './pages/help_requests.php';
            break;
          case 'farms':
            include './pages/farms.php';
            break;
          case 'users':
            include './pages/users.php';
            break;
          case 'announcements':
            include './pages/announcements.php';
            break;
          case 'admin_management':
            include './pages/admin_management.php';
            break;
          case 'account':
            include './pages/accounts.php';
            break;
          default:
            include './pages/overview.php';
            break;
        }
      } else {
        include './pages/overview.php';
      }
      ?>
    </div>
  </div>
</body>

</html>