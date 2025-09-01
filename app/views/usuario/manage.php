<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-users text-primary"></i> 
                        Gestión de Usuarios
                    </h1>
                    <p class="text-muted mb-0">
                        Administración de usuarios del sistema Multi-Restaurante
                    </p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-user-plus"></i> Nuevo Usuario
                    </button>
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

    <!-- User Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['total_users'] ?? 0; ?></div>
                            <div class="small">Total Usuarios</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['active_users'] ?? 0; ?></div>
                            <div class="small">Usuarios Activos</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['admins_count'] ?? 0; ?></div>
                            <div class="small">Administradores</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-shield fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0"><?php echo $stats['hostess_count'] ?? 0; ?></div>
                            <div class="small">Hostess</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="searchUsers" class="form-label">Buscar Usuarios</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchUsers" 
                                       placeholder="Nombre, email o usuario...">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="filterRole" class="form-label">Filtrar por Rol</label>
                            <select class="form-select" id="filterRole">
                                <option value="">Todos los roles</option>
                                <option value="superadmin">Superadmin</option>
                                <option value="admin">Administrador</option>
                                <option value="hostess">Hostess</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="filterStatus" class="form-label">Filtrar por Estado</label>
                            <select class="form-select" id="filterStatus">
                                <option value="">Todos los estados</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" id="clearFilters">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-list"></i> Lista de Usuarios
                    </h6>
                </div>
                
                <div class="card-body">
                    <?php 
                    $users = $users ?? [];
                    if (empty($users)): 
                    ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay usuarios registrados</h4>
                            <p class="text-muted mb-4">
                                Comienza creando el primer usuario del sistema.
                            </p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                <i class="fas fa-user-plus"></i> Crear Primer Usuario
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="usersTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Rol</th>
                                        <th>Restaurante</th>
                                        <th>Estado</th>
                                        <th>Último Acceso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-<?php echo $user['role'] === 'superadmin' ? 'danger' : ($user['role'] === 'admin' ? 'primary' : 'info'); ?> text-white me-3">
                                                        <i class="fas fa-<?php echo $user['role'] === 'superadmin' ? 'crown' : ($user['role'] === 'admin' ? 'user-shield' : 'user-tie'); ?>"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($user['username']); ?>
                                                        </small>
                                                        <div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <span class="badge bg-<?php echo $user['role'] === 'superadmin' ? 'danger' : ($user['role'] === 'admin' ? 'primary' : 'info'); ?>">
                                                    <i class="fas fa-<?php echo $user['role'] === 'superadmin' ? 'crown' : ($user['role'] === 'admin' ? 'user-shield' : 'user-tie'); ?>"></i>
                                                    <?php echo ucfirst($user['role']); ?>
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <?php if (isset($user['restaurant_name']) && $user['restaurant_name']): ?>
                                                    <div class="small">
                                                        <i class="fas fa-store text-muted"></i>
                                                        <?php echo htmlspecialchars($user['restaurant_name']); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td>
                                                <?php if ($user['is_active']): ?>
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
                                                <small class="text-muted">
                                                    <?php if (isset($user['last_login']) && $user['last_login']): ?>
                                                        <i class="fas fa-clock"></i>
                                                        <?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?>
                                                    <?php else: ?>
                                                        <i class="fas fa-minus"></i> Nunca
                                                    <?php endif; ?>
                                                </small>
                                            </td>
                                            
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary btn-edit-user" 
                                                            data-user-id="<?php echo $user['id']; ?>"
                                                            title="Editar Usuario">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    <button class="btn btn-sm btn-outline-info btn-view-user" 
                                                            data-user-id="<?php echo $user['id']; ?>"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    <button class="btn btn-sm btn-outline-warning btn-reset-password" 
                                                            data-user-id="<?php echo $user['id']; ?>"
                                                            title="Restablecer Contraseña">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                    
                                                    <button class="btn btn-sm btn-outline-secondary btn-toggle-status" 
                                                            data-user-id="<?php echo $user['id']; ?>"
                                                            data-status="<?php echo $user['is_active'] ? '0' : '1'; ?>"
                                                            title="<?php echo $user['is_active'] ? 'Desactivar' : 'Activar'; ?>">
                                                        <i class="fas fa-<?php echo $user['is_active'] ? 'user-slash' : 'user-check'; ?>"></i>
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

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i> Crear Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createUserForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Rol *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Seleccionar rol...</option>
                                    <option value="admin">Administrador</option>
                                    <option value="hostess">Hostess</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="restaurant_id" class="form-label">Restaurante</label>
                                <select class="form-select" id="restaurant_id" name="restaurant_id">
                                    <option value="">Seleccionar restaurante...</option>
                                    <?php 
                                    $restaurants = $restaurants ?? [];
                                    foreach ($restaurants as $restaurant): 
                                    ?>
                                        <option value="<?php echo $restaurant['id']; ?>">
                                            <?php echo htmlspecialchars($restaurant['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                <div class="form-text">Mínimo 8 caracteres</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">
                            Usuario activo
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">
                    <i class="fas fa-save"></i> Crear Usuario
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit"></i> Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_first_name" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_last_name" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_username" class="form-label">Nombre de Usuario *</label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_role" class="form-label">Rol *</label>
                                <select class="form-select" id="edit_role" name="role" required>
                                    <option value="">Seleccionar rol...</option>
                                    <option value="admin">Administrador</option>
                                    <option value="hostess">Hostess</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_restaurant_id" class="form-label">Restaurante</label>
                                <select class="form-select" id="edit_restaurant_id" name="restaurant_id">
                                    <option value="">Seleccionar restaurante...</option>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <option value="<?php echo $restaurant['id']; ?>">
                                            <?php echo htmlspecialchars($restaurant['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                        <label class="form-check-label" for="edit_is_active">
                            Usuario activo
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="updateUserBtn">
                    <i class="fas fa-save"></i> Actualizar Usuario
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key"></i> Restablecer Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="resetPasswordForm">
                    <input type="hidden" id="reset_user_id" name="user_id">
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña *</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        <div class="form-text">Mínimo 8 caracteres</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirm" class="form-label">Confirmar Nueva Contraseña *</label>
                        <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="resetPasswordBtn">
                    <i class="fas fa-key"></i> Restablecer Contraseña
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search and filters
    const searchInput = document.getElementById('searchUsers');
    const roleFilter = document.getElementById('filterRole');
    const statusFilter = document.getElementById('filterStatus');
    
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;
        const rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const role = row.querySelector('.badge').textContent.toLowerCase().trim();
            const status = row.querySelector('.badge:last-of-type').textContent.toLowerCase().includes('activo');
            
            let showRow = true;
            
            if (searchTerm && !text.includes(searchTerm)) {
                showRow = false;
            }
            
            if (roleValue && !role.includes(roleValue)) {
                showRow = false;
            }
            
            if (statusValue && ((statusValue === '1' && !status) || (statusValue === '0' && status))) {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('keyup', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
    statusFilter.addEventListener('change', filterUsers);
    
    // Clear filters
    document.getElementById('clearFilters').addEventListener('click', function() {
        searchInput.value = '';
        roleFilter.value = '';
        statusFilter.value = '';
        filterUsers();
    });
    
    // Password confirmation validation
    function setupPasswordValidation(passwordId, confirmId) {
        const password = document.getElementById(passwordId);
        const confirmPassword = document.getElementById(confirmId);
        
        function validatePassword() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        
        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    }
    
    setupPasswordValidation('password', 'password_confirm');
    setupPasswordValidation('new_password', 'new_password_confirm');
    
    // Create user
    document.getElementById('saveUserBtn').addEventListener('click', function() {
        const form = document.getElementById('createUserForm');
        const formData = new FormData(form);
        formData.append('ajax', '1');
        
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        
        fetch('<?php echo BASE_URL; ?>usuario/create', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Usuario creado exitosamente');
                bootstrap.Modal.getInstance(document.getElementById('createUserModal')).hide();
                location.reload();
            } else {
                App.showAlert('danger', data.message || 'Error al crear usuario');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        });
    });
    
    // Edit user
    document.querySelectorAll('.btn-edit-user').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            loadUserData(userId);
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        });
    });
    
    // Update user
    document.getElementById('updateUserBtn').addEventListener('click', function() {
        const form = document.getElementById('editUserForm');
        const formData = new FormData(form);
        formData.append('ajax', '1');
        
        fetch('<?php echo BASE_URL; ?>usuario/update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Usuario actualizado exitosamente');
                bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                location.reload();
            } else {
                App.showAlert('danger', data.message || 'Error al actualizar usuario');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        });
    });
    
    // Reset password
    document.querySelectorAll('.btn-reset-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            document.getElementById('reset_user_id').value = userId;
            new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
        });
    });
    
    // Reset password submit
    document.getElementById('resetPasswordBtn').addEventListener('click', function() {
        const form = document.getElementById('resetPasswordForm');
        const formData = new FormData(form);
        formData.append('ajax', '1');
        
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        
        fetch('<?php echo BASE_URL; ?>usuario/reset-password', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Contraseña restablecida exitosamente');
                bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
            } else {
                App.showAlert('danger', data.message || 'Error al restablecer contraseña');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        });
    });
    
    // Toggle user status
    document.querySelectorAll('.btn-toggle-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const newStatus = this.dataset.status;
            const action = newStatus === '1' ? 'activar' : 'desactivar';
            
            if (confirm(`¿Está seguro de ${action} este usuario?`)) {
                fetch('<?php echo BASE_URL; ?>usuario/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}&status=${newStatus}&ajax=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        App.showAlert('success', `Usuario ${action}do exitosamente`);
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
    
    function loadUserData(userId) {
        fetch(`<?php echo BASE_URL; ?>usuario/get/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                document.getElementById('edit_user_id').value = user.id;
                document.getElementById('edit_first_name').value = user.first_name;
                document.getElementById('edit_last_name').value = user.last_name;
                document.getElementById('edit_username').value = user.username;
                document.getElementById('edit_email').value = user.email;
                document.getElementById('edit_role').value = user.role;
                document.getElementById('edit_restaurant_id').value = user.restaurant_id || '';
                document.getElementById('edit_phone').value = user.phone || '';
                document.getElementById('edit_is_active').checked = user.is_active == 1;
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
</script>

<style>
.avatar-circle {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 1rem;
}
</style>