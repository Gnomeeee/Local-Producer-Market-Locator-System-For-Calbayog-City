<?php
  include '../Database/dbconnect.php';

  $user_id = $_SESSION['user_id'];
  
  $stmt = $conn->prepare("
  SELECT r.review_id, r.farm_id, r.rating, r.comment_text, r.review_date, f.farm_name

  FROM consumer_reviews r
  JOIN farms f ON r.farm_id = f.farm_id
  WHERE r.user_id = ? ORDER BY r.review_id DESC, r.review_id DESC 
  ");

  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $reviews = $result->num_rows;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/review.css">
  <title></title>
</head>
<body>
  <div class="my-reviews-container">
    <div class="rev-header">
      <h2>My reviews</h2>
      <p>Reviews you've written for local farms</p>
    </div>
    <div class="con-body"> 
      <?php if($reviews > 0): ?>
            <?php while($revs = $result->fetch_assoc()): ?>
              <div class="rev">
              <div class="farm-id-rate">
                <p>Farm name:  <?= htmlspecialchars($revs['farm_name']) ?></p>
                <div class="rev-rating" aria-label="Rating">
                  <?= str_repeat('★',(int)$revs['rating']) . str_repeat('☆', 5 - (int)$revs['rating']); ?>
                </div>
                </div>
                <p class="rev-comment">
                  <?= htmlspecialchars($revs['comment_text']); ?></p>
                <p class="rev-date">
                  <?= htmlspecialchars($revs['review_date']); ?>
                </p>
          </div>
          <?php endwhile; ?>

           <?php else: ?>
            <div class="message">
              <img src="../Assets/svg/message-circle-svgrepo-com.svg" alt="">
              <p>You haven't written any reviews yet</p>
              <p>Visit farms and share your experiences</p>
            </div>

        <?php endif; ?>
    </div>
  </div>
</body>
</html>