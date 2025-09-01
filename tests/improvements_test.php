<?php
/**
 * Test script for the implemented improvements
 * 
 * Tests:
 * 1. Keywords route functionality
 * 2. Image path handling
 * 3. Dashboard revenue chart replacement
 * 4. Reservation flow basic validation
 */

require_once __DIR__ . '/../config/config.php';

class ImprovementsTest {
    
    public function runTests() {
        echo "<h2>Testing Multi-Restaurant Improvements</h2>\n";
        
        $this->testKeywordsRoute();
        $this->testImagePathHandling();
        $this->testDashboardChanges();
        $this->testReservationFlow();
        
        echo "<hr>\n";
        echo "<h3>Test Summary</h3>\n";
        echo "<p>All basic functionality tests completed.</p>\n";
    }
    
    private function testKeywordsRoute() {
        echo "<h3>1. Testing Keywords Route</h3>\n";
        
        // Check if the route exists in the router
        $indexContent = file_get_contents(__DIR__ . '/../public/index.php');
        
        if (strpos($indexContent, 'restaurante/update-keywords') !== false) {
            echo "<p style='color: green;'>✓ Keywords route found in index.php</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Keywords route not found in index.php</p>\n";
        }
        
        // Check if the controller method exists
        $controllerContent = file_get_contents(__DIR__ . '/../app/controllers/SuperadminController.php');
        
        if (strpos($controllerContent, 'updateKeywordsCompat') !== false) {
            echo "<p style='color: green;'>✓ updateKeywordsCompat method found in SuperadminController</p>\n";
        } else {
            echo "<p style='color: red;'>✗ updateKeywordsCompat method not found in SuperadminController</p>\n";
        }
    }
    
    private function testImagePathHandling() {
        echo "<h3>2. Testing Image Path Handling</h3>\n";
        
        // Test home page image handling
        $homeContent = file_get_contents(__DIR__ . '/../app/views/home/index.php');
        
        if (strpos($homeContent, 'uploads/restaurants/') !== false) {
            echo "<p style='color: green;'>✓ Home page image path updated</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Home page image path not updated</p>\n";
        }
        
        // Test search page image handling
        $searchContent = file_get_contents(__DIR__ . '/../app/views/home/search.php');
        
        if (strpos($searchContent, 'uploads/restaurants/') !== false) {
            echo "<p style='color: green;'>✓ Search page image path updated</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Search page image path not updated</p>\n";
        }
        
        // Test dashboard image handling
        $dashboardContent = file_get_contents(__DIR__ . '/../app/views/superadmin/dashboard.php');
        
        if (strpos($dashboardContent, 'uploads/restaurants/') !== false) {
            echo "<p style='color: green;'>✓ Dashboard image path updated</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Dashboard image path not updated</p>\n";
        }
    }
    
    private function testDashboardChanges() {
        echo "<h3>3. Testing Dashboard Revenue Chart Replacement</h3>\n";
        
        $dashboardContent = file_get_contents(__DIR__ . '/../app/views/superadmin/dashboard.php');
        
        // Check if revenue chart was removed
        if (strpos($dashboardContent, 'id="revenueChart"') === false) {
            echo "<p style='color: green;'>✓ Revenue chart canvas removed</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Revenue chart canvas still present</p>\n";
        }
        
        // Check if table was added
        if (strpos($dashboardContent, 'Ingresos y Reservaciones por Restaurante') !== false) {
            echo "<p style='color: green;'>✓ Income and reservations table added</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Income and reservations table not found</p>\n";
        }
        
        // Check if initializeRevenueChart function was removed
        if (strpos($dashboardContent, 'initializeRevenueChart()') === false) {
            echo "<p style='color: green;'>✓ Revenue chart initialization removed</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Revenue chart initialization still present</p>\n";
        }
    }
    
    private function testReservationFlow() {
        echo "<h3>4. Testing Reservation Flow</h3>\n";
        
        $reserveContent = file_get_contents(__DIR__ . '/../app/views/reservation/reserve.php');
        $controllerContent = file_get_contents(__DIR__ . '/../app/controllers/ReservationController.php');
        
        // Check if confirmation messages exist
        if (strpos($controllerContent, 'Reservación creada exitosamente') !== false) {
            echo "<p style='color: green;'>✓ Success message found in ReservationController</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Success message not found in ReservationController</p>\n";
        }
        
        // Check if AJAX submission exists
        if (strpos($reserveContent, 'App.submitFormAjax') !== false) {
            echo "<p style='color: green;'>✓ AJAX form submission found</p>\n";
        } else {
            echo "<p style='color: red;'>✗ AJAX form submission not found</p>\n";
        }
        
        // Check if confirmation redirect exists
        if (strpos($controllerContent, 'reservation/confirmation/') !== false) {
            echo "<p style='color: green;'>✓ Confirmation redirect found</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Confirmation redirect not found</p>\n";
        }
    }
}

// Run tests if accessed directly
if (basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__)) {
    $test = new ImprovementsTest();
    $test->runTests();
}
?>