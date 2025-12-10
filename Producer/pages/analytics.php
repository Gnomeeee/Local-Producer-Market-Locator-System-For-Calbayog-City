<?php
include '../Database/dbconnect.php';

$producer_id = $_SESSION['producer_id'];

// GETTING PRODUCERS IN DATABASE 

$prodStmt = $conn->prepare("SELECT * FROM producers WHERE producer_id = ?");
$prodStmt->bind_param('i', $producer_id);
$prodStmt->execute();
$producer = $prodStmt->get_result()->fetch_assoc();

// GETTING COUNTER OF THE PRODUCT LISTED BY THE PRODUCER

$prodStmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products p INNER JOIN farms f ON p.farm_id = f.farm_id WHERE producer_id = ?");
$prodStmt->bind_param('i', $producer_id);
$prodStmt->execute();
$prodCounter = $prodStmt->get_result()->fetch_assoc()['total_products'];

// GETTING THE COUNTER OF MARKET SCHEDULE BASED ON THE PRODUCER

$marSchedStmt = $conn->prepare("SELECT COUNT(*) AS total_schedule FROM 
market_schedules ms JOIN farms f ON ms.farm_id = f.farm_id WHERE producer_id = ?");
$marSchedStmt->bind_param('i', $producer_id);
$marSchedStmt->execute();
$schedCounter = $marSchedStmt->get_result()->fetch_assoc()['total_schedule'];

// GET FIRST THE FARM ID 

$farmStmt = $conn->prepare("SELECT farm_id FROM farms WHERE producer_id = ?");
$farmStmt->bind_param('i', $producer_id);
$farmStmt->execute();
$farmResult = $farmStmt->get_result()->fetch_assoc();

$farm_id = $farmResult['farm_id'] ?? null;

// DEFAULT RATING
$average_rating = "0.0";

if ($farm_id) {

  $revRatingStmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM consumer_reviews WHERE farm_id = ?");
  $revRatingStmt->bind_param('i', $farm_id);
  $revRatingStmt->execute();
  $revRatingResult = $revRatingStmt->get_result()->fetch_assoc();

  if ($revRatingResult['avg_rating'] !== null) {
    $average_rating = number_format((float)$revRatingResult['avg_rating'], 1);
  }
}

// GETTING ALSO THE REVIEW COUNTER

$revStmt = $conn->prepare("SELECT COUNT(*) AS total_reviews FROM consumer_reviews cr JOIN farms f ON cr.farm_id = f.farm_id WHERE producer_id = ? ");
$revStmt->bind_param('i', $producer_id);
$revStmt->execute();
$revCounter = $revStmt->get_result()->fetch_assoc()['total_reviews'];


// GET THE STATUS OF THE PRODUCER 
$statusStmt = $conn->prepare("SELECT account_status FROM producers WHERE producer_id = ?");
$statusStmt->bind_param('i', $producer_id);
$statusStmt->execute();
$status = $statusStmt->get_result()->fetch_assoc()['account_status'];

// GETTING THE HELP REQUEST COUNTER 

$reqStmt = $conn->prepare("SELECT COUNT(*) AS total_requests FROM admin_help_requests WHERE producer_id = ?");
$reqStmt->bind_param('i', $producer_id);
$reqStmt->execute();
$reqCounter = $reqStmt->get_result()->fetch_assoc()['total_requests'];

// SWITCH PARA SA COLOR NGAN ICON SA STATUS 

$statusColor = '';
$statusText = '';
$icon = '';

switch ($status) {
  case 'pending':
    $statusColor = '#D97706';
    $statusText = 'Pending Verification';
    $icon = '../Assets/svg/time-svgrepo-com.svg';
    break;
  case 'verified':
    $statusColor = 'rgba(6, 105, 6, 1)';
    $statusText = 'Verified Producer';
    $icon = '../Assets/svg/check-circle-svgrepo-com.svg';
    break;
  case 'cancel':
    $statusColor = '#ff4e72ff';
    $statusText = 'Rejected';
    $icon = '../Assets/svg/shield-xmark-svgrepo-com.svg';
  default:
    $statusColor = '#6B7280';
    $statusText = 'Unknown';
    $icon = 'Assets/svg/circle-question-svgrepo-com.svg';
}

function hex2filter($hex)
{
  switch (strtolower($hex)) {
    case 'rgba(6, 105, 6, 1)':
      return 'invert(38%) sepia(81%) saturate(341%) hue-rotate(80deg) brightness(96%) contrast(92%)'; // green
    case '#d97706':
      return 'invert(45%) sepia(71%) saturate(577%) hue-rotate(3deg) brightness(96%) contrast(97%)'; // orange
    case '#ff4e72':
      return 'invert(47%) sepia(75%) saturate(4370%) hue-rotate(340deg) brightness(95%) contrast(102%)'; // red
    default:
      return 'invert(33%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(83%) contrast(88%)'; // gray
  }
}

$iconFilter = hex2filter($statusColor);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/analyticss.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="../Assets/Javascript/edit-user-info.js"></script>
  <script src="../Assets/Javascript/user-toggle-password.js"></script>
  <title>Accounts</title>
</head>

<body>
  <div class="analytics-container">
    <div class="analytics-details">
      <div class="producer-func">
        <div class="total-products-content">
          <div class="title">
            <img src="../Assets/svg/box-svgrepo-com.svg" alt="">
            <h3>Total Products</h3>
          </div>
          <div class="par">
            <p><?= (int)$prodCounter ?></p>
          </div>
        </div>
      </div>
      <div class="producer-func">
        <div class="market-schedule-content">
          <div class="title">
            <img src="../Assets/svg/date-calendar-schedule-event-appointment-svgrepo-com.svg" alt="">
            <h3>Market Days</h3>
          </div>
          <div class="par">
            <p><?= (int)$schedCounter ?></p>
          </div>
        </div>
      </div>
      <div class="producer-func">
        <div class="average-rating-content">
          <div class="title">
            <img src="../Assets/svg/star-svgrepo-com.svg" alt="">
            <h3>Average Rating</h3>
          </div>
          <div class="par">
            <p><?= $average_rating ?></p>
          </div>
        </div>
      </div>
      <div class="producer-func">
        <div class="total-reviews-content">
          <div class="title">
            <img src="../Assets/svg/message-circle-svgrepo-com.svg" alt="">
            <h3>Total Reviews</h3>
          </div>
          <div class="par">
            <p><?= (int)$revCounter ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="analytics-settings">
      <div class="analytics-header">
        <h3>Farm Performance</h3>
        <p>Your farm statistics at a glance</p>
      </div>
      <div class="analytics-body">
        <div class="analytics-information">
          <div class="verification-content">
            <div class="verification-information">
              <h3>Verification Status</h3>
              <p style="font-size: 17px;">
                <?php
                echo '<span style="display:inline-flex; align-items:center;">
                      <img src="' . $icon . '" alt="' . $statusText . '" style="width:22px;height:22px;margin-right:8px; filter:' . $iconFilter . ';">
                      <span style="color:' . $statusColor . ';">' . $statusText . '</span>
                    </span>';
                ?>
              </p>
            </div>
          </div>
        </div>
        <div class="help-requests">
          <div class="help-request-content">
            <div class="help-request-information">
              <h3>Help Requests</h3>
              <p><?= (int) $reqCounter . ' total requests' ?></p>
            </div>
          </div>
        </div>
        <div class="member-since">
          <div class="member-since-content">
            <div class="member-since-information">
              <h3>Member Since</h3>
              <p><?= date("F j, Y ", strtotime($producer['reg_date'])) ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>