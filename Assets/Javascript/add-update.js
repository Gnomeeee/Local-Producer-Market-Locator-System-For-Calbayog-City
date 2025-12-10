/* ADD PRODUCT MODAL */
function openAddProductModal() {
  document.getElementById("addProductModal").style.display = "flex";
}

function closeAddProductModal() {
  document.getElementById("addProductModal").style.display = "none";
}

/* UPDATE PRODUCT MODAL */
function openUpdateProductModal(id, name, category, price, stock, unit) {
  document.getElementById("updateProductModal").style.display = "flex";

  document.getElementById("update_product_id").value = id;
  document.getElementById("update_product_name").value = name;
  document.getElementById("update_price").value = price;
  document.getElementById("update_stock").value = stock;

  // Set Category dropdown
  const categorySelect = document.getElementById("update_category");
  for (let i = 0; i < categorySelect.options.length; i++) {
    if (
      categorySelect.options[i].value.toLowerCase() === category.toLowerCase()
    ) {
      categorySelect.selectedIndex = i;
      break;
    }
  }

  // Set Unit dropdown
  const unitSelect = document.getElementById("update_unit");
  for (let i = 0; i < unitSelect.options.length; i++) {
    if (unitSelect.options[i].value.toLowerCase() === unit.toLowerCase()) {
      unitSelect.selectedIndex = i;
      break;
    }
  }
}

function closeUpdateProductModal() {
  document.getElementById("updateProductModal").style.display = "none";
}
