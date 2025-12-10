<?php
session_start();
include '../Database/dbconnect.php';

if (!isset($_SESSION['producer_id'])) {
    $_SESSION['product_error'] = 'Unauthorized access';
    header("Location: dashboard.php?page=products");
    exit();
}

$producer_id = (int) $_SESSION['producer_id'];

if (isset($_POST['update_product'])) {

    // Get and sanitize inputs
    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;

    // Validate product ID
    if ($product_id <= 0) {
        $_SESSION['product_error'] = 'Invalid product ID';
        header("Location: dashboard.php?page=products");
        exit();
    }

    // Validate product name
    if (empty($product_name) || strlen($product_name) < 2) {
        $_SESSION['product_error'] = 'Product name is too short';
        header("Location: dashboard.php?page=products");
        exit();
    }

    // Validate category
    $allowed_categories = ['Fruits', 'Vegetables', 'Grains', 'Meat', 'Others'];
    if (!in_array($category, $allowed_categories)) {
        $_SESSION['product_error'] = 'Invalid category selected';
        header("Location: dashboard.php?page=products");
        exit();
    }

    // Validate unit
    $allowed_units = ['kg','lbs','pc','bundle','dozen'];
    if (!in_array($unit, $allowed_units)) {
        $_SESSION['product_error'] = 'Invalid unit selected';
        header("Location: dashboard.php?page=products");
        exit();
    }

    // Validate price and stock
    if (!is_numeric($price) || $price <= 0) {
        $_SESSION['product_error'] = 'Invalid price value';
        header("Location: dashboard.php?page=products");
        exit();
    }
    if (!is_numeric($stock) || $stock < 0) {
        $_SESSION['product_error'] = 'Invalid stock value';
        header("Location: dashboard.php?page=products");
        exit();
    }

    // Get farm_id of producer
    $farmStmt = $conn->prepare("SELECT farm_id FROM farms WHERE producer_id = ?");
    $farmStmt->bind_param('i', $producer_id);
    $farmStmt->execute();
    $farmResult = $farmStmt->get_result();

    if ($farmResult->num_rows === 0) {
        $_SESSION['product_error'] = 'No farm found for this producer';
        header("Location: dashboard.php?page=products");
        exit();
    }

    $farm_id = $farmResult->fetch_assoc()['farm_id'];

    // Update product
    $updateStmt = $conn->prepare("
        UPDATE products 
        SET product_name = ?,
            category = ?,
            unit_price = ?,
            stock_quantity = ?,
            unit_of_measure = ?,
            last_updated_date = NOW()
        WHERE product_id = ? AND farm_id = ?
    ");

    $updateStmt->bind_param(
        'ssdisii', 
        $product_name, 
        $category, 
        $price, 
        $stock, 
        $unit, 
        $product_id, 
        $farm_id
    );

    if ($updateStmt->execute()) {
        $_SESSION['product_success'] = 'Product updated successfully';
    } else {
        $_SESSION['product_error'] = 'Error while updating the product';
    }

    header("Location: dashboard.php?page=products");
    exit();
}
?>
