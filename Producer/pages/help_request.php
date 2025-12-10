<?php
include '../Database/dbconnect.php';

$producer_id = (int) $_SESSION['producer_id'];

// GET HELP REQUEST BY PRODUCER_ID

$reqStmt = $conn->prepare("SELECT * FROM admin_help_requests WHERE producer_id = ?");
$reqStmt->bind_param('i', $producer_id);
$reqStmt->execute();
$reqResult = $reqStmt->get_result();

$statusColor = '';
$backgroundColor = '';
$statusText = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/help-requestss.css">
  <script src="../Assets/Javascript/help-request.js" defer></script>
  <title>Document</title>
</head>

<body>
  <div class="help-request-container">
    <div class="top-content">
      <div class="top-texts">
        <h3>Get Technical Assistance</h3>
        <p>Need help? Contact the admin team</p>
      </div>
      <div class="top-button">
        <button id="openHelpModal">
          <img src="../Assets/svg/add-svgrepo-com.svg" alt="">
          New Request
        </button>
      </div>
    </div>
    <div class="help-request-box">
      <div class="help-request-header">
        <h3>My Help Requests</h3>
        <p>Track your support tickets</p>
      </div>
      <div class="help-request-body">
        <?php if ($reqResult->num_rows > 0): ?>
          <?php while ($request = $reqResult->fetch_assoc()): ?>
            <?php
            switch ($request['status']) {
              case 'Open':
                $statusColor = '#0a64ffff';
                $statusText = 'Open';
                $backgroundColor = '#e3edffff';
                break;
              case 'In Progress':
                $statusColor = '#813100ff';
                $statusText = 'In Progress';
                $backgroundColor = '#fff2baff';
                break;
              case 'Resolved':
                $statusColor = '#008407ff';
                $statusText = 'Resolved';
                $backgroundColor = '#cfffcaff';
                break;
              default:
                $statusColor = '#0a64ffff';
                $statusText = 'Open';
                $backgroundColor = '#e3edffff';
            } ?>
            <div class="request-content">
              <div class="requests-text">
                <div class="title-status">
                  <h3><?= htmlspecialchars($request['subject']) ?></h3>
                  <?php echo '<div style="border: 1px solid;
                      padding: 5px 10px; 
                      margin-left: 5px;
                      border-radius: 50px;
                      font-size: 13px;
                      border: ' . $backgroundColor . ';
                      background-color:' . $backgroundColor . ';">
                      <span style="color:' . $statusColor . ';">' . $statusText . '</span>
                    </div>'; ?>
                </div>
                <p><?= htmlspecialchars($request['message_text']) ?></p>
              </div>
              <?php if (!empty(trim($request['admin_response'])) && !empty($request['date_responded'])): ?>
                <div class="admin-response">
                  <h3>Admin Response:</h3>
                  <p><?= nl2br(htmlspecialchars($request['admin_response'])) ?></p>
                  <p>Responded:
                    <?= date("F j, Y h:i A", strtotime($request['date_responded'])) ?>
                  </p>
                </div>
              <?php endif; ?>
              <div class="date">
                <p>Created: <?= date("F j, Y h:i A", strtotime($request['request_date'])) ?>
                </p>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="message">
            <img src="../Assets/svg/circle-question-svgrepo-com.svg" alt="">
            <h3>No help requests yet</h3>
            <p>Need assistance? Create your first help request</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <!-- Overlay -->
  <div id="helpModalOverlay" class="modal-overlay"></div>

  <!-- Modal -->
  <div id="helpModal" class="modal">
    <div class="modal-header">
      <h2>Request Technical Assistance</h2>
      <span id="closeHelpModal" class="close-btn">&times;</span>
    </div>

    <p class="modal-desc">
      Describe the issue you're facing and our admin team will help you
    </p>

    <form action="submit_help_request.php" method="POST" class="modal-form">

      <input type="hidden" name="producer_id" value="<?= $producer_id ?>">
      <label>Subject *</label>
      <input type="text" name="subject" class="input-field"
        placeholder="e.g., Cannot update farm profile" required>

      <label>Message *</label>
      <textarea name="message" class="textarea-field"
        placeholder="Please describe your issue in detail..." required></textarea>

      <div class="modal-actions">
        <button type="button" id="cancelHelpModal" class="cancel-btn">Cancel</button>
        <button type="submit" name="send_help_request" class="send-btn">
          <img src="../Assets/svg/circle-question-svgrepo-com.svg" alt="">
          Send Request</button>
      </div>
    </form>
  </div>
</body>

</html>