<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../Assets/svg/sprout-svgrepo-com.svg" type="icon/svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../Assets/Styles/signup-producer.css">

  <script src="../Assets/Javascript/toggle-password.js"></script>
  <script src="../Assets/Javascript/loading-effects.js"></script>
  <script src="../Assets/Javascript/message.js" defer></script>
  <script src="../Assets/Javascript/password_matching.js" defer></script>
  <title>LPMLS - Signup - Producer</title>
</head>
<body>
  <div class="container">
    <button class="return-home" onclick="window.location.href='form-handler.php'">
        <div class="left-icon">
          <i class="fa-solid fa-arrow-left"></i>
        </div>
      <p>Back</p>
      </button>
    <div class="signup-form">
      <div class="header">
          <div class="header-svg">
              <img class="producer-svg" src="../Assets/svg/tractor-2-svgrepo-com.svg" alt="User"> 
          </div>
          <div class="title-text">
            <h2>Create Your Account</h2>
            <p>Signing up as: <span>Producer</span></p>
          </div>
        </div>
        <?php
          if(isset($_SESSION['error'])){
            echo "<div class='error' id='reviewMessage'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
          }elseif(isset($_SESSION['success'])){
            echo "<div class='success' id='reviewMessage'>{$_SESSION['success']}</div>";
            unset($_SESSION['success']);
          }
        ?>
        <div class="signup">
          <form action="../includes/functions.php" method="post">
            <label for="username">Username</label>
            <div class="input">
              <img src="../Assets/svg/user-circle-svgrepo-com.svg" alt="">
              <input type="text" name="username" placeholder="Juan Dela Cruz" >
            </div>
            <label for="Email">Email</label>
            <div class="input">
              <img src="../Assets/svg/email-svgrepo-com.svg" alt="">
              <input type="email" name="email" placeholder="your@email.com" >
            </div>
            <label for="phone-number">Phone Number</label>
            <div class="input">
              <img src="../Assets/svg/telephone-svgrepo-com.svg" alt="">
              <input type="text" name="phone_number" placeholder="+63 912 345 678" >
            </div>
            <label for="password">Password</label>
            <div class="input">
              <img src="../Assets/svg/lock-alt-svgrepo-com.svg" alt="">
              <input type="password" name="password" id="password" placeholder="At least 8 characters" >
               <i class="fa-solid fa-eye" id="togglePassword"></i>
            </div>
            <label for="confirm-password">Confirm Password</label>
            <div class="input">
              <img src="../Assets/svg/lock-alt-svgrepo-com.svg" alt="">
              <input type="password" name="confirm_password" id="confirmPassword" placeholder="Re-enter your password" >
              <i class="fa-solid fa-eye" id="toggleConfirm"></i>
            </div>
            <div class="notice">
              <p>Your account will be pending until verified by an admin. Need help? Contact our support team.</p>
            </div>
            <button class="btn" type="submit" name="producer_signup" id="signupBtn">
              <span class="btn-spinner" id="btnSpinner"></span>
              <span id="btnText">Create Account</span>
            </button>
          </form>
          <div class="login-link">
            <label for="login-link">Already have an account? <a href="../login.php">Login</a></label>
          </div>
        </div>
    </div>
  </div>
</body>
</html>