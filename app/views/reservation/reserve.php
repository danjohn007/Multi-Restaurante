<div class="container py-4">
    <!-- Restaurant Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="<?php 
                                if (!empty($restaurant['logo_url'])) {
                                    // Check if it's already a full URL or relative path
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
                                 style="object-fit: cover; max-height: 120px;"
                                 onerror="this.src='<?php echo BASE_URL; ?>public/images/restaurant-placeholder.svg'">
                        </div>
                        <div class="col-md-8">
                            <h1 class="h2 mb-2">Reservar Mesa en <?php echo htmlspecialchars($restaurant['name']); ?></h1>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($restaurant['description'] ?? ''); ?></p>
                        </div>
                        <div class="col-md-2 text-center">
                            <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
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

    <!-- Reservation Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus"></i> Datos de la Reservación
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>/reserve" 
                          method="POST" id="reservationForm" class="ajax-form">
                        
                        <!-- Reservation Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle"></i> Detalles de la Reservación
                                </h6>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="reservation_date" class="form-label">Fecha *</label>
                                <input type="date" class="form-control" id="reservation_date" name="reservation_date" 
                                       value="<?php echo $selectedDate; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="reservation_time" class="form-label">Hora *</label>
                                <select class="form-select" id="reservation_time" name="reservation_time" required>
                                    <?php 
                                    $openTime = strtotime($restaurant['opening_time'] ?? '11:00:00');
                                    $closeTime = strtotime($restaurant['closing_time'] ?? '22:00:00');
                                    
                                    for ($time = $openTime; $time <= $closeTime; $time += 1800): // 30 min intervals
                                        $timeStr = date('H:i', $time);
                                        $selected = ($timeStr === $selectedTime) ? 'selected' : '';
                                        echo "<option value=\"{$timeStr}\" {$selected}>{$timeStr}</option>";
                                    endfor;
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="party_size" class="form-label">Número de Personas *</label>
                                <select class="form-select" id="party_size" name="party_size" required>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($i == $partySize) ? 'selected' : ''; ?>>
                                            <?php echo $i; ?> persona<?php echo ($i > 1) ? 's' : ''; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user"></i> Información del Cliente
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                       placeholder="Nombre y apellido" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                                       placeholder="+52 55 1234 5678" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Email (opcional)</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                       placeholder="cliente@email.com">
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-comment"></i> Solicitudes Especiales
                                </h6>
                                
                                <textarea class="form-control" id="special_requests" name="special_requests" 
                                          rows="3" placeholder="Celebración especial, alergias, preferencias de mesa, etc."></textarea>
                                <div class="form-text">
                                    Opcional: Cualquier solicitud especial o información adicional.
                                </div>
                            </div>
                        </div>

                        <!-- Available Tables Preview -->
                        <div class="row mb-4" id="tableAvailabilitySection" style="display: none;">
                            <div class="col-12">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-check-circle"></i> Mesas Disponibles
                                </h6>
                                <div id="availableTablesContainer">
                                    <!-- Available tables will be displayed here -->
                                </div>
                                <div id="selectedTableInfo" class="alert alert-info mt-3" style="display: none;">
                                    <!-- Selected table info will be displayed here -->
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>" 
                                       class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="button" class="btn btn-primary" onclick="checkAvailabilityBeforeSubmit()">
                                        <i class="fas fa-calendar-check"></i> Verificar Disponibilidad
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                        <i class="fas fa-check"></i> Confirmar Reservación
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar with Information -->
        <div class="col-lg-4">
            <!-- Restaurant Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información del Restaurante
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($restaurant['opening_time'] && $restaurant['closing_time']): ?>
                        <div class="mb-3">
                            <strong><i class="fas fa-clock text-primary"></i> Horario:</strong><br>
                            <?php echo date('H:i', strtotime($restaurant['opening_time'])); ?> - 
                            <?php echo date('H:i', strtotime($restaurant['closing_time'])); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($restaurant['phone']): ?>
                        <div class="mb-3">
                            <strong><i class="fas fa-phone text-primary"></i> Teléfono:</strong><br>
                            <a href="tel:<?php echo $restaurant['phone']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($restaurant['phone']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($restaurant['address']): ?>
                        <div class="mb-3">
                            <strong><i class="fas fa-map-marker-alt text-primary"></i> Dirección:</strong><br>
                            <?php echo htmlspecialchars($restaurant['address']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($restaurant['email']): ?>
                        <div class="mb-0">
                            <strong><i class="fas fa-envelope text-primary"></i> Email:</strong><br>
                            <a href="mailto:<?php echo $restaurant['email']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($restaurant['email']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reservation Tips -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb"></i> Consejos para tu Reservación
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Las reservaciones se confirman automáticamente
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-info"></i>
                            Llega 10 minutos antes de tu hora reservada
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone text-warning"></i>
                            Para cambios, contacta directamente al restaurante
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-utensils text-primary"></i>
                            Menciona cualquier alergia o preferencia alimentaria
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let availableTables = [];
let selectedTable = null;

function checkAvailabilityBeforeSubmit() {
    const date = document.getElementById('reservation_date').value;
    const time = document.getElementById('reservation_time').value;
    const partySize = document.getElementById('party_size').value;
    
    if (!date || !time || !partySize) {
        App.showAlert('warning', 'Por favor complete los datos de la reservación');
        return;
    }
    
    // Show loading
    const checkBtn = event.target;
    const originalText = checkBtn.innerHTML;
    checkBtn.disabled = true;
    checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
    
    // Check availability
    fetch(`<?php echo BASE_URL; ?>api/restaurants/<?php echo $restaurant['id']; ?>/availability?date=${date}&time=${time}&party_size=${partySize}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.tables.length > 0) {
                availableTables = data.tables;
                showAvailableTables(data.tables);
                
                App.showAlert('success', 'Mesas disponibles encontradas. Seleccione una mesa para continuar.', 3000);
            } else {
                availableTables = [];
                selectedTable = null;
                document.getElementById('tableAvailabilitySection').style.display = 'none';
                document.getElementById('submitBtn').style.display = 'none';
                checkBtn.style.display = 'inline-block';
                
                App.showAlert('warning', 'No hay mesas disponibles para el horario seleccionado. Intente con otra fecha u horario.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error al verificar disponibilidad');
        })
        .finally(() => {
            checkBtn.disabled = false;
            checkBtn.innerHTML = originalText;
        });
}

function showAvailableTables(tables) {
    const section = document.getElementById('tableAvailabilitySection');
    const container = document.getElementById('availableTablesContainer');
    
    // Sort tables by capacity (smallest first that fits the party size)
    const partySize = parseInt(document.getElementById('party_size').value);
    const suitableTables = tables.filter(table => table.capacity >= partySize)
                                 .sort((a, b) => a.capacity - b.capacity);
    
    let tablesHtml = '<div class="row">';
    
    suitableTables.forEach((table, index) => {
        const isRecommended = index === 0; // First table is recommended (smallest suitable)
        tablesHtml += `
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card table-option ${isRecommended ? 'border-success' : 'border-secondary'}" 
                     data-table-id="${table.id}" onclick="selectTable(${table.id})">
                    <div class="card-body text-center">
                        <div class="table-icon mb-2">
                            <i class="fas fa-utensils fa-2x ${isRecommended ? 'text-success' : 'text-muted'}"></i>
                        </div>
                        <h6 class="card-title">Mesa ${table.table_number}</h6>
                        <p class="card-text">
                            <small class="text-muted">Capacidad: ${table.capacity} personas</small>
                            ${table.location ? `<br><small class="text-muted">${table.location}</small>` : ''}
                            ${isRecommended ? '<br><span class="badge bg-success">Recomendada</span>' : ''}
                        </p>
                    </div>
                </div>
            </div>
        `;
    });
    
    tablesHtml += '</div>';
    
    if (suitableTables.length === 0) {
        tablesHtml = '<div class="alert alert-warning">No se encontraron mesas adecuadas para el número de personas.</div>';
    }
    
    container.innerHTML = tablesHtml;
    section.style.display = 'block';
    
    // Auto-select the recommended table
    if (suitableTables.length > 0) {
        selectTable(suitableTables[0].id);
    }
}

function selectTable(tableId) {
    // Remove previous selection
    document.querySelectorAll('.table-option').forEach(card => {
        card.classList.remove('border-primary', 'selected');
        card.style.backgroundColor = '';
    });
    
    // Mark new selection
    const selectedCard = document.querySelector(`[data-table-id="${tableId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('border-primary', 'selected');
        selectedCard.style.backgroundColor = '#e3f2fd';
    }
    
    // Find selected table data
    selectedTable = availableTables.find(table => table.id == tableId);
    
    if (selectedTable) {
        // Show selected table info
        const infoDiv = document.getElementById('selectedTableInfo');
        infoDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-utensils fa-2x text-primary me-3"></i>
                <div>
                    <strong>Mesa ${selectedTable.table_number} seleccionada</strong><br>
                    <small class="text-muted">
                        Capacidad: ${selectedTable.capacity} personas
                        ${selectedTable.location ? ` • ${selectedTable.location}` : ''}
                    </small>
                </div>
                <div class="ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeTableSelection()">
                        <i class="fas fa-exchange-alt"></i> Cambiar Mesa
                    </button>
                </div>
            </div>
        `;
        infoDiv.style.display = 'block';
        
        // Show submit button
        document.getElementById('submitBtn').style.display = 'inline-block';
        document.querySelector('[onclick="checkAvailabilityBeforeSubmit()"]').style.display = 'none';
    }
}

function changeTableSelection() {
    // Hide selected table info and show table options again
    document.getElementById('selectedTableInfo').style.display = 'none';
    document.getElementById('availableTablesContainer').style.display = 'block';
    document.getElementById('submitBtn').style.display = 'none';
    
    // Remove selection
    selectedTable = null;
    document.querySelectorAll('.table-option').forEach(card => {
        card.classList.remove('border-primary', 'selected');
        card.style.backgroundColor = '';
    });
}

// Form validation and submission
document.getElementById('reservationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate required fields
    const requiredFields = ['customer_name', 'customer_phone', 'reservation_date', 'reservation_time', 'party_size'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        App.showAlert('warning', 'Por favor complete todos los campos requeridos');
        return;
    }
    
    // Validate phone format
    const phone = document.getElementById('customer_phone').value;
    if (!App.utils.isValidPhone(phone)) {
        App.showAlert('warning', 'Por favor ingrese un número de teléfono válido');
        document.getElementById('customer_phone').classList.add('is-invalid');
        return;
    }
    
    // Validate email format if provided
    const email = document.getElementById('customer_email').value;
    if (email && !App.utils.isValidEmail(email)) {
        App.showAlert('warning', 'Por favor ingrese un email válido');
        document.getElementById('customer_email').classList.add('is-invalid');
        return;
    }
    
    // Check if table is selected
    if (!selectedTable) {
        App.showAlert('warning', 'Debe seleccionar una mesa antes de confirmar la reservación');
        return;
    }
    
    // Show confirmation popup with reservation summary
    showReservationConfirmation(this);
});

// Auto-check availability if coming from restaurant page with parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('date') && urlParams.get('time') && urlParams.get('party_size')) {
        setTimeout(checkAvailabilityBeforeSubmit, 1000);
    }
});

// Date and time change handlers
document.getElementById('reservation_date').addEventListener('change', function() {
    resetAvailability();
});

document.getElementById('reservation_time').addEventListener('change', function() {
    resetAvailability();
});

document.getElementById('party_size').addEventListener('change', function() {
    resetAvailability();
});

function resetAvailability() {
    availableTables = [];
    selectedTable = null;
    document.getElementById('tableAvailabilitySection').style.display = 'none';
    document.getElementById('submitBtn').style.display = 'none';
    document.querySelector('[onclick="checkAvailabilityBeforeSubmit()"]').style.display = 'inline-block';
}

// Show reservation confirmation popup
function showReservationConfirmation(form) {
    const formData = new FormData(form);
    const customerName = formData.get('customer_name');
    const customerPhone = formData.get('customer_phone');
    const customerEmail = formData.get('customer_email') || 'No especificado';
    const reservationDate = formData.get('reservation_date');
    const reservationTime = formData.get('reservation_time');
    const partySize = formData.get('party_size');
    const specialRequests = formData.get('special_requests') || 'Ninguna';
    
    // Format date for display
    const dateObj = new Date(reservationDate);
    const formattedDate = dateObj.toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    const confirmationHTML = `
        <div class="modal fade" id="reservationConfirmModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i>
                            Confirmar Reservación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Información del Cliente</h6>
                                <p><strong>Nombre:</strong> ${customerName}</p>
                                <p><strong>Teléfono:</strong> ${customerPhone}</p>
                                <p><strong>Email:</strong> ${customerEmail}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Detalles de la Reservación</h6>
                                <p><strong>Fecha:</strong> ${formattedDate}</p>
                                <p><strong>Hora:</strong> ${reservationTime}</p>
                                <p><strong>Personas:</strong> ${partySize}</p>
                                <p><strong>Mesa:</strong> ${selectedTable.name} (Capacidad: ${selectedTable.capacity})</p>
                            </div>
                        </div>
                        ${specialRequests !== 'Ninguna' ? `
                            <div class="mt-3">
                                <h6 class="text-primary">Solicitudes Especiales</h6>
                                <p>${specialRequests}</p>
                            </div>
                        ` : ''}
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>¡Importante!</strong> Por favor verifique que todos los datos sean correctos antes de confirmar su reservación.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" id="confirmReservationBtn">
                            <i class="fas fa-check me-2"></i>Confirmar Reservación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('reservationConfirmModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', confirmationHTML);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('reservationConfirmModal'));
    modal.show();
    
    // Handle confirmation
    document.getElementById('confirmReservationBtn').addEventListener('click', function() {
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        
        // Disable button to prevent double submission
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
        
        modal.hide();
        
        // Show processing alert
        App.showAlert('info', 'Procesando su reservación...', 2000);
        
        // Now submit the form
        const formData = new FormData(form);
        formData.append('selected_table_id', selectedTable.id);
        formData.append('ajax', '1');
        
        App.submitFormAjaxWithData(form, formData);
    });
}
</script>

<style>
.table-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.table-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.table-option.selected {
    background-color: #e3f2fd !important;
    border-color: #2196f3 !important;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.table-icon {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>