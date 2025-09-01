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
        
        // Get chart data for dashboard
        $chartData = [
            'reservation_dates' => $this->getReservationTrendDates(),
            'reservation_counts' => $this->getReservationTrendCounts(), 
            'restaurant_names' => $this->getRestaurantNames(),
            'restaurant_revenues' => $this->getRestaurantRevenues()
        ];
        
        $data = [
            'title' => 'Panel Superadmin - Multi-Restaurante',
            'stats' => $stats,
            'recentRestaurants' => $recentRestaurants,
            'restaurantStats' => $restaurantStats,
            'chartData' => $chartData
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
    
    public function updateKeywords($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido'], 405);
        }
        
        try {
            $restaurantModel = $this->loadModel('Restaurant');
            $restaurant = $restaurantModel->find($id);
            
            if (!$restaurant) {
                $this->jsonResponse(['success' => false, 'message' => 'Restaurante no encontrado'], 404);
            }
            
            $keywords = $_POST['keywords'] ?? '';
            $restaurantModel->update($id, ['keywords' => $keywords]);
            
            $this->jsonResponse(['success' => true, 'message' => 'Keywords actualizadas exitosamente']);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Error al actualizar keywords: ' . $e->getMessage()], 500);
        }
    }
    
    public function toggleStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido'], 405);
        }
        
        try {
            $restaurantModel = $this->loadModel('Restaurant');
            $restaurant = $restaurantModel->find($id);
            
            if (!$restaurant) {
                $this->jsonResponse(['success' => false, 'message' => 'Restaurante no encontrado'], 404);
            }
            
            $newStatus = $_POST['status'] ?? '';
            $isActive = ($newStatus === 'active') ? 1 : 0;
            
            $restaurantModel->update($id, ['is_active' => $isActive]);
            
            $action = $isActive ? 'activa' : 'desactiva';
            $this->jsonResponse(['success' => true, 'message' => "Restaurante {$action}do exitosamente"]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Error al cambiar estado: ' . $e->getMessage()], 500);
        }
    }
    
    public function globalMetrics() {
        // Get filter parameters
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-m-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-d'),
            'restaurant_id' => $_GET['restaurant_id'] ?? null,
            'food_type' => $_GET['food_type'] ?? null,
            'keywords' => $_GET['keywords'] ?? null
        ];
        
        // Get global metrics with filters
        $metrics = [
            'total_revenue' => $this->getTotalRevenue($filters),
            'reservations_today' => $this->getReservationsToday($filters),
            'top_restaurants' => $this->getTopRestaurants($filters),
            'monthly_stats' => $this->getMonthlyStats($filters),
            'total_restaurants' => $this->getTotalRestaurantsFiltered($filters),
            'total_reservations' => $this->getTotalReservationsFiltered($filters),
            'total_customers' => $this->getTotalCustomersFiltered($filters),
            'avg_occupancy' => $this->getAverageOccupancy($filters),
            'chart_data' => $this->getChartData($filters),
            'recent_activity' => $this->getRecentActivity($filters)
        ];
        
        // Get filter data for dropdowns
        $filterData = [
            'restaurants' => $this->getRestaurantsForFilter(),
            'food_types' => $this->getFoodTypesForFilter()
        ];
        
        // Handle AJAX requests
        if (isset($_GET['ajax'])) {
            $this->jsonResponse([
                'success' => true,
                'metrics' => $metrics
            ]);
            return;
        }
        
        $data = [
            'title' => 'Métricas Globales - Superadmin',
            'metrics' => $metrics,
            'filterData' => $filterData,
            'currentFilters' => $filters
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/metrics', $data);
        $this->loadView('layout/footer');
    }
    
    public function users() {
        $userModel = $this->loadModel('User');
        $users = $userModel->findAll([], 'created_at DESC');
        
        $data = [
            'title' => 'Gestionar Usuarios - Superadmin',
            'users' => $users
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/manage_users', $data);
        $this->loadView('layout/footer');
    }
    
    public function settings() {
        $data = [
            'title' => 'Configuración del Sistema - Superadmin'
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/system_config', $data);
        $this->loadView('layout/footer');
    }
    
    private function getTotalReservations() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM reservations");
        return $stmt->fetchColumn();
    }
    
    private function getTotalRevenue($filters = []) {
        $sql = "SELECT COALESCE(SUM(total_amount), 0) FROM bills b 
                JOIN reservations r ON b.reservation_id = r.id 
                JOIN restaurants res ON r.restaurant_id = res.id 
                WHERE b.closed_at IS NOT NULL";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(b.closed_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(b.closed_at) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND res.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (res.name LIKE ? OR res.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    private function getReservationsToday($filters = []) {
        $sql = "SELECT COUNT(*) FROM reservations r 
                JOIN restaurants res ON r.restaurant_id = res.id 
                WHERE r.reservation_date = CURDATE()";
        $params = [];
        
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND res.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (res.name LIKE ? OR res.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    private function getTopRestaurants($filters = []) {
        $sql = "SELECT r.name, r.id, COUNT(res.id) as reservations, COALESCE(SUM(b.total_amount), 0) as revenue
                FROM restaurants r
                LEFT JOIN reservations res ON r.id = res.restaurant_id
                LEFT JOIN bills b ON res.id = b.reservation_id
                WHERE r.is_active = 1";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(res.reservation_date) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(res.reservation_date) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND r.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (r.name LIKE ? OR r.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $sql .= " GROUP BY r.id, r.name ORDER BY revenue DESC LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getMonthlyStats($filters = []) {
        $sql = "SELECT 
                    DATE_FORMAT(reservation_date, '%Y-%m') as month,
                    COUNT(*) as reservations,
                    COALESCE(SUM(b.total_amount), 0) as revenue
                FROM reservations res
                LEFT JOIN bills b ON res.id = b.reservation_id
                LEFT JOIN restaurants r ON res.restaurant_id = r.id
                WHERE res.reservation_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(res.reservation_date) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(res.reservation_date) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND res.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND r.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (r.name LIKE ? OR r.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $sql .= " GROUP BY DATE_FORMAT(reservation_date, '%Y-%m') ORDER BY month DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getReservationTrendDates() {
        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(reservation_date, '%d/%m') as date_label
            FROM reservations 
            WHERE reservation_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY reservation_date
            ORDER BY reservation_date ASC
        ");
        $stmt->execute();
        $results = $stmt->fetchAll();
        return array_column($results, 'date_label');
    }
    
    private function getReservationTrendCounts() {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reservations 
            WHERE reservation_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY reservation_date
            ORDER BY reservation_date ASC
        ");
        $stmt->execute();
        $results = $stmt->fetchAll();
        return array_column($results, 'count');
    }
    
    private function getRestaurantNames() {
        $stmt = $this->db->prepare("
            SELECT r.name
            FROM restaurants r
            WHERE r.is_active = 1
            ORDER BY r.name
        ");
        $stmt->execute();
        $results = $stmt->fetchAll();
        return array_column($results, 'name');
    }
    
    private function getRestaurantRevenues() {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.total_amount), 0) as revenue
            FROM restaurants r
            LEFT JOIN reservations res ON r.id = res.restaurant_id
            LEFT JOIN bills b ON res.id = b.reservation_id AND b.closed_at IS NOT NULL
            WHERE r.is_active = 1
            GROUP BY r.id
            ORDER BY r.name
        ");
        $stmt->execute();
        $results = $stmt->fetchAll();
        return array_column($results, 'revenue');
    }
    
    private function getTotalRestaurantsFiltered($filters = []) {
        $sql = "SELECT COUNT(*) FROM restaurants r WHERE r.is_active = 1";
        $params = [];
        
        if (!empty($filters['food_type'])) {
            $sql .= " AND r.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (r.name LIKE ? OR r.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    private function getTotalReservationsFiltered($filters = []) {
        $sql = "SELECT COUNT(*) FROM reservations r 
                JOIN restaurants res ON r.restaurant_id = res.id WHERE 1=1";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(r.reservation_date) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(r.reservation_date) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND res.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (res.name LIKE ? OR res.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    private function getTotalCustomersFiltered($filters = []) {
        $sql = "SELECT COUNT(DISTINCT c.id) FROM customers c 
                JOIN reservations r ON c.id = r.customer_id 
                JOIN restaurants res ON r.restaurant_id = res.id WHERE 1=1";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(r.reservation_date) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(r.reservation_date) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND res.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (res.name LIKE ? OR res.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    private function getAverageOccupancy($filters = []) {
        // Calculate average occupancy based on reservations vs total table capacity
        $sql = "SELECT AVG(
                    (SELECT COUNT(*) FROM reservations res2 
                     WHERE res2.restaurant_id = r.id 
                     AND res2.reservation_date = CURDATE()
                     AND res2.status IN ('confirmed', 'seated', 'completed'))
                    /
                    GREATEST(1, (SELECT COUNT(*) FROM tables t 
                                WHERE t.restaurant_id = r.id 
                                AND t.is_active = 1))
                ) * 100 as avg_occupancy
                FROM restaurants r WHERE r.is_active = 1";
        $params = [];
        
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND r.food_type = ?";
            $params[] = $filters['food_type'];
        }
        if (!empty($filters['keywords'])) {
            $sql .= " AND (r.name LIKE ? OR r.keywords LIKE ?)";
            $keyword = '%' . $filters['keywords'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() ?: 0;
    }
    
    private function getChartData($filters = []) {
        return [
            'reservation_dates' => $this->getReservationTrendDatesFiltered($filters),
            'reservation_counts' => $this->getReservationTrendCountsFiltered($filters),
            'cuisine_labels' => $this->getCuisineLabelsFiltered($filters),
            'cuisine_counts' => $this->getCuisineCountsFiltered($filters),
            'hourly_labels' => $this->getHourlyLabels(),
            'hourly_counts' => $this->getHourlyCountsFiltered($filters)
        ];
    }
    
    private function getRecentActivity($filters = []) {
        $sql = "SELECT 
                    r.reservation_date as created_at,
                    'Reservación' as event_type,
                    res.name as restaurant_name,
                    r.customer_name as user_name,
                    CONCAT('Mesa para ', r.party_size, ' personas') as details
                FROM reservations r
                JOIN restaurants res ON r.restaurant_id = res.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(r.reservation_date) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(r.reservation_date) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        
        $sql .= " ORDER BY r.reservation_date DESC LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getRestaurantsForFilter() {
        $stmt = $this->db->prepare("SELECT id, name FROM restaurants WHERE is_active = 1 ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function getFoodTypesForFilter() {
        $stmt = $this->db->prepare("SELECT DISTINCT food_type FROM restaurants WHERE is_active = 1 AND food_type IS NOT NULL ORDER BY food_type");
        $stmt->execute();
        $results = $stmt->fetchAll();
        return array_column($results, 'food_type');
    }
    
    private function getReservationTrendDatesFiltered($filters = []) {
        $sql = "SELECT DATE_FORMAT(reservation_date, '%d/%m') as date_label
                FROM reservations r 
                JOIN restaurants res ON r.restaurant_id = res.id
                WHERE reservation_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $params = [];
        
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND res.food_type = ?";
            $params[] = $filters['food_type'];
        }
        
        $sql .= " GROUP BY reservation_date ORDER BY reservation_date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        return array_column($results, 'date_label');
    }
    
    private function getReservationTrendCountsFiltered($filters = []) {
        $sql = "SELECT COUNT(*) as count
                FROM reservations r 
                JOIN restaurants res ON r.restaurant_id = res.id
                WHERE reservation_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $params = [];
        
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND res.food_type = ?";
            $params[] = $filters['food_type'];
        }
        
        $sql .= " GROUP BY reservation_date ORDER BY reservation_date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        return array_column($results, 'count');
    }
    
    private function getCuisineLabelsFiltered($filters = []) {
        $sql = "SELECT DISTINCT food_type FROM restaurants WHERE is_active = 1 AND food_type IS NOT NULL";
        $params = [];
        
        if (!empty($filters['food_type'])) {
            $sql .= " AND food_type = ?";
            $params[] = $filters['food_type'];
        }
        
        $sql .= " ORDER BY food_type";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        return array_column($results, 'food_type');
    }
    
    private function getCuisineCountsFiltered($filters = []) {
        $sql = "SELECT COUNT(*) as count
                FROM restaurants r 
                WHERE r.is_active = 1 AND r.food_type IS NOT NULL";
        $params = [];
        
        if (!empty($filters['food_type'])) {
            $sql .= " AND r.food_type = ?";
            $params[] = $filters['food_type'];
        }
        
        $sql .= " GROUP BY r.food_type ORDER BY r.food_type";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        return array_column($results, 'count');
    }
    
    private function getHourlyLabels() {
        return ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'];
    }
    
    private function getHourlyCountsFiltered($filters = []) {
        $sql = "SELECT 
                    HOUR(reservation_time) as hour,
                    COUNT(*) as count
                FROM reservations r
                JOIN restaurants res ON r.restaurant_id = res.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(r.reservation_date) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(r.reservation_date) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['restaurant_id'])) {
            $sql .= " AND r.restaurant_id = ?";
            $params[] = $filters['restaurant_id'];
        }
        if (!empty($filters['food_type'])) {
            $sql .= " AND res.food_type = ?";
            $params[] = $filters['food_type'];
        }
        
        $sql .= " GROUP BY HOUR(reservation_time) ORDER BY HOUR(reservation_time)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        
        // Create array with all hours, filling in 0 for missing hours
        $hourCounts = array_fill(0, 12, 0); // 11 AM to 10 PM
        foreach ($results as $result) {
            $hourIndex = $result['hour'] - 11; // 11 AM = index 0
            if ($hourIndex >= 0 && $hourIndex < 12) {
                $hourCounts[$hourIndex] = $result['count'];
            }
        }
        return $hourCounts;
    }
}
?>