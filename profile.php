<?php
require_once 'php/middleware.php';
Middleware::authRequired();

$auth = new Auth();
$user = $auth->getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Update name and email
        if (empty($name)) throw new Exception('Name is required');
        if (empty($email)) throw new Exception('Email is required');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Check if email is being changed
        if ($email !== $user['email']) {
            $database = new Database();
            $conn = $database->connect();

            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user['id']]);

            if ($stmt->rowCount() > 0) {
                throw new Exception('Email already in use by another account');
            }
        }

        // Update password if provided
        if (!empty($current_password)) {
            if (empty($new_password)) throw new Exception('New password is required');
            if (strlen($new_password) < 8) throw new Exception('New password must be at least 8 characters');
            if ($new_password !== $confirm_password) throw new Exception('New passwords do not match');

            // Verify current password
            $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $db_user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!password_verify($current_password, $db_user['password_hash'])) {
                throw new Exception('Current password is incorrect');
            }

            // Update password
            $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = ?");
            $stmt->execute([$name, $email, $password_hash, $user['id']]);
        } else {
            // Update without password
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user['id']]);
        }

        // Update session
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        $success = 'Profile updated successfully!';
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
    <div class="dashboard-container">
        <?php include 'partials/sidebar.php'; ?>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <h1>Profile Settings</h1>
            </header>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <div class="form  fade-in">
                    <form method="POST" action="profile.php">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" required
                                value="<?php echo htmlspecialchars($user['name']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required
                                value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>

                        <div class="form-section">
                            <h3 class="text-uppercase">Change Password</h3>
                            <p class="form-hint">Leave blank to keep current password</p>

                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" id="current_password">
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password">
                                <small>At least 8 characters</small>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" name="confirm_password" id="confirm_password">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary form-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="profile-actions-container">
                <div class="profile-actions">
                    <div class="export-data">
                        <h3>Download your data</h3>
                        <p>Export all your data in a CSV file</p>
                        <button type="button" id="export-data">
                            Download
                        </button>
                    </div>

                    <div class="bar"></div>

                    <div class="danger-zone">
                        <h3>Danger Zone</h3>
                        <p>This action is irreversible.</p>
                        <div class="danger-action">
                            <button type="button" id="delete-account">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script>
        document.getElementById('export-data').addEventListener('click', function() {
            if (confirm('This will export all your data as a CSV file. Continue?')) {
                window.location.href = 'export-data.php';
            }
        });

        document.getElementById('delete-account').addEventListener('click', function() {
            if (confirm('WARNING: This will permanently delete your account and all associated data. This cannot be undone. Are you sure?')) {
                window.location.href = 'delete-account.php';
            }
        });
    </script>

    <script src="js/main.js"></script>
</body>

</html>