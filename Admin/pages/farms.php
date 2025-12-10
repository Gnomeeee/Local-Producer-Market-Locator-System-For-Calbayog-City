<?php
include '../Database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['error'] = 'Unauthorized access.';
  header("Location: ../login.php");
  exit();
}

$admin_id = (int) $_SESSION['admin_id'];

$query = $conn->query("
    SELECT f.*, 
           p.username,
           (SELECT COUNT(*) FROM products WHERE farm_id = f.farm_id) AS product_count
    FROM farms f
    LEFT JOIN producers p ON p.producer_id = f.producer_id
    WHERE f.approval_status = 'Approved'
");

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./Styles/admin-farm.css">
  <title>Document</title>
</head>

<body>
  <div class="verified-farms-container">
    <div class="vf-header">
      <h2>Verified Farms</h2>
      <p>All approved farms on the platform</p>
    </div>

    <div class="vf-table-wrapper">
      <table class="vf-table">
        <thead>
          <tr>
            <th>Farm Name</th>
            <th>Producer</th>
            <th>Location</th>
            <th>Products</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = $query->fetch_assoc()) : ?>
            <tr>
              <td><?= htmlspecialchars($row['farm_name']) ?></td>

              <td><?= htmlspecialchars($row['username']) ?></td>

              <td>
                <span class="location-icon">📍</span>
                <?= htmlspecialchars($row['address']) ?>
              </td>

              <td><?= (int)$row['product_count'] ?></td>

              <td>
                <span class="status-pill active">
                  <img src="../Assets/svg/check-circle-svgrepo-com.svg">
                  Active
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>