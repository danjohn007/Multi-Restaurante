<?php
/**
 * Base Controller Class
 */
class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    protected function loadModel($model) {
        $modelFile = __DIR__ . '/models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        throw new Exception("Model $model not found");
    }
    
    protected function loadView($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View $view not found");
        }
    }
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit;
    }
    
    protected function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
    protected function requireAuth($role = null) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
        
        if ($role && $_SESSION['user_role'] !== $role) {
            $this->redirect('auth/unauthorized');
        }
        
        return $_SESSION;
    }
    
    protected function isLoggedIn() {
        session_start();
        return isset($_SESSION['user_id']);
    }
}
?>