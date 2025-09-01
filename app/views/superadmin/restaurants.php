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
                        Administración de todos los restaurantes del sistema
                    </p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>superadmin/restaurants/inactive" class="btn btn-outline-warning me-2">
                        <i class="fas fa-store-slash"></i> Ver Inactivos
                    </a>
                    <a href="<?php echo BASE_URL; ?>superadmin/restaurants/create" class="btn btn-primary">
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

    <!-- Restaurants List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Lista de Restaurantes
                            <span class="badge bg-primary ms-2"><?php echo count($restaurants); ?></span>
                        </h5>
                        
                        <div class="d-flex gap-2">
                            <div class="input-group" style="width: 300px;">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchRestaurants" 
                                       placeholder="Buscar restaurantes...">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php if (empty($restaurants)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-store fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay restaurantes registrados</h4>
                            <p class="text-muted mb-4">
                                Comienza creando tu primer restaurante en el sistema.
                            </p>
                            <a href="<?php echo BASE_URL; ?>superadmin/restaurants/create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primer Restaurante
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="restaurantsTable">
                                <thead>
                                    <tr>
                                        <th>Restaurante</th>
                                        <th>Tipo de Comida</th>
                                        <th>Contacto</th>
                                        <th>Estadísticas</th>
                                        <th>Horarios</th>
                                        <th>Estado</th>
                                        <th>Keywords SEO</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <tr data-restaurant-id="<?php echo $restaurant['id']; ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
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
                                                         class="rounded me-3" 
                                                         width="60" height="60"
                                                         alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                                         style="object-fit: cover;"
                                                         onerror="this.src='<?php echo BASE_URL; ?>public/images/restaurant-placeholder.svg'">
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
                                                    
                                                    <?php if ($restaurant['address']): ?>
                                                        <div>
                                                            <i class="fas fa-map-marker-alt text-muted"></i> 
                                                            <?php echo htmlspecialchars(substr($restaurant['address'], 0, 30)); ?>
                                                            <?php if (strlen($restaurant['address']) > 30): ?>...<?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="small">
                                                    <div class="mb-1">
                                                        <i class="fas fa-calendar-alt text-primary"></i> 
                                                        <strong><?php echo number_format($restaurant['total_reservations']); ?></strong> reservaciones
                                                    </div>
                                                    <div class="mb-1">
                                                        <i class="fas fa-check-circle text-success"></i> 
                                                        <strong><?php echo number_format($restaurant['completed_reservations']); ?></strong> completadas
                                                    </div>
                                                    <div class="mb-1">
                                                        <i class="fas fa-dollar-sign text-success"></i> 
                                                        <strong>$<?php echo number_format($restaurant['total_revenue'], 2); ?></strong>
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-utensils text-info"></i> 
                                                        <strong><?php echo number_format($restaurant['total_tables']); ?></strong> mesas
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <?php if ($restaurant['opening_time'] && $restaurant['closing_time']): ?>
                                                    <div class="small">
                                                        <i class="fas fa-clock text-muted"></i> 
                                                        <?php echo date('H:i', strtotime($restaurant['opening_time'])); ?> - 
                                                        <?php echo date('H:i', strtotime($restaurant['closing_time'])); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">No definido</span>
                                                <?php endif; ?>
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
                                                            <i class="fas fa-tags"></i> Agregar
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       target="_blank"
                                                       title="Ver página pública">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <a href="<?php echo BASE_URL; ?>superadmin/restaurants/<?php echo $restaurant['id']; ?>/edit" 
                                                       class="btn btn-sm btn-outline-warning"
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <button class="btn btn-sm btn-outline-info btn-edit-keywords" 
                                                            data-restaurant-id="<?php echo $restaurant['id']; ?>"
                                                            data-keywords="<?php echo htmlspecialchars($restaurant['keywords'] ?? ''); ?>"
                                                            title="Editar Keywords SEO">
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

<!-- Keywords Modal -->
<div class="modal fade" id="keywordsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tags"></i> Editar Keywords SEO
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="keywordsForm">
                    <input type="hidden" id="keywordsRestaurantId">
                    
                    <div class="mb-3">
                        <label for="keywords" class="form-label">
                            Keywords (separadas por comas)
                        </label>
                        <textarea class="form-control" id="keywords" rows="4" 
                                  placeholder="tacos, quesadillas, comida mexicana, tradicional, auténtico"></textarea>
                        <div class="form-text">
                            Estas palabras clave ayudarán a los usuarios a encontrar el restaurante en las búsquedas.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="saveKeywords">
                    <i class="fas fa-save"></i> Guardar Keywords
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchRestaurants');
    const table = document.getElementById('restaurantsTable');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        Array.from(rows).forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
    
    // Keywords modal
    const keywordsModal = new bootstrap.Modal(document.getElementById('keywordsModal'));
    
    document.querySelectorAll('.btn-edit-keywords, .btn-add-keywords').forEach(btn => {
        btn.addEventListener('click', function() {
            const restaurantId = this.dataset.restaurantId;
            const keywords = this.dataset.keywords || '';
            
            document.getElementById('keywordsRestaurantId').value = restaurantId;
            document.getElementById('keywords').value = keywords;
            
            keywordsModal.show();
        });
    });
    
    // Save keywords
    document.getElementById('saveKeywords').addEventListener('click', function() {
        const restaurantId = document.getElementById('keywordsRestaurantId').value;
        const keywords = document.getElementById('keywords').value;
        
        fetch('<?php echo BASE_URL; ?>superadmin/restaurants/' + restaurantId + '/keywords', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'keywords=' + encodeURIComponent(keywords) + '&ajax=1'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Keywords actualizadas exitosamente');
                keywordsModal.hide();
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
                fetch('<?php echo BASE_URL; ?>superadmin/restaurants/' + restaurantId + '/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'status=' + newStatus + '&ajax=1'
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
});
</script>