<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

public function __construct() {
    // Load .env once
    if (!getenv('DB_HOST')) {
        require_once __DIR__ . '/../env-loader.php';
        loadEnv(__DIR__ . '/../.env');
    }

    $this->host = getenv('DB_HOST');
    $this->db_name = getenv('DB_NAME');
    $this->username = getenv('DB_USER');
    $this->password = getenv('DB_PASS');

    if (!$this->db_name) {
        throw new Exception("No database selected: DB_NAME not set in .env");
    }
}


    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
    throw new Exception("Database connection failed: " . $e->getMessage());
}


        return $this->conn;
    }
}
?>