<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Consumer') {
  header('Location: ../login.php');
  exit();
}

$user_id = $_SESSION['user_id'];

// FOR REVIEWS COUNTER

$revstmt = $conn->prepare("SELECT COUNT(*) AS total_reviews FROM consumer_reviews WHERE user_id = ?");

$revstmt->bind_param('i', $user_id);
$revstmt->execute();
$revCounter = $revstmt->get_result()->fetch_assoc()['total_reviews'];

// FOR FAVORITES COUNTER

$favStmt = $conn->prepare("SELECT COUNT(*) as total_favorites FROM favorites WHERE user_id = ?");

$favStmt->bind_param('i', $user_id);
$favStmt->execute();
$favCounter = $favStmt->get_result()->fetch_assoc()['total_favorites'];

// FOR ANNOUNCEMENT

$annStmt = $conn->prepare("
    SELECT *
    FROM announcements
    WHERE status = 'Active'
      AND (audience = 'All Users' OR audience = 'Consumers')
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
  <link rel="stylesheet" href="./Styles/con-dashboard.css">
  <link rel="icon" href="../Assets/svg/sprout-svgrepo-com.svg" type="icon/svg">
  <script src="../Assets/Javascript/active-button.js" defer></script>
  <script src="../Assets/Javascript/message.js" defer></script>
  <title>Consumer | Dashboard</title>
</head>

<body>
  <header class="lpmls-header">
    <div class="content">
      <div class="left-content">
        <div class="header-svg">
          <img class="sprout" src="../Assets/svg/sprout-svgrepo-com.svg" alt="sprout">
        </div>
        <div class="lpml">
          <h1>Local Producers Market Locator</h1>
          <?php
          if (isset($_SESSION['user_id'], $_SESSION['login_type'])) {
            $stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              $user = $result->fetch_assoc();

              if ($_SESSION['login_type'] === 'username') {
                echo "<p> Welcome, " . htmlspecialchars($user['username']) . "</p>";
              } else {
                echo "<p> Welcome, " . htmlspecialchars($user['email']) . "</p>";
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
            // FOR REVIEW
            if (isset($_SESSION['review_error'])): ?>
              <div class="message-error" id="reviewMessage">
                <?= $_SESSION['review_error']; ?>
              </div>
              <?php unset($_SESSION['review_error']); ?>
            <?php elseif (isset($_SESSION['review_success'])): ?>
              <div class="message-success" id="reviewMessage">
                <?= $_SESSION['review_success']; ?>
              </div>
              <?php unset($_SESSION['review_success']); ?>
            <?php endif; ?>

            <!-- FOR FAVORITES -->

            <?php if (isset($_SESSION['fav_added'])): ?>
              <div class="message-success" id="reviewMessage">
                <?= $_SESSION['fav_added']; ?>
              </div>
              <?php unset($_SESSION['fav_added']);  ?>

            <?php elseif (isset($_SESSION['fav_deleted'])): ?>
              <div class="message-error" id="reviewMessage">
                <?= $_SESSION['fav_deleted']; ?>
              </div>
              <?php unset($_SESSION['fav_deleted']); ?>
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
          </div>
          <div class="btn">
            <img class="log-icon" src="../Assets/svg/logout-svgrepo-com.svg" alt="">
            <button class="log-btn" onclick="window.location.href='logout.php'">Logout</button>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <div class="container">
    <!-- FOR ANNOUNCEMENT -->

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
      <button data-page="find_farms" onclick="navigatePage(this)">
        <img src="../Assets/svg/search-alt-1-svgrepo-com.svg" alt="search">Find Farms
      </button>
      <button data-page="favorites" onclick="navigatePage(this)">
        <img src="../Assets/svg/heart-svgrepo-com.svg" alt="favorites">Favorites (<?= (int)$favCounter ?>)
      </button>
      <button data-page="reviews" onclick="navigatePage(this)">
        <img src="../Assets/svg/message-circle-svgrepo-com.svg" alt="reviews">My Reviews (<?= (int)$revCounter ?>)
      </button>
      <button data-page="account" onclick="navigatePage(this)">
        <img src="../Assets/svg/settings-svgrepo-com.svg" alt="Account">Account
      </button>
      <div class="mess">
        <?php if (isset($_SESSION['review_error'])) {
          echo "<div class='message-error'>{$_SESSION['review_error']}</div>";
          unset($_SESSION['review_error']);
        } elseif (isset($_SESSION['review_success'])) {
          echo "<div class='message-success'>{$_SESSION['review_success']}</div>";
          unset($_SESSION['review_success']);
        } ?>
      </div>
    </div>
    <div class="page-content">
      <?php
      if (isset($_GET['page'])) {
        $page = $_GET['page'];

        switch ($page) {
          case 'favorites':
            include './pages/favorites.php';
            break;
          case 'reviews':
            include './pages/reviews.php';
            break;
          case 'account':
            include './pages/accounts.php';
            break;
          default:
            include './pages/find_farms.php';
            break;
        }
      } else {
        include './pages/find_farms.php';
      }
      ?>
    </div>
  </div>
</body>

</html>