<?php
require_once __DIR__ . '/Model.php';

class Customer extends Model {
    protected $table = 'customers';
    
    public function findByPhone($phone) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function getCustomerStats($customerId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT r.id) as total_visits,
                COUNT(DISTINCT CASE WHEN r.status = 'completed' THEN r.id END) as completed_visits,
                COALESCE(SUM(b.total_amount), 0) as total_spent,
                COALESCE(AVG(b.total_amount), 0) as avg_ticket,
                MAX(r.reservation_date) as last_visit_date,
                MIN(r.reservation_date) as first_visit_date
            FROM reservations r
            LEFT JOIN bills b ON r.id = b.reservation_id
            WHERE r.customer_id = ?
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetch();
    }
    
    public function getTopCustomersBySpending($restaurantId = null, $limit = 10) {
        $whereClause = $restaurantId ? "WHERE r.restaurant_id = ?" : "";
        $params = $restaurantId ? [$restaurantId, $limit] : [$limit];
        
        $stmt = $this->db->prepare("
            SELECT 
                c.*, 
                COUNT(DISTINCT r.id) as total_visits,
                COALESCE(SUM(b.total_amount), 0) as total_spent,
                COALESCE(AVG(b.total_amount), 0) as avg_ticket,
                MAX(r.reservation_date) as last_visit_date
            FROM customers c
            JOIN reservations r ON c.id = r.customer_id
            LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
            $whereClause
            GROUP BY c.id
            HAVING total_spent > 0
            ORDER BY total_spent DESC
            LIMIT ?
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getTopCustomersByVisits($restaurantId = null, $limit = 10) {
        $whereClause = $restaurantId ? "WHERE r.restaurant_id = ?" : "";
        $params = $restaurantId ? [$restaurantId, $limit] : [$limit];
        
        $stmt = $this->db->prepare("
            SELECT 
                c.*, 
                COUNT(DISTINCT r.id) as total_visits,
                COALESCE(SUM(b.total_amount), 0) as total_spent,
                COALESCE(AVG(b.total_amount), 0) as avg_ticket,
                MAX(r.reservation_date) as last_visit_date
            FROM customers c
            JOIN reservations r ON c.id = r.customer_id
            LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
            $whereClause
            GROUP BY c.id
            ORDER BY total_visits DESC
            LIMIT ?
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getCustomersForReactivation($restaurantId, $daysSinceLastVisit = 60) {
        $stmt = $this->db->prepare("
            SELECT 
                c.*, 
                COUNT(DISTINCT r.id) as total_visits,
                COALESCE(SUM(b.total_amount), 0) as total_spent,
                MAX(r.reservation_date) as last_visit_date,
                DATEDIFF(CURDATE(), MAX(r.reservation_date)) as days_since_last_visit
            FROM customers c
            JOIN reservations r ON c.id = r.customer_id
            LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
            WHERE r.restaurant_id = ?
            GROUP BY c.id
            HAVING days_since_last_visit >= ? AND total_visits >= 2
            ORDER BY last_visit_date DESC
        ");
        $stmt->execute([$restaurantId, $daysSinceLastVisit]);
        return $stmt->fetchAll();
    }
    
    public function getBirthdayCustomers($restaurantId, $month = null, $day = null) {
        $month = $month ?: date('n');
        $whereClause = "WHERE r.restaurant_id = ? AND c.birth_month = ?";
        $params = [$restaurantId, $month];
        
        if ($day) {
            $whereClause .= " AND c.birth_day = ?";
            $params[] = $day;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                c.*, 
                COUNT(DISTINCT r.id) as total_visits,
                COALESCE(SUM(b.total_amount), 0) as total_spent,
                MAX(r.reservation_date) as last_visit_date
            FROM customers c
            JOIN reservations r ON c.id = r.customer_id
            LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
            $whereClause
            GROUP BY c.id
            ORDER BY c.birth_day ASC, c.first_name ASC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getRFMAnalysis($restaurantId) {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                COUNT(DISTINCT r.id) as frequency,
                COALESCE(SUM(b.total_amount), 0) as monetary,
                DATEDIFF(CURDATE(), MAX(r.reservation_date)) as recency_days,
                MAX(r.reservation_date) as last_visit_date,
                MIN(r.reservation_date) as first_visit_date
            FROM customers c
            JOIN reservations r ON c.id = r.customer_id
            LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
            WHERE r.restaurant_id = ?
            GROUP BY c.id
            HAVING frequency > 0
        ");
        $stmt->execute([$restaurantId]);
        $customers = $stmt->fetchAll();
        
        // Calculate RFM scores (1-5 scale)
        if (!empty($customers)) {
            $recencyValues = array_column($customers, 'recency_days');
            $frequencyValues = array_column($customers, 'frequency');
            $monetaryValues = array_column($customers, 'monetary');
            
            $recencyPercentiles = $this->calculatePercentiles($recencyValues);
            $frequencyPercentiles = $this->calculatePercentiles($frequencyValues);
            $monetaryPercentiles = $this->calculatePercentiles($monetaryValues);
            
            foreach ($customers as &$customer) {
                // Recency: lower days = higher score (inverted)
                $customer['recency_score'] = $this->getScore($customer['recency_days'], $recencyPercentiles, true);
                $customer['frequency_score'] = $this->getScore($customer['frequency'], $frequencyPercentiles);
                $customer['monetary_score'] = $this->getScore($customer['monetary'], $monetaryPercentiles);
                
                // Overall RFM score
                $customer['rfm_score'] = ($customer['recency_score'] + $customer['frequency_score'] + $customer['monetary_score']) / 3;
                
                // Customer segment
                $customer['segment'] = $this->getRFMSegment(
                    $customer['recency_score'],
                    $customer['frequency_score'],
                    $customer['monetary_score']
                );
            }
        }
        
        return $customers;
    }
    
    private function calculatePercentiles($values) {
        sort($values);
        $count = count($values);
        
        return [
            '20' => $values[intval($count * 0.2)],
            '40' => $values[intval($count * 0.4)],
            '60' => $values[intval($count * 0.6)],
            '80' => $values[intval($count * 0.8)]
        ];
    }
    
    private function getScore($value, $percentiles, $inverted = false) {
        if ($value <= $percentiles['20']) {
            return $inverted ? 5 : 1;
        } elseif ($value <= $percentiles['40']) {
            return $inverted ? 4 : 2;
        } elseif ($value <= $percentiles['60']) {
            return $inverted ? 3 : 3;
        } elseif ($value <= $percentiles['80']) {
            return $inverted ? 2 : 4;
        } else {
            return $inverted ? 1 : 5;
        }
    }
    
    private function getRFMSegment($recency, $frequency, $monetary) {
        $score = ($recency + $frequency + $monetary) / 3;
        
        if ($score >= 4.5) {
            return 'Champions';
        } elseif ($score >= 4.0) {
            return 'Loyal Customers';
        } elseif ($score >= 3.5) {
            return 'Potential Loyalists';
        } elseif ($score >= 3.0) {
            return 'New Customers';
        } elseif ($score >= 2.5) {
            return 'Promising';
        } elseif ($score >= 2.0) {
            return 'Need Attention';
        } elseif ($score >= 1.5) {
            return 'About to Sleep';
        } else {
            return 'At Risk';
        }
    }
    
    public function searchCustomers($restaurantId, $query) {
        $searchTerm = '%' . $query . '%';
        
        $stmt = $this->db->prepare("
            SELECT DISTINCT c.*
            FROM customers c
            JOIN reservations r ON c.id = r.customer_id
            WHERE r.restaurant_id = ? AND (
                c.first_name LIKE ? OR
                c.last_name LIKE ? OR
                c.phone LIKE ? OR
                c.email LIKE ? OR
                CONCAT(c.first_name, ' ', c.last_name) LIKE ?
            )
            ORDER BY c.first_name, c.last_name
            LIMIT 50
        ");
        
        $stmt->execute([
            $restaurantId, $searchTerm, $searchTerm, 
            $searchTerm, $searchTerm, $searchTerm
        ]);
        
        return $stmt->fetchAll();
    }
}
?>