/* ============================
      ADD SCHEDULE MODAL
============================ */
function openAddScheduleModal() {
  document.getElementById("addScheduleModal").style.display = "flex";
}

function closeAddScheduleModal() {
  document.getElementById("addScheduleModal").style.display = "none";
}

/* ============================
      UPDATE SCHEDULE MODAL
============================ */
function openUpdateScheduleModal(
  schedule_id,
  day,
  start_time,
  end_time,
  location
) {
  // Set modal fields
  document.getElementById("update_schedule_id").value = schedule_id;
  document.getElementById("update_day").value = day;
  document.getElementById("update_start_time").value = start_time;
  document.getElementById("update_end_time").value = end_time;
  document.getElementById("update_location").value = location;

  // Show modal
  document.getElementById("updateScheduleModal").style.display = "block";
}

function closeUpdateScheduleModal() {
  document.getElementById("updateScheduleModal").style.display = "none";
}

/* ============================
      CLICK OUTSIDE TO CLOSE
============================ */
window.onclick = function (e) {
  let addModal = document.getElementById("addScheduleModal");
  let updateModal = document.getElementById("updateScheduleModal");

  if (e.target === addModal) {
    addModal.style.display = "none";
  }
  if (e.target === updateModal) {
    updateModal.style.display = "none";
  }
};
