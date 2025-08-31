<?php
require_once __DIR__ . '/Controller.php';

class ReservationController extends Controller {
    
    public function restaurant($id) {
        $restaurantModel = $this->loadModel('Restaurant');
        $tableModel = $this->loadModel('Table');
        
        $restaurant = $restaurantModel->find($id);
        
        if (!$restaurant || !$restaurant['is_active']) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Restaurante no encontrado</h1>";
            return;
        }
        
        // Get available tables for today
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');
        $tables = $tableModel->getActive($id);
        
        $data = [
            'title' => htmlspecialchars($restaurant['name']) . ' - Disponibilidad',
            'restaurant' => $restaurant,
            'tables' => $tables,
            'today' => $today,
            'currentTime' => $currentTime
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('reservation/restaurant', $data);
        $this->loadView('layout/footer');
    }
    
    public function reserve($id) {
        $restaurantModel = $this->loadModel('Restaurant');
        $tableModel = $this->loadModel('Table');
        
        $restaurant = $restaurantModel->find($id);
        
        if (!$restaurant || !$restaurant['is_active']) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Restaurante no encontrado</h1>";
            return;
        }
        
        $data = [
            'title' => 'Reservar Mesa - ' . htmlspecialchars($restaurant['name']),
            'restaurant' => $restaurant,
            'selectedDate' => $_GET['date'] ?? date('Y-m-d'),
            'selectedTime' => $_GET['time'] ?? '19:00',
            'partySize' => $_GET['party_size'] ?? 2
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('reservation/reserve', $data);
        $this->loadView('layout/footer');
    }
    
    public function processReservation($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('restaurant/' . $id);
        }
        
        $restaurantModel = $this->loadModel('Restaurant');
        $tableModel = $this->loadModel('Table');
        $customerModel = $this->loadModel('Customer');
        
        $restaurant = $restaurantModel->find($id);
        
        if (!$restaurant || !$restaurant['is_active']) {
            $this->jsonResponse(['success' => false, 'message' => 'Restaurante no encontrado']);
            return;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Validate required fields
            $required = ['customer_name', 'customer_phone', 'reservation_date', 'reservation_time', 'party_size'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo {$field} es requerido");
                }
            }
            
            // Find or create customer
            $customerId = null;
            if (!empty($_POST['customer_phone'])) {
                $customer = $customerModel->findByPhone($_POST['customer_phone']);
                if ($customer) {
                    $customerId = $customer['id'];
                    
                    // Update customer data if provided
                    $updateData = [];
                    if (!empty($_POST['customer_name']) && $customer['first_name'] !== $_POST['customer_name']) {
                        $nameParts = explode(' ', $_POST['customer_name'], 2);
                        $updateData['first_name'] = $nameParts[0];
                        $updateData['last_name'] = $nameParts[1] ?? '';
                    }
                    if (!empty($_POST['customer_email']) && $customer['email'] !== $_POST['customer_email']) {
                        $updateData['email'] = $_POST['customer_email'];
                    }
                    
                    if (!empty($updateData)) {
                        $customerModel->update($customerId, $updateData);
                    }
                } else {
                    // Create new customer
                    $nameParts = explode(' ', $_POST['customer_name'], 2);
                    $customerData = [
                        'first_name' => $nameParts[0],
                        'last_name' => $nameParts[1] ?? '',
                        'phone' => $_POST['customer_phone'],
                        'email' => $_POST['customer_email'] ?? null
                    ];
                    $customerId = $customerModel->create($customerData);
                }
            }
            
            // Check table availability
            $availableTables = $tableModel->getAvailable(
                $id,
                $_POST['reservation_date'],
                $_POST['reservation_time'],
                $_POST['party_size']
            );
            
            if (empty($availableTables)) {
                throw new Exception('No hay mesas disponibles para el horario seleccionado');
            }
            
            // Select best table (smallest that fits the party size)
            $selectedTable = $availableTables[0];
            foreach ($availableTables as $table) {
                if ($table['capacity'] >= $_POST['party_size'] && $table['capacity'] < $selectedTable['capacity']) {
                    $selectedTable = $table;
                }
            }
            
            // Create reservation
            $reservationData = [
                'restaurant_id' => $id,
                'customer_id' => $customerId,
                'customer_name' => $_POST['customer_name'],
                'customer_phone' => $_POST['customer_phone'],
                'customer_email' => $_POST['customer_email'] ?? null,
                'reservation_date' => $_POST['reservation_date'],
                'reservation_time' => $_POST['reservation_time'],
                'party_size' => $_POST['party_size'],
                'table_ids' => $selectedTable['id'],
                'special_requests' => $_POST['special_requests'] ?? null,
                'status' => 'confirmed'
            ];
            
            $reservationId = $this->createReservation($reservationData);
            
            $this->db->commit();
            
            // Send confirmation (in a real app, you'd send email/SMS here)
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Reservación creada exitosamente',
                    'reservation_id' => $reservationId,
                    'redirect' => BASE_URL . 'reservation/confirmation/' . $reservationId
                ]);
            } else {
                $_SESSION['success'] = 'Reservación creada exitosamente';
                $this->redirect('reservation/confirmation/' . $reservationId);
            }
            
        } catch (Exception $e) {
            $this->db->rollBack();
            
            if (isset($_POST['ajax'])) {
                $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('restaurant/' . $id . '/reserve');
            }
        }
    }
    
    public function confirmation($id) {
        $reservation = $this->getReservation($id);
        
        if (!$reservation) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Reservación no encontrada</h1>";
            return;
        }
        
        $data = [
            'title' => 'Confirmación de Reservación',
            'reservation' => $reservation
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('reservation/confirmation', $data);
        $this->loadView('layout/footer');
    }
    
    public function checkAvailability($restaurantId) {
        $date = $_GET['date'] ?? date('Y-m-d');
        $time = $_GET['time'] ?? '19:00:00';
        $partySize = $_GET['party_size'] ?? 2;
        
        $tableModel = $this->loadModel('Table');
        $tables = $tableModel->getAvailable($restaurantId, $date, $time, $partySize);
        
        $this->jsonResponse([
            'success' => true,
            'tables' => $tables,
            'available' => !empty($tables)
        ]);
    }
    
    private function createReservation($data) {
        $stmt = $this->db->prepare("
            INSERT INTO reservations (
                restaurant_id, customer_id, customer_name, customer_phone, customer_email,
                reservation_date, reservation_time, party_size, table_ids, special_requests, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['restaurant_id'],
            $data['customer_id'],
            $data['customer_name'],
            $data['customer_phone'],
            $data['customer_email'],
            $data['reservation_date'],
            $data['reservation_time'],
            $data['party_size'],
            $data['table_ids'],
            $data['special_requests'],
            $data['status']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    private function getReservation($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, res.name as restaurant_name, t.table_number
            FROM reservations r
            JOIN restaurants res ON r.restaurant_id = res.id
            LEFT JOIN tables t ON FIND_IN_SET(t.id, r.table_ids) > 0
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>