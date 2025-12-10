document.addEventListener("DOMContentLoaded", () => {
  const form =
    document.querySelector("form#loginForm") || document.querySelector("form");
  if (!form) return;

  const submitBtn = form.querySelector("button[type='submit']");
  const btnSpinner = submitBtn?.querySelector(".btn-spinner");
  const btnText = submitBtn?.querySelector("#btnText");

  form.addEventListener("submit", () => {
    try {
      // Visual feedback on the button
      if (submitBtn) submitBtn.classList.add("loading");
      if (btnSpinner) btnSpinner.style.display = "inline-block";
      if (btnText) {
        if (!btnText.dataset.defaultText)
          btnText.dataset.defaultText = btnText.textContent || "";
        btnText.textContent = "Verifying...";

        setTimeout(() => {
          try {
            if (btnText)
              btnText.textContent = btnText.dataset?.defaultText || "Login";
          } catch {}
        }, 3000);
      }

      const overlay = document.createElement("div");
      overlay.setAttribute("data-login-overlay", "true");
      overlay.style.position = "fixed";
      overlay.style.inset = "0";
      overlay.style.background = "#efffeaff";
      overlay.style.display = "flex";
      overlay.style.flexDirection = "column";
      overlay.style.alignItems = "center";
      overlay.style.justifyContent = "center";
      overlay.style.zIndex = "9999";

      const spinner = document.createElement("div");
      spinner.style.width = "40px";
      spinner.style.height = "40px";
      spinner.style.border = "4px solid #e0e0e0";
      spinner.style.borderTop = "4px solid #0b7a1d";
      spinner.style.borderRadius = "50%";
      spinner.style.animation = "loginSpin 1s linear infinite";

      const text = document.createElement("div");
      text.textContent = "Loading your dashboard...";
      text.style.marginTop = "14px";
      text.style.color = "#055d05";
      text.style.fontWeight = "600";
      text.style.fontSize = "16px";

      overlay.appendChild(spinner);
      overlay.appendChild(text);
      document.body.appendChild(overlay);

      if (!document.querySelector("style[data-login-spin]")) {
        const style = document.createElement("style");
        style.setAttribute("data-login-spin", "true");
        style.textContent =
          "@keyframes loginSpin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}";
        document.head.appendChild(style);
      }
    } catch (_) {}
  });
});
