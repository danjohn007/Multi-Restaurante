<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-calendar-check text-primary"></i> 
                        Gestión de Reservaciones
                    </h1>
                    <p class="text-muted mb-0">Administrar reservaciones del restaurante</p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>hostess" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Panel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="<?php echo $selectedDate; ?>" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="setDate('today')">Hoy</button>
                                <button type="button" class="btn btn-outline-primary" onclick="setDate('tomorrow')">Mañana</button>
                                <button type="button" class="btn btn-outline-primary" onclick="setDate('week')">Esta Semana</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Reservaciones para <?php echo date('d/m/Y', strtotime($selectedDate)); ?>
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
                                        <th>Contacto</th>
                                        <th>Personas</th>
                                        <th>Mesa</th>
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
                                                <strong><?php echo htmlspecialchars($reservation['customer_name']); ?></strong>
                                                <?php if (!empty($reservation['special_requests'])): ?>
                                                    <br><small class="text-muted">
                                                        <i class="fas fa-comment"></i> 
                                                        <?php echo htmlspecialchars(substr($reservation['special_requests'], 0, 50)); ?>
                                                        <?php if (strlen($reservation['special_requests']) > 50) echo '...'; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="fas fa-phone"></i> 
                                                    <?php echo htmlspecialchars($reservation['customer_phone']); ?>
                                                </div>
                                                <?php if (!empty($reservation['customer_email'])): ?>
                                                    <div>
                                                        <i class="fas fa-envelope"></i> 
                                                        <?php echo htmlspecialchars($reservation['customer_email']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo $reservation['party_size']; ?> personas
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($reservation['table_ids'])): ?>
                                                    <span class="badge bg-info">
                                                        Mesa <?php echo $reservation['table_ids']; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin asignar</span>
                                                <?php endif; ?>
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
                                                            onclick="viewDetails(<?php echo $reservation['id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    <div class="btn-group">
                                                        <button class="btn btn-outline-secondary dropdown-toggle" 
                                                                data-bs-toggle="dropdown" title="Más opciones">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#" onclick="editReservation(<?php echo $reservation['id']; ?>)">
                                                                <i class="fas fa-edit"></i> Editar
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">
                                                                <i class="fas fa-times"></i> Cancelar
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay reservaciones</h4>
                            <p class="text-muted mb-4">
                                No se encontraron reservaciones para la fecha seleccionada.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setDate(period) {
    const dateInput = document.getElementById('date');
    const today = new Date();
    
    switch(period) {
        case 'today':
            dateInput.value = today.toISOString().split('T')[0];
            break;
        case 'tomorrow':
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.value = tomorrow.toISOString().split('T')[0];
            break;
        case 'week':
            // For now, just go to today
            dateInput.value = today.toISOString().split('T')[0];
            break;
    }
    
    dateInput.form.submit();
}

function viewDetails(reservationId) {
    App.showAlert('info', 'Vista de detalles en desarrollo');
}

function editReservation(reservationId) {
    App.showAlert('info', 'Funcionalidad de edición en desarrollo');
}

function cancelReservation(reservationId) {
    if (confirm('¿Está seguro de cancelar esta reservación?')) {
        App.showAlert('info', 'Funcionalidad de cancelación en desarrollo');
    }
}
</script>