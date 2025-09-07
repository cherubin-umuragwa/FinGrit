<?php
require_once 'php/middleware.php';
require_once 'php/transactions.php';
require_once 'php/goals.php';

$auth = new Auth();
$user = $auth->getCurrentUser();

if (!$user) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

$transaction = new Transaction();
$summary = $transaction->getSummary($user['id']);
$recentTransactions = $transaction->getAll($user['id'], 5);
$categoryData = $transaction->getByCategory($user['id']);

$goal = new Goal();
$goals = $goal->getAll($user['id']);

// Prepare data for charts
$incomeCategories = [];
$expenseCategories = [];
foreach ($categoryData as $item) {
    if ($item['type'] === 'income') {
        $incomeCategories[$item['category']] = (float)$item['total'];
    } else {
        $expenseCategories[$item['category']] = (float)$item['total'];
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
                <h1>Welcome back, <?php echo htmlspecialchars($user['name'] ?? 'Guest'); ?> .</h1>
                <div class="header-actions">
                    <a href="transactions.php?action=add" class="btn btn-primary">
                        <i class="bi bi-plus"></i>
                        Add Transaction
                    </a>
                </div>
            </header>

            <div class="dashboard-widgets">
                <div class="widget income-widget">
                    <div class="widget-header">
                        <h3>Income</h3>
                        <i class="bi bi-arrow-down card-icon"></i>
                    </div>
                    <div class="widget-content">
                        <p class="card-value"><?php echo formatCurrency($summary['income']); ?></p>
                        <p class="widget-subtext">This month</p>
                    </div>
                </div>

                <div class="widget expense-widget">
                    <div class="widget-header">
                        <h3>Expenses</h3>
                        <i class="bi bi-arrow-up card-icon"></i>
                    </div>
                    <div class="widget-content">
                        <p class="card-value"><?php echo formatCurrency($summary['expense']); ?></p>
                        <p class="widget-subtext">This month</p>
                    </div>
                </div>

                <div class="widget balance-widget">
                    <div class="widget-header">
                        <h3>Balance</h3>
                        <i class="bi bi-currency-dollar card-icon"></i>
                    </div>
                    <div class="widget-content">
                        <p class="card-value <?php echo $summary['net'] >= 0 ? 'positive' : 'negative'; ?>">
                            <?php echo formatCurrency($summary['net']); ?>
                        </p>
                        <p class="widget-subtext">Net this month</p>
                    </div>
                </div>

                <div class="widget goals-widget">
                    <div class="widget-header">
                        <h3>Goals Progress</h3>
                        <i class="bi bi-bullseye card-icon"></i>
                    </div>
                    <div class="widget-content">
                        <?php if (!empty($goals)): ?>
                            <?php foreach (array_slice($goals, 0, 2) as $goal): ?>
                                <div class="goal-item">
                                    <div class="goal-info">
                                        <h4><?php echo htmlspecialchars($goal['name']); ?></h4>
                                        <p><?php echo formatCurrency($goal['current_savings']); ?> of <?php echo formatCurrency($goal['target_amount']); ?></p>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo calculateProgress($goal['current_savings'], $goal['target_amount']); ?>%; background-color: <?php echo calculateProgress($goal['current_savings'], $goal['target_amount']) >= 100 ? '#27AE60' : '#E1B12C'; ?>"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <a href="goals.php" class="view-all">View All</a>
                        <?php else: ?>
                            <p class="no-goals">No goals yet. <a href="goals.php?action=add">Create one</a> to get started!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


        </main>
    </div>

    <script src="js/main.js"></script>

</body>

</html>