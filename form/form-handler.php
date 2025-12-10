<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../Assets/svg/sprout-svgrepo-com.svg" type="svg/icon type">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../Assets/Styles/Form.css">
  <title>LMPLS - Form</title>
</head>
<body>
  <div class="container">
    <div class="button-icon">
      <button class="btn" onclick="window.location.href='../index.php'">
        <div class="icon">
          <i class="fa-solid fa-arrow-left"></i>
        </div>
        <p>Back to Home</p>
      </button>
    </div>
    <div class="card">
      <div class="card-header">
      <div class="svg-text">
        <div class="header-svg">
          <img class="sprout-svg" src="../Assets/svg/sprout-svgrepo-com.svg" alt="Logo-sprout">
        </div>
        <div class="header-title-text">
          <h2>Join Our Community</h2>
          <p>Choose how you want to participate</p>
        </div>
      </div>
    </div>
    <div class="middle">
      <div class="signup-form">
        <div class="form">
          <button class="button" onclick="window.location.href='signup-consumer.php'">
            <div class="middle-svg">
              <img class="user-svg" src="../Assets/svg/user-svgrepo-com.svg" alt="Consumers">
            </div>
            <div class="image-title-text">
              <h3>Consumer</h3>
              <p>Find and connect with local farmers to buy fresh produce</p>
            </div>
          </button>
          </div>
          <div class="form">
          <div class="button" onclick="window.location.href='signup-producer.php'">
            <button class="image-title-text">
              <div class="middle-tractor-svg">
              <img class="tractor-svg" src="../Assets/svg/tractor-2-svgrepo-com.svg" alt="Consumers">
            </div>
              <h3>Producer/Farmer</h3>
              <p>List your farm and products to reach more customers</p>
          </button>
        </div>
      </div>
    </div>
    <div class="verification">
        <div class="verification-last-form">
          <div class="verification-svg">
            <img class="protection-shield" src="../Assets/svg/shield-check-svgrepo-com.svg" alt="Sheild"></div>
            <p>Producer accounts require admin verification to ensure quality and authenticity</p>     
        </div>
      </div>
  </div>
  <div class="last">
    <div class="link">
      <label for="link">Already have an account?
        <a href="../login.php">Login</a>
      </label>
    </div>
  </div>
</div>
</body>
</html>