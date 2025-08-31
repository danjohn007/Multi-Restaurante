<?php
require_once __DIR__ . '/Controller.php';

class SuperadminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth('superadmin');
    }
    
    public function dashboard() {
        $restaurantModel = $this->loadModel('Restaurant');
        $userModel = $this->loadModel('User');
        
        // Get statistics
        $stats = [
            'total_restaurants' => $restaurantModel->count(['is_active' => 1]),
            'total_admins' => $userModel->count(['role' => 'admin']),
            'total_hostess' => $userModel->count(['role' => 'hostess']),
            'total_reservations' => $this->getTotalReservations()
        ];
        
        // Get recent restaurants
        $recentRestaurants = $restaurantModel->findAll(['is_active' => 1], 'created_at DESC', 5);
        
        // Get restaurant stats
        $restaurantStats = $restaurantModel->getWithStats();
        
        $data = [
            'title' => 'Panel Superadmin - Multi-Restaurante',
            'stats' => $stats,
            'recentRestaurants' => $recentRestaurants,
            'restaurantStats' => $restaurantStats
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/dashboard', $data);
        $this->loadView('layout/footer');
    }
    
    public function restaurants() {
        $restaurantModel = $this->loadModel('Restaurant');
        $restaurants = $restaurantModel->getWithStats();
        
        $data = [
            'title' => 'Gestión de Restaurantes - Superadmin',
            'restaurants' => $restaurants
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/restaurants', $data);
        $this->loadView('layout/footer');
    }
    
    public function createRestaurant() {
        $data = [
            'title' => 'Crear Restaurante - Superadmin'
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/create_restaurant', $data);
        $this->loadView('layout/footer');
    }
    
    public function storeRestaurant() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('superadmin/restaurants');
        }
        
        $restaurantModel = $this->loadModel('Restaurant');
        $userModel = $this->loadModel('User');
        
        try {
            $this->db->beginTransaction();
            
            // Create restaurant
            $restaurantData = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'food_type' => $_POST['food_type'],
                'keywords' => $_POST['keywords'],
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'opening_time' => $_POST['opening_time'],
                'closing_time' => $_POST['closing_time']
            ];
            
            $restaurantId = $restaurantModel->create($restaurantData);
            
            // Create admin user for restaurant
            if (!empty($_POST['admin_username'])) {
                $adminData = [
                    'username' => $_POST['admin_username'],
                    'email' => $_POST['admin_email'],
                    'password' => $_POST['admin_password'],
                    'role' => 'admin',
                    'restaurant_id' => $restaurantId,
                    'first_name' => $_POST['admin_first_name'],
                    'last_name' => $_POST['admin_last_name'],
                    'phone' => $_POST['admin_phone']
                ];
                
                $userModel->createUser($adminData);
            }
            
            $this->db->commit();
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => true, 'message' => 'Restaurante creado exitosamente']);
            } else {
                $_SESSION['success'] = 'Restaurante creado exitosamente';
                $this->redirect('superadmin/restaurants');
            }
            
        } catch (Exception $e) {
            $this->db->rollBack();
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => false, 'message' => 'Error al crear restaurante: ' . $e->getMessage()]);
            } else {
                $_SESSION['error'] = 'Error al crear restaurante: ' . $e->getMessage();
                $this->redirect('superadmin/restaurants/create');
            }
        }
    }
    
    public function editRestaurant($id) {
        $restaurantModel = $this->loadModel('Restaurant');
        $restaurant = $restaurantModel->find($id);
        
        if (!$restaurant) {
            $_SESSION['error'] = 'Restaurante no encontrado';
            $this->redirect('superadmin/restaurants');
        }
        
        $data = [
            'title' => 'Editar Restaurante - Superadmin',
            'restaurant' => $restaurant
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/edit_restaurant', $data);
        $this->loadView('layout/footer');
    }
    
    public function updateRestaurant($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('superadmin/restaurants');
        }
        
        $restaurantModel = $this->loadModel('Restaurant');
        
        try {
            $restaurantData = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'food_type' => $_POST['food_type'],
                'keywords' => $_POST['keywords'],
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'opening_time' => $_POST['opening_time'],
                'closing_time' => $_POST['closing_time'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            $restaurantModel->update($id, $restaurantData);
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => true, 'message' => 'Restaurante actualizado exitosamente']);
            } else {
                $_SESSION['success'] = 'Restaurante actualizado exitosamente';
                $this->redirect('superadmin/restaurants');
            }
            
        } catch (Exception $e) {
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => false, 'message' => 'Error al actualizar restaurante: ' . $e->getMessage()]);
            } else {
                $_SESSION['error'] = 'Error al actualizar restaurante: ' . $e->getMessage();
                $this->redirect('superadmin/restaurants/' . $id . '/edit');
            }
        }
    }
    
    public function globalMetrics() {
        // Get global metrics
        $metrics = [
            'total_revenue' => $this->getTotalRevenue(),
            'reservations_today' => $this->getReservationsToday(),
            'top_restaurants' => $this->getTopRestaurants(),
            'monthly_stats' => $this->getMonthlyStats()
        ];
        
        $data = [
            'title' => 'Métricas Globales - Superadmin',
            'metrics' => $metrics
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/metrics', $data);
        $this->loadView('layout/footer');
    }
    
    private function getTotalReservations() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM reservations");
        return $stmt->fetchColumn();
    }
    
    private function getTotalRevenue() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) FROM bills WHERE closed_at IS NOT NULL");
        return $stmt->fetchColumn();
    }
    
    private function getReservationsToday() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservations WHERE reservation_date = CURDATE()");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    private function getTopRestaurants() {
        $stmt = $this->db->prepare("
            SELECT r.name, r.id, COUNT(res.id) as reservations, COALESCE(SUM(b.total_amount), 0) as revenue
            FROM restaurants r
            LEFT JOIN reservations res ON r.id = res.restaurant_id
            LEFT JOIN bills b ON res.id = b.reservation_id
            WHERE r.is_active = 1
            GROUP BY r.id, r.name
            ORDER BY revenue DESC
            LIMIT 10
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function getMonthlyStats() {
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(reservation_date, '%Y-%m') as month,
                COUNT(*) as reservations,
                COALESCE(SUM(b.total_amount), 0) as revenue
            FROM reservations res
            LEFT JOIN bills b ON res.id = b.reservation_id
            WHERE reservation_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(reservation_date, '%Y-%m')
            ORDER BY month DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>