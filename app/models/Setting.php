<?php
require_once __DIR__ . '/Model.php';

class Setting extends Model {
    protected $table = 'settings';
    
    public function __construct() {
        parent::__construct();
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(255) UNIQUE NOT NULL,
            setting_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        try {
            $this->db->exec($sql);
        } catch (Exception $e) {
            // Table creation failed, but continue - settings will use defaults
            error_log("Failed to create settings table: " . $e->getMessage());
        }
    }
    
    public function setSetting($key, $value) {
        $sql = "INSERT INTO {$this->table} (setting_key, setting_value) 
                VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE setting_value = :value, updated_at = CURRENT_TIMESTAMP";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':key' => $key,
            ':value' => $value
        ]);
    }
    
    public function getSetting($key, $default = null) {
        $sql = "SELECT setting_value FROM {$this->table} WHERE setting_key = :key";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':key' => $key]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['setting_value'] : $default;
    }
    
    public function getAllSettings() {
        $sql = "SELECT setting_key, setting_value FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $settings = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        return $settings;
    }
    
    public function deleteSetting($key) {
        $sql = "DELETE FROM {$this->table} WHERE setting_key = :key";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':key' => $key]);
    }
}
?>