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
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i> Filtros de Análisis
                    </h5>
                </div>
                <div class="card-body">
                    <form id="metricsFiltersForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Fecha Desde</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="<?php echo date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Fecha Hasta</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="food_type_filter" class="form-label">Tipo de Cocina</label>
                            <select class="form-select" id="food_type_filter" name="food_type">
                                <option value="">Todos los tipos</option>
                                <option value="Italiana">Italiana</option>
                                <option value="Mexicana">Mexicana</option>
                                <option value="Japonesa">Japonesa</option>
                                <option value="China">China</option>
                                <option value="Americana">Americana</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Mediterránea">Mediterránea</option>
                                <option value="Internacional">Internacional</option>
                                <option value="Mariscos">Mariscos</option>
                                <option value="Vegetariana">Vegetariana</option>
                                <option value="Steakhouse">Steakhouse</option>
                                <option value="Café">Café</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="restaurant_filter" class="form-label">Restaurante</label>
                            <select class="form-select" id="restaurant_filter" name="restaurant_id">
                                <option value="">Todos los restaurantes</option>
                                <?php if (!empty($metrics['all_restaurants'])): ?>
                                    <?php foreach ($metrics['all_restaurants'] as $restaurant): ?>
                                        <option value="<?php echo $restaurant['id']; ?>">
                                            <?php echo htmlspecialchars($restaurant['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label for="keyword_filter" class="form-label">Palabra Clave</label>
                            <input type="text" class="form-control" id="keyword_filter" name="keyword" 
                                   placeholder="Buscar por palabra clave en nombre o descripción...">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
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

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Sales by Date Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line"></i> Ventas por Fecha
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesByDateChart" width="400" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Sales by Cuisine Type Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Ventas por Tipo de Cocina
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesByCuisineChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Active Restaurants Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar"></i> Restaurantes Más Activos
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="mostActiveRestaurantsChart" width="400" height="120"></canvas>
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

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
    // Handle filters form
    document.getElementById('metricsFiltersForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
});

function initializeCharts() {
    // Sample data - in a real application, this would come from the server
    const salesByDateData = {
        labels: <?php echo json_encode(array_column($metrics['monthly_stats'] ?? [], 'month')); ?>,
        datasets: [{
            label: 'Ventas ($)',
            data: <?php echo json_encode(array_column($metrics['monthly_stats'] ?? [], 'revenue')); ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    };

    const salesByCuisineData = {
        labels: <?php echo json_encode(array_column($metrics['sales_by_cuisine'] ?? [], 'food_type')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($metrics['sales_by_cuisine'] ?? [], 'revenue')); ?>,
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
                '#FF6384',
                '#C9CBCF'
            ]
        }]
    };

    const mostActiveRestaurantsData = {
        labels: <?php echo json_encode(array_column($metrics['top_restaurants'] ?? [], 'name')); ?>,
        datasets: [{
            label: 'Reservaciones',
            data: <?php echo json_encode(array_column($metrics['top_restaurants'] ?? [], 'reservations')); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    // Sales by Date Chart
    const salesByDateCtx = document.getElementById('salesByDateChart').getContext('2d');
    new Chart(salesByDateCtx, {
        type: 'line',
        data: salesByDateData,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Tendencia de Ventas por Mes'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Sales by Cuisine Chart
    const salesByCuisineCtx = document.getElementById('salesByCuisineChart').getContext('2d');
    new Chart(salesByCuisineCtx, {
        type: 'doughnut',
        data: salesByCuisineData,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribución por Tipo de Cocina'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Most Active Restaurants Chart
    const mostActiveCtx = document.getElementById('mostActiveRestaurantsChart').getContext('2d');
    new Chart(mostActiveCtx, {
        type: 'bar',
        data: mostActiveRestaurantsData,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Restaurantes por Número de Reservaciones'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function applyFilters() {
    const formData = new FormData(document.getElementById('metricsFiltersForm'));
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    // Add ajax parameter
    params.append('ajax', '1');
    
    // Show loading state
    App.showAlert('info', 'Aplicando filtros...');
    
    // Fetch filtered data
    fetch('<?php echo BASE_URL; ?>superadmin/metrics?' + params.toString())
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateChartsWithData(data.metrics);
                App.showAlert('success', 'Filtros aplicados exitosamente');
            } else {
                App.showAlert('danger', 'Error al aplicar filtros: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión al aplicar filtros');
        });
}

function updateChartsWithData(metrics) {
    // This function would update the charts with new filtered data
    // For now, it will just reload the page with the new parameters
    const formData = new FormData(document.getElementById('metricsFiltersForm'));
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    window.location.href = '<?php echo BASE_URL; ?>superadmin/metrics?' + params.toString();
}
</script>