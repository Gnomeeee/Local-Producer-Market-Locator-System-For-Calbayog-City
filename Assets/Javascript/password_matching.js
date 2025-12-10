document.addEventListener("DOMContentLoaded", () => {
  const password = document.querySelector("#password");
  const confirmPassword = document.querySelector("#confirmPassword");
  const form = document.querySelector(".signup form");

  // Create message element
  let message = document.createElement("p");
  message.id = "passwordMessage";
  message.style.fontSize = "14px";
  message.style.marginTop = "5px";
  message.style.marginBottom = "15px";
  message.style.display = "none";

  // Insert message after confirm password input
  confirmPassword.closest(".input").insertAdjacentElement("afterend", message);

  // Real-time password match check
  const checkMatch = () => {
    if (confirmPassword.value === "") {
      message.style.display = "none";
      password.classList.remove("valid", "invalid");
      confirmPassword.classList.remove("valid", "invalid");
      return;
    }

    message.style.display = "block";

    if (password.value === confirmPassword.value) {
      message.textContent = "✅ Passwords match";
      message.style.color = "green";
      password.classList.add("valid");
      password.classList.remove("invalid");
      confirmPassword.classList.add("valid");
      confirmPassword.classList.remove("invalid");
    } else {
      message.textContent = "❌ Passwords do not match";
      message.style.color = "red";
      password.classList.add("invalid");
      password.classList.remove("valid");
      confirmPassword.classList.add("invalid");
      confirmPassword.classList.remove("valid");
    }
  };

  password.addEventListener("input", checkMatch);
  confirmPassword.addEventListener("input", checkMatch);

  form.addEventListener("submit", (e) => {
    if (password.value !== confirmPassword.value) {
      e.preventDefault();
      message.textContent = "❌ Passwords do not match";
      message.style.color = "red";
    }
  });
});
