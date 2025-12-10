<?php
include '../Database/dbconnect.php';

// ONLY ADMIN CAN ACCESS
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
  $_SESSION['error'] = 'Unauthorized access.';
  header("Location: ../login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

// FETCH ALL HELP REQUESTS
$query = $conn->prepare("
    SELECT r.*, p.username, p.email, f.farm_name
    FROM admin_help_requests r
    LEFT JOIN producers p ON r.producer_id = p.producer_id
    LEFT JOIN farms f ON p.producer_id = f.producer_id
    ORDER BY r.request_date DESC
");
$query->execute();
$requests = $query->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Help Requests</title>
  <link rel="stylesheet" href="./Styles/admin-help-req.css">
  <script src="../Assets/Javascript/admin-help.js" defer></script>
</head>

<body>

  <div class="help-request-container">

    <div class="top-content">
      <h2>Producer Help Requests</h2>
      <p>Respond to producer technical concerns</p>
    </div>

    <div class="help-request-box">

      <?php if ($requests->num_rows > 0): ?>
        <?php while ($row = $requests->fetch_assoc()): ?>

          <?php
          // STATUS COLORS
          switch ($row['status']) {
            case 'Open':
              $color = '#0a64ff';
              $bg = '#e3edff';
              break;
            case 'In Progress':
              $color = '#a76b00';
              $bg = '#fff2ba';
              break;
            case 'Resolved':
              $color = '#0b7a00';
              $bg = '#d5ffd4';
              break;
            default:
              $color = '#0a64ff';
              $bg = '#e3edff';
          }
          ?>

          <div class="request-card">

            <div class="request-header">
              <h3><?= htmlspecialchars($row['subject']) ?></h3>

              <span class="status" style="background:<?= $bg ?>; color:<?= $color ?>">
                <?= $row['status'] ?>
              </span>
            </div>

            <p class="producer-info">
            <div class="username">
              <b>From:</b> <?= htmlspecialchars($row['username']) ?>
              <span>(<?= htmlspecialchars($row['email']) ?>)</span>
            </div>
            <div class="farm">
              <b>Farm:</b> <?= $row['farm_name'] ?: "N/A" ?>
            </div>
            </p>

            <p class="message-text">
              <?= nl2br(htmlspecialchars($row['message_text'])) ?>
            </p>

            <?php if (!empty($row['admin_response'])): ?>
              <div class="admin-response-box">
                <h4>Your Response:</h4>
                <p><?= nl2br(htmlspecialchars($row['admin_response'])) ?></p>

                <?php if (!empty($row['date_responded'])): ?>
                  <small>Responded: <?= date("F j, Y h:i A", strtotime($row['date_responded'])) ?></small>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <div class="request-footer">
              <small>Created: <?= date("F j, Y h:i A", strtotime($row['request_date'])) ?></small>

              <?php if ($row['status'] === 'Open' || $row['status'] === 'In Progress'): ?>
                <button class="respond-btn"
                  data-id="<?= $row['request_id'] ?>"
                  data-status="<?= $row['status'] ?>"
                  data-subject="<?= htmlspecialchars($row['subject']) ?>"
                  data-message="<?= htmlspecialchars($row['message_text']) ?>">
                  <img src="../Assets/svg/message-square-01-svgrepo-com.svg" alt="">
                  Respond
                </button>
              <?php endif; ?>

            </div>

          </div>

        <?php endwhile; ?>
      <?php else: ?>
        <div class="message">
          <img src="../Assets/svg/circle-question-svgrepo-com.svg" alt="">
          <h3>No help requests found</h3>
          <p>Producers have not submitted any support requests.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- OVERLAY -->
  <div id="respondModalOverlay" class="modal-overlay"></div>

  <!-- MODAL -->
  <div id="respondModal" class="modal-glass">
    <div class="modal-header">
      <h2>Respond to Help Request</h2>
      <span id="closeRespondModal" class="close-btn">&times;</span>
    </div>

    <form id="respondForm" action="submit_admin_response.php" method="POST">

      <input type="hidden" name="request_id" id="modalRequestId">

      <div class="modal-field">
        <label>Subject</label>
        <input type="text" id="modalSubject" readonly>
      </div>

      <div class="modal-field">
        <label>Message</label>
        <textarea id="modalMessage" readonly></textarea>
      </div>

      <div class="modal-field" id="responseWrapper">
        <label>Your Response *</label>
        <textarea name="response" id="modalResponse" placeholder="Provide detailed assistance..." required></textarea>
      </div>

      <div class="modal-actions" id="modalButtons">
        <button type="button" id="cancelRespondModal" class="cancel-btn">Cancel</button>

        <button type="submit" name="mark_progress" id="progressBtn" class="progress-btn">
          Mark In Progress
        </button>

        <button type="submit" name="resolve_and_send" id="resolveBtn" class="send-btn">
          Resolve & Send
        </button>
      </div>

    </form>
  </div>

</body>

<script>
  document.querySelectorAll(".respond-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      let status = btn.dataset.status;

      document.getElementById("modalRequestId").value = btn.dataset.id;
      document.getElementById("modalSubject").value = btn.dataset.subject;
      document.getElementById("modalMessage").value = btn.dataset.message;

      let responseWrapper = document.getElementById("responseWrapper");
      let modalButtons = document.getElementById("modalButtons");

      if (status === "Resolved") {
        responseWrapper.style.display = "none";
        modalButtons.style.display = "none";
      } else {
        responseWrapper.style.display = "block";
        modalButtons.style.display = "flex";
      }

      document.getElementById("respondModalOverlay").classList.add("active");
      document.getElementById("respondModal").classList.add("active");
    });
  });

  function closeModal() {
    document.getElementById("respondModalOverlay").classList.remove("active");
    document.getElementById("respondModal").classList.remove("active");
  }

  document.getElementById("closeRespondModal").onclick = closeModal;
  document.getElementById("cancelRespondModal").onclick = closeModal;
</script>

</html>