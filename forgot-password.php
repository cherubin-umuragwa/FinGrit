<?php
require_once 'php/middleware.php';
Middleware::guestOnly();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = sanitizeInput($_POST['email']);

        if (empty($email)) {
            throw new Exception('Email is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Check if email exists
        $database = new Database();
        $conn = $database->connect();

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() === 0) {
            throw new Exception('No account found with that email');
        }

        // Generate reset token -- This will be improved to be more secure in the future version
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $stmt = $conn->prepare("
            INSERT INTO password_resets (email, token, expires_at)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE token = ?, expires_at = ?
        ");
        $stmt->execute([$email, $token, $expires, $token, $expires]);

        // Coming soon. Send email with reset link
        $resetLink = getenv('APP_URL') . "/reset-password.php?token=$token";

        // Coming soon. For demo purposes, you will just see a link
        $success = "Password reset link: <a href='$resetLink'>$resetLink</a>";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinGrit - Analytics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/specific_styles.css" rel="stylesheet">
</head>

<body>
    <div class="main-container">
        <div class="col-md-6 text-center mx-auto page-header"">
            <h1>Forgot Password</h1>
            <p>Enter your email to receive a password reset link</p>
        </div>

        <?php if ($error): ?>
        <div class=" alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
        <div class="footer-footer">
            <a href="login.php" class="btn btn-primary">Back to Login</a>
        </div>
    <?php else: ?>
        <div class="form-container">
            <div class="form  fade-in">
                <form method="POST" action="forgot-password.php">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary form-btn">Send Reset Link</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="form-footer">
            <p>Remember your password? <a href="login.php">Login here</a></p>
        </div>
    <?php endif; ?>
    </div>

    <script src="js/main.js"></script>
</body>

</html>