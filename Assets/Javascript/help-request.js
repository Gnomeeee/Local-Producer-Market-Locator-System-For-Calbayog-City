document.getElementById("openHelpModal").addEventListener("click", () => {
  document.getElementById("helpModal").style.display = "block";
  document.getElementById("helpModalOverlay").style.display = "block";
});

document.getElementById("closeHelpModal").addEventListener("click", closeModal);
document
  .getElementById("cancelHelpModal")
  .addEventListener("click", closeModal);

function closeModal() {
  document.getElementById("helpModal").style.display = "none";
  document.getElementById("helpModalOverlay").style.display = "none";
}
