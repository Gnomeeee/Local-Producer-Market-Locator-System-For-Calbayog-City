<?php
include '../Database/dbconnect.php';

$user_id = $_SESSION['user_id'];

// SELECT FAVORITES FROM DATABASE 

$favStmt = $conn->prepare("SELECT 
    fav.favorite_id, 
    fav.farm_id, 
    fav.date_added,
    fm.farm_name,
    fm.address,
    fm.city,
    fm.phone_number,
    fm.description


FROM favorites AS fav 
JOIN farms AS fm ON fav.farm_id = fm.farm_id
WHERE fav.user_id = ?
");

$favStmt->bind_param('i', $user_id);
$favStmt->execute();
$favResult = $favStmt->get_result();

// --------------------

// REMOVE FAVORITES 

$favFarms = [];

if($user_id){
  $favSql = "SELECT farm_id FROM favorites WHERE user_id = ?";
  $favStmt = $conn->prepare($favSql);
  $favStmt->bind_param('i', $user_id);
  $favStmt->execute();
  $favRes = $favStmt->get_result();
  
  while($row = $favRes->fetch_assoc()){
    $favFarms[] = $row['farm_id'];
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <link rel="stylesheet" href="./Styles/cons-favorite.css">
  <link rel="stylesheet" href="./Styles/find-farm.css">
  <script src="../Assets/Javascript/view-detail.js"></script>
  <script src="../Assets/Javascript/favorite.js"></script>
  <title>Favorites</title>
</head>
<body>
  <div class="fav-container">
    <div class="fav-header">
      <div class="fav-svg">
        <img src="../Assets/svg/red-heart-svgrepo-com.svg" alt="Favorites">
      <h2>Favorite Farms</h2>
      </div>
      <p>Quick access to your favorite producers</p>
    </div>
    <div class="fav-card">
      <?php if($favResult->num_rows > 0): ?>
        <?php while($favs = $favResult->fetch_assoc()): ?>
            <div class="fav-content">
              <div class="name-address-btn">
                <div class="farm-name-address">
                  <h3><?= htmlspecialchars($favs['farm_name']); ?></h3>
                    <div class="farm-address">
                    <img src="../Assets/svg/map-pin-svgrepo-com.svg" alt="">
                    <p><?= htmlspecialchars($favs['address'] . ' ' . $favs['city']);?></p>
                  </div>
                </div>
                <div class="button-heart">
                  <button class="view-details-btn" data-farm-id="<?= $favs['farm_id'] ?>">View Details</button>
                  <div class="img-div">
                    <img class="favBtn <?= in_array($favs['farm_id'], $favFarms) ? 'active' : '' ?>" data-farm-id="<?= $favs['farm_id'] ?>" onclick="toggleFavorite(this)" src="../Assets/svg/heart-svgrepo-com.svg" alt="Favorites">
                  </div>
                </div>
              </div>    
            </div>
          <?php endwhile; ?>
          <?php else: ?>
            <div class="fav-message">
              <img src="../Assets/svg/heart-svgrepo-com.svg" alt="Favorites">
              <h3>You haven't added any favorites yet</h3>
              <p>Browse farms and click the heart icon to save them here</p>
            </div>
    <?php endif; ?>
    </div>
  </div>
  <div id="farmModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" id="modalContent">
        <span class="close-modal" onclick="closeModal()">x</span>
    </div>
</div>
</body>
</html>