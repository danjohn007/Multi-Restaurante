<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-utensils text-primary"></i> 
                        Gestión de Mesas
                    </h1>
                    <p class="text-muted mb-0">Configurar y administrar las mesas del restaurante</p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>admin/tables/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Mesa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Mesas Configuradas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tables)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Capacidad</th>
                                        <th>Ubicación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tables as $table): ?>
                                        <tr>
                                            <td>
                                                <strong>Mesa <?php echo $table['table_number']; ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo $table['capacity']; ?> personas
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($table['location'] ?? 'Sin especificar'); ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $table['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo $table['is_active'] ? 'Activa' : 'Inactiva'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="editTable(<?php echo $table['id']; ?>)" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-<?php echo $table['is_active'] ? 'warning' : 'success'; ?>" 
                                                            onclick="toggleTableStatus(<?php echo $table['id']; ?>, <?php echo $table['is_active'] ? 0 : 1; ?>)"
                                                            title="<?php echo $table['is_active'] ? 'Desactivar' : 'Activar'; ?>">
                                                        <i class="fas fa-<?php echo $table['is_active'] ? 'eye-slash' : 'eye'; ?>"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" onclick="deleteTable(<?php echo $table['id']; ?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
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
                            <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay mesas configuradas</h4>
                            <p class="text-muted mb-4">
                                Comience agregando mesas para su restaurante.
                            </p>
                            <a href="<?php echo BASE_URL; ?>admin/tables/create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Mesa
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editTable(tableId) {
    App.showAlert('info', 'Funcionalidad de edición en desarrollo');
}

function toggleTableStatus(tableId, newStatus) {
    const action = newStatus ? 'activar' : 'desactivar';
    if (confirm(`¿Está seguro de ${action} esta mesa?`)) {
        App.showAlert('info', 'Funcionalidad de cambio de estado en desarrollo');
    }
}

function deleteTable(tableId) {
    if (confirm('¿Está seguro de eliminar esta mesa? Esta acción no se puede deshacer.')) {
        App.showAlert('info', 'Funcionalidad de eliminación en desarrollo');
    }
}
</script>