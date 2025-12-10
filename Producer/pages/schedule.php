<?php
include '../Database/dbconnect.php';

$producer_id = (int) $_SESSION['producer_id'];

$marSchedStmt = $conn->prepare("SELECT * FROM market_schedules ms JOIN farms f ON ms.farm_id = f.farm_id WHERE producer_id = ?");
$marSchedStmt->bind_param('i', $producer_id);
$marSchedStmt->execute();
$marSchedResult = $marSchedStmt->get_result();

// GET SCHEDULE


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/market-schedule.css">
  <script src="../Assets/Javascript/schedule.js"></script>
  <title>Document</title>
</head>

<body>
  <div class="market-schedule-container">
    <div class="market-schedule-header">
      <div class="market-texts">
        <h3>Market Schedule</h3>
        <p>Set your market days and locations</p>
      </div>
      <div class="market-button">
        <button class="market-btn" onclick="openAddScheduleModal()">
          <img src="../Assets/svg/add-svgrepo-com.svg" alt="Add Schedule">
          Add Schedule
        </button>
      </div>
    </div>
    <div class="market-schedule-body">
      <?php if ($marSchedResult->num_rows > 0): ?>
        <?php while ($marSched = $marSchedResult->fetch_assoc()): ?>
          <div class="market-schedule-card">
            <div class="market-img-texts">
              <div class="market-img">
                <img src="../Assets/svg/date-calendar-schedule-event-appointment-svgrepo-com.svg" alt="Market Schedule">
              </div>
              <div class="market-schedule-texts">
                <h3><?= htmlspecialchars($marSched['day_of_week']) . ' - ' . date("g:i A", strtotime($marSched['start_time'])) . ' to ' . date("g:i A", strtotime($marSched['end_time'])) ?></h3>
                <p><img src="../Assets/svg/map-pin-svgrepo-com.svg" alt="Address"><?= $marSched['location'] ?></p>
              </div>
            </div>
            <div class="market-schedule-action">
              <div class="edit-btn">
                <button onclick="openUpdateScheduleModal(
                          <?= $marSched['schedule_id'] ?>,
                          '<?= addslashes($marSched['day_of_week']) ?>',
                          '<?= date("g:i A", strtotime($marSched['start_time'])) ?>',
                          '<?= date("g:i A", strtotime($marSched['end_time'])) ?>',
                          '<?= addslashes($marSched['location']) ?>'
                      )">
                  <img src="../Assets/svg/pen-new-square-svgrepo-com.svg" alt="edit button">
                </button>
              </div>

              <div class="delete-btn">
                <form action="delete_schedule.php" method="post" style="display:inline;">
                  <input type="hidden" name="schedule_id" value="<?= $marSched['schedule_id'] ?>">
                  <button type="submit" name="delete_schedule" class="dlt-btn">
                    <img src="../Assets/svg/trash-alt-svgrepo-com.svg" alt="delete button">
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="message">
          <img src="../Assets/svg/date-calendar-schedule-event-appointment-svgrepo-com.svg" alt="schedule">
          <h3>No market schedule set</h3>
          <p>Let consumers know when and where to find you</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- ===========================
      ADD SCHEDULE MODAL
=========================== -->
  <div id="addScheduleModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeAddScheduleModal()">&times;</span>

      <h2>Add Market Schedule</h2>
      <p class="subtitle">Add when and where consumers can find you</p>

      <form action="add_schedule.php" method="POST">

        <input type="hidden" name="producer_id" value="<?= $producer_id ?>">

        <label>Day *</label>
        <select name="day" required>
          <option value="" disabled selected>Select day</option>
          <option>Monday</option>
          <option>Tuesday</option>
          <option>Wednesday</option>
          <option>Thursday</option>
          <option>Friday</option>
          <option>Saturday</option>
          <option>Sunday</option>
        </select>

        <label>Start Time *</label>
        <input type="text" name="start_time" placeholder="e.g., 6:00 AM - 12:00 PM" required>

        <label>End Time *</label>
        <input type="text" name="end_time" placeholder="e.g., 6:00 AM - 12:00 PM" required>

        <label>Location *</label>
        <input type="text" name="location" placeholder="e.g., Calbayog Public Market" required>

        <div class="modal-actions">
          <button type="button" class="cancel-btn" onclick="closeAddScheduleModal()">Cancel</button>
          <button type="submit" name="add_schedule" class="submit-btn">Add Schedule</button>
        </div>
      </form>
    </div>
  </div>


  <!-- ===========================
      UPDATE SCHEDULE MODAL
=========================== -->
  <div id="updateScheduleModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeUpdateScheduleModal()">&times;</span>

      <h2>Update Schedule</h2>
      <p class="subtitle">Edit your market schedule details</p>

      <form action="update_schedule.php" method="POST">
        <input type="hidden" name="schedule_id" value="<?= $marSched['schedule_id'] ?>" id="update_schedule_id">

        <label>Day *</label>
        <select name="day" id="update_day" required>
          <option>Monday</option>
          <option>Tuesday</option>
          <option>Wednesday</option>
          <option>Thursday</option>
          <option>Friday</option>
          <option>Saturday</option>
          <option>Sunday</option>
        </select>

        <label>Start Time *</label>
        <input type="text" name="start_time" id="update_start_time" required>

        <label>End Time *</label>
        <input type="text" name="end_time" id="update_end_time" required>

        <label>Location *</label>
        <input type="text" name="location" id="update_location" required>

        <div class="modal-actions">
          <button type="button" class="cancel-btn" onclick="closeUpdateScheduleModal()">Cancel</button>
          <button type="submit" name="update_schedule" class="submit-btn">Update Schedule</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>