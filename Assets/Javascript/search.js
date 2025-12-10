document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchFarmInput");
  const farmContainer =
    document.querySelector(".farm-container") || document.createElement("div");
  farmContainer.classList.add("farm-container");

  if (!document.querySelector(".farm-container")) {
    document.body.appendChild(farmContainer);
  }

  searchInput.addEventListener("input", () => {
    const query = searchInput.value.trim();

    fetch(`../../search-farms.php?search_farm=${encodeURIComponent(query)}`)
      .then((response) => response.text())
      .then((html) => {
        farmContainer.innerHTML = html;
      })
      .catch((err) => console.error(err));
  });
});
