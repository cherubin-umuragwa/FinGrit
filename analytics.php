<?php
require_once 'php/middleware.php';
require_once 'php/transactions.php';

$auth = new Auth();
$user = $auth->getCurrentUser();

if (!$user) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

$transaction = new Transaction();
$recentTransactions = $transaction->getRecent($user['id']);
// Handle date filter
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

if ($startDate && $endDate) {
    $transactions = $transaction->getByDateRange($user['id'], $startDate, $endDate);
} else {
    $transactions = $transaction->getAll($user['id']);
}

$categories = getCategories();

// Calculate analytics data
$analyticsData = calculateAnalytics($transactions);
$monthlyData = calculateMonthlyData($transactions);
$categoryData = calculateCategoryData($transactions);

// Functions to calculate analytics
function calculateAnalytics($transactions)
{
    $totalIncome = 0;
    $totalExpenses = 0;
    $savingsRate = 0;

    foreach ($transactions as $transaction) {
        if ($transaction['type'] === 'income') {
            $totalIncome += $transaction['amount'];
        } else {
            $totalExpenses += $transaction['amount'];
        }
    }

    if ($totalIncome > 0) {
        $savingsRate = (($totalIncome - $totalExpenses) / $totalIncome) * 100;
    }

    return [
        'total_income' => $totalIncome,
        'total_expenses' => $totalExpenses,
        'net_flow' => $totalIncome - $totalExpenses,
        'savings_rate' => $savingsRate,
        'transaction_count' => count($transactions)
    ];
}

function calculateMonthlyData($transactions)
{
    $monthlyData = [];

    foreach ($transactions as $transaction) {
        $month = date('Y-m', strtotime($transaction['date']));

        if (!isset($monthlyData[$month])) {
            $monthlyData[$month] = [
                'income' => 0,
                'expenses' => 0,
                'net' => 0
            ];
        }

        if ($transaction['type'] === 'income') {
            $monthlyData[$month]['income'] += $transaction['amount'];
        } else {
            $monthlyData[$month]['expenses'] += $transaction['amount'];
        }

        $monthlyData[$month]['net'] = $monthlyData[$month]['income'] - $monthlyData[$month]['expenses'];
    }

    // Sort by month (newest first)
    krsort($monthlyData);

    return array_slice($monthlyData, 0, 12); // Return last 12 months
}

function calculateCategoryData($transactions)
{
    $categoryData = [
        'income' => [],
        'expense' => []
    ];

    foreach ($transactions as $transaction) {
        $type = $transaction['type'];
        $category = $transaction['category'];
        $amount = $transaction['amount'];

        if (!isset($categoryData[$type][$category])) {
            $categoryData[$type][$category] = 0;
        }

        $categoryData[$type][$category] += $amount;
    }

    // Sort categories by amount (highest first)
    foreach ($categoryData as $type => $categories) {
        arsort($categoryData[$type]);
    }

    return $categoryData;
}

// Get current period for display
$currentPeriod = date('F Y');
$previousPeriod = date('F Y', strtotime('-1 month'));

// Calculate period-over-period changes
$currentMonth = date('Y-m');
$previousMonth = date('Y-m', strtotime('-1 month'));

$currentMonthIncome = isset($monthlyData[$currentMonth]['income']) ? $monthlyData[$currentMonth]['income'] : 0;
$previousMonthIncome = isset($monthlyData[$previousMonth]['income']) ? $monthlyData[$previousMonth]['income'] : 0;
$incomeChange = $previousMonthIncome > 0 ? (($currentMonthIncome - $previousMonthIncome) / $previousMonthIncome) * 100 : 0;

