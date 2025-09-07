<?php
require_once 'db.php';
require_once 'helpers.php';

/**
 * Get monthly summary data for a user
 */
function getMonthlySummary($user_id, $year = null) {
    $year = $year ?? date('Y');
    $database = new Database();
    $conn = $database->connect();
    
    $stmt = $conn->prepare("
        SELECT 
            MONTH(date) as month,
            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
        FROM transactions
        WHERE user_id = ? AND YEAR(date) = ?
        GROUP BY MONTH(date)
        ORDER BY month
    ");
    
    $stmt->execute([$user_id, $year]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get category spending breakdown
 */
function getCategoryBreakdown($user_id, $type = 'expense', $limit = 5) {
    $database = new Database();
    $conn = $database->connect();
    
    $stmt = $conn->prepare("
        SELECT category, SUM(amount) as total
        FROM transactions
        WHERE user_id = ? AND type = ?
        GROUP BY category
        ORDER BY total DESC
        LIMIT ?
    ");
    
    $stmt->execute([$user_id, $type, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get recent transactions with pagination
 */
function getRecentTransactions($user_id, $limit = 10, $offset = 0) {
    $database = new Database();
    $conn = $database->connect();
    
    $stmt = $conn->prepare("
        SELECT * FROM transactions
        WHERE user_id = ?
        ORDER BY date DESC, id DESC
        LIMIT ? OFFSET ?
    ");
    
    $stmt->execute([$user_id, $limit, $offset]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get goals with progress calculation
 */
function getGoalsWithProgress($user_id) {
    $database = new Database();
    $conn = $database->connect();
    
    $stmt = $conn->prepare("
        SELECT *, 
            (current_savings / target_amount) * 100 as progress,
            DATEDIFF(deadline, CURDATE()) as days_remaining
        FROM goals
        WHERE user_id = ?
        ORDER BY deadline ASC
    ");
    
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Check if email exists in database
 */
function emailExists($email) {
    $database = new Database();
    $conn = $database->connect();
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount() > 0;
}