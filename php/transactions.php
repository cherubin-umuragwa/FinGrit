<?php
require_once 'db.php';
require_once 'helpers.php';

class Transaction {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

        public function getRecent($userId, $limit = 3) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC LIMIT ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getByDateRange($userId, $startDate, $endDate) {
    $stmt = $this->db->prepare("
        SELECT * FROM transactions 
        WHERE user_id = :user_id
        AND date BETWEEN :start_date AND :end_date
        ORDER BY date DESC
    ");
    
    $stmt->execute([
        ':user_id' => $userId,
        ':start_date' => $startDate,
        ':end_date' => $endDate
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function create($user_id, $type, $category, $amount, $date, $notes = '') {
        validateTransactionData($type, $category, $amount, $date);

        $stmt = $this->db->prepare("
            INSERT INTO transactions 
            (user_id, type, category, amount, date, notes) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([$user_id, $type, $category, $amount, $date, $notes]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $user_id, $type, $category, $amount, $date, $notes = '') {
        validateTransactionData($type, $category, $amount, $date);

        $stmt = $this->db->prepare("
            UPDATE transactions 
            SET type = ?, category = ?, amount = ?, date = ?, notes = ? 
            WHERE id = ? AND user_id = ?
        ");
        
        $stmt->execute([$type, $category, $amount, $date, $notes, $id, $user_id]);
        
        return $stmt->rowCount();
    }

    public function delete($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        
        return $stmt->rowCount();
    }

    public function getAll($user_id, $limit = null, $offset = null) {
    // Base query
    $query = "SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC";

    // Add LIMIT and OFFSET if provided
    if ($limit !== null) {
        $limit = (int)$limit;  // ensure integer
        $query .= " LIMIT $limit";

        if ($offset !== null) {
            $offset = (int)$offset;  // ensure integer
            $query .= " OFFSET $offset";
        }
    }

    // Prepare and execute
    $stmt = $this->db->prepare($query);
    $stmt->execute([$user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function getById($id, $user_id) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSummary($userId) {
    $currentMonth = date('m');
    $currentYear = date('Y');

    $stmt = $this->db->prepare("
        SELECT 
            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
        FROM transactions
        WHERE user_id = :user_id
        AND MONTH(date) = :month
        AND YEAR(date) = :year
    ");
    $stmt->execute([
        ':user_id' => $userId,
        ':month' => $currentMonth,
        ':year' => $currentYear
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return [
        'income' => $row['income'] ?? 0,
        'expense' => $row['expense'] ?? 0,
        'net' => ($row['income'] ?? 0) - ($row['expense'] ?? 0)
    ];
}

    public function getByCategory($user_id, $start_date = null, $end_date = null) {
        $query = "
            SELECT 
                category,
                type,
                SUM(amount) as total,
                COUNT(*) as count
            FROM transactions
            WHERE user_id = ?
        ";
        
        $params = [$user_id];
        
        if ($start_date) {
            $query .= " AND date >= ?";
            $params[] = $start_date;
        }
        
        if ($end_date) {
            $query .= " AND date <= ?";
            $params[] = $end_date;
        }
        
        $query .= " GROUP BY category, type ORDER BY type, total DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


function validateTransactionData($type, $category, $amount, $date) {
    if (!in_array($type, ['income', 'expense'])) {
        throw new Exception("Invalid transaction type");
    }
    
    if (empty($category)) {
        throw new Exception("Category is required");
    }
    
    if (!is_numeric($amount) || $amount <= 0) {
        throw new Exception("Amount must be a positive number");
    }
    
    if (!strtotime($date)) {
        throw new Exception("Invalid date format");
    }
}


?>