<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Producer') {
  header("Location: ../login.php");
  exit();
}

$producer_id = (int)$_SESSION['producer_id'];

// CHECK THE STATUS OF PRODUCER 

$statusStmt = $conn->prepare("SELECT account_status FROM producers WHERE producer_id = ?");
$statusStmt->bind_param('i', $producer_id);
$statusStmt->execute();
$status = $statusStmt->get_result()->fetch_assoc()['account_status'];

// PRODUCTS COUNTER 

$proStmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products p INNER JOIN farms f ON p.farm_id = f.farm_id
 WHERE producer_id = ?");
$proStmt->bind_param('i', $producer_id);
$proStmt->execute();
$prodCounter = $proStmt->get_result()->fetch_assoc()['total_products'];

// MARKET SCHEDULE COUNTER

$schedStmt = $conn->prepare("SELECT COUNT(*) AS total_schedule FROM market_schedules ms INNER JOIN farms f ON ms.farm_id = f.farm_id WHERE producer_id = ?");
$schedStmt->bind_param('i', $producer_id);
$schedStmt->execute();
$schedCounter = $schedStmt->get_result()->fetch_assoc()['total_schedule'];

// REVIEW COUNTER FOR PRODUCER

$revStmt = $conn->prepare("SELECT COUNT(*) AS total_reviews FROM consumer_reviews cr INNER JOIN farms f ON cr.farm_id = f.farm_id WHERE producer_id = ?");
$revStmt->bind_param('i', $producer_id);
$revStmt->execute();
$revCounter = $revStmt->get_result()->fetch_assoc()['total_reviews'];

// HELP REQUEST COUNTER 

$reqStmt = $conn->prepare("SELECT COUNT(*) AS total_requests FROM admin_help_requests WHERE producer_id = ?");
$reqStmt->bind_param('i', $producer_id);
$reqStmt->execute();
$reqCounter = $reqStmt->get_result()->fetch_assoc()['total_requests'];

// CHECK IF THE PRODUCER IS ALREADY VERIFIED

$verifyStmt = $conn->prepare("SELECT is_verified FROM producers WHERE producer_id = ?");
$verifyStmt->bind_param('i', $producer_id);
$verifyStmt->execute();
$verifyRes = $verifyStmt->get_result()->fetch_assoc();

$_SESSION['is_verified'] = (int)$verifyRes['is_verified'];

