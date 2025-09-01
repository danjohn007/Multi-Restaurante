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