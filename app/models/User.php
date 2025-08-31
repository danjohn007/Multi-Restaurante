<?php
require_once __DIR__ . '/Model.php';

class User extends Model {
    protected $table = 'users';
    
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        
        return false;
    }
    
    public function findByRestaurant($restaurantId) {
        return $this->findAll(['restaurant_id' => $restaurantId]);
    }
    
    public function findByRole($role) {
        return $this->findAll(['role' => $role]);
    }
    
    public function createUser($data) {
        // Hash password before saving
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        return $this->create($data);
    }
    
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password_hash' => $hashedPassword]);
    }
    
    public function isActive($userId) {
        $user = $this->find($userId);
        return $user && $user['is_active'] == 1;
    }
}
?>