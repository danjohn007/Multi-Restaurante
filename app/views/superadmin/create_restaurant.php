<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-store text-primary"></i> 
                        Crear Nuevo Restaurante
                    </h1>
                    <p class="text-muted mb-0">
                        Complete el formulario para dar de alta un nuevo restaurante
                    </p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
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

    <!-- Restaurant Form -->
    <div class="row">
        <div class="col-lg-8 col-xl-9">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-store"></i> Información del Restaurante
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>superadmin/restaurants/create" id="restaurantForm">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Restaurante *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="food_type" class="form-label">Tipo de Cocina *</label>
                                    <select class="form-select" id="food_type" name="food_type" required>
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
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Descripción del restaurante, especialidades, ambiente..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keywords" class="form-label">Palabras Clave SEO</label>
                            <input type="text" class="form-control" id="keywords" name="keywords" 
                                   placeholder="Ej: restaurante, pizza, comida italiana, Roma Norte">
                            <div class="form-text">Separe las palabras clave con comas. Ayuda a mejorar la visibilidad en búsquedas.</div>
                        </div>

                        <!-- Contact Information -->
                        <hr class="my-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-address-book"></i> Información de Contacto
                        </h6>

                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección *</label>
                            <textarea class="form-control" id="address" name="address" rows="2" required
                                      placeholder="Dirección completa del restaurante"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required
                                           placeholder="+52 55 1234 5678">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email de Contacto *</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           placeholder="contacto@restaurante.com">
                                </div>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <hr class="my-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-clock"></i> Horarios de Operación
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="opening_time" class="form-label">Hora de Apertura *</label>
                                    <input type="time" class="form-control" id="opening_time" name="opening_time" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="closing_time" class="form-label">Hora de Cierre *</label>
                                    <input type="time" class="form-control" id="closing_time" name="closing_time" required>
                                </div>
                            </div>
                        </div>

                        <!-- Admin User -->
                        <hr class="my-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-shield"></i> Usuario Administrador
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_first_name" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="admin_first_name" name="admin_first_name" required
                                           placeholder="Juan">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_last_name" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" id="admin_last_name" name="admin_last_name" required
                                           placeholder="Pérez">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_username" class="form-label">Nombre de Usuario *</label>
                                    <input type="text" class="form-control" id="admin_username" name="admin_username" required
                                           placeholder="admin_restaurante">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Email del Administrador *</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required
                                           placeholder="admin@restaurante.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="admin_phone" class="form-label">Teléfono del Admin</label>
                                    <input type="tel" class="form-control" id="admin_phone" name="admin_phone" 
                                           placeholder="+52 55 1234 5678">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Contraseña *</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password" required
                                           minlength="8">
                                    <div class="form-text">Mínimo 8 caracteres</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="admin_password_confirm" class="form-label">Confirmar Contraseña *</label>
                                    <input type="password" class="form-control" id="admin_password_confirm" name="admin_password_confirm" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-undo"></i> Limpiar Formulario
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Restaurante
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 col-xl-3">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información Importante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Campos Obligatorios</h6>
                        <p class="small text-muted">
                            Los campos marcados con (*) son obligatorios para crear el restaurante.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Usuario Administrador</h6>
                        <p class="small text-muted">
                            Se creará automáticamente un usuario administrador para gestionar este restaurante.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Palabras Clave SEO</h6>
                        <p class="small text-muted">
                            Ayudan a que el restaurante aparezca en las búsquedas de los clientes.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb"></i> Consejos
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Use una descripción atractiva
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Incluya palabras clave relevantes
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Verifique los datos de contacto
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Configure horarios precisos
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const password = document.getElementById('admin_password');
    const confirmPassword = document.getElementById('admin_password_confirm');
    
    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Las contraseñas no coinciden');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);
    
    // Form validation
    document.getElementById('restaurantForm').addEventListener('submit', function(e) {
        validatePassword();
        if (!confirmPassword.checkValidity()) {
            e.preventDefault();
            confirmPassword.reportValidity();
        }
    });
});
</script>