<?php
require_once 'db.php';
require_once 'helpers.php';

class Goal {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create($user_id, $name, $target_amount, $deadline = null, $current_savings = 0) {
        validateGoalData($name, $target_amount, $deadline, $current_savings);

        $stmt = $this->db->prepare("
            INSERT INTO goals 
            (user_id, name, target_amount, current_savings, deadline) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([$user_id, $name, $target_amount, $current_savings, $deadline]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $user_id, $name, $target_amount, $deadline = null, $current_savings = 0) {
        validateGoalData($name, $target_amount, $deadline, $current_savings);

        $stmt = $this->db->prepare("
            UPDATE goals 
            SET name = ?, target_amount = ?, current_savings = ?, deadline = ? 
            WHERE id = ? AND user_id = ?
        ");
        
        $stmt->execute([$name, $target_amount, $current_savings, $deadline, $id, $user_id]);
        
        return $stmt->rowCount();
    }

    public function delete($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM goals WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        
        return $stmt->rowCount();
    }

    public function getAll($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY deadline ASC");
        $stmt->execute([$user_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $user_id) {
        $stmt = $this->db->prepare("SELECT * FROM goals WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addToSavings($id, $user_id, $amount) {
        if (!is_numeric($amount)) {
            throw new Exception("Amount must be a number");
        }

        $stmt = $this->db->prepare("
            UPDATE goals 
            SET current_savings = current_savings + ? 
            WHERE id = ? AND user_id = ?
        ");
        
        $stmt->execute([$amount, $id, $user_id]);
        
        return $stmt->rowCount();
    }
}

function validateGoalData($name, $target_amount, $deadline, $current_savings) {
    if (empty($name)) {
        throw new Exception("Goal name is required");
    }
    
    if (!is_numeric($target_amount) || $target_amount <= 0) {
        throw new Exception("Target amount must be a positive number");
    }
    
    if ($deadline !== null && !strtotime($deadline)) {
        throw new Exception("Invalid deadline format");
    }
    
    if (!is_numeric($current_savings) || $current_savings < 0) {
        throw new Exception("Current savings must be a positive number");
    }
    
    if ($current_savings > $target_amount) {
        throw new Exception("Current savings cannot exceed target amount");
    }
}
?>