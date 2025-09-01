<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-concierge-bell text-primary"></i> 
                        Panel Hostess
                    </h1>
                    <p class="text-muted mb-0">
                        Gestión de reservaciones y atención al cliente
                    </p>
                </div>
                <div>
                    <span class="badge bg-info px-3 py-2">
                        <i class="fas fa-concierge-bell"></i> Hostess
                    </span>
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
                                <?php echo number_format($stats['total_reservations']); ?>
                            </div>
                            <div class="stat-label">Reservaciones Hoy</div>
                        </div>
                        <div class="text-primary">
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
                                <?php echo number_format($stats['pending_checkins']); ?>
                            </div>
                            <div class="stat-label">Pendientes Check-in</div>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-clock fa-3x"></i>
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
                                <?php echo number_format($stats['completed_today']); ?>
                            </div>
                            <div class="stat-label">Completadas</div>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-3x"></i>
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
                                <?php echo number_format($stats['active_tables']); ?>
                            </div>
                            <div class="stat-label">Mesas Activas</div>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-utensils fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?php echo BASE_URL; ?>hostess/reservations" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-calendar-check"></i><br>
                                <small>Ver Reservaciones</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-lg w-100 mb-3" onclick="quickCheckIn()">
                                <i class="fas fa-user-check"></i><br>
                                <small>Check-in Rápido</small>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info btn-lg w-100 mb-3" onclick="viewTables()">
                                <i class="fas fa-utensils"></i><br>
                                <small>Estado de Mesas</small>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning btn-lg w-100 mb-3" onclick="newReservation()">
                                <i class="fas fa-plus"></i><br>
                                <small>Nueva Reservación</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Reservations -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day"></i> Reservaciones de Hoy
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($reservations)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Hora</th>
                                        <th>Cliente</th>
                                        <th>Personas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo date('H:i', strtotime($reservation['reservation_time'])); ?></strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($reservation['customer_name']); ?></strong>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($reservation['customer_phone']); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo $reservation['party_size']; ?> personas</span>
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
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <?php if ($reservation['status'] === 'confirmed'): ?>
                                                        <a href="<?php echo BASE_URL; ?>hostess/checkin/<?php echo $reservation['id']; ?>" 
                                                           class="btn btn-outline-success" title="Check-in">
                                                            <i class="fas fa-user-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($reservation['status'] === 'seated'): ?>
                                                        <a href="<?php echo BASE_URL; ?>hostess/billing/<?php echo $reservation['id']; ?>" 
                                                           class="btn btn-outline-primary" title="Facturar">
                                                            <i class="fas fa-receipt"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <button class="btn btn-outline-info" title="Ver Detalles" 
                                                            onclick="viewReservationDetails(<?php echo $reservation['id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay reservaciones para hoy</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Table Status -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-utensils"></i> Estado de Mesas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tables)): ?>
                        <div class="row">
                            <?php foreach ($tables as $table): ?>
                                <div class="col-6 mb-3">
                                    <div class="card table-status <?php echo $table['status'] === 'occupied' ? 'border-warning' : 'border-success'; ?>">
                                        <div class="card-body text-center p-2">
                                            <div class="table-number mb-1">
                                                <strong>Mesa <?php echo $table['table_number']; ?></strong>
                                            </div>
                                            <small class="text-muted"><?php echo $table['capacity']; ?> personas</small>
                                            <div class="mt-1">
                                                <span class="badge <?php echo $table['status'] === 'occupied' ? 'bg-warning' : 'bg-success'; ?>">
                                                    <?php echo $table['status'] === 'occupied' ? 'Ocupada' : 'Disponible'; ?>
                                                </span>
                                            </div>
                                            <?php if ($table['status'] === 'occupied' && !empty($table['customer_name'])): ?>
                                                <div class="mt-1">
                                                    <small class="text-muted"><?php echo htmlspecialchars($table['customer_name']); ?></small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-utensils fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay mesas configuradas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function quickCheckIn() {
    // Implement quick check-in functionality
    App.showAlert('info', 'Funcionalidad de check-in rápido en desarrollo');
}

function viewTables() {
    // Implement table view functionality
    App.showAlert('info', 'Vista detallada de mesas en desarrollo');
}

function newReservation() {
    // Implement new reservation functionality
    App.showAlert('info', 'Funcionalidad de nueva reservación en desarrollo');
}

function viewReservationDetails(reservationId) {
    // Implement reservation details view
    App.showAlert('info', 'Vista de detalles de reservación en desarrollo');
}
</script>

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

.table-status {
    transition: all 0.3s ease;
}

.table-status:hover {
    transform: scale(1.05);
}
</style>