<?php
/**
 * Temporary test page to demonstrate dashboard changes
 * This bypasses authentication for demonstration purposes only
 */

require_once __DIR__ . '/../config/config.php';

// Mock data for demonstration
$stats = [
    'total_restaurants' => 12,
    'total_admins' => 8,
    'total_hostess' => 15,
    'total_reservations' => 245,
    'monthly_stats' => [
        ['month' => 'Enero', 'reservations' => 45],
        ['month' => 'Febrero', 'reservations' => 52],
        ['month' => 'Marzo', 'reservations' => 38],
        ['month' => 'Abril', 'reservations' => 61],
        ['month' => 'Mayo', 'reservations' => 49],
        ['month' => 'Junio', 'reservations' => 55]
    ]
];

$restaurantStats = [
    [
        'id' => 1,
        'name' => 'La Parrilla Dorada',
        'logo_url' => null,
        'total_reservations' => 45,
        'total_revenue' => 2250,
        'total_tables' => 8,
        'is_active' => 1,
        'created_at' => '2024-01-15 09:30:00'
    ],
    [
        'id' => 2,
        'name' => 'Pizzería Roma',
        'logo_url' => null,
        'total_reservations' => 38,
        'total_revenue' => 1900,
        'total_tables' => 6,
        'is_active' => 1,
        'created_at' => '2024-02-01 14:20:00'
    ],
    [
        'id' => 3,
        'name' => 'Sushi Zen',
        'logo_url' => null,
        'total_reservations' => 52,
        'total_revenue' => 3120,
        'total_tables' => 10,
        'is_active' => 1,
        'created_at' => '2024-01-20 11:45:00'
    ],
    [
        'id' => 4,
        'name' => 'Tacos El Primo',
        'logo_url' => null,
        'total_reservations' => 29,
        'total_revenue' => 1450,
        'total_tables' => 5,
        'is_active' => 1,
        'created_at' => '2024-03-10 16:15:00'
    ],
    [
        'id' => 5,
        'name' => 'Café Central',
        'logo_url' => null,
        'total_reservations' => 41,
        'total_revenue' => 2050,
        'total_tables' => 7,
        'is_active' => 1,
        'created_at' => '2024-02-14 10:00:00'
    ],
    [
        'id' => 6,
        'name' => 'Bistro Français',
        'logo_url' => null,
        'total_reservations' => 35,
        'total_revenue' => 2625,
        'total_tables' => 9,
        'is_active' => 1,
        'created_at' => '2024-03-01 13:30:00'
    ]
];

$recentRestaurants = array_slice($restaurantStats, 0, 3);

$data = [
    'title' => 'Panel Superadmin - Multi-Restaurante (Demo)',
    'stats' => $stats,
    'recentRestaurants' => $recentRestaurants,
    'restaurantStats' => $restaurantStats
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .stat-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-light">

<?php 
// Include the dashboard view
include __DIR__ . '/../app/views/superadmin/dashboard.php';
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>