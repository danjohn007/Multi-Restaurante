<?php
require_once __DIR__ . '/Model.php';

class Restaurant extends Model {
    protected $table = 'restaurants';
    
    public function search($query) {
        $searchTerms = '%' . $query . '%';
        $stmt = $this->db->prepare("
            SELECT *, MATCH(name, description, keywords) AGAINST(?) as relevance 
            FROM restaurants 
            WHERE is_active = 1 AND (
                name LIKE ? OR 
                description LIKE ? OR 
                keywords LIKE ? OR 
                food_type LIKE ? OR
                MATCH(name, description, keywords) AGAINST(?)
            )
            ORDER BY relevance DESC, name ASC
        ");
        $stmt->execute([$query, $searchTerms, $searchTerms, $searchTerms, $searchTerms, $query]);
        return $stmt->fetchAll();
    }
    
    public function getActive() {
        return $this->findAll(['is_active' => 1], 'name');
    }
    
    public function getByFoodType($foodType) {
        return $this->findAll(['food_type' => $foodType, 'is_active' => 1], 'name');
    }
    
    public function getFoodTypes() {
        $stmt = $this->db->prepare("SELECT DISTINCT food_type FROM restaurants WHERE is_active = 1 AND food_type IS NOT NULL ORDER BY food_type");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getWithStats($restaurantId = null) {
        $whereClause = $restaurantId ? "WHERE r.id = ?" : "WHERE r.is_active = 1";
        $params = $restaurantId ? [$restaurantId] : [];
        
        $stmt = $this->db->prepare("
            SELECT r.*, 
                   COUNT(DISTINCT res.id) as total_reservations,
                   COUNT(DISTINCT CASE WHEN res.status = 'completed' THEN res.id END) as completed_reservations,
                   COALESCE(SUM(b.total_amount), 0) as total_revenue,
                   COUNT(DISTINCT t.id) as total_tables
            FROM restaurants r
            LEFT JOIN reservations res ON r.id = res.restaurant_id
            LEFT JOIN bills b ON res.id = b.reservation_id
            LEFT JOIN tables t ON r.id = t.restaurant_id AND t.is_active = 1
            $whereClause
            GROUP BY r.id
            ORDER BY r.name
        ");
        $stmt->execute($params);
        return $restaurantId ? $stmt->fetch() : $stmt->fetchAll();
    }
    
    public function updateKeywords($restaurantId, $keywords) {
        return $this->update($restaurantId, ['keywords' => $keywords]);
    }
}
?>