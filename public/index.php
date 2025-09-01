<?php
/**
 * Main application entry point
 * Multi-Restaurant Reservation System
 */

// Include configuration and core files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Router.php';
require_once __DIR__ . '/../app/models/Model.php';
require_once __DIR__ . '/../app/controllers/Controller.php';

// Start session
session_start();

// Initialize router
$router = new Router();

// Define routes
$router->get('', 'HomeController@index');
$router->get('home', 'HomeController@index');
$router->get('search', 'HomeController@search');
$router->post('search', 'HomeController@search');

// Authentication routes
$router->get('auth/login', 'AuthController@login');
$router->post('auth/login', 'AuthController@doLogin');
$router->get('auth/logout', 'AuthController@logout');
$router->get('auth/unauthorized', 'AuthController@unauthorized');

// Superadmin routes
$router->get('superadmin', 'SuperadminController@dashboard');
$router->get('superadmin/restaurants', 'SuperadminController@restaurants');
$router->get('superadmin/restaurants/inactive', 'SuperadminController@inactiveRestaurants');
$router->get('superadmin/metrics', 'SuperadminController@globalMetrics');
$router->get('superadmin/users', 'SuperadminController@users');
$router->get('superadmin/settings', 'SuperadminController@settings');
$router->get('superadmin/restaurants/create', 'SuperadminController@createRestaurant');
$router->post('superadmin/restaurants/create', 'SuperadminController@storeRestaurant');
$router->get('superadmin/restaurants/(\d+)/edit', 'SuperadminController@editRestaurant');
$router->post('superadmin/restaurants/(\d+)/edit', 'SuperadminController@updateRestaurant');
$router->post('superadmin/restaurants/(\d+)/keywords', 'SuperadminController@updateKeywords');
$router->post('superadmin/restaurants/(\d+)/toggle-status', 'SuperadminController@toggleStatus');

// Additional routes for restaurante namespace (backward compatibility)
$router->post('restaurante/update-keywords', 'SuperadminController@updateKeywordsCompat');
$router->get('restaurante/edit', 'SuperadminController@restaurants');

// Restaurant admin routes
$router->get('admin', 'AdminController@dashboard');
$router->get('admin/profile', 'AdminController@profile');
$router->post('admin/profile', 'AdminController@updateProfile');
$router->get('admin/tables', 'AdminController@tables');
$router->get('admin/tables/create', 'AdminController@createTable');
$router->post('admin/tables/create', 'AdminController@storeTable');
$router->get('admin/users', 'AdminController@users');
$router->get('admin/reports', 'AdminController@reports');

// Hostess routes
$router->get('hostess', 'HostessController@dashboard');
$router->get('hostess/reservations', 'HostessController@reservations');
$router->get('hostess/checkin/(\d+)', 'HostessController@checkin');
$router->post('hostess/checkin/(\d+)', 'HostessController@processCheckin');
$router->get('hostess/billing/(\d+)', 'HostessController@billing');
$router->post('hostess/billing/(\d+)', 'HostessController@processBilling');

// Public reservation routes
$router->get('restaurant/(\d+)', 'ReservationController@restaurant');
$router->get('restaurant/(\d+)/reserve', 'ReservationController@reserve');
$router->post('restaurant/(\d+)/reserve', 'ReservationController@processReservation');
$router->get('reservation/confirmation/(\d+)', 'ReservationController@confirmation');

// Marketing routes
$router->get('marketing', 'MarketingController@dashboard');
$router->get('marketing/segments', 'MarketingController@segments');
$router->get('marketing/campaigns', 'MarketingController@campaigns');

// API routes
$router->get('api/restaurants/search', 'ApiController@searchRestaurants');
$router->get('api/restaurants/(\d+)/availability', 'ApiController@checkAvailability');

// Dispatch the request
try {
    $router->dispatch();
} catch (Exception $e) {
    // Log error and show generic error page
    error_log($e->getMessage());
    
    // Only send headers if they haven't been sent already
    if (!headers_sent()) {
        header("HTTP/1.0 500 Internal Server Error");
        echo "<h1>500 - Internal Server Error</h1>";
        echo "<p>Something went wrong. Please try again later.</p>";
        echo "<a href='" . BASE_URL . "'>Go Home</a>";
    } else {
        // Headers already sent, just output error in current context
        echo "<div style='background: #dc3545; color: white; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage());
        echo "</div>";
    }
}
?>