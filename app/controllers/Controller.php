<?php
/**
 * Base Controller Class
 */
class Controller {
    protected $db;
    
    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            // Database connection failed - this will be handled at a higher level
            $this->db = null;
        }
    }
    
    protected function loadModel($model) {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        throw new Exception("Model $model not found");
    }
    
    protected function loadView($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View $view not found");
        }
    }
    
    protected function redirect($url) {
        if (!headers_sent()) {
            header('Location: ' . BASE_URL . ltrim($url, '/'));
        }
        exit;
    }
    
    protected function jsonResponse($data, $status = 200) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
            http_response_code($status);
        }
        echo json_encode($data);
        exit;
    }
    
    protected function requireAuth($role = null) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
        
        if ($role) {
            // Support both single role string and array of roles
            $allowedRoles = is_array($role) ? $role : [$role];
            $userRole = $_SESSION['user_role'] ?? '';
            
            if (!in_array($userRole, $allowedRoles)) {
                $this->redirect('auth/unauthorized');
            }
        }
        
        return $_SESSION;
    }
    
    protected function isLoggedIn() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }
    
    protected function getUserRestaurantId() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return $_SESSION['restaurant_id'] ?? null;
    }
}
?>