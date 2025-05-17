<?php
session_start();

// 1) Include DB connection
require __DIR__ . '/../includes/db_connection.php';

// 2) Load Composer’s autoloader
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$recaptcha_secret = '6Lf5rx4rAAAAAPoAlOsbg35veUXqHkvVcx-m7FH-';

// Check database connection
if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Database connection error: Please check your db_connection.php file");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
    // 3) Validate reCAPTCHA
    $captcha = $_POST['g-recaptcha-response'] ?? '';
    if (empty($captcha)) {
        $message = "Please verify you're not a robot.";
    } else {
        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$captcha}"
        );
        $response = json_decode($verify);
        if (!$response->success) {
            $message = "reCAPTCHA verification failed.";
        } else {
            // 4) Sanitize inputs
            $displayName = trim($_POST["username"] ?? '');
            $email = trim($_POST["email"] ?? '');
            $password = $_POST["password"] ?? '';
            $confirm = $_POST["conf_password"] ?? '';

            // 5) Validation
            if (empty($displayName) || empty($email) || empty($password)) {
                $message = "All fields are required.";
            } elseif ($password !== $confirm) {
                $message = "Passwords do not match.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@nbsc.edu.ph')) {
                $message = "Only valid @nbsc.edu.ph emails are allowed.";
            } else {
                // 6) Check existing email using prepared statement
                $stmt = $conn->prepare("SELECT email FROM register WHERE email = ? LIMIT 1");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $message = "User with this email already exists.";
                } else {
                    // 7) Generate OTP
                    $otp = rand(100000, 999999);
                    $passHash = password_hash($password, PASSWORD_BCRYPT);

                    // Insert using prepared statement
                    $insertStmt = $conn->prepare("
    INSERT INTO register 
    (Username, email, Password, CodeV, verification, created_at)
    VALUES (?, ?, ?, ?, 0, NOW())
");

                    $insertStmt->bind_param("sssi", $displayName, $email, $passHash, $otp);

                    if ($insertStmt->execute()) {
                        // Store in session
                        $_SESSION['mail'] = $email;
                        $_SESSION['otp'] = $otp;

                        // 8) Send OTP email
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = '20212118@nbsc.edu.ph';
                            $mail->Password = 'oncv vprd ermv amti';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $mail->setFrom('20212118@nbsc.edu.ph', 'Verification Team');
                            $mail->addAddress($email, $displayName);
                            $mail->isHTML(true);
                            $mail->Subject = "Your Verification Code";

                            $mail->Body = "
                                <p>Hi <strong>{$displayName}</strong>,</p>
                                <h3>Your verification code is: <strong>{$otp}</strong></h3>
                                <p>Enter this code on the verification page to complete your registration.</p>
                                <p>Thank you!</p>
                            ";

                            $mail->send();
                            header("Location: verification.php");
                            exit;
                        } catch (Exception $e) {
                            $message = "Failed to send email: " . $mail->ErrorInfo;
                        }
                    } else {
                        $message = "Database error: " . $conn->error;
                    }
                    $insertStmt->close();
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: url('') center/cover no-repeat;
      display: flex; 
      justify-content: center; 
      align-items: center; 
      height: 100vh; 
      margin: 0;
    }
    .register-box {
      background: rgba(255,255,255,0.9);
      padding: 2rem; 
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      width: 100%; 
      max-width: 450px;
    }
    .register-box h2 { 
      text-align: center;
      margin-bottom: 1.5rem; 
    }
    .form-control { 
      padding: 1rem; 
      border-radius: 8px; 
      border: 1px solid #ccc; 
    }
    .btn-green { 
      background: #1DB954; 
      color: #fff; 
      padding: .75rem; 
      width: 100%; 
      border: none; 
      border-radius: 30px; 
      transition: background 0.3s;
    }
    .btn-green:hover { 
      background:rgb(6, 20, 11); 
    }
    #togglePassword { 
      position: absolute; 
      right: 1rem; 
      top: 50%; 
      transform: translateY(-50%);
      cursor: pointer; 
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Create Account</h2>
    <?php if ($message): ?>
      <div class="alert alert-warning text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST" id="registerForm">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email (@nbsc.edu.ph)" required>
      </div>
      <div class="mb-3 position-relative">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <span id="togglePassword">👁️</span>
      </div>
      <div class="mb-3">
        <input type="password" name="conf_password" class="form-control" placeholder="Confirm Password" required>
      </div>
      <div class="mb-3 text-center">
        <div class="g-recaptcha d-inline-block" data-sitekey="6Lf5rx4rAAAAAA0sTqSdsuvV82PVAIDLAh1_ww3Y"></div>
      </div>
      <button type="submit" name="register" class="btn btn-green mb-3">Register</button>
      <div class="text-center">
        <a href="login.php">Already have an account? Login</a>
      </div>
    </form>
  </div>

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', () => {
      const pwd = document.getElementById('password');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    });

    // Email domain validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const email = this.email.value.trim();
      if (!email.endsWith('@nbsc.edu.ph')) {
        e.preventDefault();
        alert('Only @nbsc.edu.ph emails are allowed.');
      }
    });
  </script>
</body>
</html>