<?php
session_start();
include("../includes/db_connection.php");

$message = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Input validation
    if (empty($username) || empty($password)) {
        $message = "Please fill in all fields";
    } else {
        $stmt = $conn->prepare("SELECT * FROM register WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if ((int)$row['verification'] !== 1) {
                $message = "Account not verified. Please check your email for the OTP.";
            } elseif (password_verify($password, $row['Password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['Username'];
                header("Location: home.php");
                exit();
            } else {
                $message = "Wrong password.";
            }
        } else {
            $message = "User not found.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }
        .container {
            width: 320px;
            padding: 20px;
            background: linear-gradient(to bottom, #f7c9d7, #003b36);
            margin: 100px auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }
        form {
            width: 100%;
        }
        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }
        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            display: block;
            margin-bottom: 10px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #66ff99;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #33cc66;
        }
        .message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 35px;
            cursor: pointer;
            color: #666;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            display: block;
            width: 95%;
            padding: 10px;
            background-color: #6ec1e4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .register-link a:hover {
            background-color: #4aa3d2;
        }
        @media (max-width: 480px) {
            .container {
                width: 95%;
                margin: 50px auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (!empty($message)) : ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <label>Username:</label>
            <input type="text" name="username" required>

            <div class="password-container">
                <label>Password:</label>
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

            <button type="submit" name="login">Login</button>
        </form>

        <div class="register-link">
            <a href="register.php">Create new account</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const inputs = Array.from(document.querySelectorAll('input[required]'));
            const emptyFields = inputs.filter(input => !input.value.trim());
            
            if (emptyFields.length > 0) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</body>
</html>