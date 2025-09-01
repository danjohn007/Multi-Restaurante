<?php
/**
 * Test Database connection class with SQLite
 */
class TestDatabase {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            // Use SQLite in memory for testing
            $this->connection = new PDO(
                "sqlite::memory:",
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            // Create minimal tables for testing
            $this->initTables();
        } catch (PDOException $e) {
            throw new Exception("Test database connection failed: " . $e->getMessage());
        }
    }
    
    private function initTables() {
        $tables = [
            "CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE,
                email TEXT UNIQUE,
                password_hash TEXT,
                role TEXT,
                restaurant_id INTEGER,
                first_name TEXT,
                last_name TEXT,
                phone TEXT,
                is_active INTEGER DEFAULT 1,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE restaurants (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT,
                description TEXT,
                food_type TEXT,
                keywords TEXT,
                address TEXT,
                phone TEXT,
                email TEXT,
                opening_time TEXT,
                closing_time TEXT,
                is_active INTEGER DEFAULT 1,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )",
            "INSERT INTO users (username, email, password_hash, role, first_name, last_name) VALUES 
                ('superadmin', 'superadmin@test.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin', 'Test', 'Admin')",
            "INSERT INTO restaurants (name, description, food_type, keywords) VALUES 
                ('Test Restaurant', 'Test Description', 'Test Food', 'test,keywords')"
        ];
        
        foreach ($tables as $sql) {
            $this->connection->exec($sql);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function testConnection() {
        try {
            $stmt = $this->connection->query("SELECT 1 as test");
            return $stmt->fetch()['test'] === 1;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>