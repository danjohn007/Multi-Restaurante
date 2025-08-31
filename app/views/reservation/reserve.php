<div class="container py-4">
    <!-- Restaurant Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="<?php echo $restaurant['logo_url'] ?? BASE_URL . 'public/images/restaurant-placeholder.jpg'; ?>" 
                                 class="img-fluid rounded" 
                                 alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
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
                                    <i class="fas fa-check-circle"></i> Mesa Preseleccionada
                                </h6>
                                <div id="selectedTableInfo" class="alert alert-success">
                                    <!-- Table info will be displayed here -->
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
                
                // Show submit button
                document.getElementById('submitBtn').style.display = 'inline-block';
                checkBtn.style.display = 'none';
                
                App.showAlert('success', 'Mesa disponible encontrada. Puede proceder con la reservación.', 3000);
            } else {
                App.showAlert('warning', 'No hay mesas disponibles para el horario seleccionado. Intente con otra fecha u horario.');
                
                // Hide submit button
                document.getElementById('submitBtn').style.display = 'none';
                checkBtn.style.display = 'inline-block';
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
    const infoDiv = document.getElementById('selectedTableInfo');
    
    // Show the best table (smallest that fits)
    const bestTable = tables.reduce((best, current) => 
        current.capacity < best.capacity ? current : best
    );
    
    infoDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-utensils fa-2x text-success me-3"></i>
            <div>
                <strong>Mesa ${bestTable.table_number}</strong> preseleccionada<br>
                <small class="text-muted">Capacidad: ${bestTable.capacity} personas</small>
            </div>
        </div>
    `;
    
    section.style.display = 'block';
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
    
    // Check if tables are available
    if (availableTables.length === 0) {
        App.showAlert('warning', 'Debe verificar la disponibilidad antes de confirmar la reservación');
        return;
    }
    
    // Submit form
    App.submitFormAjax(this);
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
    document.getElementById('tableAvailabilitySection').style.display = 'none';
    document.getElementById('submitBtn').style.display = 'none';
    document.querySelector('[onclick="checkAvailabilityBeforeSubmit()"]').style.display = 'inline-block';
}
</script>