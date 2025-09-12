<?php
require_once 'php/middleware.php';
Middleware::authRequired();

$user = Middleware::getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $database = new Database();
        $conn = $database->connect();
        
        // Verify password
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $db_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($_POST['password'], $db_user['password_hash'])) {
            throw new Exception('Password is incorrect');
        }
        
        // Begin transaction
        $conn->beginTransaction();
        
        try {
            // Delete transactions
            $stmt = $conn->prepare("DELETE FROM transactions WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            
            // Delete goals
            $stmt = $conn->prepare("DELETE FROM goals WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            
            // Delete user
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Commit transaction
            $conn->commit();
            
            // Logout
            $auth = new Auth();
            $auth->logout();
            
            // Redirect to home with success message
            header("Location: index.php?message=" . urlencode('Your account has been permanently deleted.'));
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
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
    <title>FinGrit - Delete Account</title>
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
                <h1>Delete Your Account</h1>
                <p>This action cannot be undone. All your data will be permanently erased.</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <div class="form-container">
                <div class="form  fade-in">
            <form method="POST" action="delete-account.php">
                <div class="form-group">
                    <label for="password">Enter Your Password to Confirm</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-error">Permanently Delete Account</button>
                    <a href="profile.php" class="btn btn-text">Cancel</a>
                </div>
            </form>
            </div>
            </div>
    </div>

<script src="js/main.js"></script>
</body>

</html>