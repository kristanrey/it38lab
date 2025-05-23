<?php
session_start();
include('connect/connection.php');

// Initialize login attempt tracking
if (!isset($_SESSION['attempt'])) {
    $_SESSION['attempt'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

$max_attempts = 3;
$block_duration = 30;

if ($_SESSION['attempt'] >= $max_attempts) {
    $time_since_last_attempt = time() - $_SESSION['last_attempt_time'];

    if ($time_since_last_attempt < $block_duration) {
        $wait = $block_duration - $time_since_last_attempt;
        echo "<script>alert('Too many failed attempts. Try again in $wait seconds.');</script>";
        exit();
    } else {
        $_SESSION['attempt'] = 0;
    }
}

if (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($connect, trim($_POST['email']));
    $password = trim($_POST['password']);

    $sql = mysqli_query($connect, "SELECT * FROM login WHERE email = '$email'");
    $count = mysqli_num_rows($sql);

    if ($count > 0) {
        $fetch = mysqli_fetch_assoc($sql);
        $hashpassword = $fetch["password"];

        if ($fetch["status"] == 0) {
            echo "<script>alert('Please verify your email before logging in.');</script>";
        } else if (password_verify($password, $hashpassword)) {
            $_SESSION['email'] = $email;
            $_SESSION['attempt'] = 0; // reset on successful login
            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
            $_SESSION['attempt'] += 1;
            $_SESSION['last_attempt_time'] = time();
        }
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
        $_SESSION['attempt'] += 1;
        $_SESSION['last_attempt_time'] = time();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: #fff;
      padding: 40px 30px;
      width: 360px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .login-box h2 {
      font-size: 2rem;
      margin-bottom: 2rem;
      text-align: center;
    }

    .form-control {
      background-color: #ffffff;
      border: 1px solid #ccc;
      color: #000;
      font-size: 1.1rem;
      padding: 1rem;
      border-radius: 8px;
    }

    .form-control:focus {
      background-color: #fff;
      outline: none;
      box-shadow: 0 0 0 2px #1DB954;
      border-color: #1DB954;
      color: #000;
    }

    .btn-green {
      background-color: #1DB954;
      color: #fff;
      padding: 0.75rem;
      font-size: 1.1rem;
      border-radius: 30px;
      border: none;
      width: 100%;
    }

    .btn-green:hover {
      background-color: #1ed760;
    }

    .form-check-label {
      color: #000;
    }

    .login-footer a {
      color: #1DB954;
      text-decoration: none;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }

    .toggle-password {
      position: absolute;
      right: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      color: #666;
      cursor: pointer;
    }

    .position-relative {
      position: relative;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Login</h2>
  <form action="#" method="POST" name="login">
    <div class="mb-4 position-relative">
      <input type="text" class="form-control" name="email" placeholder="Email address" required autofocus>
    </div>

    <div class="mb-4 position-relative">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
      <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
    </div>

    <div class="mb-4 form-check">
      <input type="checkbox" class="form-check-input" id="remember" name="remember">
      <label class="form-check-label" for="remember">Remember me</label>
    </div>

    <div class="mb-4">
      <button type="submit" name="login" class="btn btn-green">Log In</button>
    </div>

    <div class="login-footer text-center">
      <a href="recover_psw.php">Forgot your password?</a><br>
      <a href="register.php">Don't have an account? Sign up</a>
    </div>
  </form>
</div>

<script>
  const toggle = document.getElementById('togglePassword');
  const password = document.getElementById('password');

  toggle.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('bi-eye');
  });
</script>

</body>
</html>
