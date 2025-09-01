<?php
require_once __DIR__ . '/Model.php';

class Bill extends Model {
    protected $table = 'bills';
    
    public function getByReservation($reservationId) {
        return $this->findOne(['reservation_id' => $reservationId]);
    }
    
    public function getRevenueByDateRange($restaurantId, $dateFrom, $dateTo) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(b.closed_at) as date,
                COUNT(*) as total_bills,
                SUM(b.subtotal) as total_subtotal,
                SUM(b.tax_amount) as total_tax,
                SUM(b.total_amount) as total_revenue,
                AVG(b.total_amount) as avg_bill_amount
            FROM bills b
            JOIN reservations r ON b.reservation_id = r.id
            WHERE r.restaurant_id = ? AND DATE(b.closed_at) BETWEEN ? AND ?
            GROUP BY DATE(b.closed_at)
            ORDER BY date DESC
        ");
        $stmt->execute([$restaurantId, $dateFrom, $dateTo]);
        return $stmt->fetchAll();
    }
    
    public function getTodayRevenue($restaurantId) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.total_amount), 0) as revenue
            FROM bills b
            JOIN reservations r ON b.reservation_id = r.id
            WHERE r.restaurant_id = ? AND DATE(b.closed_at) = CURDATE()
        ");
        $stmt->execute([$restaurantId]);
        return $stmt->fetchColumn();
    }
    
    public function getMonthlyRevenue($restaurantId, $year = null, $month = null) {
        if (!$year) $year = date('Y');
        if (!$month) $month = date('m');
        
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.total_amount), 0) as revenue
            FROM bills b
            JOIN reservations r ON b.reservation_id = r.id
            WHERE r.restaurant_id = ? 
            AND YEAR(b.closed_at) = ? 
            AND MONTH(b.closed_at) = ?
        ");
        $stmt->execute([$restaurantId, $year, $month]);
        return $stmt->fetchColumn();
    }
    
    public function getPaymentMethodStats($restaurantId, $dateFrom = null, $dateTo = null) {
        $whereClause = "r.restaurant_id = ?";
        $params = [$restaurantId];
        
        if ($dateFrom && $dateTo) {
            $whereClause .= " AND DATE(b.closed_at) BETWEEN ? AND ?";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                b.payment_method,
                COUNT(*) as count,
                SUM(b.total_amount) as total_amount
            FROM bills b
            JOIN reservations r ON b.reservation_id = r.id
            WHERE $whereClause
            GROUP BY b.payment_method
            ORDER BY total_amount DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getTopRevenueHours($restaurantId, $dateFrom = null, $dateTo = null) {
        $whereClause = "r.restaurant_id = ?";
        $params = [$restaurantId];
        
        if ($dateFrom && $dateTo) {
            $whereClause .= " AND DATE(b.closed_at) BETWEEN ? AND ?";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                HOUR(b.closed_at) as hour,
                COUNT(*) as bills_count,
                SUM(b.total_amount) as total_revenue,
                AVG(b.total_amount) as avg_bill
            FROM bills b
            JOIN reservations r ON b.reservation_id = r.id
            WHERE $whereClause
            GROUP BY HOUR(b.closed_at)
            ORDER BY total_revenue DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function createFromReservation($reservationId, $billData) {
        $billData['reservation_id'] = $reservationId;
        $billData['created_at'] = date('Y-m-d H:i:s');
        
        if (!isset($billData['closed_at'])) {
            $billData['closed_at'] = date('Y-m-d H:i:s');
        }
        
        // Calculate tax if not provided
        if (!isset($billData['tax_amount']) && isset($billData['subtotal'])) {
            $taxRate = 0.16; // 16% IVA in Mexico
            $billData['tax_amount'] = $billData['subtotal'] * $taxRate;
        }
        
        // Calculate total if not provided
        if (!isset($billData['total_amount'])) {
            $billData['total_amount'] = $billData['subtotal'] + $billData['tax_amount'];
        }
        
        return $this->create($billData);
    }
}
?>