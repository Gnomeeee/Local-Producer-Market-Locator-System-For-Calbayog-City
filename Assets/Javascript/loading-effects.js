// FOR SIGNUP BOTH CONSUMER AND PRODUCER
document.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    const submitBtn = form.querySelector("button[type='submit']");
    const btnSpinner = submitBtn?.querySelector(".btn-spinner");
    const btnText = submitBtn?.querySelector("#btnText");

    form.addEventListener("submit", (e) => {
      if (submitBtn && btnSpinner && btnText) {
        submitBtn.classList.add("loading");
        btnSpinner.style.display = "inline-block";
        btnText.textContent = "Processing...";

        setTimeout(() => {
          submitBtn.classList.remove("loading");
          btnSpinner.style.display = "none";
          btnText.textContent = "Submit";
        }, 3000);
      }
    });
  });

  const successMsg = document.querySelector(".success");
  if (successMsg) {
    setTimeout(() => {
      successMsg.textContent = "Redirecting to login...";
    }, 2000);

    setTimeout(() => {
      successMsg.style.transition = "opacity 0.4s ease";
      successMsg.style.opacity = "0";
    }, 3500);

    setTimeout(() => {
      window.location.href = "../login.php";
    }, 4000);
  }
});
