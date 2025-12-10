<?php
session_start();


// FOR CONSUMER SIGNUP INI 

function consumer_signup()
{
  if (isset($_POST['consumer_signup'])) {
    include '../Database/dbconnect.php';
    $con_username = trim($_POST['username']);
    $con_email = trim($_POST['email']);
    $con_phone_number = trim($_POST['phone_number']);
    $con_password = trim($_POST['password']);
    $con_confirm_password = trim($_POST['confirm_password']);

    // FOR ERROR HANDLING AND VALIDATION

    if (empty($con_username) || empty($con_email) || empty($con_phone_number) || empty($con_password) || empty($con_confirm_password)) {
      $_SESSION['error'] = 'Please fill in all fields.';
      header("location: ../form/signup-consumer.php");
      exit();
    }

    if ($con_confirm_password !== $con_password) {
      $_SESSION['error'] = 'Passwords do not match.';
      header("location: ../form/signup-consumer.php");
      exit();
    }

    if (!preg_match("/^['a-zA-Z0-9']+$/", $con_username) || strlen($con_username) < 6) {
      $_SESSION['error'] = 'Username must at-least 6 characters long. no spaces.';
      header("location: ../form/signup-consumer.php");
      exit();
    }

    if (!preg_match("/^\+?\d{11,}$/", $con_phone_number)) {
      $_SESSION['error'] = 'Invalid phone number. Only digits allowed and must be at-least 11 digits.';
      header("location: ../form/signup-consumer.php");
      exit();
    }

    if (!filter_var($con_email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = 'Invalid email address.';
      header("location: ../form/signup-consumer.php");
      exit();
    }

    if (strlen($con_password) < 8) {
      $_SESSION['error'] = 'Password must at-least 8 characters long.';
      header("location: ../form/signup-consumer.php");
      exit();
    }


    // CHECK USER AND PRODUCER THRU EMAIL AND USERNAME IF ALREADY EXIST

    $check = $conn->prepare("SELECT username, email FROM users WHERE BINARY username = ? OR BINARY email = ?
    UNION
    SELECT username, email FROM producers WHERE BINARY username = ? OR BINARY email = ?");

    $check->bind_param('ssss', $con_username, $con_email, $con_username, $con_email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      if ($row['username'] === $con_username && $row['email'] === $con_email) {
        $_SESSION['error'] = 'A user with this email and username has already been registered.';
      } elseif ($row['username'] === $con_username) {
        $_SESSION['error'] = 'A user with this username has already been registered.';
      } elseif ($row['email'] === $con_email) {
        $_SESSION['error'] = 'A user with this email has already been registered.';
      }

      header("location: ../form/signup-consumer.php");
      exit();
    }

    //ELSE REGISTERED IF NOT FOUND IN THE DATABASE

    $hash_password = password_hash($con_password, PASSWORD_DEFAULT);
    $role_id = 3;

    $stmt = $conn->prepare("INSERT INTO users(username, email, phone_number, password, role_id) VALUES(?,?,?,?,?);");
    $stmt->bind_param('ssssi', $con_username, $con_email, $con_phone_number, $hash_password, $role_id);

    if ($stmt->execute()) {
      $_SESSION['success'] = 'Registration successful. Please wait...';
      header("location: ../form/signup-consumer.php");
      exit();
    } else {
      $_SESSION['error'] = 'Error during registration';
      header("location: ../form/signup-consumer.php");
      exit();
    }
  }
}
consumer_signup();


// FOR PRODUCER SIGNUP 

function producer_signup()
{
  if (isset($_POST['producer_signup'])) {
    include '../Database/dbconnect.php';
    $pro_username = trim($_POST['username']);
    $pro_email = trim($_POST['email']);
    $pro_phone_number = trim($_POST['phone_number']);
    $pro_password = trim($_POST['password']);
    $pro_confirm_password = trim($_POST['confirm_password']);

    // FOR ERROR HANDLING AN VALIDATION

    if (empty($pro_username) || empty($pro_email) || empty($pro_phone_number) || empty($pro_password) || empty($pro_confirm_password)) {
      $_SESSION['error'] = 'Please fill in all fields.';
      header("location: ../form/signup-producer.php");
      exit();
    }

    if (strlen($pro_username) < 6 || !preg_match('/^[a-zA-Z0-9]+$/', $pro_username)) {
      $_SESSION['error'] = 'Username must be at least 6 characters long. no spaces.';
      header("location: ../form/signup-producer.php");
      exit();
    }

    if (!preg_match('/^\+?\d{10,11}$/', $pro_phone_number)) {
      $_SESSION['error'] = 'Invalid phone number. Only digits allowed and must be at-least 11 digits.';
      header("location: ../form/signup-producer.php");
      exit();
    }

    if (!filter_var($pro_email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = 'Invalid email address.';
      header('location: ../form/signup-producer.php');
      exit();
    }

    if ($pro_confirm_password !== $pro_password) {
      $_SESSION['error'] = 'Passwords do not match.';
      header("location: ../form/signup-producer.php");
      exit();
    }

    if (strlen($pro_password) < 8) {
      $_SESSION['error'] = 'Password must atleast 8 characters long.';
      header("location: ../form/signup-producer.php");
      exit();
    }

    // FOR CHECKING USERNAME AND EMAIL IF ALREADY EXISTS

    $check = $conn->prepare("SELECT username, email FROM producers WHERE BINARY username = ? OR BINARY email = ? 
    UNION
    SELECT username, email FROM users WHERE BINARY username = ? OR BINARY email = ?");

    $check->bind_param('ssss', $pro_username, $pro_email, $pro_username, $pro_email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      if ($row['username'] === $pro_username && $row['email'] === $pro_email) {
        $_SESSION['error'] = 'A user with this username and email has already been registered.';
      } elseif ($row['username'] === $pro_username) {
        $_SESSION['error'] = 'A user with this username has already been registered.';
      } elseif ($row['email'] === $pro_email) {
        $_SESSION['error'] = 'A user with this email has already been registered.';
      }

      header("location: ../form/signup-producer.php");
      exit();
    }

    // ELSE REGISTERED IF NOT EXISTS

    $hash_password = password_hash($pro_password, PASSWORD_DEFAULT);
    $role_id = 2;
    $is_Verified = 0;

    $stmt = $conn->prepare("INSERT INTO producers (username, email, phone_number, password,  is_verified, role_id) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param('ssssii', $pro_username, $pro_email, $pro_phone_number, $hash_password, $is_Verified, $role_id);

    if ($stmt->execute()) {
      $_SESSION['success'] = 'Registration successful. Please wait...';
      header("location: ../form/signup-producer.php");
      exit();
    } else {
      $_SESSION['error'] = 'Error during registration.';
      header("location: ../form/signup-producer.php");
      exit();
    }
  }
}
producer_signup();


// FOR LOGIN USERS(CONSUMER) PRODUCERS AND ADMINS

function login()
{
  if (isset($_POST['login'])) {
    include '../Database/dbconnect.php';

    $login_input = trim($_POST['login_input']);
    $password = trim($_POST['password']);

    // BASIC VALIDATION
    if (empty($login_input) || empty($password)) {
      $_SESSION['login_error'] = 'Please fill in all fields.';
      header("location: ../login.php");
      exit();
    }

    // FOR ADMIN LOGIN CHECK
    $stmt = $conn->prepare("SELECT * FROM admins WHERE BINARY username = ? OR BINARY email = ?");
    $stmt->bind_param('ss', $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $admin = $result->fetch_assoc();
      if (password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['email'] = $admin['email'];
        $_SESSION['role_id'] = $admin['role_id'];
        $_SESSION['role_name'] = 'Admin';
        $_SESSION['login_type'] = ($login_input === $admin['username']) ? 'username' : 'email';

        sleep(3);
        header("Location: ../Admin/dashboard.php");
        exit();
      }
    }

    // FOR PRODUCER LOGIN CHECK
    $stmt = $conn->prepare("SELECT * FROM producers WHERE BINARY username = ? OR BINARY email = ?");
    $stmt->bind_param('ss', $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $producer = $result->fetch_assoc();

      // CHECK IF DEACTIVATED
      if ($producer['status'] !== 'Active') {
        $_SESSION['login_error'] = "Your account is deactivated. Contact admin.";
        header("Location: ../login.php");
        exit();
      }

      if (password_verify($password, $producer['password'])) {
        $_SESSION['producer_id'] = $producer['producer_id'];
        $_SESSION['role_id'] = $producer['role_id'];
        $_SESSION['role_name'] = 'Producer';
        $_SESSION['is_verified'] = $producer['is_verified'];
        $_SESSION['login_type'] = ($login_input === $producer['username']) ? 'username' : 'email';

        sleep(3);
        header("Location: ../Producer/dashboard.php");
        exit();
      }
    }

    // FOR CONSUMER (USER) LOGIN CHECK
    $stmt = $conn->prepare("SELECT * FROM users WHERE BINARY username = ? OR BINARY email = ?");
    $stmt->bind_param('ss', $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $consumer = $result->fetch_assoc();

      // CHECK IF DEACTIVATED
      if ($consumer['account_status'] !== 'Active') {
        $_SESSION['login_error'] = "Your account is deactivated. Contact admin.";
        header("Location: ../login.php");
        exit();
      }

      if (password_verify($password, $consumer['password'])) {
        $_SESSION['user_id'] = $consumer['user_id'];
        $_SESSION['role_id'] = $consumer['role_id'];
        $_SESSION['role_name'] = 'Consumer';
        $_SESSION['login_type'] = ($login_input === $consumer['username']) ? 'username' : 'email';

        sleep(3);
        header("Location: ../Consumer/dashboard.php");
        exit();
      }
    }

    // INVALID LOGIN FOR ALL ROLES
    $_SESSION['login_error'] = 'Invalid credentials.';
    header("location: ../login.php");
    exit();
  }
}

login();
