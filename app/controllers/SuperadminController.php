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
            'total_reservations' => $this->getTotalReservations(),
            'monthly_stats' => $this->getMonthlyStats()
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
    
    public function inactiveRestaurants() {
        $restaurantModel = $this->loadModel('Restaurant');
        $restaurants = $restaurantModel->getInactiveWithStats();
        
        $data = [
            'title' => 'Restaurantes Inactivos - Superadmin',
            'restaurants' => $restaurants
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/inactive_restaurants', $data);
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
            
            // Handle image upload
            if (isset($_FILES['restaurant_image']) && $_FILES['restaurant_image']['error'] === UPLOAD_ERR_OK) {
                $uploadedFile = $this->handleImageUpload($_FILES['restaurant_image'], $id);
                if ($uploadedFile) {
                    $restaurantData['logo_url'] = $uploadedFile;
                }
            }
            
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
    
    public function updateKeywordsCompat() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido'], 405);
        }
        
        try {
            $restaurantModel = $this->loadModel('Restaurant');
            $restaurantId = $_POST['restaurant_id'] ?? null;
            
            if (!$restaurantId) {
                $this->jsonResponse(['success' => false, 'message' => 'ID de restaurante requerido'], 400);
            }
            
            $restaurant = $restaurantModel->find($restaurantId);
            
            if (!$restaurant) {
                $this->jsonResponse(['success' => false, 'message' => 'Restaurante no encontrado'], 404);
            }
            
            $keywords = $_POST['keywords'] ?? '';
            $restaurantModel->update($restaurantId, ['keywords' => $keywords]);
            
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
        $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        $foodType = $_GET['food_type'] ?? '';
        $restaurantId = $_GET['restaurant_id'] ?? '';
        $keyword = $_GET['keyword'] ?? '';
        
        // Get enhanced metrics with filters
        $metrics = [
            'total_revenue' => $this->getTotalRevenue($dateFrom, $dateTo, $foodType, $restaurantId, $keyword),
            'reservations_today' => $this->getReservationsToday(),
            'top_restaurants' => $this->getTopRestaurants($dateFrom, $dateTo, $foodType, $keyword),
            'monthly_stats' => $this->getMonthlyStats($dateFrom, $dateTo, $foodType, $restaurantId, $keyword),
            'sales_by_cuisine' => $this->getSalesByCuisine($dateFrom, $dateTo, $restaurantId, $keyword),
            'all_restaurants' => $this->getAllActiveRestaurants()
        ];
        
        // Handle AJAX requests
        if (isset($_GET['ajax'])) {
            $this->jsonResponse(['success' => true, 'metrics' => $metrics]);
            return;
        }
        
        $data = [
            'title' => 'Métricas Globales - Superadmin',
            'metrics' => $metrics
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('superadmin/metrics', $data);
        $this->loadView('layout/footer');
    }
    
    public function users() {
        // Redirect to the UserController manage method instead
        $this->redirect('usuario/manage');
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
    
    private function getTotalRevenue($dateFrom = null, $dateTo = null, $foodType = '', $restaurantId = '', $keyword = '') {
        $whereConditions = ['b.closed_at IS NOT NULL'];
        $params = [];
        
        if ($dateFrom && $dateTo) {
            $whereConditions[] = 'res.reservation_date BETWEEN ? AND ?';
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        
        if ($foodType) {
            $whereConditions[] = 'r.food_type = ?';
            $params[] = $foodType;
        }
        
        if ($restaurantId) {
            $whereConditions[] = 'r.id = ?';
            $params[] = $restaurantId;
        }
        
        if ($keyword) {
            $whereConditions[] = '(r.name LIKE ? OR r.description LIKE ? OR r.keywords LIKE ?)';
            $keywordParam = '%' . $keyword . '%';
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.total_amount), 0) 
            FROM bills b 
            JOIN reservations res ON b.reservation_id = res.id
            JOIN restaurants r ON res.restaurant_id = r.id
            WHERE $whereClause
        ");
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    private function getReservationsToday() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservations WHERE reservation_date = CURDATE()");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    private function getTopRestaurants($dateFrom = null, $dateTo = null, $foodType = '', $keyword = '') {
        $whereConditions = ['r.is_active = 1'];
        $params = [];
        
        if ($dateFrom && $dateTo) {
            $whereConditions[] = 'res.reservation_date BETWEEN ? AND ?';
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        
        if ($foodType) {
            $whereConditions[] = 'r.food_type = ?';
            $params[] = $foodType;
        }
        
        if ($keyword) {
            $whereConditions[] = '(r.name LIKE ? OR r.description LIKE ? OR r.keywords LIKE ?)';
            $keywordParam = '%' . $keyword . '%';
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $stmt = $this->db->prepare("
            SELECT r.name, r.id, COUNT(res.id) as reservations, COALESCE(SUM(b.total_amount), 0) as revenue
            FROM restaurants r
            LEFT JOIN reservations res ON r.id = res.restaurant_id
            LEFT JOIN bills b ON res.id = b.reservation_id
            WHERE $whereClause
            GROUP BY r.id, r.name
            ORDER BY revenue DESC
            LIMIT 10
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getMonthlyStats($dateFrom = null, $dateTo = null, $foodType = '', $restaurantId = '', $keyword = '') {
        $whereConditions = [];
        $params = [];
        
        if ($dateFrom && $dateTo) {
            $whereConditions[] = 'res.reservation_date BETWEEN ? AND ?';
            $params[] = $dateFrom;
            $params[] = $dateTo;
        } else {
            $whereConditions[] = 'res.reservation_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)';
        }
        
        if ($foodType) {
            $whereConditions[] = 'r.food_type = ?';
            $params[] = $foodType;
        }
        
        if ($restaurantId) {
            $whereConditions[] = 'r.id = ?';
            $params[] = $restaurantId;
        }
        
        if ($keyword) {
            $whereConditions[] = '(r.name LIKE ? OR r.description LIKE ? OR r.keywords LIKE ?)';
            $keywordParam = '%' . $keyword . '%';
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }
        
        $whereClause = $whereConditions ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(res.reservation_date, '%Y-%m') as month,
                COUNT(*) as reservations,
                COALESCE(SUM(b.total_amount), 0) as revenue
            FROM reservations res
            LEFT JOIN bills b ON res.id = b.reservation_id
            LEFT JOIN restaurants r ON res.restaurant_id = r.id
            $whereClause
            GROUP BY DATE_FORMAT(res.reservation_date, '%Y-%m')
            ORDER BY month DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getSalesByCuisine($dateFrom = null, $dateTo = null, $restaurantId = '', $keyword = '') {
        $whereConditions = ['r.is_active = 1'];
        $params = [];
        
        if ($dateFrom && $dateTo) {
            $whereConditions[] = 'res.reservation_date BETWEEN ? AND ?';
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        
        if ($restaurantId) {
            $whereConditions[] = 'r.id = ?';
            $params[] = $restaurantId;
        }
        
        if ($keyword) {
            $whereConditions[] = '(r.name LIKE ? OR r.description LIKE ? OR r.keywords LIKE ?)';
            $keywordParam = '%' . $keyword . '%';
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $stmt = $this->db->prepare("
            SELECT r.food_type, COALESCE(SUM(b.total_amount), 0) as revenue, COUNT(res.id) as reservations
            FROM restaurants r
            LEFT JOIN reservations res ON r.id = res.restaurant_id
            LEFT JOIN bills b ON res.id = b.reservation_id
            WHERE $whereClause AND r.food_type IS NOT NULL
            GROUP BY r.food_type
            ORDER BY revenue DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getAllActiveRestaurants() {
        $stmt = $this->db->prepare("SELECT id, name FROM restaurants WHERE is_active = 1 ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function handleImageUpload($file, $restaurantId) {
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten JPG y PNG.');
        }
        
        // Validate file size (2MB max)
        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file['size'] > $maxSize) {
            throw new Exception('El archivo es demasiado grande. Tamaño máximo: 2MB.');
        }
        
        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../../public/uploads/restaurants/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'restaurant_' . $restaurantId . '_' . time() . '.' . strtolower($extension);
        $uploadPath = $uploadDir . $filename;
        
        // Remove old image if exists
        $restaurantModel = $this->loadModel('Restaurant');
        $currentRestaurant = $restaurantModel->find($restaurantId);
        if ($currentRestaurant && !empty($currentRestaurant['logo_url'])) {
            $oldImagePath = $uploadDir . $currentRestaurant['logo_url'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $filename;
        } else {
            throw new Exception('Error al subir la imagen.');
        }
    }
}
?>