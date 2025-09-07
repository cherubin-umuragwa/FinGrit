<?php
require_once 'php/middleware.php';
require_once 'php/goals.php';

$auth = new Auth();
$user = $auth->getCurrentUser();

if (!$user) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

$goal = new Goal();
$action = $_GET['action'] ?? null;
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'name' => $_POST['name'],
            'target_amount' => $_POST['target_amount'],
            'current_savings' => $_POST['current_savings'] ?? 0,
            'deadline' => $_POST['deadline'] ?? null,
            'user_id' => $user['id']
        ];

        if (isset($_POST['id'])) {
            // Update existing goal
            $data['id'] = $_POST['id'];
            $goal->update($data['id'], $data['user_id'], $data['name'], $data['target_amount'], $data['deadline'], $data['current_savings']);
            $message = 'Goal updated successfully!';
        } else {
            // Create new goal
            $goal->create($data['user_id'], $data['name'], $data['target_amount'], $data['deadline'], $data['current_savings']);
            $message = 'Goal created successfully!';
        }

        header("Location: goals.php?success=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

// Handle delete action
if ($action === 'delete' && isset($_GET['id'])) {
    try {
        $goal->delete($_GET['id'], $user['id']);
        $message = 'Goal deleted successfully!';
        header("Location: goals.php?success=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

// Handle add to savings action
if ($action === 'add-savings' && isset($_GET['id']) && isset($_GET['amount'])) {
    try {
        $goal->addToSavings($_GET['id'], $user['id'], $_GET['amount']);
        $message = 'Savings updated successfully!';
        header("Location: goals.php?success=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

// Get goal for editing
$editGoal = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editGoal = $goal->getById($_GET['id'], $user['id']);
    if (!$editGoal) {
        header("Location: goals.php");
        exit();
    }
}

// Get all goals for listing
$goals = $goal->getAll($user['id']);
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
                <h1><?php echo $action === 'add' ? 'Add Goal' : ($action === 'edit' ? 'Edit Goal' : 'Savings Goals'); ?></h1>
                <div class="header-actions">
                    <?php if ($action !== 'add' && $action !== 'edit'): ?>
                        <a href="goals.php?action=add" class="btn btn-primary form-btn">
                            <i class="bi bi-plus"></i>
                            Add Goal
                        </a>
                    <?php else: ?>
                        <a href="goals.php" class="btn btn-secondary" id="back-goals">
                            <i class="bi bi-arrow-left"></i>
                            Back to Goals
                        </a>
                    <?php endif; ?>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php elseif (!empty($message)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($action === 'add' || $action === 'edit'): ?>
                <div class="form-container">
                    <div class="form  fade-in">
                        <form method="POST" action="goals.php">
                            <?php if ($action === 'edit'): ?>
                                <input type="hidden" name="id" value="<?php echo $editGoal['id']; ?>">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="name">Goal Name</label>
                                <input type="text" name="name" id="name"
                                    value="<?php echo $editGoal['name'] ?? ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="target_amount">Target Amount</label>
                                <input type="number" name="target_amount" id="target_amount" step="0.01" min="0.01"
                                    value="<?php echo $editGoal['target_amount'] ?? ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="current_savings">Current Savings</label>
                                <input type="number" name="current_savings" id="current_savings" step="0.01" min="0"
                                    value="<?php echo $editGoal['current_savings'] ?? 0; ?>">
                            </div>

                            <div class="form-group">
                                <label for="deadline">Deadline (Optional)</label>
                                <input type="date" name="deadline" id="deadline"
                                    value="<?php echo $editGoal['deadline'] ?? ''; ?>">
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary form-btn">
                                    <?php echo $action === 'edit' ? 'Update Goal' : 'Add Goal'; ?>
                                </button>
                                <a href="goals.php" class="btn btn-text">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="goals-container">
                    <?php if (!empty($goals)): ?>
                        <div class="goals-grid">
                            <?php foreach ($goals as $g): ?>
                                <div class="form-container goal-container">
                                    <div class="form  fade-in">
                                        <div class="goal-card">
                                            <div class="goal-header">
                                                <h3><?php echo htmlspecialchars($g['name']); ?></h3>
                                                <div class="goal-actions">
                                                    <a href="goals.php?action=edit&id=<?php echo $g['id']; ?>" class="btn btn-icon" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="goals.php?action=delete&id=<?php echo $g['id']; ?>" class="btn btn-icon" title="Delete" onclick="return confirm('Are you sure you want to delete this goal?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="goal-progress">
                                                <div class="progress-info">
                                                    <span><?php echo formatCurrency($g['current_savings']); ?></span>
                                                    <span><?php echo formatCurrency($g['target_amount']); ?></span>
                                                </div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: <?php echo calculateProgress($g['current_savings'], $g['target_amount']); ?>%; background-color: <?php echo calculateProgress($g['current_savings'], $g['target_amount']) >= 100 ? '#27AE60' : '#E1B12C'; ?>"></div>
                                                </div>
                                                <div class="progress-percent">
                                                    <?php echo calculateProgress($g['current_savings'], $g['target_amount']); ?>%
                                                </div>
                                            </div>

                                            <?php if ($g['deadline']): ?>
                                                <div class="goal-deadline">
                                                    <i class="bi bi-clock"></i>
                                                    Target date: <?php echo formatDate($g['deadline'], 'M j, Y'); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="goal-add-savings">

                                                <form method="GET" action="goals.php">
                                                    <input type="hidden" name="action" value="add-savings">
                                                    <input type="hidden" name="id" value="<?php echo $g['id']; ?>">
                                                    <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount" required>
                                                    <div class="saving-btn">
                                                        <button type="submit" class="btn btn-small goal-btn">Add to Savings</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <img src="assets/images/no-goals.svg" alt="No goals" width="200">
                            <h3>No savings goals yet</h3>
                            <p>Start achieving your financial dreams by setting your first savings goal</p>
                            <a href="goals.php?action=add" class="btn btn-primary form-btn">Add Goal</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="js/main.js"></script>
</body>

</html>