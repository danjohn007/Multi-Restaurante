<?php
require_once __DIR__ . '/Controller.php';

class HomeController extends Controller {
    
    public function index() {
        // Initialize with default values in case database is not available
        $restaurants = [];
        $foodTypes = [];
        
        try {
            if ($this->db !== null) {
                $restaurantModel = $this->loadModel('Restaurant');
                $restaurants = $restaurantModel->getActive();
                $foodTypes = $restaurantModel->getFoodTypes();
            }
        } catch (Exception $e) {
            // Database not available - continue with empty data
            error_log("Database error in HomeController: " . $e->getMessage());
        }
        
        $data = [
            'title' => 'Multi-Restaurante - Sistema de Reservaciones',
            'restaurants' => $restaurants,
            'foodTypes' => $foodTypes,
            'searchPhrase' => 'Busca por restaurante o por tu comida favorita'
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('home/index', $data);
        $this->loadView('layout/footer');
    }
    
    public function search() {
        $query = $_GET['q'] ?? $_POST['q'] ?? '';
        $foodType = $_GET['food_type'] ?? $_POST['food_type'] ?? '';
        
        // Initialize with default values in case database is not available
        $restaurants = [];
        $foodTypes = [];
        
        try {
            if ($this->db !== null) {
                $restaurantModel = $this->loadModel('Restaurant');
                
                if (!empty($query)) {
                    $restaurants = $restaurantModel->search($query);
                } else if (!empty($foodType)) {
                    $restaurants = $restaurantModel->getByFoodType($foodType);
                } else {
                    $restaurants = $restaurantModel->getActive();
                }
                
                $foodTypes = $restaurantModel->getFoodTypes();
            }
        } catch (Exception $e) {
            // Database not available - continue with empty data
            error_log("Database error in HomeController search: " . $e->getMessage());
        }
        
        $data = [
            'title' => 'Resultados de Búsqueda - Multi-Restaurante',
            'restaurants' => $restaurants,
            'foodTypes' => $foodTypes,
            'query' => $query,
            'selectedFoodType' => $foodType,
            'searchPhrase' => 'Busca por restaurante o por tu comida favorita'
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'restaurants' => $restaurants,
                'count' => count($restaurants)
            ]);
            return;
        }
        
        $this->loadView('layout/header', $data);
        $this->loadView('home/search', $data);
        $this->loadView('layout/footer');
    }
}
?>