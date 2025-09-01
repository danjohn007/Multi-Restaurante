<?php
/**
 * Basic validation test for the fixes implemented
 */

// Test 1: Test requireAuth method with array of roles
echo "Testing requireAuth method fixes...\n";

// Start session first to avoid warnings
session_start();

// Mock the Controller class to test requireAuth functionality
require_once __DIR__ . '/../app/controllers/Controller.php';

class TestController extends Controller {
    public function __construct() {
        // Don't call parent constructor to avoid database dependency
    }
    
    public function testRequireAuth($roles) {
        // Mock session for testing
        $_SESSION['user_id'] = 1;
        $_SESSION['user_role'] = 'admin';
        
        try {
            $this->requireAuth($roles);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function testGetUserRestaurantId() {
        $_SESSION['restaurant_id'] = 123;
        return $this->getUserRestaurantId();
    }
}

$testController = new TestController();

// Test single role (should work for admin)
$result1 = $testController->testRequireAuth('admin');
echo "Test single role 'admin': " . ($result1 ? "PASS" : "FAIL") . "\n";

// Test array of roles including admin (should work)
$result2 = $testController->testRequireAuth(['admin', 'hostess']);
echo "Test array roles ['admin', 'hostess']: " . ($result2 ? "PASS" : "FAIL") . "\n";

// Test array of roles not including admin (should fail)
$result3 = $testController->testRequireAuth(['hostess', 'superadmin']);
echo "Test array roles ['hostess', 'superadmin']: " . ($result3 ? "FAIL (expected)" : "PASS (expected)") . "\n";

// Test getUserRestaurantId method
$restaurantId = $testController->testGetUserRestaurantId();
echo "Test getUserRestaurantId: " . ($restaurantId === 123 ? "PASS" : "FAIL") . "\n";

echo "\n=== All basic tests completed ===\n";

// Test 2: Validate JavaScript filtering logic (syntax check)
echo "Validating updated JavaScript in manage.php...\n";

$manageFile = __DIR__ . '/../app/views/usuario/manage.php';
if (file_exists($manageFile)) {
    $content = file_get_contents($manageFile);
    
    // Check if the data-restaurant-id attribute was added
    if (strpos($content, 'data-restaurant-id') !== false) {
        echo "✓ data-restaurant-id attribute found in user table rows\n";
    } else {
        echo "✗ data-restaurant-id attribute NOT found\n";
    }
    
    // Check if the filtering logic was updated
    if (strpos($content, 'getAttribute(\'data-restaurant-id\')') !== false) {
        echo "✓ Updated filtering logic found\n";
    } else {
        echo "✗ Updated filtering logic NOT found\n";
    }
} else {
    echo "✗ manage.php file not found\n";
}

echo "\n=== Validation completed ===\n";
?>