<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        a button {
            background-color: #6ec1e4;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        a button:hover {
            background-color: #4aa3d2;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">Login</h2>
    <form method="post" action="">
        <label>User name:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit" name="login">Login</button>
    </form>
    <br>
    <a href="register.php"><button>Create new account</button></a>
</div>

<?php
$conn = new mysqli('localhost', 'root', '', 'user_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        echo "<script>alert('Login successful! Redirecting to dashboard...');</script>";
        header("refresh:1;url=dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password!');</script>";
    }

    $stmt->close();
}
$conn->close();
?>
</body>
</html>
