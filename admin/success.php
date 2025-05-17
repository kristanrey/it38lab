<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
    <style>
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #000;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100vw;
            height: 100vh;
            background-color: white;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .circle {
            position: absolute;
            border-radius: 50%;
            background-color: #00d0ff;
            z-index: 1;
        }
        .circle.top-left {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
        }
        .circle.top-right {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
        }
        .circle.bottom-left {
            width: 300px;
            height: 300px;
            bottom: -150px;
            left: -150px;
            background-color: #2196f3;
        }
        .circle.bottom-right {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -150px;
        }
        .delivery-text {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .delivery-text img {
            width: 50px;
        }
        .emoji {
            width: 600px;
            z-index: 2;
        }
        .message {
            font-size: 26px;
            margin-top: 20px;
            color: #000;
            font-weight: bold;
            z-index: 2;
        }
        .login-button {
            margin-top: 30px;
            padding: 12px 30px;
            font-size: 18px;
            border: none;
            background-color: #00bcd4;
            color: white;
            border-radius: 25px;
            cursor: pointer;
            z-index: 2;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .login-button:hover {
            background-color: #0097a7;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Decorative Circles -->
        <div class="circle top-left"></div>
        <div class="circle top-right"></div>
        <div class="circle bottom-left"></div>
        <div class="circle bottom-right"></div>

        <!-- Delivery Message -->
        <div class="delivery-text">
            <img src="asset/logo.png" alt="Fast Delivery">
            “On time everytime”
        </div>

        <!-- Emoji Image -->
        <img class="emoji" src="https://i.pinimg.com/564x/bb/19/2d/bb192d00960abc2efa068122e8fd44d8.jpg" alt="Success Emoji">

        <!-- Message -->
        <div class="message">You have registered successfully!</div>

        <!-- Back to Login Button -->
        <a href="index.php" class="login-button">Back to Login</a>
    </div>
</body>
</html>
