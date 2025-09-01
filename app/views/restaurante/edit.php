<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-store text-primary"></i> 
                        Gestión de Restaurantes
                    </h1>
                    <p class="text-muted mb-0">
                        Listado y edición de restaurantes del sistema
                    </p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>restaurante/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Restaurante
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['total_restaurants'] ?? 0; ?></div>
                            <div class="small">Total Restaurantes</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-store fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['active_restaurants'] ?? 0; ?></div>
                            <div class="small">Activos</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['inactive_restaurants'] ?? 0; ?></div>
                            <div class="small">Inactivos</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-pause-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['total_reservations'] ?? 0; ?></div>
                            <div class="small">Reservaciones Hoy</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Restaurants Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Restaurantes
                    </h5>
                    <div class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchRestaurants" 
                                   placeholder="Buscar restaurantes...">
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php 
                    $restaurants = $restaurants ?? [];
                    if (empty($restaurants)): 
                    ?>
                        <div class="text-center py-5">
                            <i class="fas fa-store fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay restaurantes registrados</h4>
                            <p class="text-muted mb-4">
                                Comienza creando tu primer restaurante en el sistema.
                            </p>
                            <a href="<?php echo BASE_URL; ?>restaurante/create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primer Restaurante
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="restaurantsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Restaurante</th>
                                        <th>Tipo Cocina</th>
                                        <th>Contacto</th>
                                        <th>Estado</th>
                                        <th>Palabras Clave</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="fas fa-store"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo htmlspecialchars($restaurant['name']); ?></div>
                                                        <small class="text-muted">
                                                            <?php echo htmlspecialchars(substr($restaurant['description'] ?? '', 0, 50)); ?>
                                                            <?php if (strlen($restaurant['description'] ?? '') > 50): ?>...<?php endif; ?>
                                                        </small>
                                                        <div class="mt-1">
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar"></i> 
                                                                Creado: <?php echo date('d/m/Y', strtotime($restaurant['created_at'])); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo htmlspecialchars($restaurant['food_type'] ?? 'General'); ?>
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <div class="small">
                                                    <?php if ($restaurant['email']): ?>
                                                        <div class="mb-1">
                                                            <i class="fas fa-envelope text-muted"></i> 
                                                            <?php echo htmlspecialchars($restaurant['email']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($restaurant['phone']): ?>
                                                        <div class="mb-1">
                                                            <i class="fas fa-phone text-muted"></i> 
                                                            <?php echo htmlspecialchars($restaurant['phone']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div>
                                                        <i class="fas fa-clock text-muted"></i> 
                                                        <?php echo htmlspecialchars($restaurant['opening_time'] ?? 'N/A'); ?> - 
                                                        <?php echo htmlspecialchars($restaurant['closing_time'] ?? 'N/A'); ?>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <?php if ($restaurant['is_active']): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Activo
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times"></i> Inactivo
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td>
                                                <div class="keywords-container">
                                                    <?php if ($restaurant['keywords']): ?>
                                                        <?php 
                                                        $keywords = array_slice(explode(',', $restaurant['keywords']), 0, 3);
                                                        foreach ($keywords as $keyword): 
                                                        ?>
                                                            <span class="badge bg-light text-dark me-1 mb-1">
                                                                <?php echo htmlspecialchars(trim($keyword)); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                        
                                                        <?php if (count(explode(',', $restaurant['keywords'])) > 3): ?>
                                                            <span class="badge bg-secondary">
                                                                +<?php echo count(explode(',', $restaurant['keywords'])) - 3; ?> más
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-secondary btn-add-keywords" 
                                                                data-restaurant-id="<?php echo $restaurant['id']; ?>">
                                                            <i class="fas fa-plus"></i> Agregar
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary btn-edit" 
                                                            data-restaurant-id="<?php echo $restaurant['id']; ?>"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    <button class="btn btn-sm btn-outline-info btn-view" 
                                                            data-restaurant-id="<?php echo $restaurant['id']; ?>"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    <button class="btn btn-sm btn-outline-warning btn-keywords" 
                                                            data-restaurant-id="<?php echo $restaurant['id']; ?>"
                                                            title="Gestionar Keywords">
                                                        <i class="fas fa-tags"></i>
                                                    </button>
                                                    
                                                    <button class="btn btn-sm btn-outline-secondary btn-toggle-status" 
                                                            data-restaurant-id="<?php echo $restaurant['id']; ?>"
                                                            data-status="<?php echo $restaurant['is_active'] ? '0' : '1'; ?>"
                                                            title="<?php echo $restaurant['is_active'] ? 'Desactivar' : 'Activar'; ?>">
                                                        <i class="fas fa-<?php echo $restaurant['is_active'] ? 'pause' : 'play'; ?>"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Restaurant Modal -->
<div class="modal fade" id="editRestaurantModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Editar Restaurante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRestaurantForm">
                    <input type="hidden" id="edit_restaurant_id" name="restaurant_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Nombre del Restaurante *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_food_type" class="form-label">Tipo de Cocina *</label>
                                <select class="form-select" id="edit_food_type" name="food_type" required>
                                    <option value="">Seleccionar tipo...</option>
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
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_phone" class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Dirección *</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_opening_time" class="form-label">Hora de Apertura *</label>
                                <input type="time" class="form-control" id="edit_opening_time" name="opening_time" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_closing_time" class="form-label">Hora de Cierre *</label>
                                <input type="time" class="form-control" id="edit_closing_time" name="closing_time" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveRestaurantBtn">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Keywords Modal -->
<div class="modal fade" id="keywordsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tags"></i> Gestionar Palabras Clave SEO
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="keywords_restaurant_id">
                <div class="mb-3">
                    <label for="restaurant_keywords" class="form-label">Palabras Clave</label>
                    <textarea class="form-control" id="restaurant_keywords" rows="3" 
                              placeholder="Ej: restaurante, pizza, comida italiana, Roma Norte"></textarea>
                    <div class="form-text">Separe las palabras clave con comas</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveKeywordsBtn">
                    <i class="fas fa-save"></i> Guardar Keywords
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchRestaurants').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#restaurantsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Edit restaurant
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const restaurantId = this.dataset.restaurantId;
            // Load restaurant data and show modal
            loadRestaurantData(restaurantId);
            new bootstrap.Modal(document.getElementById('editRestaurantModal')).show();
        });
    });

    // Save restaurant changes
    document.getElementById('saveRestaurantBtn').addEventListener('click', function() {
        const form = document.getElementById('editRestaurantForm');
        const formData = new FormData(form);
        
        // Submit via AJAX
        fetch('<?php echo BASE_URL; ?>restaurante/update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Restaurante actualizado exitosamente');
                bootstrap.Modal.getInstance(document.getElementById('editRestaurantModal')).hide();
                location.reload();
            } else {
                App.showAlert('danger', data.message || 'Error al actualizar restaurante');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        });
    });

    // Keywords management
    document.querySelectorAll('.btn-keywords, .btn-add-keywords').forEach(btn => {
        btn.addEventListener('click', function() {
            const restaurantId = this.dataset.restaurantId;
            document.getElementById('keywords_restaurant_id').value = restaurantId;
            // Load current keywords if any
            loadRestaurantKeywords(restaurantId);
            new bootstrap.Modal(document.getElementById('keywordsModal')).show();
        });
    });

    // Save keywords
    document.getElementById('saveKeywordsBtn').addEventListener('click', function() {
        const restaurantId = document.getElementById('keywords_restaurant_id').value;
        const keywords = document.getElementById('restaurant_keywords').value;
        
        fetch('<?php echo BASE_URL; ?>restaurante/update-keywords', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `restaurant_id=${restaurantId}&keywords=${encodeURIComponent(keywords)}&ajax=1`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Keywords actualizadas exitosamente');
                bootstrap.Modal.getInstance(document.getElementById('keywordsModal')).hide();
                location.reload();
            } else {
                App.showAlert('danger', data.message || 'Error al actualizar keywords');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        });
    });

    // Toggle restaurant status
    document.querySelectorAll('.btn-toggle-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const restaurantId = this.dataset.restaurantId;
            const newStatus = this.dataset.status;
            const action = newStatus === '1' ? 'activar' : 'desactivar';
            
            if (confirm(`¿Está seguro de ${action} este restaurante?`)) {
                fetch('<?php echo BASE_URL; ?>restaurante/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `restaurant_id=${restaurantId}&status=${newStatus}&ajax=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        App.showAlert('success', `Restaurante ${action}do exitosamente`);
                        location.reload();
                    } else {
                        App.showAlert('danger', data.message || 'Error al cambiar estado');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    App.showAlert('danger', 'Error de conexión');
                });
            }
        });
    });

    function loadRestaurantData(restaurantId) {
        fetch(`<?php echo BASE_URL; ?>restaurante/get/${restaurantId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const restaurant = data.restaurant;
                document.getElementById('edit_restaurant_id').value = restaurant.id;
                document.getElementById('edit_name').value = restaurant.name;
                document.getElementById('edit_food_type').value = restaurant.food_type;
                document.getElementById('edit_description').value = restaurant.description || '';
                document.getElementById('edit_phone').value = restaurant.phone;
                document.getElementById('edit_email').value = restaurant.email;
                document.getElementById('edit_address').value = restaurant.address;
                document.getElementById('edit_opening_time').value = restaurant.opening_time;
                document.getElementById('edit_closing_time').value = restaurant.closing_time;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function loadRestaurantKeywords(restaurantId) {
        fetch(`<?php echo BASE_URL; ?>restaurante/get/${restaurantId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('restaurant_keywords').value = data.restaurant.keywords || '';
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
</script>