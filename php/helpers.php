<?php
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

function formatDate($date, $format = 'Y-m-d') {
    return date($format, strtotime($date));
}

function calculateProgress($current, $target) {
    if ($target <= 0) return 0;
    $progress = ($current / $target) * 100;
    return min(100, max(0, round($progress, 2)));
}

function getMonthName($month) {
    return date('F', mktime(0, 0, 0, $month, 1));
}

function getMonths() {
    return [
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    ];
}

function getCategories() {
    return [
        'income' => ['Salary', 'Freelance', 'Investment', 'Gift', 'Other'],
        'expense' => ['Groceries', 'Rent', 'Utilities', 'Transportation', 'Entertainment', 'Dining', 'Shopping', 'Healthcare', 'Education', 'Other']
    ];
}
?>