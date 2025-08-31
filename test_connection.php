<?php
/**
 * Connection Test and Base URL Confirmation
 * This file tests the database connection and confirms the auto-detected base URL
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/Database.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .test-card {
            margin: 20px auto;
            max-width: 600px;
        }
        .status-success {
            color: #198754;
        }
        .status-error {
            color: #dc3545;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card test-card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-cogs"></i> Test de Conexión - <?php echo APP_NAME; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Base URL Test -->
                        <div class="mb-4">
                            <h5><i class="fas fa-link"></i> Configuración de URL Base</h5>
                            <div class="alert alert-info">
                                <strong>URL Base Auto-detectada:</strong><br>
                                <code><?php echo BASE_URL; ?></code>
                            </div>
                            <small class="text-muted">
                                Esta URL se detecta automáticamente y se usa para generar enlaces en toda la aplicación.
                            </small>
                        </div>

                        <!-- Database Connection Test -->
                        <div class="mb-4">
                            <h5><i class="fas fa-database"></i> Test de Conexión a Base de Datos</h5>
                            <?php
                            try {
                                $db = Database::getInstance();
                                $isConnected = $db->testConnection();
                                
                                if ($isConnected) {
                                    echo '<div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> 
                                            <strong>Conexión Exitosa</strong><br>
                                            La conexión a la base de datos MySQL está funcionando correctamente.
                                          </div>';
                                    
                                    // Test database info
                                    $conn = $db->getConnection();
                                    $stmt = $conn->query("SELECT VERSION() as version");
                                    $version = $stmt->fetch()['version'];
                                    
                                    echo '<div class="row">
                                            <div class="col-md-6">
                                                <strong>Host:</strong> ' . DB_HOST . '<br>
                                                <strong>Base de Datos:</strong> ' . DB_NAME . '<br>
                                                <strong>Usuario:</strong> ' . DB_USER . '
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Versión MySQL:</strong> ' . $version . '<br>
                                                <strong>Estado:</strong> <span class="status-success">Conectado</span>
                                            </div>
                                          </div>';
                                } else {
                                    echo '<div class="alert alert-danger">
                                            <i class="fas fa-times-circle"></i> 
                                            <strong>Error de Conexión</strong><br>
                                            No se pudo conectar a la base de datos.
                                          </div>';
                                }
                            } catch (Exception $e) {
                                echo '<div class="alert alert-danger">
                                        <i class="fas fa-times-circle"></i> 
                                        <strong>Error de Conexión</strong><br>
                                        ' . htmlspecialchars($e->getMessage()) . '
                                      </div>';
                                      
                                echo '<div class="alert alert-warning">
                                        <strong>Pasos para solucionar:</strong>
                                        <ol>
                                            <li>Verificar que MySQL esté ejecutándose</li>
                                            <li>Crear la base de datos: <code>CREATE DATABASE ' . DB_NAME . ';</code></li>
                                            <li>Ajustar las credenciales en <code>config/config.php</code></li>
                                            <li>Ejecutar el script de instalación: <code>database/install.sql</code></li>
                                        </ol>
                                      </div>';
                            }
                            ?>
                        </div>

                        <!-- System Information -->
                        <div class="mb-4">
                            <h5><i class="fas fa-info-circle"></i> Información del Sistema</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Versión PHP:</strong> <?php echo PHP_VERSION; ?><br>
                                    <strong>Aplicación:</strong> <?php echo APP_NAME . ' v' . APP_VERSION; ?><br>
                                    <strong>Zona Horaria:</strong> <?php echo date_default_timezone_get(); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Fecha/Hora:</strong> <?php echo date('Y-m-d H:i:s'); ?><br>
                                    <strong>Servidor:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'No disponible'; ?><br>
                                    <strong>SSL:</strong> <?php echo (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'Activo' : 'Inactivo'; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="alert alert-light">
                            <h6><i class="fas fa-arrow-right"></i> Próximos Pasos</h6>
                            <ul class="mb-0">
                                <li>Ejecutar el script de instalación: <code>database/install.sql</code></li>
                                <li>Configurar el servidor web para usar <code>public/</code> como DocumentRoot</li>
                                <li>Acceder a la aplicación: <a href="<?php echo BASE_URL; ?>public/" target="_blank"><?php echo BASE_URL; ?>public/</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>