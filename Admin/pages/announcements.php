<?php
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = "Unauthorized access.";
  header("Location: ../login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

// FETCH ALL ANNOUNCEMENTS
$annQuery = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Platform Announcements</title>
  <link rel="stylesheet" href="./Styles/admin-announcements.css">
</head>

<body>

  <div class="container-box">
    <div class="announcement-header">
      <div class="header-texts">
        <h2 class="header-title">Platform Announcements</h2>
        <p class="subtitle">Create and manage announcements for users</p>
      </div>
      <button class="new-btn" onclick="openModal('create')">
        <img src="../Assets/svg/add-svgrepo-com.svg" alt="">New Announcement</button>
    </div>

    <?php if ($annQuery->num_rows === 0): ?>
      <p class="message" style="text-align:center; color:#888; margin-top:20px; font-size:16px;">No announcements have been created yet.</p>
    <?php endif; ?>

    <div class="announcement-body">
      <?php while ($row = $annQuery->fetch_assoc()): ?>
        <div class="announcement-box" data-id="<?= $row['id'] ?>">
          <div class="announcement-text">
            <h3><?= htmlspecialchars($row['title']) ?></h3>

            <?php if ($row['status'] === "Active"): ?>
              <span class="badge-active">Active</span>
            <?php else: ?>
              <span class="badge-inactive">Inactive</span>
            <?php endif; ?>

            <?php if ($row['audience'] === "All Users"): ?>
              <span class="badge-allusers">All Users</span>
            <?php elseif ($row['audience'] === "Producers"): ?>
              <span class="badge-producers">Producers Only</span>
            <?php elseif ($row['audience'] === "Consumers"): ?>
              <span class="badge-consumers">Consumers Only</span>
            <?php endif; ?>
          </div>

          <p style="margin-top:10px;"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
          <p>By System Administrator • <?= $row['created_at'] ?></p>

          <div class="button" style="margin-top:15px;">
            <?php if ($row['status'] === "Active"): ?>
              <button class="action-btn btn-deactivate" onclick="updateStatus(<?= $row['id'] ?>, 'Inactive')">Deactivate</button>
            <?php else: ?>
              <button class="action-btn btn-activate" onclick="updateStatus(<?= $row['id'] ?>, 'Active')">Activate</button>
            <?php endif; ?>

            <button class="action-btn btn-edit" onclick="openModal('edit', <?= $row['id'] ?>, '<?= htmlspecialchars(string: addslashes($row['title'])) ?>', '<?= htmlspecialchars(addslashes($row['message'])) ?>', '<?= $row['audience'] ?>')">
              <img src="../Assets/svg/pen-new-square-svgrepo-com.svg" alt="">Edit</button>
            <button class="action-btn btn-delete" onclick="deleteAnnouncement(<?= $row['id'] ?>)">
              <img src="../Assets/svg/trash-alt-svgrepo-com.svg" alt="">Delete</button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- ANNOUNCEMENT MODAL -->
  <div class="modal-bg" id="modalBox">
    <div class="modal-box">
      <h3 id="modalTitle">Create Announcement</h3>
      <p id="modalSubtitle">Create a new announcement for users</p>

      <form id="announcementForm" method="POST">
        <input type="hidden" name="id" id="annId">
        <label>Title</label>
        <input type="text" name="title" id="annTitle" class="modal-input" required>

        <label style="margin-top:10px;">Message</label>
        <textarea name="message" id="annMessage" class="modal-input" rows="4" required></textarea>

        <label style="margin-top:10px;">Audience</label>
        <select name="audience" id="annAudience" class="modal-input">
          <option value="All Users">All Users</option>
          <option value="Producers">Producers Only</option>
          <option value="Consumers">Consumers Only</option>
        </select>

        <div style="margin-top:15px; text-align:right;">
          <button type="button" class="modal-btn-cancel" onclick="closeModal()">Cancel</button>
          <button type="submit" class="modal-btn-save" id="modalSaveBtn">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Open modal (create/edit)
    function openModal(type, id = '', title = '', message = '', audience = '') {
      document.getElementById('modalBox').style.display = 'flex';
      const form = document.getElementById('announcementForm');

      if (type === 'create') {
        document.getElementById('modalTitle').innerText = 'Create Announcement';
        document.getElementById('modalSubtitle').innerText = 'Create a new announcement for users';
        form.action = 'create_announcement.php';
        document.getElementById('annId').value = '';
        document.getElementById('annTitle').value = '';
        document.getElementById('annMessage').value = '';
        document.getElementById('annAudience').value = 'All Users';
      } else if (type === 'edit') {
        document.getElementById('modalTitle').innerText = 'Edit Announcement';
        document.getElementById('modalSubtitle').innerText = 'Update your announcement';
        form.action = 'edit_announcement.php'; // <- FIXED
        document.getElementById('annId').value = id;
        document.getElementById('annTitle').value = title;
        document.getElementById('annMessage').value = message;
        document.getElementById('annAudience').value = audience;
      }

    }

    function closeModal() {
      document.getElementById('modalBox').style.display = 'none';
    }

    // Redirect for Activate/Deactivate
    function updateStatus(id, status) {
      const action = status === 'Active' ? 'activate' : 'deactivate';
      if (confirm(`Are you sure you want to ${action} this announcement?`)) {
        window.location.href = `update_status.php?id=${id}&status=${status}`;
      }
    }

    // Redirect for Delete
    function deleteAnnouncement(id) {
      if (confirm('Are you sure you want to delete this announcement?')) {
        window.location.href = `delete_announcement.php?id=${id}`;
      }
    }
  </script>



</body>

</html>