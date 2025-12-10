document.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);
  const currentPage = params.get("page"); // ?page= value
  const buttons = document.querySelectorAll(".buttons button");

  buttons.forEach((btn) => {
    const page = btn.dataset.page;

    // ✅ If no ?page=, make find_farms active
    if (!currentPage && page === "farm_profile") {
      btn.classList.add("active");
    }
    // ✅ If matches ?page=, make that active
    else if (currentPage === page) {
      btn.classList.add("active");
    } else {
      btn.classList.remove("active");
    }
  });
});

function navigatePage(button) {
  const page = button.dataset.page;
  window.location.href = "?page=" + page;
}
