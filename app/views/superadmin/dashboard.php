<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-tachometer-alt text-primary"></i> 
                        Panel Superadministrador
                    </h1>
                    <p class="text-muted mb-0">
                        Gestión global del sistema Multi-Restaurante
                    </p>
                </div>
                <div>
                    <span class="badge bg-primary px-3 py-2">
                        <i class="fas fa-crown"></i> Superadmin
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-primary">
                                <?php echo number_format($stats['total_restaurants']); ?>
                            </div>
                            <div class="stat-label">Restaurantes Activos</div>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-store fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-success">
                                <?php echo number_format($stats['total_admins']); ?>
                            </div>
                            <div class="stat-label">Administradores</div>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-user-tie fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-info">
                                <?php echo number_format($stats['total_hostess']); ?>
                            </div>
                            <div class="stat-label">Hostess</div>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-warning">
                                <?php echo number_format($stats['total_reservations']); ?>
                            </div>
                            <div class="stat-label">Total Reservaciones</div>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-calendar-alt fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Restaurants Management -->
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-store"></i> Restaurantes
                    </h5>
                    <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-sm btn-primary">
                        <i class="fas fa-cog"></i> Gestionar
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($restaurantStats)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-store fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay restaurantes registrados</h5>
                            <a href="<?php echo BASE_URL; ?>superadmin/restaurants/create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primer Restaurante
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Restaurante</th>
                                        <th>Tipo</th>
                                        <th>Reservaciones</th>
                                        <th>Ingresos</th>
                                        <th>Mesas</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($restaurantStats as $restaurant): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo $restaurant['logo_url'] ?? BASE_URL . 'public/images/restaurant-placeholder.jpg'; ?>" 
                                                         class="rounded me-2" 
                                                         width="40" height="40"
                                                         alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
                                                    <div>
                                                        <div class="fw-bold"><?php echo htmlspecialchars($restaurant['name']); ?></div>
                                                        <small class="text-muted"><?php echo htmlspecialchars($restaurant['email'] ?? ''); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo htmlspecialchars($restaurant['food_type'] ?? 'General'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold"><?php echo number_format($restaurant['total_reservations']); ?></span>
                                                <small class="text-muted d-block">
                                                    <?php echo number_format($restaurant['completed_reservations']); ?> completadas
                                                </small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    $<?php echo number_format($restaurant['total_revenue'], 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo number_format($restaurant['total_tables']); ?> mesas
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($restaurant['is_active']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mini Analytics Chart -->
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area"></i> Resumen Analítico
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="dashboardMiniChart" width="300" height="150"></canvas>
                    <div class="mt-3 text-center">
                        <small class="text-muted">Reservaciones por mes (últimos 6 meses)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="<?php echo BASE_URL; ?>superadmin/restaurants/create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Restaurante
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-outline-primary">
                            <i class="fas fa-store"></i> Gestionar Restaurantes
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>superadmin/metrics" class="btn btn-outline-info">
                            <i class="fas fa-chart-line"></i> Ver Métricas Globales
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>superadmin/users" class="btn btn-outline-secondary">
                            <i class="fas fa-users"></i> Gestionar Usuarios
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>superadmin/settings" class="btn btn-outline-warning">
                            <i class="fas fa-cog"></i> Configuración Sistema
                        </a>
                    </div>

                    <hr>

                    <h6 class="mb-3">
                        <i class="fas fa-info-circle"></i> Sistema
                    </h6>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between">
                            <span>Versión:</span>
                            <span><?php echo APP_VERSION; ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>PHP:</span>
                            <span><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Base URL:</span>
                            <span class="text-break"><?php echo BASE_URL; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <?php if (!empty($recentRestaurants)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history"></i> Restaurantes Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($recentRestaurants as $restaurant): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo $restaurant['logo_url'] ?? BASE_URL . 'public/images/restaurant-placeholder.jpg'; ?>" 
                                                     class="rounded me-3" 
                                                     width="50" height="50"
                                                     alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($restaurant['name']); ?></h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> 
                                                        <?php echo date('d/m/Y H:i', strtotime($restaurant['created_at'])); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize mini chart for dashboard
    initializeDashboardChart();
});

function initializeDashboardChart() {
    // Sample data for last 6 months - in real app this would come from server
    const monthlyData = {
        labels: <?php echo json_encode(array_column(array_slice($stats['monthly_stats'] ?? [], 0, 6), 'month')); ?>,
        datasets: [{
            label: 'Reservaciones',
            data: <?php echo json_encode(array_column(array_slice($stats['monthly_stats'] ?? [], 0, 6), 'reservations')); ?>,
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };

    const ctx = document.getElementById('dashboardMiniChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: monthlyData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 3,
                        hoverRadius: 6
                    }
                }
            }
        });
    }
}
</script>