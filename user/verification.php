<?php
session_start();
require __DIR__ . '/../includes/db_connection.php';

$message = '';

if (isset($_POST['verify'])) {
    // Validate session exists
    if (!isset($_SESSION['mail'])) {
        $message = "Session expired. Please restart verification.";
    } else {
        $otp_input = trim($_POST['otp_code']);
        $email = $_SESSION['mail'];

        // Get database OTP
        $stmt = $conn->prepare("SELECT CodeV FROM register WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $message = "Account not found. Please register first.";
        } else {
            $row = $result->fetch_assoc();
            $db_otp = $row['CodeV'];

            if ($otp_input == $db_otp) {
                // Mark as verified
                $update_stmt = $conn->prepare("UPDATE register SET verification = 1 WHERE email = ?");
                $update_stmt->bind_param("s", $email);
                
                if ($update_stmt->execute()) {
                    unset($_SESSION['otp'], $_SESSION['mail']);
                    echo "<script>
                        alert('Verification successful!');
                        window.location.href = 'login.php';
                    </script>";
                    exit();
                } else {
                    $message = "Verification failed: " . $conn->error;
                }
            } else {
                $message = "Invalid OTP code. Please check carefully.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .verify-box {
            background: rgba(255,255,255,0.9);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="verify-box">
        <h2 class="text-center">OTP Verification</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <input type="number" 
                       name="otp_code" 
                       class="form-control"
                       placeholder="Enter 6-digit code"
                       min="100000"
                       max="999999"
                       required>
            </div>
            <button type="submit" name="verify" class="btn btn-success">Verify</button>
        </form>
    </div>
</body>
</html>