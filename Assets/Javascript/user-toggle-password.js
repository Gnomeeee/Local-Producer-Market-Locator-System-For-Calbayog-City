document.addEventListener("DOMContentLoaded", () => {
  const currentPassword = document.querySelector("#current_password");
  const toggleCurrent = document.querySelector("#toggleCurrent");

  const newPassword = document.querySelector("#password");
  const togglePassword = document.querySelector("#togglePassword");

  const confirmPassword = document.querySelector("#confirmPassword");
  const toggleConfirm = document.querySelector("#toggleConfirm");

  const form = document.querySelector("form");

  // Create message element dynamically
  let message = document.createElement("p");
  message.id = "message";
  message.style.display = "flex";
  message.style.fontSize = "15px";
  message.style.marginTop = "5px";
  message.style.marginBottom = "15px";
  message.style.width = "100%";
  form.appendChild(message);

  // Insert message after confirm password input-box
  const confirmBox = confirmPassword.closest(".input-box");
  confirmBox.insertAdjacentElement("afterend", message);

  // Toggle: Current password
  toggleCurrent.addEventListener("click", () => {
    currentPassword.type =
      currentPassword.type === "password" ? "text" : "password";
    toggleCurrent.classList.toggle("fa-eye-slash");
  });

  // Toggle: New password
  togglePassword.addEventListener("click", () => {
    newPassword.type = newPassword.type === "password" ? "text" : "password";
    togglePassword.classList.toggle("fa-eye-slash");
  });

  // Toggle: Confirm password
  toggleConfirm.addEventListener("click", () => {
    confirmPassword.type =
      confirmPassword.type === "password" ? "text" : "password";
    toggleConfirm.classList.toggle("fa-eye-slash");
  });

  // Real-time password match check
  confirmPassword.addEventListener("input", () => {
    if (confirmPassword.value === "") {
      message.style.display = "none";
      return;
    }

    message.style.display = "block";
    if (newPassword.value === confirmPassword.value) {
      message.style.color = "green";
      message.textContent = "✅ Passwords match";
    } else {
      message.style.color = "red";
      message.textContent = "❌ Passwords do not match";
    }
  });

  // Block form submission if mismatch
  form.addEventListener("submit", (e) => {
    if (newPassword.value !== confirmPassword.value) {
      e.preventDefault();
      message.style.display = "block";
      message.style.color = "red";
      message.textContent = "❌ Passwords do not match";
    }
  });
});
