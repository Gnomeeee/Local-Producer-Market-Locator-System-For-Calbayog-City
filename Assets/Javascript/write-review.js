document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("writeReviewForm");
  const ratingSelect = document.getElementById("ratingSelect");
  const comment = document.getElementById("comment");
  const ratingError = document.getElementById("ratingError");
  const commentError = document.getElementById("commentError");
  const formStatus = document.getElementById("formStatus");
  const submitBtn = document.getElementById("submitWriteReview");
  const spinner = submitBtn?.querySelector(".btn-spinner");
  const btnText = submitBtn?.querySelector("#btnText");
  const cancel = document.getElementById("cancelWriteReview");
  const closeX = document.getElementById("closeWriteReview");

  const closeModal = () => {
    window.location.hash = "";
  };
  const resetForm = () => {
    form?.reset();
    if (ratingError) ratingError.textContent = "";
    if (commentError) commentError.textContent = "";
    if (formStatus) formStatus.textContent = "";
    submitBtn?.classList.remove("loading");
    if (spinner) spinner.style.display = "none";
    if (btnText) btnText.textContent = "Submit";
  };

  [cancel, closeX].forEach(
    (btn) =>
      btn &&
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        resetForm();
        closeModal();
      })
  );

  if (form) {
    form.addEventListener("submit", (e) => {
      let valid = true;

      if (!ratingSelect.value) {
        if (ratingError) ratingError.textContent = "Please select a rating.";
        valid = false;
      } else if (ratingError) ratingError.textContent = "";

      if (!comment.value.trim()) {
        if (commentError) commentError.textContent = "Comment is required.";
        valid = false;
      } else if (commentError) commentError.textContent = "";

      if (!valid) {
        e.preventDefault();
        return;
      }

      submitBtn?.classList.add("loading");
      if (spinner) spinner.style.display = "inline-block";
      if (btnText) btnText.textContent = "Processing...";
      if (formStatus) formStatus.textContent = "Submitting your review...";
      // Allow normal form submission to PHP
    });
  }
});
