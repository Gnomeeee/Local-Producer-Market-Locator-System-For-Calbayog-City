<?php
include '../Database/dbconnect.php';

$producer_id = $_SESSION['producer_id'];

$prodStmt = $conn->prepare("SELECT * FROM products p 
JOIN farms f ON p.farm_id = f.farm_id 
WHERE producer_id = ?");
$prodStmt->bind_param('i', $producer_id);
$prodStmt->execute();
$prodResult = $prodStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/prods-products.css">
  <script src="../Assets/Javascript/add-update.js"></script>
  <title>Products</title>
</head>

<body>
  <div class="product-container">
    <div class="product-header">
      <div class="header-content">
        <div class="header-texts">
          <h3>Product Listings</h3>
          <p>Manage products available at your farm</p>
        </div>
        <div class="header-btn">
          <button class="img-btn" onclick="openAddProductModal()">
            <img src="../Assets/svg/add-svgrepo-com.svg" alt="Add product">
            Add product
          </button>
        </div>
      </div>
    </div>

    <div class="product-body">
      <?php if ($prodResult->num_rows > 0): ?>
        <table class="product-table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($product = $prodResult->fetch_assoc()):
              // Normalize category for JS
              $category_js = ucfirst(strtolower(trim($product['category'])));
            ?>
              <tr>
                <td class="farm-name">
                  <span>
                    <?= htmlspecialchars($product['product_name']); ?>
                  </span>
                </td>
                <td>
                  <span class="category-badge"><?= htmlspecialchars($product['category']); ?></span>
                </td>
                <td class="price">
                  <span>₱
                    <?= htmlspecialchars($product['unit_price']) ?>/
                    <?= htmlspecialchars($product['unit_of_measure']) ?>
                  </span>
                </td>
                <td>
                  <?= htmlspecialchars($product['stock_quantity']) . ' ' . htmlspecialchars($product['unit_of_measure']); ?>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="edit-btn" onclick="openUpdateProductModal(
                  <?= $product['product_id'] ?>,
                  '<?= htmlspecialchars($product['product_name'], ENT_QUOTES) ?>',
                  '<?= $category_js ?>',
                  <?= $product['unit_price'] ?>,
                  <?= $product['stock_quantity'] ?>,
                  '<?= htmlspecialchars($product['unit_of_measure'], ENT_QUOTES) ?>'
              )">
                      <img src="../Assets/svg/pen-new-square-svgrepo-com.svg" alt="Edit">
                    </button>
                    <form action="delete_product.php" method="POST" style="display:inline;">
                      <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                      <button type="submit" name="delete_product" class="dlt-btn">
                        <img src="../Assets/svg/trash-alt-svgrepo-com.svg" alt="Delete">
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="message">
          <img src="../Assets/svg/box-svgrepo-com.svg">
          <h3>No products added yet</h3>
          <p>Add your first product</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- ADD PRODUCT MODAL -->
  <div id="addProductModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeAddProductModal()">&times;</span>
      <h2>Add New Product</h2>
      <p class="subtitle">Add a product to your farm listings</p>
      <form action="add_product.php" method="POST" id="addProductForm">
        <input type="hidden" name="producer_id" value="<?= $producer_id ?>">
        <!-- PRODUCT NAME -->
        <label>Product Name *</label>
        <input type="text" name="product_name" placeholder="e.g., Organic Tomatoes" required>
        <!-- CATEGORY -->
        <label>Category *</label>
        <select name="category" required>
          <option value="" disabled selected>Select category</option>
          <option value="Fruits">Fruits</option>
          <option value="Vegetables">Vegetables</option>
          <option value="Grains">Grains</option>
          <option value="Meat">Meat</option>
          <option value="Others">Others</option>
        </select>
        <!-- PRICE + UNIT -->
        <div class="row">
          <div class="col">
            <label>Price (₱) *</label>
            <input type="number" name="price" min="1" required>
          </div>
          <div class="col">
            <label>Unit *</label>
            <select name="unit" required>
              <option value="kg">kg</option>
              <option value="lbs">lbs</option>
              <option value="pc">pc</option>
              <option value="bundle">bundle</option>
              <option value="dozen">dozen</option>
            </select>
          </div>
        </div>
        <!-- STOCK -->
        <label>Available Stock *</label>
        <input type="number" name="stock" min="1" required>
        <div class="modal-actions">
          <button type="button" class="cancel-btn" onclick="closeAddProductModal()">Cancel</button>
          <button type="submit" name="add_product" class="submit-btn">Add Product</button>
        </div>
      </form>
    </div>
  </div>

  <!-- UPDATE PRODUCT MODAL -->
  <div id="updateProductModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeUpdateProductModal()">&times;</span>

      <h2>Update Product</h2>
      <p class="subtitle">Modify your product details</p>

      <form action="update_product.php" method="POST" id="updateProductForm">
        <input type="hidden" name="product_id" id="update_product_id">

        <label>Product Name *</label>
        <input type="text" name="product_name" id="update_product_name" required>

        <label>Category *</label>
        <select name="category" id="update_category" required>
          <option value="Fruits">Fruits</option>
          <option value="Vegetables">Vegetables</option>
          <option value="Grains">Grains</option>
          <option value="Meat">Meat</option>
          <option value="Others">Others</option>
        </select>

        <div class="row">
          <div class="col">
            <label>Price (₱) *</label>
            <input type="number" name="price" id="update_price" min="1" required>
          </div>
          <div class="col">
            <label>Unit *</label>
            <select name="unit" id="update_unit" required>
              <option value="kg">kg</option>
              <option value="lbs">lbs</option>
              <option value="pc">pc</option>
              <option value="bundle">bundle</option>
              <option value="dozen">dozen</option>
            </select>
          </div>
        </div>

        <label>Available Stock *</label>
        <input type="number" name="stock" id="update_stock" min="1" required>

        <div class="modal-actions">
          <button type="button" class="cancel-btn" onclick="closeUpdateProductModal()">Cancel</button>
          <button type="submit" name="update_product" class="submit-btn">Update Product</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>