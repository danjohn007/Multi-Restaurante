<?php
require_once __DIR__ . '/Model.php';

class Reservation extends Model {
    protected $table = 'reservations';
    
    public function getTodayReservations($restaurantId) {
        $stmt = $this->db->prepare("
            SELECT r.*, c.first_name, c.last_name, c.phone as customer_phone_alt
            FROM reservations r
            LEFT JOIN customers c ON r.customer_id = c.id
            WHERE r.restaurant_id = ? AND r.reservation_date = CURDATE()
            ORDER BY r.reservation_time ASC
        ");
        $stmt->execute([$restaurantId]);
        return $stmt->fetchAll();
    }
    
    public function getByRestaurantAndDate($restaurantId, $date) {
        $stmt = $this->db->prepare("
            SELECT r.*, c.first_name, c.last_name, c.phone as customer_phone_alt
            FROM reservations r
            LEFT JOIN customers c ON r.customer_id = c.id
            WHERE r.restaurant_id = ? AND r.reservation_date = ?
            ORDER BY r.reservation_time ASC
        ");
        $stmt->execute([$restaurantId, $date]);
        return $stmt->fetchAll();
    }
    
    public function getTodayCount($restaurantId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM reservations 
            WHERE restaurant_id = ? AND reservation_date = CURDATE()
        ");
        $stmt->execute([$restaurantId]);
        return $stmt->fetchColumn();
    }
    
    public function getRecent($restaurantId, $limit = 5) {
        $stmt = $this->db->prepare("
            SELECT r.*, c.first_name, c.last_name 
            FROM reservations r
            LEFT JOIN customers c ON r.customer_id = c.id
            WHERE r.restaurant_id = ? 
            ORDER BY r.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$restaurantId, $limit]);
        return $stmt->fetchAll();
    }
    
    public function getStatsByDateRange($restaurantId, $dateFrom, $dateTo) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(reservation_date) as date,
                COUNT(*) as total_reservations,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_reservations,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_reservations,
                AVG(party_size) as avg_party_size
            FROM reservations 
            WHERE restaurant_id = ? AND reservation_date BETWEEN ? AND ?
            GROUP BY DATE(reservation_date)
            ORDER BY date DESC
        ");
        $stmt->execute([$restaurantId, $dateFrom, $dateTo]);
        return $stmt->fetchAll();
    }
    
    public function findByCustomerPhone($phone) {
        return $this->findAll(['customer_phone' => $phone], 'created_at DESC');
    }
    
    public function getAvailableTimeSlots($restaurantId, $date, $partySize) {
        // Get restaurant operating hours
        $restaurantModel = new Restaurant();
        $restaurant = $restaurantModel->find($restaurantId);
        
        if (!$restaurant) {
            return [];
        }
        
        $openTime = strtotime($restaurant['opening_time']);
        $closeTime = strtotime($restaurant['closing_time']);
        $timeSlots = [];
        
        // Generate time slots every 30 minutes
        for ($time = $openTime; $time <= $closeTime; $time += 1800) {
            $timeStr = date('H:i:s', $time);
            
            // Check if there are available tables for this time slot
            $tableModel = new Table();
            $availableTables = $tableModel->getAvailable($restaurantId, $date, $timeStr, $partySize);
            
            if (!empty($availableTables)) {
                $timeSlots[] = [
                    'time' => $timeStr,
                    'display_time' => date('H:i', $time),
                    'available_tables' => count($availableTables)
                ];
            }
        }
        
        return $timeSlots;
    }
    
    public function updateStatus($reservationId, $status, $notes = null) {
        $updateData = ['status' => $status];
        
        if ($status === 'seated') {
            $updateData['checked_in_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'completed') {
            $updateData['completed_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'cancelled') {
            $updateData['cancelled_at'] = date('Y-m-d H:i:s');
        }
        
        if ($notes) {
            $updateData['notes'] = $notes;
        }
        
        return $this->update($reservationId, $updateData);
    }
}
?>