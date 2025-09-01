<?php
require_once __DIR__ . '/Controller.php';

class AuthController extends Controller {
    
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['user_role']);
        }
        
        $data = [
            'title' => 'Iniciar Sesión - Multi-Restaurante',
            'error' => $_SESSION['login_error'] ?? null
        ];
        
        unset($_SESSION['login_error']);
        
        $this->loadView('layout/header', $data);
        $this->loadView('auth/login', $data);
        $this->loadView('layout/footer');
    }
    
    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/login');
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'Por favor, ingrese usuario y contraseña';
            $this->redirect('auth/login');
        }
        
        $userModel = $this->loadModel('User');
        $user = $userModel->authenticate($username, $password);
        
        if ($user && $user['is_active']) {
            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['restaurant_id'] = $user['restaurant_id'];
            $_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
            
            // Redirect to appropriate dashboard
            $this->redirectToDashboard($user['role']);
        } else {
            $_SESSION['login_error'] = 'Credenciales incorrectas o usuario inactivo';
            $this->redirect('auth/login');
        }
    }
    
    public function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        $this->redirect('');
    }
    
    public function unauthorized() {
        $data = [
            'title' => 'Acceso No Autorizado - Multi-Restaurante'
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('auth/unauthorized', $data);
        $this->loadView('layout/footer');
    }
    
    private function redirectToDashboard($role) {
        switch ($role) {
            case 'superadmin':
                $this->redirect('superadmin');
                break;
            case 'admin':
                $this->redirect('admin');
                break;
            case 'hostess':
                $this->redirect('hostess');
                break;
            default:
                $this->redirect('');
        }
    }
}
?>