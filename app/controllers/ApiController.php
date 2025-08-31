<?php
require_once __DIR__ . '/Controller.php';

class ApiController extends Controller {
    
    public function searchRestaurants() {
        $query = $_GET['q'] ?? '';
        $foodType = $_GET['food_type'] ?? '';
        
        $restaurantModel = $this->loadModel('Restaurant');
        
        if (!empty($query)) {
            $restaurants = $restaurantModel->search($query);
        } else if (!empty($foodType)) {
            $restaurants = $restaurantModel->getByFoodType($foodType);
        } else {
            $restaurants = $restaurantModel->getActive();
        }
        
        $this->jsonResponse([
            'success' => true,
            'restaurants' => $restaurants,
            'count' => count($restaurants)
        ]);
    }
    
    public function checkAvailability($restaurantId) {
        $date = $_GET['date'] ?? date('Y-m-d');
        $time = $_GET['time'] ?? '19:00:00';
        $partySize = (int)($_GET['party_size'] ?? 2);
        
        // Validate inputs
        if (!$this->isValidDate($date) || !$this->isValidTime($time) || $partySize < 1) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Parámetros inválidos'
            ], 400);
            return;
        }
        
        // Check if restaurant exists and is active
        $restaurantModel = $this->loadModel('Restaurant');
        $restaurant = $restaurantModel->find($restaurantId);
        
        if (!$restaurant || !$restaurant['is_active']) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Restaurante no encontrado'
            ], 404);
            return;
        }
        
        // Get available tables
        $tableModel = $this->loadModel('Table');
        $tables = $tableModel->getAvailable($restaurantId, $date, $time, $partySize);
        
        $this->jsonResponse([
            'success' => true,
            'tables' => $tables,
            'available' => !empty($tables),
            'date' => $date,
            'time' => $time,
            'party_size' => $partySize
        ]);
    }
    
    public function getCustomerSearch() {
        $session = $this->requireAuth();
        $query = $_GET['q'] ?? '';
        $restaurantId = $session['restaurant_id'] ?? null;
        
        if (strlen($query) < 2) {
            $this->jsonResponse([
                'success' => true,
                'customers' => []
            ]);
            return;
        }
        
        $customerModel = $this->loadModel('Customer');
        
        if ($session['user_role'] === 'superadmin') {
            // Superadmin can search across all restaurants
            $customers = $this->searchCustomersGlobal($query);
        } else {
            // Restaurant admin/hostess can only search their restaurant's customers
            if (!$restaurantId) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Usuario no asociado a un restaurante'
                ], 403);
                return;
            }
            $customers = $customerModel->searchCustomers($restaurantId, $query);
        }
        
        $this->jsonResponse([
            'success' => true,
            'customers' => $customers
        ]);
    }
    
    public function getCustomerStats($customerId) {
        $session = $this->requireAuth();
        
        $customerModel = $this->loadModel('Customer');
        $customer = $customerModel->find($customerId);
        
        if (!$customer) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
            return;
        }
        
        // Get customer statistics
        $stats = $customerModel->getCustomerStats($customerId);
        
        $this->jsonResponse([
            'success' => true,
            'customer' => $customer,
            'stats' => $stats
        ]);
    }
    
    public function getReservationCalendar() {
        $session = $this->requireAuth();
        $restaurantId = $session['restaurant_id'] ?? null;
        
        $start = $_GET['start'] ?? date('Y-m-01');
        $end = $_GET['end'] ?? date('Y-m-t');
        
        if ($session['user_role'] === 'superadmin') {
            $reservations = $this->getReservationsGlobal($start, $end);
        } else {
            if (!$restaurantId) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Usuario no asociado a un restaurante'
                ], 403);
                return;
            }
            $reservations = $this->getReservationsByRestaurant($restaurantId, $start, $end);
        }
        
        // Format for FullCalendar
        $events = array_map(function($reservation) {
            $color = $this->getReservationColor($reservation['status']);
            
            return [
                'id' => $reservation['id'],
                'title' => $reservation['customer_name'] . ' (' . $reservation['party_size'] . ' pers.)',
                'start' => $reservation['reservation_date'] . 'T' . $reservation['reservation_time'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'customer_phone' => $reservation['customer_phone'],
                    'status' => $reservation['status'],
                    'table_ids' => $reservation['table_ids'],
                    'special_requests' => $reservation['special_requests']
                ]
            ];
        }, $reservations);
        
        $this->jsonResponse($events);
    }
    
    public function getRestaurantMetrics($restaurantId = null) {
        $session = $this->requireAuth();
        
        // Authorization check
        if ($session['user_role'] !== 'superadmin' && $session['restaurant_id'] != $restaurantId) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
            return;
        }
        
        $period = $_GET['period'] ?? 'month'; // day, week, month, year
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        $metrics = $this->calculateRestaurantMetrics($restaurantId, $period, $startDate, $endDate);
        
        $this->jsonResponse([
            'success' => true,
            'metrics' => $metrics
        ]);
    }
    
    private function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    
    private function isValidTime($time) {
        return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $time);
    }
    
    private function searchCustomersGlobal($query) {
        $searchTerm = '%' . $query . '%';
        
        $stmt = $this->db->prepare("
            SELECT DISTINCT c.*, r.name as restaurant_name
            FROM customers c
            JOIN reservations res ON c.id = res.customer_id
            JOIN restaurants r ON res.restaurant_id = r.id
            WHERE c.first_name LIKE ? OR
                  c.last_name LIKE ? OR
                  c.phone LIKE ? OR
                  c.email LIKE ? OR
                  CONCAT(c.first_name, ' ', c.last_name) LIKE ?
            ORDER BY c.first_name, c.last_name
            LIMIT 50
        ");
        
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    private function getReservationsGlobal($start, $end) {
        $stmt = $this->db->prepare("
            SELECT r.*, res.name as restaurant_name
            FROM reservations r
            JOIN restaurants res ON r.restaurant_id = res.id
            WHERE r.reservation_date BETWEEN ? AND ?
            ORDER BY r.reservation_date, r.reservation_time
        ");
        $stmt->execute([$start, $end]);
        return $stmt->fetchAll();
    }
    
    private function getReservationsByRestaurant($restaurantId, $start, $end) {
        $stmt = $this->db->prepare("
            SELECT *
            FROM reservations
            WHERE restaurant_id = ? AND reservation_date BETWEEN ? AND ?
            ORDER BY reservation_date, reservation_time
        ");
        $stmt->execute([$restaurantId, $start, $end]);
        return $stmt->fetchAll();
    }
    
    private function getReservationColor($status) {
        $colors = [
            'pending' => '#ffc107',
            'confirmed' => '#0dcaf0',
            'seated' => '#198754',
            'completed' => '#0d6efd',
            'cancelled' => '#dc3545',
            'no_show' => '#6c757d'
        ];
        
        return $colors[$status] ?? '#6c757d';
    }
    
    private function calculateRestaurantMetrics($restaurantId, $period, $startDate, $endDate) {
        // Define date range based on period
        if (!$startDate || !$endDate) {
            switch ($period) {
                case 'day':
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d');
                    break;
                case 'week':
                    $startDate = date('Y-m-d', strtotime('monday this week'));
                    $endDate = date('Y-m-d', strtotime('sunday this week'));
                    break;
                case 'month':
                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');
                    break;
                case 'year':
                    $startDate = date('Y-01-01');
                    $endDate = date('Y-12-31');
                    break;
            }
        }
        
        $whereClause = $restaurantId ? "WHERE r.restaurant_id = ?" : "";
        $params = $restaurantId ? [$restaurantId, $startDate, $endDate] : [$startDate, $endDate];
        
        if ($restaurantId) {
            $whereClause .= " AND r.reservation_date BETWEEN ? AND ?";
        } else {
            $whereClause = "WHERE r.reservation_date BETWEEN ? AND ?";
        }
        
        // Total metrics
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT r.id) as total_reservations,
                COUNT(DISTINCT CASE WHEN r.status = 'completed' THEN r.id END) as completed_reservations,
                COUNT(DISTINCT CASE WHEN r.status = 'cancelled' THEN r.id END) as cancelled_reservations,
                COUNT(DISTINCT CASE WHEN r.status = 'no_show' THEN r.id END) as no_show_reservations,
                COALESCE(SUM(b.total_amount), 0) as total_revenue,
                COALESCE(AVG(b.total_amount), 0) as avg_ticket,
                COUNT(DISTINCT r.customer_id) as unique_customers
            FROM reservations r
            LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
            $whereClause
        ");
        $stmt->execute($params);
        $totals = $stmt->fetch();
        
        // Daily breakdown
        $stmt = $this->db->prepare("
            SELECT 
                r.reservation_date,
                COUNT(DISTINCT r.id) as reservations,
                COALESCE(SUM(b.total_amount), 0) as revenue
            FROM reservations r
            LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
            $whereClause
            GROUP BY r.reservation_date
            ORDER BY r.reservation_date
        ");
        $stmt->execute($params);
        $daily = $stmt->fetchAll();
        
        return [
            'totals' => $totals,
            'daily' => $daily,
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
}
?>