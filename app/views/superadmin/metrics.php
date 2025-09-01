<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-chart-line text-primary"></i> 
                        Métricas Globales
                    </h1>
                    <p class="text-muted mb-0">
                        Análisis de rendimiento del sistema Multi-Restaurante
                    </p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>superadmin" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Panel
                    </a>
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

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-filter"></i> Filtros de Métricas
                    </h6>
                </div>
                <div class="card-body">
                    <form id="metricsFiltersForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                       value="<?php echo $currentFilters['date_from'] ?? date('Y-m-01'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                       value="<?php echo $currentFilters['date_to'] ?? date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="restaurant_filter" class="form-label">Restaurante</label>
                                <select class="form-select" id="restaurant_filter" name="restaurant_id">
                                    <option value="">Todos los restaurantes</option>
                                    <?php foreach ($filterData['restaurants'] ?? [] as $restaurant): ?>
                                        <option value="<?php echo $restaurant['id']; ?>" 
                                                <?php echo (($currentFilters['restaurant_id'] ?? '') == $restaurant['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($restaurant['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="food_type_filter" class="form-label">Tipo de Cocina</label>
                                <select class="form-select" id="food_type_filter" name="food_type">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach ($filterData['food_types'] ?? [] as $type): ?>
                                        <option value="<?php echo htmlspecialchars($type); ?>"
                                                <?php echo (($currentFilters['food_type'] ?? '') === $type) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($type); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <label for="keywords_filter" class="form-label">Palabras Clave</label>
                                <input type="text" class="form-control" id="keywords_filter" name="keywords" 
                                       placeholder="Buscar por palabras clave de restaurantes..."
                                       value="<?php echo htmlspecialchars($currentFilters['keywords'] ?? ''); ?>">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="applyFiltersBtn">
                                    <i class="fas fa-filter"></i> Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-success">
                                $<?php echo number_format($metrics['total_revenue'] ?? 0, 2); ?>
                            </div>
                            <div class="stat-label">Ingresos Totales</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign text-success"></i>
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
                                <?php echo number_format($metrics['reservations_today'] ?? 0); ?>
                            </div>
                            <div class="stat-label">Reservas Hoy</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-calendar-day text-info"></i>
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
                                <?php echo count($metrics['top_restaurants'] ?? []); ?>
                            </div>
                            <div class="stat-label">Restaurantes Top</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-trophy text-warning"></i>
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
                            <div class="stat-number text-primary">
                                <?php echo count($metrics['monthly_stats'] ?? []); ?>
                            </div>
                            <div class="stat-label">Meses de Datos</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-bar text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Reservations Trend Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line"></i> Tendencia de Reservaciones (Últimos 30 días)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="reservationsTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Revenue by Restaurant Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Distribución de Ingresos
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Restaurants -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy"></i> Top Restaurantes por Ingresos
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($metrics['top_restaurants'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Restaurante</th>
                                        <th>Reservas</th>
                                        <th>Ingresos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($metrics['top_restaurants'] as $restaurant): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($restaurant['name']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo number_format($restaurant['reservations']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success">
                                                    $<?php echo number_format($restaurant['revenue'], 2); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay datos disponibles</h5>
                            <p class="text-muted">Las métricas aparecerán cuando haya actividad en el sistema.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt"></i> Estadísticas Mensuales
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($metrics['monthly_stats'])): ?>
                        <div class="space-y-3">
                            <?php foreach (array_slice($metrics['monthly_stats'], 0, 6) as $stat): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong><?php echo $stat['month']; ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo number_format($stat['reservations']); ?> reservas
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-success">
                                            $<?php echo number_format($stat['revenue'], 2); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Sin datos mensuales</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools"></i> Acciones de Métricas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3 d-md-flex">
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimir Reporte
                        </button>
                        <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-outline-secondary">
                            <i class="fas fa-store"></i> Ver Restaurantes
                        </a>
                        <a href="<?php echo BASE_URL; ?>superadmin" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i> Volver al Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
    // Apply filters functionality
    document.getElementById('applyFiltersBtn').addEventListener('click', function() {
        const form = document.getElementById('metricsFiltersForm');
        const formData = new FormData(form);
        
        // Show loading state
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtrando...';
        
        // Apply filters via AJAX
        const params = new URLSearchParams(formData);
        params.append('ajax', '1');
        
        fetch('<?php echo BASE_URL; ?>superadmin/metrics?' + params.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the page with filtered data
                    updateMetricsDisplay(data.metrics);
                    App.showAlert('success', 'Filtros aplicados correctamente');
                } else {
                    App.showAlert('danger', data.message || 'Error al aplicar filtros');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                App.showAlert('danger', 'Error de conexión al aplicar filtros');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    });
    
    function updateMetricsDisplay(metrics) {
        // Update KPI cards
        const cards = document.querySelectorAll('.stat-number');
        if (cards[0]) cards[0].textContent = '$' + (metrics.total_revenue || 0).toLocaleString('es-MX', {minimumFractionDigits: 2});
        if (cards[1]) cards[1].textContent = (metrics.reservations_today || 0).toLocaleString();
        if (cards[2]) cards[2].textContent = (metrics.top_restaurants?.length || 0);
        if (cards[3]) cards[3].textContent = (metrics.monthly_stats?.length || 0);
        
        // Update top restaurants table
        const tableBody = document.querySelector('.table tbody');
        if (tableBody && metrics.top_restaurants) {
            tableBody.innerHTML = metrics.top_restaurants.map(restaurant => `
                <tr>
                    <td><strong>${restaurant.name}</strong></td>
                    <td><span class="badge bg-info">${restaurant.reservations.toLocaleString()}</span></td>
                    <td><span class="text-success">$${restaurant.revenue.toLocaleString('es-MX', {minimumFractionDigits: 2})}</span></td>
                </tr>
            `).join('');
        }
        
        // Update monthly stats
        const monthlyContainer = document.querySelector('.space-y-3');
        if (monthlyContainer && metrics.monthly_stats) {
            monthlyContainer.innerHTML = metrics.monthly_stats.slice(0, 6).map(stat => `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>${stat.month}</strong><br>
                        <small class="text-muted">${stat.reservations.toLocaleString()} reservas</small>
                    </div>
                    <div class="text-end">
                        <span class="text-success">$${stat.revenue.toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                    </div>
                </div>
            `).join('');
        }
    }
    
    function initializeCharts() {
        // Reservations Trend Chart
        const reservationsTrendCtx = document.getElementById('reservationsTrendChart');
        if (reservationsTrendCtx) {
            window.reservationsTrendChart = new Chart(reservationsTrendCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($metrics['chart_data']['reservation_dates'] ?? []); ?>,
                    datasets: [{
                        label: 'Reservaciones por día',
                        data: <?php echo json_encode($metrics['chart_data']['reservation_counts'] ?? []); ?>,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });
        }
        
        // Revenue Distribution Chart
        const revenueDistributionCtx = document.getElementById('revenueDistributionChart');
        if (revenueDistributionCtx) {
            window.revenueDistributionChart = new Chart(revenueDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_column($metrics['top_restaurants'] ?? [], 'name')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($metrics['top_restaurants'] ?? [], 'revenue')); ?>,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB', 
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40',
                            '#FF6384',
                            '#C7E596'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = new Intl.NumberFormat('es-MX', {
                                        style: 'currency',
                                        currency: 'MXN'
                                    }).format(context.raw);
                                    return label + ': ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>