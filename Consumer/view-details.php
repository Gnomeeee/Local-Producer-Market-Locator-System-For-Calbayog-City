<?php 
  session_start();
  include '../Database/dbconnect.php';
  
  if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo "Invalid request";
    exit;
  }
  $id = (int)$_GET['id'];
  $user_id = $_SESSION['user_id'];

  // Farm and producer
  $stmt = $conn->prepare("
    SELECT f.*, p.username 
    FROM farms f 
    JOIN producers p ON f.producer_id = p.producer_id 
    WHERE f.farm_id = ?
  "); 
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $farm = $result->fetch_assoc();
  $stmt->close();

  if (!$farm) {
    http_response_code(404);
    echo "Farm not found";
    exit;
  }

  // Products
  
  $prodStmt = $conn->prepare("SELECT product_name, category, unit_price, unit_of_measure, stock_quantity FROM products WHERE farm_id = ?");
  $prodStmt->bind_param("i", $id);
  $prodStmt->execute();
  $products = $prodStmt->get_result();

  // Market schedules

  $schedStmt = $conn->prepare("
    SELECT day_of_week, start_time, end_time, location 
    FROM market_schedules 
    WHERE farm_id = ?
    ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time
  ");
  $schedStmt->bind_param("i", $id);
  $schedStmt->execute();
  $schedules = $schedStmt->get_result();

  // REVIEW 
  
  $revStmt = $conn->prepare(" SELECT 

  r.review_id, 
  r.rating, 
  r.comment_text, 
  r.review_date, 
  u.username,
  u.email
  
  FROM consumer_reviews r JOIN users u ON r.user_id = u.user_id WHERE r.farm_id = ? ORDER BY r.review_date DESC, r.review_id DESC ");

  $revStmt->bind_param('i', $id); $revStmt->execute();
  $reviews = $revStmt->get_result();
  $reviewCount = $reviews->num_rows;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($farm['farm_name']); ?> - Details</title>
  
  <link rel="stylesheet" href="./Styles/view-detail.css">
  <link rel="stylesheet" href="./Styles/cons-review.css">
  <script src="../Assets/Javascript/write-review.js" defer></script>
</head>
<body data-farm-id="<?= $id ?>">
  <div class="card">
    <h2><?= htmlspecialchars($farm['farm_name']); ?></h2>

    <div class="loc">
      <img src="../Assets/svg/map-pin-svgrepo-com.svg" alt="location" />
      <p><?= htmlspecialchars(trim(($farm['address'] ?? '') . ' ' . ($farm['city'] ?? ''))); ?></p>
    </div>

    <div class="about-farm-container">
      <div class="about-farm">
        <img src="../Assets/svg/sprout-svgrepo-com.svg" alt="about farm" />
        <h3>About the farm</h3>
      </div>
      <p><?= nl2br(htmlspecialchars($farm['description'] ?? '')); ?></p>
      <div class="owner">
        <img src="../Assets/svg/box-svgrepo-com.svg" alt="container" />
        <span>Managed by: </span> <strong><?= htmlspecialchars($farm['username']); ?></strong>
      </div>
      <div class="contact">
        <img src="../Assets/svg/telephone-svgrepo-com.svg" alt="Number" />
        <p>Contact: </p><?= htmlspecialchars($farm['phone_number'] ?? ''); ?>
      </div>
    </div>

    <div class="available-products">
      <img src="../Assets/svg/box-svgrepo-com.svg" alt="container" />
      <h3>Available Products</h3>
    </div>
    <?php if ($products->num_rows > 0): ?>
      <div class="product-card">
        <?php while ($prod = $products->fetch_assoc()): ?>
          <div class="product-box">
            <h3><?= htmlspecialchars($prod['product_name']); ?></h3>
            <div class="cat"><p><?= htmlspecialchars($prod['category']); ?></p></div>
            <p>₱<?= htmlspecialchars($prod['unit_price'] . '/' . $prod['unit_of_measure']); ?></p>
            <p>Stock: <?= htmlspecialchars($prod['stock_quantity'] . ' ' . $prod['unit_of_measure']); ?></p>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="message"><p>No products listed yet</p></div>
    <?php endif; ?>
    <?php $prodStmt->close(); ?>

    <div class="schedule">
      <img src="../Assets/svg/date-calendar-schedule-event-appointment-svgrepo-com.svg" alt="schedule" />
      <p>Market Schedule</p>
    </div>
    <?php if ($schedules->num_rows > 0): ?>
      <?php while ($schd = $schedules->fetch_assoc()): ?>
        <div class="sched">
          <div class="time">
            <img src="../Assets/svg/time-svgrepo-com.svg" alt="time" />
            <p><?= htmlspecialchars($schd['day_of_week'] . ' - ' . $schd['start_time'] . ' - ' . $schd['end_time']); ?></p>
          </div>
          <div class="locat">
            <img src="../Assets/svg/map-pin-svgrepo-com.svg" alt="location" />
            <p><?= htmlspecialchars($schd['location']); ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="message"><p>No schedule available</p></div>
    <?php endif; ?>
    <?php $schedStmt->close(); ?>

    <div class="reviews">
      <div class="ambot">
        <img src="../Assets/svg/message-circle-svgrepo-com.svg" alt="reviews" />
        <p>Reviews(<?= (int)$reviewCount ?>)</p>
      </div>
      <a style="text-decoration: none;" href="#writeReviewModal" class="write-review-btn" id="openWriteReview">Write Review</a>
    </div>

    <?php if ($reviewCount > 0): ?>
      <div class="con-review">
  <?php while ($rev = $reviews->fetch_assoc()): ?>
    <div class="rev-box">
      <div class="user-rev">
        <p class="rev-username">
          <?= htmlspecialchars($rev['username']) ?>
        </p>
        <div class="rev-rating" aria-label="Rating">
          <?= str_repeat('★', (int)$rev['rating']) . str_repeat('☆', 5 - (int)$rev['rating']); ?>
        </div>
      </div>

      <p class="rev-comment">
        <?= nl2br(htmlspecialchars($rev['comment_text'])); ?>
      </p>

      <p class="rev-date">
        <?= htmlspecialchars($rev['review_date']); ?>
      </p>
    </div> <!-- END .rev-box -->
  <?php endwhile; ?>
</div> <!-- END .con-review -->

    <?php else: ?>
      <div class="rev-message">
        <img src="../Assets/svg/message-circle-svgrepo-com.svg" alt="message" />
        <p>No reviews yet</p>
        <p>Be the first to review!</p>
      </div>
    <?php endif; ?>
    <?php $revStmt->close(); ?>
  </div>
  <!-- Write Review Modal -->
<div class="modal-backdrop" id="writeReviewModal" aria-hidden="true">
  <a href="#" class="modal-close" id="closeWriteReview" aria-label="Close">×</a>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="writeReviewTitle">
    <h3 style="color: rgb(2, 62, 2);" id="writeReviewTitle">Write a Review</h3>
    <br>
    <p style="color: gray;">Share your experience<?= isset($farm['farm_name']) ? ' with ' . htmlspecialchars($farm['farm_name']) : '' ?></p>
    <br>
    <br>
    <form id="writeReviewForm" method="post" action="write-review.php" novalidate>
      <div class="form-group">
        <label style="color: rgb(2, 62, 2);" for="ratingSelect">Rating</label>
        <select id="ratingSelect" name="rating" required>
          <option value="" disabled selected >Select rating</option>
          <option value="5">5 - Excellent</option>
          <option value="4">4 - Very Good</option>
          <option value="3">3 - Good</option>
          <option value="2">2 - Fair</option>
          <option value="1">1 - Poor</option>
        </select>
        <div class="error" id="ratingError"></div>
      </div>
      <br>
      <div class="form-group">
        <label style="color: rgb(2, 62, 2);" for="comment">Comment</label>
        <textarea id="comment" name="comment" rows="4" placeholder="Share your experience..." maxlength="1000" required></textarea>
        <div class="error" id="commentError"></div>
      </div>

      <input type="hidden" name="farm_id" value="<?= (int)$id ?>">

      <div class="form-actions">
        <a href="#" class="btn-secondary" id="cancelWriteReview">Cancel</a>
        <button type="submit" class="btn-primary" id="submitWriteReview">
          <span class="btn-spinner" aria-hidden="true" style="display:none;"></span>
          <span id="btnText">Submit</span>
        </button>
      </div>
      <div id="formStatus" class="form-status" role="status" aria-live="polite"></div>
    </form>
  </div>
</div>
</body>
</html>