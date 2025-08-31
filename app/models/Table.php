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
        $stmt = $this->db->prepare("
            SELECT t.* FROM tables t
            WHERE t.restaurant_id = ? 
            AND t.is_active = 1 
            AND t.capacity >= ?
            AND (t.valid_from IS NULL OR t.valid_from <= ?)
            AND (t.valid_until IS NULL OR t.valid_until >= ?)
            AND t.id NOT IN (
                SELECT DISTINCT CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(table_ids, ',', numbers.n), ',', -1) AS UNSIGNED) as table_id
                FROM reservations
                CROSS JOIN (
                    SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
                ) numbers
                WHERE CHAR_LENGTH(table_ids) - CHAR_LENGTH(REPLACE(table_ids, ',', '')) >= numbers.n - 1
                AND reservation_date = ?
                AND ABS(TIME_TO_SEC(reservation_time) - TIME_TO_SEC(?)) < 7200
                AND status IN ('confirmed', 'seated')
            )
            ORDER BY t.capacity ASC, t.table_number ASC
        ");
        $stmt->execute([$restaurantId, $partySize, $date, $date, $date, $time]);
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