$currentMonthExpenses = isset($monthlyData[$currentMonth]['expenses']) ? $monthlyData[$currentMonth]['expenses'] : 0;
$previousMonthExpenses = isset($monthlyData[$previousMonth]['expenses']) ? $monthlyData[$previousMonth]['expenses'] : 0;
$expensesChange = $previousMonthExpenses > 0 ? (($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100 : 0;
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
        <main class="dashboard-main" id="dashboardMain">
            <header class="dashboard-header">
                <h1 class="header1">Financial Analytics</h1>
                <div class="date-filter header-actions1">
                    <?php if ($startDate && $endDate): ?>
                        <!-- Show selected period if dates are chosen -->
                        <span class="selected-period">
                            Period: <?php echo date('M j, Y', strtotime($startDate)) . " - " . date('M j, Y', strtotime($endDate)); ?>
                        </span>
                        <button id="changeDatesBtn" class="btn btn-primary btn-sm">Change</button>
                    <?php else: ?>
                        <!-- Show form if no dates selected -->
                        <form method="get" class="filter-form" id="dateFilterForm">
                            <input type="date" name="start_date" required>
                            <input type="date" name="end_date" required>
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="topbar">
                    <div class="top1">
                    <div class="hamburger-menu" id="hamburgerMenu">
                        <i class="bi bi-list"></i>
                    </div>
                <h1>Financial Analytics</h1>
                <!-- Overlay for mobile -->
                    <div class="sidebar-overlay" id="sidebarOverlay"></div>
                    </div>
                <div class="date-filter">
                    <?php if ($startDate && $endDate): ?>
                        <!-- Show selected period if dates are chosen -->
                        <span class="selected-period">
                            Period: <?php echo date('M j, Y', strtotime($startDate)) . " - " . date('M j, Y', strtotime($endDate)); ?>
                        </span>
                        <button id="changeDatesBtn" class="btn btn-primary btn-sm">Change</button>
                    <?php else: ?>
                        <!-- Show form if no dates selected -->
                        <form method="get" class="filter-form" id="dateFilterForm">
                            <input type="date" name="start_date" required>
                            <input type="date" name="end_date" required>
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                        </form>
                    <?php endif; ?>
                </div>
                </div>
            </header>

            <!-- Analytics Cards -->
            <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="card-header">
                        <h3 class="card-title">Total Income</h3>
                        <div class="card-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                    <div class="card-value"><?php echo formatCurrency($analyticsData['total_income']); ?></div>
                    <div class="card-change <?php echo $incomeChange >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="bi bi-arrow-<?php echo $incomeChange >= 0 ? 'up' : 'down'; ?>"></i>
                        <span><?php echo abs(round($incomeChange, 1)); ?>% from last month</span>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <h3 class="card-title">Total Expenses</h3>
                        <div class="card-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <div class="card-value"><?php echo formatCurrency($analyticsData['total_expenses']); ?></div>
                    <div class="card-change <?php echo $expensesChange <= 0 ? 'positive' : 'negative'; ?>">
                        <i class="bi bi-arrow-<?php echo $expensesChange <= 0 ? 'down' : 'up'; ?>"></i>
                        <span><?php echo abs(round($expensesChange, 1)); ?>% from last month</span>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <h3 class="card-title">Net Cash Flow</h3>
                        <div class="card-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                    <div class="card-value <?php echo $analyticsData['net_flow'] >= 0 ? 'income' : 'expense'; ?>">
                        <?php echo ($analyticsData['net_flow'] >= 0 ? '+' : '') . formatCurrency($analyticsData['net_flow']); ?>
                    </div>
                    <div class="card-change">
                        <span><?php echo $analyticsData['transaction_count']; ?> transactions</span>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <h3 class="card-title">Savings Rate</h3>
                        <div class="card-icon">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                    </div>
                    <div class="card-value"><?php echo round($analyticsData['savings_rate'], 1); ?>%</div>
                    <div class="card-change">
                        <span>Of your income saved</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-grid">
                <div class="chart-container">
                    <div class="chart-header">
                        <h3 class="chart-title">Income vs Expenses</h3>
                        <div class="chart-actions">
                            <button class="download-chart" data-chart-id="incomeExpenseChart">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="expand-chart">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </button>
                        </div>
                    </div>
                    <div id="incomeExpenseChart"></div>
                </div>

                <div class="chart-container">
                    <div class="chart-header">
                        <h3 class="chart-title">Spending by Category</h3>
                        <div class="chart-actions">
                            <button class="download-chart" data-chart-id="categoryChart">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="expand-chart">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </button>
                        </div>
                    </div>
                    <div id="categoryChart"></div>
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="recent-transactions">
                <div class="section-header">
                    <h3>Recent Transactions</h3>
                    <a href="transactions.php" class="view-all">View All <i class="bi bi-list"></i> </a>
                </div>

                <div class="transactions-list">
                    <?php if (!empty($recentTransactions)): ?>
                        <?php foreach ($recentTransactions as $transaction): ?>
                            <div class="transaction-item">
                                <div class="transaction-icon" style="background-color: <?php echo $transaction['type'] === 'income' ? '#27AE60' : '#FF6F3C'; ?>">
                                    <?php if ($transaction['type'] === 'income'): ?>
                                        <i class="bi bi-arrow-down"></i>
                                    <?php else: ?>
                                        <i class="bi bi-arrow-up"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="transaction-details">
                                    <h4><?php echo htmlspecialchars($transaction['category']); ?></h4>
                                    <p><?php echo formatDate($transaction['date'], 'M j, Y'); ?></p>
                                </div>
                                <div class="transaction-amount <?php echo $transaction['type'] === 'income' ? 'income' : 'expense'; ?>">
                                    <?php echo ($transaction['type'] === 'income' ? '+' : '-') . formatCurrency($transaction['amount']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-transactions">No transactions yet. <a href="transactions.php?action=add">Add one</a> to get started!</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Pass PHP data to JavaScript -->
    <script>
        window.analyticsData = {
            monthly: {
                labels: [<?php echo implode(',', array_map(function ($month) {
                                return "'" . date('M Y', strtotime($month . '-01')) . "'";
                            }, array_keys($monthlyData))); ?>],
                income: [<?php echo implode(',', array_column($monthlyData, 'income')); ?>],
                expenses: [<?php echo implode(',', array_column($monthlyData, 'expenses')); ?>]
            },
            categories: {
                labels: [<?php
                            $expenseCategories = array_slice($categoryData['expense'], 0, 5);
                            echo implode(',', array_map(function ($category) {
                                return "'" . addslashes($category) . "'";
                            }, array_keys($expenseCategories)));
                            ?>],
                data: [<?php echo implode(',', array_values($expenseCategories)); ?>]
            }
        };

        // Changing the date for the analytics
        document.addEventListener("DOMContentLoaded", () => {
            const changeBtn = document.getElementById("changeDatesBtn");
            if (changeBtn) {
                changeBtn.addEventListener("click", () => {
                    // Replace URL params with no filter
                    window.location.href = "analytics.php";
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/analytics.js"></script>
    <script src="js/hamburger.js"></script>
</body>

</html>