<?php
require_once '../../config/database.php';

$token = $_GET['token'] ?? '';
$error = null;
$success = false;

if ($token) {
    try {
        // Verify token
        $stmt = $pdo->prepare("SELECT email, expires_at, used FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        if (!$row) {
            $error = "Invalid or expired reset link. Please request a new password reset.";
        } else {
            $email = $row['email'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';

                if (strlen($password) < 8) {
                    $error = "Password must be at least 8 characters long";
                } elseif ($password !== $confirm_password) {
                    $error = "Passwords do not match";
                } else {
                    // Update password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                    $stmt->execute([$hashed_password, $email]);

                    // Mark token as used
                    $stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
                    $stmt->execute([$token]);

                    $success = true;
                }
            }
        }
    } catch (Exception $e) {
        error_log("Password reset error: " . $e->getMessage());
        $error = "An error occurred. Please try again.";
    }
} else {
    $error = "Invalid reset link";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Egypt Hotels</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .reset-password-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #1d4ed8;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-login a {
            color: #2563eb;
            text-decoration: none;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <h2 style="text-align: center; margin-bottom: 30px;">Reset Password</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Your password has been successfully reset. You can now <a href="login.html">login</a> with your new password.
            </div>
        <?php elseif ($token && !$error): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                <button type="submit" class="btn-submit">Reset Password</button>
            </form>
        <?php endif; ?>

        <div class="back-to-login">
            <a href="login.html">Back to Login</a>
        </div>
    </div>
</body>
</html> 