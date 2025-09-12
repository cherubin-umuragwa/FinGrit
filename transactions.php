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
$action = $_GET['action'] ?? null;
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'type' => $_POST['type'],
            'category' => $_POST['category'],
            'amount' => $_POST['amount'],
            'date' => $_POST['date'],
            'notes' => $_POST['notes'] ?? '',
            'user_id' => $user['id']
        ];

        if (isset($_POST['id'])) {
            // Update existing transaction
            $data['id'] = $_POST['id'];
            $transaction->update($data['id'], $data['user_id'], $data['type'], $data['category'], $data['amount'], $data['date'], $data['notes']);
            $message = 'Transaction updated successfully!';
        } else {
            // Create new transaction
            $transaction->create($data['user_id'], $data['type'], $data['category'], $data['amount'], $data['date'], $data['notes']);
            $message = 'Transaction added successfully!';
        }

        header("Location: transactions.php?success=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

// Handle delete action
if ($action === 'delete' && isset($_GET['id'])) {
    try {
        $transaction->delete($_GET['id'], $user['id']);
        $message = 'Transaction deleted successfully!';
        header("Location: transactions.php?success=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

// Get transaction for editing
$editTransaction = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editTransaction = $transaction->getById($_GET['id'], $user['id']);
    if (!$editTransaction) {
        header("Location: transactions.php");
        exit();
    }
}

// Get all transactions for listing
$transactions = $transaction->getAll($user['id']);
$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinGrit - Transactions</title>
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
                <h1 class="header1"><?php echo $action === 'add' ? 'Add Transaction' : ($action === 'edit' ? 'Edit Transaction' : 'Transactions'); ?></h1>
                <div class="header-actions header-actions1">
                    <?php if ($action !== 'add' && $action !== 'edit'): ?>
                        <a href="transactions.php?action=add" class="btn btn-primary">
                            <i class="bi bi-plus"></i>
                            Add Transaction
                        </a>
                    <?php else: ?>
                        <a href="transactions.php" class="btn btn-secondary" id="back-transactions">
                            <i class="bi bi-arrow-left"></i>
                            Back to Transactions
                        </a>
                    <?php endif; ?>
                </div>
                <div class="topbar">
                    <div class="top1">
                        <div class="hamburger-menu" id="hamburgerMenu">
                            <i class="bi bi-list"></i>
                        </div>
                        <h1><?php echo $action === 'add' ? 'Add Transaction' : ($action === 'edit' ? 'Edit Transaction' : 'Transactions'); ?></h1>
                        <!-- Overlay for mobile -->
                        <div class="sidebar-overlay" id="sidebarOverlay"></div>
                    </div>
                    <div class="header-actions">
                        <?php if ($action !== 'add' && $action !== 'edit'): ?>
                            <a href="transactions.php?action=add" class="btn btn-primary">
                                <i class="bi bi-plus"></i>
                                Add Transaction
                            </a>
                        <?php else: ?>
                            <a href="transactions.php" class="btn btn-secondary" id="back-transactions">
                                <i class="bi bi-arrow-left"></i>
                                Back to Transactions
                            </a>
                        <?php endif; ?>
                    </div>
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
                        <form method="POST" action="transactions.php">
                            <?php if ($action === 'edit'): ?>
                                <input type="hidden" name="id" value="<?php echo $editTransaction['id']; ?>">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" id="type" required>
                                    <option value="income" <?php echo ($editTransaction['type'] ?? '') === 'income' ? 'selected' : ''; ?>>Income</option>
                                    <option value="expense" <?php echo ($editTransaction['type'] ?? '') === 'expense' ? 'selected' : ''; ?>>Expense</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="category">Category</label>
                                <select name="category" id="category" required>
                                    <option value="">Select a category</option>
                                    <!-- Income categories -->
                                    <optgroup label="Income" id="income-categories">
                                        <?php foreach ($categories['income'] as $category): ?>
                                            <option value="<?php echo $category; ?>" <?php echo ($editTransaction['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                                <?php echo $category; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <!-- Expense categories -->
                                    <optgroup label="Expense" id="expense-categories">
                                        <?php foreach ($categories['expense'] as $category): ?>
                                            <option value="<?php echo $category; ?>" <?php echo ($editTransaction['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                                <?php echo $category; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0.01"
                                    value="<?php echo $editTransaction['amount'] ?? ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="date"
                                    value="<?php echo $editTransaction['date'] ?? date('Y-m-d'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3"><?php echo $editTransaction['notes'] ?? ''; ?></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary form-btn">
                                    <?php echo $action === 'edit' ? 'Update Transaction' : 'Add Transaction'; ?>
                                </button>
                                <a href="transactions.php" class="btn btn-text">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="transactions-container">
                    <?php if (!empty($transactions)): ?>
                        <div class="transactions-list">
                            <?php foreach ($transactions as $t): ?>
                                <div class="transaction-item">
                                    <div class="transaction-icon" style="background-color: <?php echo $t['type'] === 'income' ? '#27AE60' : '#FF6F3C'; ?>">
                                        <?php if ($t['type'] === 'income'): ?>
                                            <i class="bi bi-arrow-down"></i>
                                        <?php else: ?>
                                            <i class="bi bi-arrow-up"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="transaction-details">
                                        <h4><?php echo htmlspecialchars($t['category']); ?></h4>
                                        <p><?php echo formatDate($t['date'], 'M j, Y'); ?></p>
                                        <?php if (!empty($t['notes'])): ?>
                                            <p class="transaction-notes"><?php echo htmlspecialchars($t['notes']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="transaction-amount <?php echo $t['type'] === 'income' ? 'income' : 'expense'; ?>">
                                        <?php echo ($t['type'] === 'income' ? '+' : '-') . formatCurrency($t['amount']); ?>
                                    </div>
                                    <div class="transaction-actions">
                                        <a href="transactions.php?action=edit&id=<?php echo $t['id']; ?>" class="btn btn-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="transactions.php?action=delete&id=<?php echo $t['id']; ?>" class="btn btn-icon" title="Delete" onclick="return confirm('Are you sure you want to delete this transaction?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty1">
                                <img src="assets/images/no-transactions.png" alt="No transactions" width="200">
                                <h3>No transactions yet</h3>
                                <p>Start tracking your finances
                                    <br>by adding your first transaction
                                </p>
                                <a href="transactions.php?action=add" class="btn btn-primary form-btn">Add Transaction</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        // Show/hide categories based on type selection
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category');
        const incomeCategories = document.getElementById('income-categories');
        const expenseCategories = document.getElementById('expense-categories');

        function updateCategoryOptions() {
            if (typeSelect.value === 'income') {
                incomeCategories.style.display = 'block';
                expenseCategories.style.display = 'none';
            } else {
                incomeCategories.style.display = 'none';
                expenseCategories.style.display = 'block';
            }

            // Reset category selection if it doesn't match the type
            const selectedCategory = categorySelect.value;
            const validCategories = typeSelect.value === 'income' ?
                <?php echo json_encode($categories['income']); ?> :
                <?php echo json_encode($categories['expense']); ?>;

            if (!validCategories.includes(selectedCategory)) {
                categorySelect.value = '';
            }
        }

        typeSelect.addEventListener('change', updateCategoryOptions);

        // Initialize on page load
        updateCategoryOptions();
    </script>

    <script src="js/main.js"></script>
    <script src="js/hamburger.js"></script>

</body>

</html>