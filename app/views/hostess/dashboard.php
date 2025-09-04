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
/**
 * Hostess Dashboard - Functional Quick Actions
 * Improvements to make shortcuts operational for key actions
 */

function quickCheckIn() {
    // Fetch pending check-ins and show modal
    fetch('<?php echo BASE_URL; ?>hostess/quickCheckinData')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showQuickCheckinModal(data.reservations);
            } else {
                App.showAlert('error', data.message || 'Error al cargar reservaciones pendientes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('error', 'Error de conexión al cargar datos');
        });
}

function viewTables() {
    // Fetch table status and show modal
    fetch('<?php echo BASE_URL; ?>hostess/tableStatusData')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showTableStatusModal(data.tables);
            } else {
                App.showAlert('error', data.message || 'Error al cargar estado de mesas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('error', 'Error de conexión al cargar datos');
        });
}

function newReservation() {
    // Show new reservation modal
    showNewReservationModal();
}

function viewReservationDetails(reservationId) {
    // Fetch reservation details and show modal
    fetch('<?php echo BASE_URL; ?>hostess/reservationDetails/' + reservationId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showReservationDetailsModal(data.reservation);
            } else {
                App.showAlert('error', data.message || 'Error al cargar detalles de la reservación');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('error', 'Error de conexión al cargar datos');
        });
}

