function toggleFavorite(el) {
  const farmId = el.dataset.farmId;

  fetch(".../../toggle_favorite.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "farm_id=" + farmId,
  })
    .then((res) => res.text())
    .then((response) => {
      if (response === "added") {
        el.classList.add("active");
        location.reload();
      } else if (response === "removed") {
        el.classList.remove("active");
        location.reload();
      } else if (response === "not_logged_in") {
        alert("Please log in first.");
      } else {
        alert("Something went wrong.");
      }
    });
}
