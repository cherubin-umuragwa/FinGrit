<?php
require_once 'php/middleware.php';
Middleware::guestOnly();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            throw new Exception('Email and password are required');
        }

        $auth = new Auth();
        $auth->login($email, $password);

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
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
    <title>FinGrit - Login</title>
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
            <h1>Welcome Back</h1>
            <p>Login to manage your finances</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <div class="form-container">
            <div class="form  fade-in">
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" required
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-input">
                            <input type="password" name="password" id="password" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary form-btn" id="login-btn">Login</button>
                    </div>
                </form>
            </div>
            <div class="form-footer">
                <p>Don't have an account? <a href="register.php">Register here</span></a></p>
                <p><a href="forgot-password.php">Forgot password?</a></p>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>

</html>