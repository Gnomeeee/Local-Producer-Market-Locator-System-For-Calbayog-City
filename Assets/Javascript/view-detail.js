document.addEventListener("DOMContentLoaded", () => {
  // handle view details buttons
  document.querySelectorAll(".view-details-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const farmId = btn.dataset.farmId;

      // AJAX call
      fetch(`.../../view-details.php?id=${farmId}`)
        .then((res) => res.text())
        .then((html) => {
          document.getElementById("modalContent").innerHTML =
            `<span class='close-modal' onclick='closeModal()'>×</span>` + html;
          openModal();
        });
    });
  });
});

function openModal() {
  document.getElementById("farmModal").style.display = "flex";
}

function closeModal() {
  document.getElementById("farmModal").style.display = "none";
}
