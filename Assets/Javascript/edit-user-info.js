// OPEN MODAL
function openModal(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.style.display = "flex";
  setTimeout(() => modal.classList.add("modal-show"), 10); // optional fade-in
}

// CLOSE MODAL
function closeModal(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.remove("modal-show"); // start fade-out
  setTimeout(() => (modal.style.display = "none"), 200); // match CSS transition
}

// CLOSE WHEN CLICKING OUTSIDE THE MODAL CONTENT
window.addEventListener("click", function (event) {
  const modals = document.querySelectorAll(".modal");
  modals.forEach((modal) => {
    // Only close if the modal is clicked outside its content
    if (event.target === modal) {
      closeModal(modal.id);
    }
  });
});
