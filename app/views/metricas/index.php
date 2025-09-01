<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-chart-line text-primary"></i> 
                        Panel de Métricas del Sistema
                    </h1>
                    <p class="text-muted mb-0">
                        Análisis y estadísticas del rendimiento del sistema Multi-Restaurante
                    </p>
                </div>
                <div>
                    <button class="btn btn-outline-primary" id="refreshMetrics">
                        <i class="fas fa-sync-alt"></i> Actualizar Datos
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fas fa-download"></i> Exportar Reporte
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h3 mb-0"><?php echo $metrics['total_restaurants'] ?? 0; ?></div>
                            <div class="small">Restaurantes Totales</div>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    +<?php echo $metrics['new_restaurants_month'] ?? 0; ?> este mes
                                </span>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-store fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h3 mb-0"><?php echo $metrics['total_reservations'] ?? 0; ?></div>
                            <div class="small">Reservaciones Totales</div>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    <?php echo $metrics['reservations_today'] ?? 0; ?> hoy
                                </span>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h3 mb-0"><?php echo $metrics['total_customers'] ?? 0; ?></div>
                            <div class="small">Clientes Registrados</div>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    +<?php echo $metrics['new_customers_week'] ?? 0; ?> esta semana
                                </span>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h3 mb-0"><?php echo number_format($metrics['avg_occupancy'] ?? 0, 1); ?>%</div>
                            <div class="small">Ocupación Promedio</div>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    <?php echo $metrics['peak_hours'] ?? 'N/A'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-pie fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Reservations Trend -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-chart-line"></i> Tendencia de Reservaciones (Últimos 30 días)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="reservationsTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Restaurant Types Distribution -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-chart-pie"></i> Tipos de Cocina
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="cuisineTypesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Tables Row -->
    <div class="row mb-4">
        <!-- Top Performing Restaurants -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-trophy"></i> Restaurantes Más Populares
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Restaurante</th>
                                    <th>Reservaciones</th>
                                    <th>Ocupación</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $topRestaurants = $metrics['top_restaurants'] ?? [];
                                foreach ($topRestaurants as $restaurant): 
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-store"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold small"><?php echo htmlspecialchars($restaurant['name']); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($restaurant['food_type']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo $restaurant['reservations']; ?></span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: <?php echo $restaurant['occupancy']; ?>%" 
                                                     aria-valuenow="<?php echo $restaurant['occupancy']; ?>" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small><?php echo number_format($restaurant['occupancy'], 1); ?>%</small>
                                        </td>
                                        <td>
                                            <div class="text-warning">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star<?php echo $i <= $restaurant['rating'] ? '' : '-o'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Performance -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-tachometer-alt"></i> Rendimiento del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center mb-3">
                                <h4 class="text-success"><?php echo number_format($metrics['avg_response_time'] ?? 0, 2); ?>s</h4>
                                <small class="text-muted">Tiempo de Respuesta Promedio</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center mb-3">
                                <h4 class="text-info"><?php echo number_format($metrics['uptime'] ?? 0, 1); ?>%</h4>
                                <small class="text-muted">Disponibilidad</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center mb-3">
                                <h4 class="text-warning"><?php echo $metrics['active_sessions'] ?? 0; ?></h4>
                                <small class="text-muted">Sesiones Activas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center mb-3">
                                <h4 class="text-primary"><?php echo $metrics['searches_today'] ?? 0; ?></h4>
                                <small class="text-muted">Búsquedas Hoy</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics -->
    <div class="row mb-4">
        <!-- Hourly Distribution -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-clock"></i> Distribución de Reservaciones por Hora
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="hourlyDistributionChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-users-cog"></i> Segmentos de Clientes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Clientes VIP</span>
                            <span class="small fw-bold"><?php echo $metrics['vip_customers'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-gold" style="width: <?php echo ($metrics['vip_customers'] ?? 0) / max(($metrics['total_customers'] ?? 1), 1) * 100; ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Clientes Frecuentes</span>
                            <span class="small fw-bold"><?php echo $metrics['frequent_customers'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: <?php echo ($metrics['frequent_customers'] ?? 0) / max(($metrics['total_customers'] ?? 1), 1) * 100; ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Clientes Ocasionales</span>
                            <span class="small fw-bold"><?php echo $metrics['occasional_customers'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: <?php echo ($metrics['occasional_customers'] ?? 0) / max(($metrics['total_customers'] ?? 1), 1) * 100; ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Nuevos Clientes</span>
                            <span class="small fw-bold"><?php echo $metrics['new_customers'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: <?php echo ($metrics['new_customers'] ?? 0) / max(($metrics['total_customers'] ?? 1), 1) * 100; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-history"></i> Actividad Reciente del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Evento</th>
                                    <th>Restaurante</th>
                                    <th>Usuario</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $recentActivity = $metrics['recent_activity'] ?? [];
                                foreach ($recentActivity as $activity): 
                                ?>
                                    <tr>
                                        <td>
                                            <small><?php echo date('d/m/Y H:i', strtotime($activity['created_at'])); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $activity['type'] === 'reservation' ? 'success' : ($activity['type'] === 'cancellation' ? 'danger' : 'info'); ?>">
                                                <?php echo ucfirst($activity['type']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?php echo htmlspecialchars($activity['restaurant_name']); ?></small>
                                        </td>
                                        <td>
                                            <small><?php echo htmlspecialchars($activity['user_name'] ?? 'Sistema'); ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo htmlspecialchars($activity['description']); ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-download"></i> Exportar Reporte de Métricas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Formato de Exportación</label>
                        <select class="form-select" id="export_format" name="format" required>
                            <option value="">Seleccionar formato...</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="export_period" class="form-label">Período</label>
                        <select class="form-select" id="export_period" name="period" required>
                            <option value="">Seleccionar período...</option>
                            <option value="today">Hoy</option>
                            <option value="week">Esta Semana</option>
                            <option value="month">Este Mes</option>
                            <option value="quarter">Trimestre</option>
                            <option value="year">Este Año</option>
                            <option value="custom">Período Personalizado</option>
                        </select>
                    </div>
                    
                    <div id="customPeriod" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Incluir Secciones</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_kpis" name="sections[]" value="kpis" checked>
                            <label class="form-check-label" for="include_kpis">
                                Indicadores Clave de Rendimiento
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_charts" name="sections[]" value="charts" checked>
                            <label class="form-check-label" for="include_charts">
                                Gráficas y Tendencias
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_restaurants" name="sections[]" value="restaurants" checked>
                            <label class="form-check-label" for="include_restaurants">
                                Rendimiento por Restaurante
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_customers" name="sections[]" value="customers">
                            <label class="form-check-label" for="include_customers">
                                Análisis de Clientes
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="generateReportBtn">
                    <i class="fas fa-download"></i> Generar Reporte
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
    // Refresh metrics
    document.getElementById('refreshMetrics').addEventListener('click', function() {
        location.reload();
    });
    
    // Export period change handler
    document.getElementById('export_period').addEventListener('change', function() {
        const customPeriod = document.getElementById('customPeriod');
        if (this.value === 'custom') {
            customPeriod.style.display = 'block';
        } else {
            customPeriod.style.display = 'none';
        }
    });
    
    // Generate report
    document.getElementById('generateReportBtn').addEventListener('click', function() {
        const form = document.getElementById('exportForm');
        const formData = new FormData(form);
        
        // Simulate report generation
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
        
        setTimeout(() => {
            App.showAlert('success', 'Reporte generado exitosamente. Se iniciará la descarga.');
            btn.disabled = false;
            btn.innerHTML = originalText;
            bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
        }, 2000);
    });
    
    function initializeCharts() {
        // Reservations Trend Chart
        const reservationsTrendCtx = document.getElementById('reservationsTrendChart');
        if (reservationsTrendCtx) {
            new Chart(reservationsTrendCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($metrics['chart_data']['reservation_dates'] ?? []); ?>,
                    datasets: [{
                        label: 'Reservaciones',
                        data: <?php echo json_encode($metrics['chart_data']['reservation_counts'] ?? []); ?>,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Cuisine Types Chart
        const cuisineTypesCtx = document.getElementById('cuisineTypesChart');
        if (cuisineTypesCtx) {
            new Chart(cuisineTypesCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($metrics['chart_data']['cuisine_labels'] ?? []); ?>,
                    datasets: [{
                        data: <?php echo json_encode($metrics['chart_data']['cuisine_counts'] ?? []); ?>,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Hourly Distribution Chart
        const hourlyDistributionCtx = document.getElementById('hourlyDistributionChart');
        if (hourlyDistributionCtx) {
            new Chart(hourlyDistributionCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($metrics['chart_data']['hourly_labels'] ?? []); ?>,
                    datasets: [{
                        label: 'Reservaciones por Hora',
                        data: <?php echo json_encode($metrics['chart_data']['hourly_counts'] ?? []); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}
.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}
.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #117a8b);
}
.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #d39e00);
}
.bg-gold {
    background: linear-gradient(45deg, #ffd700, #ffed4a);
}
.avatar-sm {
    width: 2rem;
    height: 2rem;
    font-size: 0.8rem;
}
</style>