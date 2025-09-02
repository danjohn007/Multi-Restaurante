<?php
require_once __DIR__ . '/Controller.php';

class HostessController extends Controller {
    
    public function __construct() {
        parent::__construct();
        // Allow public access for hostess-public routes, but require auth for private routes
        if (!$this->isPublicRoute()) {
            $this->requireAuth(['hostess', 'admin']);
        }
    }
    
    private function isPublicRoute() {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/public/hostess-public/') !== false;
    }
    
    public function dashboard() {
        $restaurantId = $this->getUserRestaurantId();
        
        if (!$restaurantId) {
            $_SESSION['error'] = 'No tienes un restaurante asignado. Contacta al administrador.';
            $this->redirect('auth/login');
            return;
        }
        
        $reservationModel = $this->loadModel('Reservation');
        $tableModel = $this->loadModel('Table');
        
        // Get today's reservations
        $todayReservations = $reservationModel->getTodayReservations($restaurantId);
        
        // Get table status
        $tables = $tableModel->getTablesWithStatus($restaurantId);
        
        $stats = [
            'total_reservations' => count($todayReservations),
            'active_tables' => count($tables),
            'pending_checkins' => count(array_filter($todayReservations, function($r) {
                return $r['status'] === 'confirmed';
            })),
            'completed_today' => count(array_filter($todayReservations, function($r) {
                return $r['status'] === 'completed';
            }))
        ];
        
        $data = [
            'title' => 'Panel Hostess - Dashboard',
            'reservations' => $todayReservations,
            'tables' => $tables,
            'stats' => $stats
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('hostess/dashboard', $data);
        $this->loadView('layout/footer');
    }
    
    public function reservations() {
        $restaurantId = $this->getUserRestaurantId();
        
        if (!$restaurantId) {
            $_SESSION['error'] = 'No tienes un restaurante asignado.';
            $this->redirect('hostess');
            return;
        }
        
        $reservationModel = $this->loadModel('Reservation');
        
        // Get date filter
        $dateFilter = $_GET['date'] ?? date('Y-m-d');
        
        $reservations = $reservationModel->getByRestaurantAndDate($restaurantId, $dateFilter);
        
        $data = [
            'title' => 'Gestión de Reservaciones',
            'reservations' => $reservations,
            'selectedDate' => $dateFilter
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('hostess/reservations', $data);
        $this->loadView('layout/footer');
    }
    
    public function checkin($reservationId) {
        $reservationModel = $this->loadModel('Reservation');
        $reservation = $reservationModel->find($reservationId);
        
        if (!$reservation) {
            $_SESSION['error'] = 'Reservación no encontrada';
            $this->redirect('hostess/reservations');
            return;
        }
        
        // Verify restaurant access
        $restaurantId = $this->getUserRestaurantId();
        if ($reservation['restaurant_id'] != $restaurantId) {
            $_SESSION['error'] = 'No tienes permisos para esta reservación';
            $this->redirect('hostess/reservations');
            return;
        }
        
        $data = [
            'title' => 'Check-in Reservación',
            'reservation' => $reservation
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('hostess/checkin', $data);
        $this->loadView('layout/footer');
    }
    
    public function processCheckin($reservationId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('hostess/reservations');
        }
        
        try {
            $reservationModel = $this->loadModel('Reservation');
            $reservation = $reservationModel->find($reservationId);
            
            if (!$reservation) {
                throw new Exception('Reservación no encontrada');
            }
            
            // Verify restaurant access
            $restaurantId = $this->getUserRestaurantId();
            if ($reservation['restaurant_id'] != $restaurantId) {
                throw new Exception('No tienes permisos para esta reservación');
            }
            
            // Update reservation status
            $updateData = [
                'status' => 'seated',
                'checked_in_at' => date('Y-m-d H:i:s'),
                'table_ids' => $_POST['table_id'] ?? $reservation['table_ids'],
                'notes' => $_POST['notes'] ?? null
            ];
            
            $reservationModel->update($reservationId, $updateData);
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Check-in realizado exitosamente'
                ]);
            } else {
                $_SESSION['success'] = 'Check-in realizado exitosamente';
                $this->redirect('hostess/reservations');
            }
            
        } catch (Exception $e) {
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('hostess/checkin/' . $reservationId);
            }
        }
    }
    
    public function billing($reservationId) {
        $reservationModel = $this->loadModel('Reservation');
        $reservation = $reservationModel->find($reservationId);
        
        if (!$reservation) {
            $_SESSION['error'] = 'Reservación no encontrada';
            $this->redirect('hostess/reservations');
            return;
        }
        
        // Verify restaurant access
        $restaurantId = $this->getUserRestaurantId();
        if ($reservation['restaurant_id'] != $restaurantId) {
            $_SESSION['error'] = 'No tienes permisos para esta reservación';
            $this->redirect('hostess/reservations');
            return;
        }
        
        $data = [
            'title' => 'Facturación',
            'reservation' => $reservation
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('hostess/billing', $data);
        $this->loadView('layout/footer');
    }
    
    public function processBilling($reservationId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('hostess/reservations');
        }
        
        try {
            $reservationModel = $this->loadModel('Reservation');
            $billModel = $this->loadModel('Bill');
            
            $reservation = $reservationModel->find($reservationId);
            
            if (!$reservation) {
                throw new Exception('Reservación no encontrada');
            }
            
            // Verify restaurant access
            $restaurantId = $this->getUserRestaurantId();
            if ($reservation['restaurant_id'] != $restaurantId) {
                throw new Exception('No tienes permisos para esta reservación');
            }
            
            $this->db->beginTransaction();
            
            // Create bill
            $billData = [
                'reservation_id' => $reservationId,
                'subtotal' => $_POST['subtotal'],
                'tax_amount' => $_POST['tax_amount'],
                'total_amount' => $_POST['total_amount'],
                'payment_method' => $_POST['payment_method'],
                'closed_at' => date('Y-m-d H:i:s')
            ];
            
            $billId = $billModel->create($billData);
            
            // Update reservation status
            $reservationModel->update($reservationId, [
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->db->commit();
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Facturación completada exitosamente',
                    'bill_id' => $billId
                ]);
            } else {
                $_SESSION['success'] = 'Facturación completada exitosamente';
                $this->redirect('hostess/reservations');
            }
            
        } catch (Exception $e) {
            $this->db->rollBack();
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('hostess/billing/' . $reservationId);
            }
        }
    }
    
    /**
     * Quick check-in endpoint for AJAX requests
     * Returns reservations pending check-in for today
     */
    public function quickCheckinData() {
        $restaurantId = $this->getUserRestaurantId();
        
        if (!$restaurantId) {
            $this->jsonResponse(['success' => false, 'message' => 'No tienes un restaurante asignado']);
            return;
        }
        
        try {
            $reservationModel = $this->loadModel('Reservation');
            $pendingReservations = $reservationModel->getPendingCheckins($restaurantId);
            
            $this->jsonResponse([
                'success' => true,
                'reservations' => $pendingReservations
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Get table status for AJAX requests
     */
    public function tableStatusData() {
        $restaurantId = $this->getUserRestaurantId();
        
        if (!$restaurantId) {
            $this->jsonResponse(['success' => false, 'message' => 'No tienes un restaurante asignado']);
            return;
        }
        
        try {
            $tableModel = $this->loadModel('Table');
            $tables = $tableModel->getTablesWithStatus($restaurantId);
            
            $this->jsonResponse([
                'success' => true,
                'tables' => $tables
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Create new reservation endpoint
     */
    public function createReservation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        $restaurantId = $this->getUserRestaurantId();
        
        if (!$restaurantId) {
            $this->jsonResponse(['success' => false, 'message' => 'No tienes un restaurante asignado']);
            return;
        }
        
        try {
            $reservationModel = $this->loadModel('Reservation');
            
            // Validate required fields
            $required = ['customer_name', 'customer_phone', 'party_size', 'reservation_date', 'reservation_time'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo {$field} es requerido");
                }
            }
            
            // Prepare reservation data
            $reservationData = [
                'restaurant_id' => $restaurantId,
                'customer_name' => $_POST['customer_name'],
                'customer_phone' => $_POST['customer_phone'],
                'customer_email' => $_POST['customer_email'] ?? null,
                'party_size' => (int)$_POST['party_size'],
                'reservation_date' => $_POST['reservation_date'],
                'reservation_time' => $_POST['reservation_time'],
                'special_requests' => $_POST['special_requests'] ?? null,
                'status' => 'confirmed',
                'created_by' => $_SESSION['user']['id'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $reservationId = $reservationModel->create($reservationData);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Reservación creada exitosamente',
                'reservation_id' => $reservationId
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Get reservation details for modal view
     */
    public function reservationDetails($reservationId) {
        $restaurantId = $this->getUserRestaurantId();
        
        if (!$restaurantId) {
            $this->jsonResponse(['success' => false, 'message' => 'No tienes un restaurante asignado']);
            return;
        }
        
        try {
            $reservationModel = $this->loadModel('Reservation');
            $reservation = $reservationModel->find($reservationId);
            
            if (!$reservation || $reservation['restaurant_id'] != $restaurantId) {
                $this->jsonResponse(['success' => false, 'message' => 'Reservación no encontrada']);
                return;
            }
            
            $this->jsonResponse([
                'success' => true,
                'reservation' => $reservation
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}
?>