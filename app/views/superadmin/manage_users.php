<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-users text-primary"></i> 
                        Gestionar Usuarios
                    </h1>
                    <p class="text-muted mb-0">
                        Administración de usuarios del sistema Multi-Restaurante
                    </p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>superadmin" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Panel
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

    <!-- Users Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-primary">
                                <?php 
                                $superadminCount = 0;
                                if (isset($users)) {
                                    $superadminCount = count(array_filter($users, function($user) {
                                        return $user['role'] === 'superadmin';
                                    }));
                                }
                                echo number_format($superadminCount);
                                ?>
                            </div>
                            <div class="stat-label">Superadmins</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-crown text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-warning">
                                <?php 
                                $adminCount = 0;
                                if (isset($users)) {
                                    $adminCount = count(array_filter($users, function($user) {
                                        return $user['role'] === 'admin';
                                    }));
                                }
                                echo number_format($adminCount);
                                ?>
                            </div>
                            <div class="stat-label">Administradores</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-shield text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-info">
                                <?php 
                                $hostessCount = 0;
                                if (isset($users)) {
                                    $hostessCount = count(array_filter($users, function($user) {
                                        return $user['role'] === 'hostess';
                                    }));
                                }
                                echo number_format($hostessCount);
                                ?>
                            </div>
                            <div class="stat-label">Hostess</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-concierge-bell text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stat-number text-success">
                                <?php echo isset($users) ? number_format(count($users)) : '0'; ?>
                            </div>
                            <div class="stat-label">Total Usuarios</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Usuarios
                    </h5>
                    <div>
                        <button class="btn btn-primary btn-sm" disabled>
                            <i class="fas fa-plus"></i> Crear Usuario
                        </button>
                        <small class="text-muted ms-2">(Próximamente)</small>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Restaurante</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                            </td>
                                            <td>
                                                <?php 
                                                $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                                                echo htmlspecialchars($fullName ?: 'N/A');
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?>
                                            </td>
                                            <td>
                                                <?php
                                                $roleClass = [
                                                    'superadmin' => 'bg-primary',
                                                    'admin' => 'bg-warning',
                                                    'hostess' => 'bg-info'
                                                ];
                                                $roleIcon = [
                                                    'superadmin' => 'fa-crown',
                                                    'admin' => 'fa-user-shield',
                                                    'hostess' => 'fa-concierge-bell'
                                                ];
                                                $userRole = $user['role'] ?? 'user';
                                                ?>
                                                <span class="badge <?php echo $roleClass[$userRole] ?? 'bg-secondary'; ?>">
                                                    <i class="fas <?php echo $roleIcon[$userRole] ?? 'fa-user'; ?>"></i>
                                                    <?php echo ucfirst($userRole); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($user['restaurant_id'])): ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-store"></i> ID: <?php echo $user['restaurant_id']; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Activo
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" disabled>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted d-block mt-1">Próximamente</small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay usuarios registrados</h5>
                            <p class="text-muted">Los usuarios aparecerán cuando se registren en el sistema.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3 d-md-flex">
                        <button class="btn btn-outline-primary" disabled>
                            <i class="fas fa-plus"></i> Crear Usuario
                        </button>
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-file-export"></i> Exportar Lista
                        </button>
                        <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-outline-info">
                            <i class="fas fa-store"></i> Ver Restaurantes
                        </a>
                        <a href="<?php echo BASE_URL; ?>superadmin" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i> Volver al Panel
                        </a>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> 
                        Las funciones de creación y edición están en desarrollo y estarán disponibles próximamente.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>