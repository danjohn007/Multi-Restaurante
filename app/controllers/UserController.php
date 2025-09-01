<?php
require_once __DIR__ . '/Controller.php';

class UserController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
    }
    
    public function manage() {
        $userModel = $this->loadModel('User');
        $restaurantModel = $this->loadModel('Restaurant');
        
        $users = $userModel->findAllWithRestaurants();
        $restaurants = $restaurantModel->getActive();
        
        $data = [
            'title' => 'Gestión de Usuarios',
            'users' => $users,
            'restaurants' => $restaurants
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('usuario/manage', $data);
        $this->loadView('layout/footer');
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userModel = $this->loadModel('User');
                
                // Validate required fields
                $required = ['first_name', 'last_name', 'username', 'email', 'password', 'role'];
                foreach ($required as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo {$field} es requerido");
                    }
                }
                
                // Check if username or email already exists
                if ($userModel->findOne(['username' => $_POST['username']])) {
                    throw new Exception('El nombre de usuario ya existe');
                }
                
                if ($userModel->findOne(['email' => $_POST['email']])) {
                    throw new Exception('El email ya existe');
                }
                
                // Prepare data
                $userData = [
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'role' => $_POST['role'],
                    'restaurant_id' => !empty($_POST['restaurant_id']) ? $_POST['restaurant_id'] : null,
                    'phone' => $_POST['phone'] ?? null,
                    'is_active' => isset($_POST['is_active']) ? 1 : 0
                ];
                
                $userId = $userModel->createUser($userData);
                
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Usuario creado exitosamente',
                        'user_id' => $userId
                    ]);
                } else {
                    $_SESSION['success'] = 'Usuario creado exitosamente';
                    $this->redirect('usuario/manage');
                }
                
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
                } else {
                    $_SESSION['error'] = $e->getMessage();
                    $this->redirect('usuario/manage');
                }
            }
        } else {
            $this->redirect('usuario/manage');
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userModel = $this->loadModel('User');
                $userId = $_POST['user_id'];
                
                if (!$userId) {
                    throw new Exception('ID de usuario requerido');
                }
                
                // Prepare update data
                $updateData = [
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'role' => $_POST['role'],
                    'restaurant_id' => !empty($_POST['restaurant_id']) ? $_POST['restaurant_id'] : null,
                    'phone' => $_POST['phone'] ?? null,
                    'is_active' => isset($_POST['is_active']) ? 1 : 0
                ];
                
                // Check if username or email already exists for other users
                $existingUser = $userModel->findOne(['username' => $_POST['username']]);
                if ($existingUser && $existingUser['id'] != $userId) {
                    throw new Exception('El nombre de usuario ya existe');
                }
                
                $existingUser = $userModel->findOne(['email' => $_POST['email']]);
                if ($existingUser && $existingUser['id'] != $userId) {
                    throw new Exception('El email ya existe');
                }
                
                $userModel->update($userId, $updateData);
                
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Usuario actualizado exitosamente'
                    ]);
                } else {
                    $_SESSION['success'] = 'Usuario actualizado exitosamente';
                    $this->redirect('usuario/manage');
                }
                
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
                } else {
                    $_SESSION['error'] = $e->getMessage();
                    $this->redirect('usuario/manage');
                }
            }
        } else {
            $this->redirect('usuario/manage');
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userModel = $this->loadModel('User');
                $userId = $_POST['user_id'];
                
                if (!$userId) {
                    throw new Exception('ID de usuario requerido');
                }
                
                // Prevent deletion of current user
                if ($userId == $_SESSION['user']['id']) {
                    throw new Exception('No puedes eliminar tu propio usuario');
                }
                
                $userModel->delete($userId);
                
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Usuario eliminado exitosamente'
                    ]);
                } else {
                    $_SESSION['success'] = 'Usuario eliminado exitosamente';
                    $this->redirect('usuario/manage');
                }
                
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
                } else {
                    $_SESSION['error'] = $e->getMessage();
                    $this->redirect('usuario/manage');
                }
            }
        } else {
            $this->redirect('usuario/manage');
        }
    }
    
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userModel = $this->loadModel('User');
                $userId = $_POST['user_id'];
                $status = $_POST['status'];
                
                if (!$userId) {
                    throw new Exception('ID de usuario requerido');
                }
                
                // Prevent suspension of current user
                if ($userId == $_SESSION['user']['id'] && $status == '0') {
                    throw new Exception('No puedes suspender tu propio usuario');
                }
                
                $userModel->update($userId, ['is_active' => $status]);
                
                $statusText = $status == '1' ? 'activado' : 'suspendido';
                
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => "Usuario {$statusText} exitosamente"
                    ]);
                } else {
                    $_SESSION['success'] = "Usuario {$statusText} exitosamente";
                    $this->redirect('usuario/manage');
                }
                
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
                } else {
                    $_SESSION['error'] = $e->getMessage();
                    $this->redirect('usuario/manage');
                }
            }
        } else {
            $this->redirect('usuario/manage');
        }
    }
    
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userModel = $this->loadModel('User');
                $userId = $_POST['user_id'];
                $newPassword = $_POST['new_password'];
                
                if (!$userId || !$newPassword) {
                    throw new Exception('ID de usuario y nueva contraseña son requeridos');
                }
                
                if (strlen($newPassword) < 6) {
                    throw new Exception('La contraseña debe tener al menos 6 caracteres');
                }
                
                $userModel->updatePassword($userId, $newPassword);
                
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Contraseña actualizada exitosamente'
                    ]);
                } else {
                    $_SESSION['success'] = 'Contraseña actualizada exitosamente';
                    $this->redirect('usuario/manage');
                }
                
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
                } else {
                    $_SESSION['error'] = $e->getMessage();
                    $this->redirect('usuario/manage');
                }
            }
        } else {
            $this->redirect('usuario/manage');
        }
    }
    
    public function getUser($id) {
        $userModel = $this->loadModel('User');
        $user = $userModel->find($id);
        
        if ($user) {
            // Remove sensitive data
            unset($user['password_hash']);
            $this->jsonResponse(['success' => true, 'user' => $user]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    }
}
?>