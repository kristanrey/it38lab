<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
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
    width: calc(100% - 20px); /* Keep input aligned properly */
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
    <h2 style="text-align:center;">Sign up</h2>
    <form method="post" action="register.php">
        <label>First name:</label>
        <input type="text" name="firstname" required>
        
        <label>Last name:</label>
        <input type="text" name="lastname" required>

        <label>User name:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit" name="register">Register</button>
    </form>
    <br>
    <p style="text-align:center;">Already have an account? <a href="login.php"><button>Login</button></a></p>
</div>

<?php
$conn = new mysqli('localhost', 'root', '', 'user_db');

if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "INSERT INTO users (firstname, lastname, username, password) VALUES ('$firstname', '$lastname', '$username', '$password')";
    if ($conn->query($query)) {
        echo "<script>alert('Registration successful!');</script>";
        header('Location: login.php');
    } else {
        echo "<script>alert('Error occurred!');</script>";
    }
}
?>
</body>
</html>
