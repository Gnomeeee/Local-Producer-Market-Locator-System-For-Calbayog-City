<?php
include '../Database/dbconnect.php';

$producer_id = (int)$_SESSION['producer_id'];


$farmStmt = $conn->prepare("SELECT farm_id FROM farms WHERE producer_id = ?");
$farmStmt->bind_param('i', $producer_id);
$farmStmt->execute();
$farmResult = $farmStmt->get_result();

if ($farmResult->num_rows === 0) {
  $farm_id = null;
} else {
  $farm_id = $farmResult->fetch_assoc()['farm_id'];
}

// GET REVIEWS 

$consRevResult = null;

if ($farm_id !== null) {
  $consRevStmt = $conn->prepare("
        SELECT cr.*, u.username
        FROM consumer_reviews cr
        JOIN users u ON cr.user_id = u.user_id
        WHERE cr.farm_id = ?
        ORDER BY cr.review_date DESC
    ");
  $consRevStmt->bind_param('i', $farm_id);
  $consRevStmt->execute();
  $consRevResult = $consRevStmt->get_result();
}

// GET AVERAGE RATING

$average_rating = "0.0";

if ($farm_id !== null) {
  $revRatingStmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM consumer_reviews WHERE farm_id = ?");
  $revRatingStmt->bind_param('i', $farm_id);
  $revRatingStmt->execute();
  $ratingRow = $revRatingStmt->get_result()->fetch_assoc();

  if ($ratingRow && $ratingRow['avg_rating'] !== null) {
    $average_rating = number_format((float)$ratingRow['avg_rating'], 1);
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/reviews.css">
  <title>Reviews</title>
</head>

<body>
  <div class="producer-review-container">

    <!-- HEADER -->
    <div class="producer-review-header">
      <div class="header-texts">
        <h3>Customer Reviews</h3>
        <p>See what customers say about your farm</p>
      </div>

      <div class="producer-ratings">
        <span>⭐ <?= $average_rating ?></span>
      </div>
    </div>

    <!-- BODY -->
    <div class="producer-review-body">

      <?php if ($farm_id === null): ?>
        <div class="message">
          <p>You have no farm profile yet. Reviews will appear once your farm is set up.</p>
        </div>

      <?php elseif ($consRevResult->num_rows > 0): ?>
        <?php while ($consRev = $consRevResult->fetch_assoc()): ?>
          <div class="review-card">

            <!-- USER + COMMENT -->
            <div class="user-rev">
              <div class="user-rev-content">
                <h4><?= htmlspecialchars($consRev['username']) ?></h4>
                <p><?= htmlspecialchars($consRev['comment_text']) ?></p>
              </div>

              <div class="rev-date">
                <p><small><?= date("F j, Y", strtotime($consRev['review_date'])) ?></small></p>
              </div>
            </div>

            <!-- STAR RATING -->
            <div class="rating-stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span><?= $i <= (int)$consRev['rating'] ? "⭐" : "☆" ?></span>
              <?php endfor; ?>
            </div>

          </div>
        <?php endwhile; ?>

      <?php else: ?>
        <div class="message">
          <img src="../Assets/svg/message-circle-svgrepo-com.svg" alt="">
          <h3>No reviews yet</h3>
          <p>Reviews will appear here once customers visit your farm.</p>
        </div>
      <?php endif; ?>

    </div>

  </div>
</body>

</html>