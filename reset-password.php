<?php
require_once 'php/middleware.php';
Middleware::guestOnly();

$error = '';
$success = '';

// Verify token
$token = $_GET['token'] ?? '';
$database = new Database();
$conn = $database->connect();

$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resetRequest) {
    $error = 'Invalid or expired reset token';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $resetRequest) {
    try {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($password) || empty($confirm_password)) {
            throw new Exception('All fields are required');
        }

        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters');
        }

        if ($password !== $confirm_password) {
            throw new Exception('Passwords do not match');
        }

        // Update password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $stmt->execute([$password_hash, $resetRequest['email']]);

        // Delete reset token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);

        $success = 'Password updated successfully! You can now login with your new password.';
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
        <div class="col-md-6 text-center mx-auto page-header">
            <h1>Reset Password</h1>
            <p>Enter your new password</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <div class="form-footer">
                <a href="login.php" class="btn btn-primary form-btn">Login Now</a>
            </div>
        <?php elseif ($resetRequest): ?>
            <div class="form-container">
                <div class="form  fade-in">
                    <form method="POST" action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>">
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" name="password" id="password" required>
                            <small>At least 8 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary form-btn">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="form-footer">
                <a href="forgot-password.php" class="btn btn-primary form-btn">Request New Reset Link</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="js/main.js"></script>
</body>

</html>