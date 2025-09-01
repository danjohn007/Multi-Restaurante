<?php
require_once __DIR__ . '/Controller.php';

class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        // Allow public access for admin-public routes, but require auth for private routes
        if (!$this->isPublicRoute()) {
            $this->requireAuth(['admin']);
        }
    }
    
    private function isPublicRoute() {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/public/hostess-public/admin') !== false;
    }
    
    public function dashboard() {
        $restaurantId = $this->getUserRestaurantId();
        
        if (!$restaurantId) {
            $_SESSION['error'] = 'No tienes un restaurante asignado. Contacta al superadministrador.';
            $this->redirect('auth/login');
            return;
        }
        
        $restaurantModel = $this->loadModel('Restaurant');
        $reservationModel = $this->loadModel('Reservation');
        $tableModel = $this->loadModel('Table');
        $userModel = $this->loadModel('User');
        
        $restaurant = $restaurantModel->find($restaurantId);
        
        // Get statistics
        $stats = [
            'total_tables' => $tableModel->count(['restaurant_id' => $restaurantId, 'is_active' => 1]),
            'total_reservations' => $reservationModel->count(['restaurant_id' => $restaurantId]),
            'today_reservations' => $reservationModel->getTodayCount($restaurantId),
            'total_staff' => $userModel->count(['restaurant_id' => $restaurantId, 'is_active' => 1])
        ];
        
        // Get recent reservations
        $recentReservations = $reservationModel->getRecent($restaurantId, 5);
        
        $data = [
            'title' => 'Panel Administrador - ' . $restaurant['name'],
            'restaurant' => $restaurant,
            'stats' => $stats,
            'recentReservations' => $recentReservations
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('admin/dashboard', $data);
        $this->loadView('layout/footer');
    }
    
    public function profile() {
        $restaurantId = $this->getUserRestaurantId();
        $restaurantModel = $this->loadModel('Restaurant');
        
        $restaurant = $restaurantModel->find($restaurantId);
        
        if (!$restaurant) {
            $_SESSION['error'] = 'Restaurante no encontrado';
            $this->redirect('admin');
            return;
        }
        
        $data = [
            'title' => 'Perfil del Restaurante',
            'restaurant' => $restaurant
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('admin/profile', $data);
        $this->loadView('layout/footer');
    }
    
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/profile');
        }
        
        try {
            $restaurantId = $this->getUserRestaurantId();
            $restaurantModel = $this->loadModel('Restaurant');
            
            // Validate required fields
            $required = ['name', 'description', 'address', 'phone', 'email'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo {$field} es requerido");
                }
            }
            
            $updateData = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'food_type' => $_POST['food_type'],
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'opening_time' => $_POST['opening_time'],
                'closing_time' => $_POST['closing_time'],
                'keywords' => $_POST['keywords'] ?? ''
            ];
            
            $restaurantModel->update($restaurantId, $updateData);
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Perfil actualizado exitosamente'
                ]);
            } else {
                $_SESSION['success'] = 'Perfil actualizado exitosamente';
                $this->redirect('admin/profile');
            }
            
        } catch (Exception $e) {
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('admin/profile');
            }
        }
    }
    
    public function tables() {
        $restaurantId = $this->getUserRestaurantId();
        $tableModel = $this->loadModel('Table');
        
        $tables = $tableModel->findAll(['restaurant_id' => $restaurantId], 'table_number ASC');
        
        $data = [
            'title' => 'Gestión de Mesas',
            'tables' => $tables
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('admin/tables', $data);
        $this->loadView('layout/footer');
    }
    
    public function createTable() {
        $data = [
            'title' => 'Crear Nueva Mesa'
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('admin/create_table', $data);
        $this->loadView('layout/footer');
    }
    
    public function storeTable() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/tables');
        }
        
        try {
            $restaurantId = $this->getUserRestaurantId();
            $tableModel = $this->loadModel('Table');
            
            // Validate required fields
            if (empty($_POST['table_number']) || empty($_POST['capacity'])) {
                throw new Exception('Número de mesa y capacidad son requeridos');
            }
            
            // Check if table number already exists
            $existingTable = $tableModel->findOne([
                'restaurant_id' => $restaurantId,
                'table_number' => $_POST['table_number']
            ]);
            
            if ($existingTable) {
                throw new Exception('El número de mesa ya existe');
            }
            
            $tableData = [
                'restaurant_id' => $restaurantId,
                'table_number' => $_POST['table_number'],
                'capacity' => $_POST['capacity'],
                'location' => $_POST['location'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            $tableId = $tableModel->create($tableData);
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Mesa creada exitosamente',
                    'table_id' => $tableId
                ]);
            } else {
                $_SESSION['success'] = 'Mesa creada exitosamente';
                $this->redirect('admin/tables');
            }
            
        } catch (Exception $e) {
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('admin/tables/create');
            }
        }
    }
    
    public function users() {
        $restaurantId = $this->getUserRestaurantId();
        $userModel = $this->loadModel('User');
        
        $users = $userModel->findByRestaurant($restaurantId);
        
        $data = [
            'title' => 'Gestión de Personal',
            'users' => $users
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('admin/users', $data);
        $this->loadView('layout/footer');
    }
    
    public function reports() {
        $restaurantId = $this->getUserRestaurantId();
        $reservationModel = $this->loadModel('Reservation');
        $billModel = $this->loadModel('Bill');
        
        // Get date filters
        $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        
        // Get report data
        $reservationStats = $reservationModel->getStatsByDateRange($restaurantId, $dateFrom, $dateTo);
        $revenueStats = $billModel->getRevenueByDateRange($restaurantId, $dateFrom, $dateTo);
        
        $data = [
            'title' => 'Reportes y Estadísticas',
            'reservationStats' => $reservationStats,
            'revenueStats' => $revenueStats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('admin/reports', $data);
        $this->loadView('layout/footer');
    }
}
?>