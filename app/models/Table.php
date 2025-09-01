<?php
require_once __DIR__ . '/Model.php';

class Table extends Model {
    protected $table = 'tables';
    
    public function findByRestaurant($restaurantId) {
        return $this->findAll(['restaurant_id' => $restaurantId], 'table_number');
    }
    
    public function getActive($restaurantId) {
        $stmt = $this->db->prepare("
            SELECT * FROM tables 
            WHERE restaurant_id = ? AND is_active = 1 
            AND (valid_from IS NULL OR valid_from <= CURDATE())
            AND (valid_until IS NULL OR valid_until >= CURDATE())
            ORDER BY table_number
        ");
        $stmt->execute([$restaurantId]);
        return $stmt->fetchAll();
    }
    
    public function getAvailable($restaurantId, $date, $time, $partySize) {
        // Fixed MySQL-compatible query to avoid SQLSTATE[42000] syntax errors
        // Simplified approach using FIND_IN_SET which is more reliable than complex SUBSTRING_INDEX
        $stmt = $this->db->prepare("
            SELECT t.* FROM tables t
            WHERE t.restaurant_id = ? 
            AND t.is_active = 1 
            AND t.capacity >= ?
            AND (t.valid_from IS NULL OR t.valid_from <= ?)
            AND (t.valid_until IS NULL OR t.valid_until >= ?)
            AND t.id NOT IN (
                SELECT DISTINCT t_res.id 
                FROM tables t_res
                INNER JOIN reservations r ON FIND_IN_SET(t_res.id, r.table_ids) > 0
                WHERE r.restaurant_id = ?
                AND r.reservation_date = ?
                AND ABS(TIME_TO_SEC(r.reservation_time) - TIME_TO_SEC(?)) < 7200
                AND r.status IN ('confirmed', 'seated')
            )
            ORDER BY t.capacity ASC, t.table_number ASC
        ");
        $stmt->execute([$restaurantId, $partySize, $date, $date, $restaurantId, $date, $time]);
        return $stmt->fetchAll();
    }
    
    public function getOccupancyStats($restaurantId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                t.table_number,
                t.capacity,
                COUNT(r.id) as reservations_count,
                COALESCE(SUM(CASE WHEN r.status = 'completed' THEN 1 ELSE 0 END), 0) as completed_count
            FROM tables t
            LEFT JOIN reservations r ON FIND_IN_SET(t.id, r.table_ids) > 0
                AND r.reservation_date BETWEEN ? AND ?
                AND r.restaurant_id = ?
            WHERE t.restaurant_id = ? AND t.is_active = 1
            GROUP BY t.id, t.table_number, t.capacity
            ORDER BY t.table_number
        ");
        $stmt->execute([$startDate, $endDate, $restaurantId, $restaurantId]);
        return $stmt->fetchAll();
    }
    
    public function getTablesWithStatus($restaurantId) {
        $stmt = $this->db->prepare("
            SELECT 
                t.*,
                CASE 
                    WHEN r.id IS NOT NULL AND r.status IN ('confirmed', 'seated') THEN 'occupied'
                    ELSE 'available'
                END as status,
                r.customer_name,
                r.reservation_time,
                r.party_size
            FROM tables t
            LEFT JOIN reservations r ON FIND_IN_SET(t.id, r.table_ids) > 0
                AND r.reservation_date = CURDATE()
                AND r.status IN ('confirmed', 'seated')
                AND ABS(TIME_TO_SEC(r.reservation_time) - TIME_TO_SEC(CURTIME())) < 7200
            WHERE t.restaurant_id = ? AND t.is_active = 1
            ORDER BY t.table_number
        ");
        $stmt->execute([$restaurantId]);
        return $stmt->fetchAll();
    }
    
    public function isTableNumberExists($restaurantId, $tableNumber, $excludeId = null) {
        $sql = "SELECT id FROM tables WHERE restaurant_id = ? AND table_number = ?";
        $params = [$restaurantId, $tableNumber];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }
}
?>