$annStmt = $conn->prepare("
    SELECT *
    FROM announcements
    WHERE status = 'Active'
      AND (audience = 'All Users' OR audience = 'Producers')
    ORDER BY created_at DESC
");
$annStmt->execute();
$announcement = $annStmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/prod-dashboard.css">
  <link rel="icon" href="../Assets/svg/sprout-svgrepo-com.svg" type="icon/svg">
  <script src="../Assets/Javascript/prod-active-button.js" defer></script>
  <script src="../Assets/Javascript/message.js" defer></script>
  <title>Producers | Dashboard</title>
</head>

<body>
  <div class="lpmls-header">
    <div class="content">
      <div class="left-content">
        <div class="header-svg">
          <img class="sprout" src="../Assets/svg/sprout-svgrepo-com.svg" alt="sprout">
        </div>
        <div class="lpml">
          <h1>Producer Dashboard</h1>
          <?php
          if (isset($_SESSION['producer_id'], $_SESSION['login_type'])) {
            $stmt = $conn->prepare("SELECT username, email FROM producers WHERE producer_id = ?");
            $stmt->bind_param('i', $_SESSION['producer_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              $producer = $result->fetch_assoc();

              if ($_SESSION['login_type'] === 'username') {
                echo "<p> Welcome, " . htmlspecialchars($producer['username']) . "</p>";
              } else {
                echo "<p> Welcome, " . htmlspecialchars($producer['email']) . "</p>";
              }
            }
          }
          ?>
        </div>
      </div>
      <nav>
        <div class="right-content">
          <div class="mess">
            <?php
            // FOR SAVING AND UPDATING FARM PROFILE
            if (isset($_SESSION['farm_profile_error'])): ?>
              <div class="message-error" id="reviewMessage">
                <?= $_SESSION['farm_profile_error']; ?>
              </div>
              <?php unset($_SESSION['farm_profile_error']); ?>
            <?php elseif (isset($_SESSION['farm_profile_success'])): ?>
              <div class="message-success" id="reviewMessage">
                <?= $_SESSION['farm_profile_success']; ?>
              </div>
              <?php unset($_SESSION['farm_profile_success']); ?>
            <?php endif; ?>

            <!-- FOR ADDING A PRODUCT -->
            <?php if (isset($_SESSION['product_error'])): ?>
              <div class="message-error" id="reviewMessage">
                <?= $_SESSION['product_error']; ?>
              </div>
              <?php unset($_SESSION['product_error']); ?>
            <?php elseif (isset($_SESSION['product_success'])): ?>
              <div class="message-success" id="reviewMessage">
                <?= $_SESSION['product_success']; ?>
              </div>
              <?php unset($_SESSION['product_success']); ?>
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
          <!-- FOR PENDING VERIFICATION -->
          <?php if ($_SESSION['is_verified'] === 0): ?>
            <div class="pending-verification">
              <img src="../Assets/svg/time-svgrepo-com.svg" alt="Pending">Pending Verification
            </div>
          <?php endif; ?>

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

  <!-- MESSAGE SECTION -->
  <?php if ($_SESSION['is_verified'] === 0): ?>
    <div class="unverified-message">
      <img src="../Assets/svg/circle-exclamation-svgrepo-com.svg" alt="Pending">
      Your producer account is pending verification. Just wait until admin verify your account.
    </div>
  <?php endif; ?>

  <div class="container">
    <!-- ANNOUNCEMENT -->
    <?php if ($announcement->num_rows > 0): ?>
      <?php while ($ann = $announcement->fetch_assoc()): ?>

        <div class="announcement-card">
          <div class="ann-content">
            <div class="text">
              <img src="../Assets/svg/alert-error-svgrepo-com.svg" alt="">
              <h3>Announcement!</h3>
            </div>
            <div class="ann-icon">
              <h4 class="ann-title"><?= htmlspecialchars($ann['title']) ?></h4>
            </div>

            <p class="ann-message"><?= nl2br(htmlspecialchars($ann['message'])) ?></p>

            <span class="ann-date">
              <?= date("m/d/Y", strtotime($ann['created_at'])) ?>
            </span>
          </div>
        </div>

      <?php endwhile; ?>
    <?php endif; ?>
    <div class="buttons">
      <button data-page="farm_profile" onclick="navigatePage(this)">
        <img src="../Assets/svg/sprout-svgrepo-com.svg" alt="search">Farm Profile
      </button>
      <button data-page="products" onclick="navigatePage(this)">
        <img src="../Assets/svg/box-svgrepo-com.svg" alt="favorites">Products (<?= (int) $prodCounter ?>)
      </button>
      <button data-page="schedule" onclick="navigatePage(this)">
        <img src="../Assets/svg/date-calendar-schedule-event-appointment-svgrepo-com.svg" alt="reviews">Schedule (<?= (int)$schedCounter ?>)
      </button>
      <button data-page="reviews" onclick="navigatePage(this)">
        <img src="../Assets/svg/star-svgrepo-com.svg" alt="Account">Reviews (<?= (int) $revCounter ?>)
      </button>
      <button data-page="help_request" onclick="navigatePage(this)">
        <img src="../Assets/svg/circle-question-svgrepo-com.svg" alt="Account">Help Request (<?= (int) $reqCounter ?>)
      </button>
      <button data-page="analytics" onclick="navigatePage(this)">
        <img src="../Assets/svg/analytics-graph-chart-svgrepo-com.svg" alt="Account">Analytics
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
          case 'farm_profile':
            include './pages/farm_profile.php';
            break;
          case 'products':
            include './pages/products.php';
            break;
          case 'schedule':
            include './pages/schedule.php';
            break;
          case 'reviews':
            include './pages/reviews.php';
            break;
          case 'help_request':
            include './pages/help_request.php';
            break;
          case 'analytics':
            include './pages/analytics.php';
            break;
          case 'account':
            include './pages/accounts.php';
            break;
          default:
            include './pages/farm_profile.php';
            break;
        }
      } else {
        include './pages/farm_profile.php';
      }
      ?>
    </div>
  </div>
</body>

</html>