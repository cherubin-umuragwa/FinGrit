<?php
require_once 'php/middleware.php';
Middleware::authRequired();

$user = Middleware::getCurrentUser();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="fingrit-data-export-' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Export transactions
$database = new Database();
$conn = $database->connect();

// Write transactions
fputcsv($output, ['Transactions']);
fputcsv($output, ['Date', 'Type', 'Category', 'Amount', 'Notes']);

$stmt = $conn->prepare("SELECT date, type, category, amount, notes FROM transactions WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user['id']]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['date'],
        ucfirst($row['type']),
        $row['category'],
        $row['amount'],
        $row['notes']
    ]);
}

// Add empty row
fputcsv($output, []);

// Write goals
fputcsv($output, ['Savings Goals']);
fputcsv($output, ['Name', 'Target Amount', 'Current Savings', 'Progress', 'Deadline']);

$stmt = $conn->prepare("SELECT name, target_amount, current_savings, deadline FROM goals WHERE user_id = ? ORDER BY deadline ASC");
$stmt->execute([$user['id']]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $progress = ($row['current_savings'] / $row['target_amount']) * 100;
    fputcsv($output, [
        $row['name'],
        $row['target_amount'],
        $row['current_savings'],
        round($progress, 2) . '%',
        $row['deadline']
    ]);
}

fclose($output);
exit();
?>