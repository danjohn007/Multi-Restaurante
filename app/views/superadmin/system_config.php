<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-cog text-primary"></i> 
                        Configuración del Sistema
                    </h1>
                    <p class="text-muted mb-0">
                        Configuración global del sistema Multi-Restaurante
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

    <!-- System Information -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Aplicación:</span>
                                <strong><?php echo APP_NAME; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Versión:</span>
                                <strong><?php echo APP_VERSION; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>PHP:</span>
                                <strong><?php echo PHP_VERSION; ?></strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Zona Horaria:</span>
                                <strong><?php echo date_default_timezone_get(); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Fecha/Hora:</span>
                                <strong><?php echo date('Y-m-d H:i:s'); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Base URL:</span>
                                <strong class="text-truncate"><?php echo BASE_URL; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-server"></i> Estado del Servidor
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Servidor Web:</span>
                                <strong><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'No disponible'; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>SSL:</span>
                                <strong class="<?php echo (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'text-success' : 'text-warning'; ?>">
                                    <?php echo (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'Activo' : 'Inactivo'; ?>
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Memoria PHP:</span>
                                <strong><?php echo ini_get('memory_limit'); ?></strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Max Upload:</span>
                                <strong><?php echo ini_get('upload_max_filesize'); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Max Execution:</span>
                                <strong><?php echo ini_get('max_execution_time'); ?>s</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Display Errors:</span>
                                <strong class="<?php echo ini_get('display_errors') ? 'text-warning' : 'text-success'; ?>">
                                    <?php echo ini_get('display_errors') ? 'Activo' : 'Inactivo'; ?>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Sections -->
    <div class="row mb-4">
        <!-- General Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sliders-h"></i> Configuración General
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="app_name" class="form-label">Nombre de la Aplicación</label>
                            <input type="text" class="form-control" id="app_name" value="<?php echo APP_NAME; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="app_timezone" class="form-label">Zona Horaria</label>
                            <select class="form-control" id="app_timezone" disabled>
                                <option selected>America/Mexico_City</option>
                                <option>America/New_York</option>
                                <option>America/Los_Angeles</option>
                                <option>Europe/Madrid</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="app_language" class="form-label">Idioma por Defecto</label>
                            <select class="form-control" id="app_language" disabled>
                                <option selected>Español (es)</option>
                                <option>English (en)</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" disabled>
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <small class="text-muted d-block mt-2">Funcionalidad en desarrollo</small>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt"></i> Configuración de Seguridad
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="force_ssl" checked disabled>
                                <label class="form-check-label" for="force_ssl">
                                    Forzar HTTPS
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enable_logs" checked disabled>
                                <label class="form-check-label" for="enable_logs">
                                    Habilitar Logs de Sistema
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="session_timeout" class="form-label">Tiempo de Sesión (minutos)</label>
                            <input type="number" class="form-control" id="session_timeout" value="30" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="max_login_attempts" class="form-label">Máximo Intentos de Login</label>
                            <input type="number" class="form-control" id="max_login_attempts" value="5" disabled>
                        </div>
                        <button type="button" class="btn btn-warning" disabled>
                            <i class="fas fa-save"></i> Guardar Seguridad
                        </button>
                        <small class="text-muted d-block mt-2">Funcionalidad en desarrollo</small>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Email & Notifications -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope"></i> Configuración de Email
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="smtp_host" class="form-label">Servidor SMTP</label>
                            <input type="text" class="form-control" id="smtp_host" placeholder="smtp.gmail.com" disabled>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="smtp_port" class="form-label">Puerto</label>
                                    <input type="number" class="form-control" id="smtp_port" value="587" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="smtp_security" class="form-label">Seguridad</label>
                                    <select class="form-control" id="smtp_security" disabled>
                                        <option>TLS</option>
                                        <option>SSL</option>
                                        <option>None</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="smtp_username" class="form-label">Usuario SMTP</label>
                            <input type="email" class="form-control" id="smtp_username" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="smtp_password" class="form-label">Contraseña SMTP</label>
                            <input type="password" class="form-control" id="smtp_password" disabled>
                        </div>
                        <button type="button" class="btn btn-info" disabled>
                            <i class="fas fa-paper-plane"></i> Probar Email
                        </button>
                        <button type="button" class="btn btn-primary" disabled>
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <small class="text-muted d-block mt-2">Funcionalidad en desarrollo</small>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell"></i> Configuración de Notificaciones
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_reservations" checked disabled>
                                <label class="form-check-label" for="email_reservations">
                                    Email para nuevas reservas
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_cancellations" checked disabled>
                                <label class="form-check-label" for="email_cancellations">
                                    Email para cancelaciones
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_reports" disabled>
                                <label class="form-check-label" for="email_reports">
                                    Reportes automáticos por email
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Email de Administrador</label>
                            <input type="email" class="form-control" id="admin_email" placeholder="admin@multi-restaurante.com" disabled>
                        </div>
                        <button type="button" class="btn btn-success" disabled>
                            <i class="fas fa-save"></i> Guardar Notificaciones
                        </button>
                        <small class="text-muted d-block mt-2">Funcionalidad en desarrollo</small>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- System Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools"></i> Acciones del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3 d-md-flex">
                        <button class="btn btn-outline-info" disabled>
                            <i class="fas fa-sync"></i> Limpiar Caché
                        </button>
                        <button class="btn btn-outline-warning" disabled>
                            <i class="fas fa-broom"></i> Limpiar Logs
                        </button>
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-download"></i> Backup Base de Datos
                        </button>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="fas fa-cog"></i> Optimizar Sistema
                        </button>
                        <a href="<?php echo BASE_URL; ?>superadmin" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i> Volver al Panel
                        </a>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> 
                        Las funciones de configuración están en desarrollo y estarán disponibles próximamente.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>