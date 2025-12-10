document.addEventListener("DOMContentLoaded", () => {
  const newPassword = document.querySelector("input[name='new_password']");
  const confirmPassword = document.querySelector(
    "input[name='confirm_password']"
  );
  const form = document.querySelector("#changePassModal form");

  // Create dynamic password match message
  let message = document.createElement("p");
  message.id = "msgPasswordMatch";
  message.style.display = "none";
  message.style.fontSize = "14px";
  message.style.marginTop = "5px";

  // Insert after confirm password input-box
  const confirmBox = confirmPassword.closest(".input-box");
  confirmBox.insertAdjacentElement("afterend", message);

  // Live password match check
  confirmPassword.addEventListener("input", () => {
    if (confirmPassword.value === "") {
      message.style.display = "none";
      return;
    }

    message.style.display = "block";
    if (newPassword.value === confirmPassword.value) {
      message.style.color = "green";
      message.textContent = "✓ Passwords match";
    } else {
      message.style.color = "red";
      message.textContent = "✗ Passwords do not match";
    }
  });

  // Prevent form submission if passwords mismatch
  form.addEventListener("submit", (e) => {
    if (newPassword.value !== confirmPassword.value) {
      e.preventDefault();
      message.style.display = "block";
      message.style.color = "red";
      message.textContent = "✗ Passwords do not match";
    }
  });
});

// OPEN MODAL
function openModal(id) {
  document.getElementById(id).style.display = "flex";
}

// CLOSE MODAL
function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// PASSWORD VISIBILITY TOGGLE
function togglePassword(icon) {
  let input = icon.previousElementSibling;

  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}
