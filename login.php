<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="./Assets/svg/sprout-svgrepo-com.svg" type="svg/icon type">
  <link rel="stylesheet" href="./Assets/Styles/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="./Assets/Javascript/toggle-password.js"></script>
  <script src="./Assets/Javascript/dashboard-loading.js"></script>
  <script src="./Assets/Javascript/message.js" defer></script>
  <title>LPMLS - Login</title>
</head>
<body>
  <div class="login-container">
      <button class="return-home" onclick="window.location.href='index.php'">
        <div class="left-icon">
          <i class="fa-solid fa-arrow-left"></i>
        </div>
      <p>Back to Home</p>
      </button>
    <div class="login">
      <div class="login-form-header">
        <div class="login-svg">
          <img class="header-svg" src="./Assets/svg/sprout-svgrepo-com.svg" alt="Sprout">
          </div>
          <div class="title-text">
            <h2>Welcome Back</h2>
            <p>Login to your Local Producers Market account</p>
        </div> 
      </div>
      <?php 
        if(isset($_SESSION['login_error'])){
          echo "<div class='error' id='reviewMessage'>{$_SESSION['login_error']}</div>";
          unset($_SESSION['login_error']);
        }elseif(isset($_SESSION['log_error'])){
          echo "<div class='error' id='reviewMessage'>{$_SESSION['log_error']}</div>";
          unset($_SESSION['log_error']);
        }
      ?>
        <div class="login-form">
          <form action="./Includes/functions.php" method="post">
            <label class="label" for="email">Email</label>
            <div class="input"><div class="email">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <input type="text" name="login_input" placeholder="Enter username or email" required>
          </div>
            <label class="label" for="password">Password</label>
            <div class="input"><div class="lock"><i class="fa-solid fa-unlock-keyhole"></i>
          </div>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
            <i id="togglePassword" class="fa-solid fa-eye"></i>
          </div>
            <div class="forgot-password"><a href="#">Forgot password?</a></div>
            <button type="submit" name="login">
             <span class="btn-spinner"></span>
             <span id="btnText">Login</span>
            </button>
          </form>
        </div>
        <div class="signup-link">
          <label class="link-label" for="signup-link">
            Don't have an account?
            <a href="./form/form-handler.php">Sign up</a>
          </label>
        </div>
    </div>
    <div class="getting-started">
      <div class="card">
        <div class="title">
          <h2>Getting Started</h2>
        </div>
        <div class="list">
          <ul>
            <li>Don't have an account? Click "Sign up" above to create one</li>
            <li>Choose your role: Consumer or Producer during signup</li>
            <li>Producer accounts require admin verification</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</body>
</html>