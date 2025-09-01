<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-store-slash text-warning"></i> 
                        Restaurantes Inactivos
                    </h1>
                    <p class="text-muted mb-0">
                        Gestión de restaurantes desactivados temporalmente
                    </p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-store"></i> Ver Activos
                    </a>
                    <a href="<?php echo BASE_URL; ?>superadmin" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Panel Principal
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

    <!-- Inactive Restaurants List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Lista de Restaurantes Inactivos
                            <span class="badge bg-warning ms-2"><?php echo count($restaurants); ?></span>
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($restaurants)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Restaurante</th>
                                        <th>Tipo de Cocina</th>
                                        <th>Contacto</th>
                                        <th>Reservaciones</th>
                                        <th>Ingresos</th>
                                        <th>Desactivado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($restaurant['logo_url'])): ?>
                                                        <img src="<?php echo BASE_URL; ?>uploads/restaurants/<?php echo htmlspecialchars($restaurant['logo_url']); ?>" 
                                                             alt="Logo" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-store text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($restaurant['name']); ?></h6>
                                                        <small class="text-muted">ID: #<?php echo $restaurant['id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <?php echo htmlspecialchars($restaurant['food_type'] ?? 'N/A'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($restaurant['phone'] ?? 'N/A'); ?></div>
                                                    <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($restaurant['email'] ?? 'N/A'); ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo number_format($restaurant['total_reservations']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success">
                                                    $<?php echo number_format($restaurant['total_revenue'], 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo date('d/m/Y', strtotime($restaurant['updated_at'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-success btn-sm" 
                                                            onclick="reactivateRestaurant(<?php echo $restaurant['id']; ?>)"
                                                            title="Reactivar Restaurante">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                    <a href="<?php echo BASE_URL; ?>superadmin/restaurants/<?php echo $restaurant['id']; ?>/edit" 
                                                       class="btn btn-outline-primary btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-info btn-sm" 
                                                            onclick="viewRestaurantDetails(<?php echo $restaurant['id']; ?>)"
                                                            title="Ver Detalles">
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
                        <div class="text-center py-5">
                            <i class="fas fa-store-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay restaurantes inactivos</h5>
                            <p class="text-muted">Todos los restaurantes están actualmente activos en el sistema.</p>
                            <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-primary">
                                <i class="fas fa-store"></i> Ver Restaurantes Activos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <?php if (!empty($restaurants)): ?>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center bg-light">
                    <div class="card-body">
                        <h5 class="text-warning"><?php echo count($restaurants); ?></h5>
                        <small class="text-muted">Restaurantes Inactivos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-light">
                    <div class="card-body">
                        <h5 class="text-info"><?php echo array_sum(array_column($restaurants, 'total_reservations')); ?></h5>
                        <small class="text-muted">Reservaciones Históricas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-light">
                    <div class="card-body">
                        <h5 class="text-success">$<?php echo number_format(array_sum(array_column($restaurants, 'total_revenue')), 2); ?></h5>
                        <small class="text-muted">Ingresos Generados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-light">
                    <div class="card-body">
                        <h5 class="text-primary"><?php echo array_sum(array_column($restaurants, 'total_tables')); ?></h5>
                        <small class="text-muted">Mesas Totales</small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function reactivateRestaurant(restaurantId) {
    if (confirm('¿Está seguro de que desea reactivar este restaurante?')) {
        fetch('<?php echo BASE_URL; ?>superadmin/restaurants/' + restaurantId + '/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'status=active&ajax=1'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                App.showAlert('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        });
    }
}

function viewRestaurantDetails(restaurantId) {
    // Redirect to restaurant edit page for now
    window.location.href = '<?php echo BASE_URL; ?>superadmin/restaurants/' + restaurantId + '/edit';
}
</script>