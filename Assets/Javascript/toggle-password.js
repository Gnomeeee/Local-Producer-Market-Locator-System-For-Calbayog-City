document.addEventListener("DOMContentLoaded", () => {
  const password = document.querySelector("#password");
  const confirmPassword = document.querySelector("#confirmPassword");
  const togglePassword = document.querySelector("#togglePassword");
  const toggleConfirm = document.querySelector("#toggleConfirm");
  const message = document.querySelector("#message");
  const form = document.querySelector("#signupForm");

  // Toggle show/hide for password
  togglePassword.addEventListener("click", function () {
    const type =
      password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    this.classList.toggle("fa-eye-slash");
  });

  // Toggle show/hide for confirm password
  toggleConfirm.addEventListener("click", function () {
    const type =
      confirmPassword.getAttribute("type") === "password" ? "text" : "password";
    confirmPassword.setAttribute("type", type);
    this.classList.toggle("fa-eye-slash");
  });

  // Real-time password match check
  confirmPassword.addEventListener("input", () => {
    if (confirmPassword.value === "") {
      message.style.display = "none";
      return;
    }

    message.style.display = "block";
    if (password.value === confirmPassword.value) {
      message.style.color = "green";
      message.textContent = "✅ Passwords match";
    } else {
      message.style.color = "red";
      message.textContent = "❌ Passwords do not match";
    }
  });

  // Prevent form submission if passwords don’t match
  form.addEventListener("submit", (e) => {
    if (password.value !== confirmPassword.value) {
      e.preventDefault();
      message.style.display = "block";
      message.style.color = "red";
      message.textContent = "❌ Passwords do not match";
    }
  });
});