// Modal functions for each feature
function showQuickCheckinModal(reservations) {
    let modalHtml = `
        <div class="modal fade" id="quickCheckinModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-user-check"></i> Check-in Rápido
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${reservations.length === 0 ? 
                            '<div class="text-center py-4"><i class="fas fa-check-circle fa-3x text-success mb-3"></i><h5>¡Todo al día!</h5><p class="text-muted">No hay reservaciones pendientes de check-in</p></div>' :
                            `<div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>Hora</th><th>Cliente</th><th>Personas</th><th>Acción</th></tr></thead>
                                    <tbody>
                                        ${reservations.map(r => `
                                            <tr>
                                                <td><strong>${r.reservation_time.substring(0,5)}</strong></td>
                                                <td>${r.customer_name}<br><small class="text-muted">${r.customer_phone}</small></td>
                                                <td><span class="badge bg-secondary">${r.party_size}</span></td>
                                                <td>
                                                    <a href="<?php echo BASE_URL; ?>hostess/checkin/${r.id}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-user-check"></i> Check-in
                                                    </a>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>`
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    document.getElementById('quickCheckinModal')?.remove();
    
    // Add modal to body and show
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    new bootstrap.Modal(document.getElementById('quickCheckinModal')).show();
}

function showTableStatusModal(tables) {
    let modalHtml = `
        <div class="modal fade" id="tableStatusModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-utensils"></i> Estado de Mesas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${tables.length === 0 ? 
                            '<div class="text-center py-4"><i class="fas fa-utensils fa-3x text-muted mb-3"></i><p class="text-muted">No hay mesas configuradas</p></div>' :
                            `<div class="row">
                                ${tables.map(table => `
                                    <div class="col-md-4 mb-3">
                                        <div class="card ${table.status === 'occupied' ? 'border-warning' : 'border-success'}">
                                            <div class="card-body text-center">
                                                <h6>Mesa ${table.table_number}</h6>
                                                <p class="text-muted mb-2">${table.capacity} personas</p>
                                                <span class="badge ${table.status === 'occupied' ? 'bg-warning' : 'bg-success'}">
                                                    ${table.status === 'occupied' ? 'Ocupada' : 'Disponible'}
                                                </span>
                                                ${table.customer_name ? `<br><small class="text-muted mt-1">${table.customer_name}</small>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>`
                        }
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo BASE_URL; ?>admin/tables" class="btn btn-primary">
                            <i class="fas fa-cog"></i> Gestionar Mesas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('tableStatusModal')?.remove();
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    new bootstrap.Modal(document.getElementById('tableStatusModal')).show();
}

function showNewReservationModal() {
    let modalHtml = `
        <div class="modal fade" id="newReservationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus"></i> Nueva Reservación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="newReservationForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre del Cliente *</label>
                                    <input type="text" name="customer_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono *</label>
                                    <input type="tel" name="customer_phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="customer_email" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Número de Personas *</label>
                                    <select name="party_size" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        ${Array.from({length: 12}, (_, i) => `<option value="${i+1}">${i+1} persona${i > 0 ? 's' : ''}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha *</label>
                                    <input type="date" name="reservation_date" class="form-control" 
                                           min="${new Date().toISOString().split('T')[0]}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hora *</label>
                                    <input type="time" name="reservation_time" class="form-control" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Solicitudes Especiales</label>
                                    <textarea name="special_requests" class="form-control" rows="3" 
                                              placeholder="Ej: Mesa junto a la ventana, silla alta para bebé..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Reservación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('newReservationModal')?.remove();
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    const modal = new bootstrap.Modal(document.getElementById('newReservationModal'));
    modal.show();
    
    // Set default date to today
    document.querySelector('input[name="reservation_date"]').value = new Date().toISOString().split('T')[0];
    
    // Handle form submission
    document.getElementById('newReservationForm').onsubmit = function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('<?php echo BASE_URL; ?>hostess/createReservation', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', data.message);
                modal.hide();
                // Refresh page to show new reservation
                setTimeout(() => location.reload(), 1500);
            } else {
                App.showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('error', 'Error al crear la reservación');
        });
    };
}

function showReservationDetailsModal(reservation) {
    let statusBadge = {
        'confirmed': '<span class="badge bg-warning">Confirmada</span>',
        'seated': '<span class="badge bg-info">En Mesa</span>',
        'completed': '<span class="badge bg-success">Completada</span>',
        'cancelled': '<span class="badge bg-danger">Cancelada</span>'
    };
    
    let modalHtml = `
        <div class="modal fade" id="reservationDetailsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-eye"></i> Detalles de Reservación #${reservation.id}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Cliente:</strong><br>
                                ${reservation.customer_name}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Teléfono:</strong><br>
                                <a href="tel:${reservation.customer_phone}">${reservation.customer_phone}</a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong><br>
                                ${reservation.customer_email ? `<a href="mailto:${reservation.customer_email}">${reservation.customer_email}</a>` : 'No proporcionado'}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Personas:</strong><br>
                                ${reservation.party_size}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Fecha:</strong><br>
                                ${new Date(reservation.reservation_date).toLocaleDateString('es-ES')}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Hora:</strong><br>
                                ${reservation.reservation_time.substring(0,5)}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Estado:</strong><br>
                                ${statusBadge[reservation.status] || reservation.status}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Creada:</strong><br>
                                ${new Date(reservation.created_at).toLocaleString('es-ES')}
                            </div>
                            ${reservation.special_requests ? 
                                `<div class="col-12 mb-3">
                                    <strong>Solicitudes Especiales:</strong><br>
                                    <p class="text-muted">${reservation.special_requests}</p>
                                </div>` : ''
                            }
                            ${reservation.notes ? 
                                `<div class="col-12 mb-3">
                                    <strong>Notas:</strong><br>
                                    <p class="text-muted">${reservation.notes}</p>
                                </div>` : ''
                            }
                        </div>
                    </div>
                    <div class="modal-footer">
                        ${reservation.status === 'confirmed' ? 
                            `<a href="<?php echo BASE_URL; ?>hostess/checkin/${reservation.id}" class="btn btn-success">
                                <i class="fas fa-user-check"></i> Check-in
                            </a>` : ''
                        }
                        ${reservation.status === 'seated' ? 
                            `<a href="<?php echo BASE_URL; ?>hostess/billing/${reservation.id}" class="btn btn-primary">
                                <i class="fas fa-receipt"></i> Facturar
                            </a>` : ''
                        }
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('reservationDetailsModal')?.remove();
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    new bootstrap.Modal(document.getElementById('reservationDetailsModal')).show();
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