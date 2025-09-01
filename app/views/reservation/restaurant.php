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
                            <h1 class="h2 mb-2"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                            <p class="text-muted mb-2"><?php echo htmlspecialchars($restaurant['description'] ?? ''); ?></p>
                            
                            <div class="row text-muted small">
                                <div class="col-md-6">
                                    <?php if ($restaurant['food_type']): ?>
                                        <div class="mb-1">
                                            <i class="fas fa-utensils"></i> 
                                            <strong>Tipo:</strong> <?php echo htmlspecialchars($restaurant['food_type']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($restaurant['opening_time'] && $restaurant['closing_time']): ?>
                                        <div class="mb-1">
                                            <i class="fas fa-clock"></i> 
                                            <strong>Horario:</strong> 
                                            <?php echo date('H:i', strtotime($restaurant['opening_time'])); ?> - 
                                            <?php echo date('H:i', strtotime($restaurant['closing_time'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($restaurant['phone']): ?>
                                        <div class="mb-1">
                                            <i class="fas fa-phone"></i> 
                                            <strong>Teléfono:</strong> <?php echo htmlspecialchars($restaurant['phone']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($restaurant['address']): ?>
                                        <div class="mb-1">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <strong>Dirección:</strong> <?php echo htmlspecialchars($restaurant['address']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>/reserve" 
                               class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus"></i><br>
                                <small>Reservar Mesa</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability Checker -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search"></i> Verificar Disponibilidad
                    </h5>
                </div>
                <div class="card-body">
                    <form id="availabilityForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="checkDate" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="checkDate" 
                                       value="<?php echo $today; ?>" min="<?php echo $today; ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="checkTime" class="form-label">Hora</label>
                                <select class="form-select" id="checkTime">
                                    <?php 
                                    $openTime = strtotime($restaurant['opening_time'] ?? '11:00:00');
                                    $closeTime = strtotime($restaurant['closing_time'] ?? '22:00:00');
                                    
                                    for ($time = $openTime; $time <= $closeTime; $time += 1800): // 30 min intervals
                                        $timeStr = date('H:i', $time);
                                        $selected = ($timeStr === '19:00') ? 'selected' : '';
                                        echo "<option value=\"{$timeStr}\" {$selected}>{$timeStr}</option>";
                                    endfor;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="partySize" class="form-label">Número de Personas</label>
                                <select class="form-select" id="partySize">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($i === 2) ? 'selected' : ''; ?>>
                                            <?php echo $i; ?> persona<?php echo ($i > 1) ? 's' : ''; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" onclick="checkAvailability()">
                                    <i class="fas fa-search"></i> Verificar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Tables -->
    <div class="row mb-4" id="availabilityResults" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle text-success"></i> Mesas Disponibles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="availableTables">
                        <!-- Tables will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Tables Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-utensils"></i> Información de Mesas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($tables)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay información de mesas disponible</h5>
                            <p class="text-muted">
                                Por favor contacte al restaurante directamente para hacer una reservación.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($tables as $table): ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">
                                                Mesa <?php echo htmlspecialchars($table['table_number']); ?>
                                            </h6>
                                            <div class="mb-2">
                                                <i class="fas fa-users fa-2x text-primary"></i>
                                            </div>
                                            <p class="card-text">
                                                <strong><?php echo $table['capacity']; ?></strong> personas
                                            </p>
                                            
                                            <?php if ($table['notes']): ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> 
                                                    <?php echo htmlspecialchars($table['notes']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-muted mb-3">
                                <i class="fas fa-info-circle"></i> 
                                Para reservar una mesa específica, utilice el verificador de disponibilidad arriba.
                            </p>
                            
                            <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>/reserve" 
                               class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus"></i> Hacer Reservación
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkAvailability() {
    const date = document.getElementById('checkDate').value;
    const time = document.getElementById('checkTime').value;
    const partySize = document.getElementById('partySize').value;
    
    if (!date || !time || !partySize) {
        App.showAlert('warning', 'Por favor complete todos los campos');
        return;
    }
    
    // Show loading
    const resultsDiv = document.getElementById('availabilityResults');
    const tablesDiv = document.getElementById('availableTables');
    
    resultsDiv.style.display = 'block';
    tablesDiv.innerHTML = '<div class="col-12 text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    // Check availability
    fetch(`<?php echo BASE_URL; ?>api/restaurants/<?php echo $restaurant['id']; ?>/availability?date=${date}&time=${time}&party_size=${partySize}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAvailableTables(data.tables, date, time, partySize);
            } else {
                tablesDiv.innerHTML = '<div class="col-12"><div class="alert alert-warning">Error al verificar disponibilidad</div></div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tablesDiv.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error de conexión</div></div>';
        });
}

function displayAvailableTables(tables, date, time, partySize) {
    const tablesDiv = document.getElementById('availableTables');
    
    if (tables.length === 0) {
        tablesDiv.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>No hay mesas disponibles</h5>
                    <p class="mb-3">
                        No hay mesas disponibles para ${partySize} persona${partySize > 1 ? 's' : ''} 
                        el ${formatDate(date)} a las ${time}.
                    </p>
                    <p class="mb-0">
                        <small>Intente con un horario diferente o contacte al restaurante directamente.</small>
                    </p>
                </div>
            </div>
        `;
        return;
    }
    
    tablesDiv.innerHTML = tables.map(table => `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6 class="card-title text-success">
                        Mesa ${table.table_number}
                    </h6>
                    <div class="mb-2">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <p class="card-text">
                        <strong>${table.capacity}</strong> personas
                    </p>
                    <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>/reserve?date=${date}&time=${time}&party_size=${partySize}&table_id=${table.id}" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-calendar-plus"></i> Reservar
                    </a>
                </div>
            </div>
        </div>
    `).join('');
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('es-MX', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Auto-check availability when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Only auto-check if it's today and current time is within restaurant hours
    const now = new Date();
    const currentTime = now.getHours() + ':' + String(now.getMinutes()).padStart(2, '0');
    
    <?php if ($restaurant['opening_time'] && $restaurant['closing_time']): ?>
    const openTime = '<?php echo $restaurant['opening_time']; ?>';
    const closeTime = '<?php echo $restaurant['closing_time']; ?>';
    
    if (currentTime >= openTime && currentTime <= closeTime) {
        setTimeout(checkAvailability, 1000);
    }
    <?php endif; ?>
});
</script>