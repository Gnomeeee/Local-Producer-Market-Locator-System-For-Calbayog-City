<?php
include '../Database/dbconnect.php';  

// FOR FAVORITES 
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id']: null;

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

// FOR SEARCH
$search = isset($_GET['search_farm']) ? trim($_GET['search_farm']) : '';

$sql = "SELECT farms.*, producers.username
        FROM farms
        JOIN producers ON farms.producer_id = producers.producer_id";

$params = [];
$types = "";

if(!empty($search)){
    $sql .= " WHERE farms.farm_name LIKE ?
              OR farms.address LIKE ?
              OR farms.city LIKE ?
              OR farms.description LIKE ?";
    $searchParam = "%{$search}%";
    $params = [$searchParam, $searchParam, $searchParam, $searchParam];
    $types = 'ssss';
}

$stmt = $conn->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./Styles/find-farm.css">
<script src="../Assets/Javascript/active-button.js" defer></script>
<script src="../Assets/Javascript/view-detail.js"></script>
<script src="../Assets/Javascript/favorite.js"></script>
<title>Find Farms</title>
</head>
<body>

<div class="search-card">
    <div class="header">
        <h3>Search Local Farms & Products</h3>
        <p>Find fresh produce near you in Calbayog City</p>
    </div>
    <div class="input-card">
        <form method="GET" action="">
            <div class="search-box">
                <input type="search" name="search_farm" placeholder="Search by farm name, location, or description..." 
                       value="<?= htmlspecialchars($search) ?>">
                <button type="submit">
                  <img src="../Assets/svg/search-alt-1-svgrepo-com.svg" alt="Search">
                </button>
            </div>
        </form>
    </div>
</div>

<?php if($result->num_rows > 0): ?>
    <div class="farm-container">
        <?php while($farms = $result->fetch_assoc()): ?>
            <div class="farms">
                <div class="farm-name">
                    <img src="../Assets/svg/farm-svgrepo-com.svg" alt="">
                    <h3><?= htmlspecialchars($farms['farm_name']); ?></h3>
                </div>
                <!-- FOR FAVORITES -->
                <div class="favorite">
                    <img class="favBtn <?= in_array($farms['farm_id'], $favFarms) ? 'active' : '' ?>"
                     data-farm-id="<?= $farms['farm_id'] ?>" onclick="toggleFavorite(this)" 
                     src="../Assets/svg/heart-svgrepo-com.svg" 
                     alt="Favorite">
                </div>
                <div class="location">
                    <img src="../Assets/svg/map-pin-svgrepo-com.svg" alt="Map pin">
                    <p><?= htmlspecialchars($farms['address']) . " " . htmlspecialchars($farms['city']); ?></p>
                </div>
                <p><?= htmlspecialchars($farms['description']); ?></p>
                <div class="owner">
                    <img src="../Assets/svg/box-svgrepo-com.svg" alt="Owner">
                    <p>By: <?= htmlspecialchars($farms['username']); ?></p>
                </div>
                <div class="phone-number">
                    <img src="../Assets/svg/telephone-svgrepo-com.svg" alt="Phone Number">
                    <?= htmlspecialchars($farms['phone_number']); ?>
                </div>
                <button class="view-details-btn" data-farm-id="<?= $farms['farm_id'] ?>">View details</button>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="no-farms">
        <div class="message">
            <img src="../Assets/svg/farm-svgrepo-com.svg" alt="Farms">
            <?php if(!empty($search)): ?>
              <h2>No farms found matching "<?= htmlspecialchars($search) ?>"</h2>
              <p>Try another search term!</p>
            <?php else: ?>
              <h2>There's no available farm at the moment.</h2>
              <p>Please come back later!</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div id="farmModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" id="modalContent">
        <span class="close-modal" onclick="closeModal()">x</span>
    </div>
</div>
</body>
</html>
