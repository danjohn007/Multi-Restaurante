<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-shield text-primary"></i> 
                        Panel Administrador
                    </h1>
                    <p class="text-muted mb-0">
                        <?php echo htmlspecialchars($restaurant['name']); ?>
                    </p>
                </div>
                <div>
                    <span class="badge bg-primary px-3 py-2">
                        <i class="fas fa-user-shield"></i> Admin
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Restaurant Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="<?php 
                                if (!empty($restaurant['logo_url'])) {
                                    if (strpos($restaurant['logo_url'], 'http') === 0) {
                                        echo htmlspecialchars($restaurant['logo_url']);
                                    } else {
                                        echo BASE_URL . 'uploads/restaurants/' . htmlspecialchars($restaurant['logo_url']);
                                    }
                                } else {
                                    echo BASE_URL . 'public/images/restaurant-placeholder.svg';
                                }
                            ?>" 
                                 class="img-fluid rounded" 
                                 alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                 style="object-fit: cover; max-height: 100px;"
                                 onerror="this.src='<?php echo BASE_URL; ?>public/images/restaurant-placeholder.svg'">
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-1"><?php echo htmlspecialchars($restaurant['name']); ?></h4>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($restaurant['description'] ?? ''); ?></p>
                            <div class="d-flex gap-3 flex-wrap">
                                <?php if ($restaurant['food_type']): ?>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($restaurant['food_type']); ?></span>
                                <?php endif; ?>
                                <?php if ($restaurant['phone']): ?>
                                    <small class="text-muted">
                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($restaurant['phone']); ?>
                                    </small>
                                <?php endif; ?>
                                <?php if ($restaurant['email']): ?>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($restaurant['email']); ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge <?php echo $restaurant['is_active'] ? 'bg-success' : 'bg-danger'; ?> p-2">
                                <i class="fas fa-<?php echo $restaurant['is_active'] ? 'check' : 'times'; ?>"></i>
                                <?php echo $restaurant['is_active'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-primary">
                                <?php echo number_format($stats['total_tables']); ?>
                            </div>
                            <div class="stat-label">Mesas Configuradas</div>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-utensils fa-3x"></i>
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
                                <?php echo number_format($stats['total_reservations']); ?>
                            </div>
                            <div class="stat-label">Total Reservaciones</div>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-calendar-alt fa-3x"></i>
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
                                <?php echo number_format($stats['today_reservations']); ?>
                            </div>
                            <div class="stat-label">Reservaciones Hoy</div>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-calendar-day fa-3x"></i>
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
                                <?php echo number_format($stats['total_staff']); ?>
                            </div>
                            <div class="stat-label">Personal</div>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
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
                        <a href="<?php echo BASE_URL; ?>admin/tables" class="btn btn-primary">
                            <i class="fas fa-utensils"></i> Gestionar Mesas
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>admin/profile" class="btn btn-outline-primary">
                            <i class="fas fa-store"></i> Perfil del Restaurante
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-info">
                            <i class="fas fa-users"></i> Gestionar Personal
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-success">
                            <i class="fas fa-chart-line"></i> Ver Reportes
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>hostess" class="btn btn-outline-secondary">
                            <i class="fas fa-concierge-bell"></i> Vista Hostess
                        </a>
                    </div>

                    <hr>

                    <h6 class="mb-3">
                        <i class="fas fa-info-circle"></i> Información
                    </h6>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between">
                            <span>Horario:</span>
                            <span>
                                <?php 
                                echo date('H:i', strtotime($restaurant['opening_time'])) . ' - ' . 
                                     date('H:i', strtotime($restaurant['closing_time'])); 
                                ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Estado:</span>
                            <span class="<?php echo $restaurant['is_active'] ? 'text-success' : 'text-danger'; ?>">
                                <?php echo $restaurant['is_active'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reservations -->
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i> Reservaciones Recientes
                    </h5>
                    <a href="<?php echo BASE_URL; ?>hostess/reservations" class="btn btn-sm btn-primary">
                        <i class="fas fa-calendar-check"></i> Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentReservations)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha/Hora</th>
                                        <th>Cliente</th>
                                        <th>Personas</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentReservations as $reservation): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></strong>
                                                </div>
                                                <small class="text-muted"><?php echo date('H:i', strtotime($reservation['reservation_time'])); ?></small>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($reservation['customer_name']); ?></strong>
                                                </div>
                                                <?php if (!empty($reservation['customer_phone'])): ?>
                                                    <small class="text-muted"><?php echo htmlspecialchars($reservation['customer_phone']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo $reservation['party_size']; ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'confirmed' => 'bg-warning',
                                                    'seated' => 'bg-info',
                                                    'completed' => 'bg-success',
                                                    'cancelled' => 'bg-danger'
                                                ];
                                                $statusText = [
                                                    'confirmed' => 'Confirmada',
                                                    'seated' => 'En Mesa',
                                                    'completed' => 'Completada',
                                                    'cancelled' => 'Cancelada'
                                                ];
                                                ?>
                                                <span class="badge <?php echo $statusClass[$reservation['status']] ?? 'bg-secondary'; ?>">
                                                    <?php echo $statusText[$reservation['status']] ?? 'Desconocido'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay reservaciones recientes</h5>
                            <p class="text-muted">Las nuevas reservaciones aparecerán aquí.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border-left: 4px solid #007bff;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}
</style>