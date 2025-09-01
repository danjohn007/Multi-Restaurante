<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-edit text-primary"></i> 
                        Editar Restaurante
                    </h1>
                    <p class="text-muted mb-0">
                        Modifique la información del restaurante: <?php echo htmlspecialchars($restaurant['name'] ?? ''); ?>
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
                    <form method="POST" action="<?php echo BASE_URL; ?>superadmin/restaurants/<?php echo $restaurant['id']; ?>/edit" id="restaurantForm">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Restaurante *</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           value="<?php echo htmlspecialchars($restaurant['name'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="food_type" class="form-label">Tipo de Cocina *</label>
                                    <select class="form-select" id="food_type" name="food_type" required>
                                        <option value="">Seleccionar tipo...</option>
                                        <option value="Italiana" <?php echo ($restaurant['food_type'] ?? '') === 'Italiana' ? 'selected' : ''; ?>>Italiana</option>
                                        <option value="Mexicana" <?php echo ($restaurant['food_type'] ?? '') === 'Mexicana' ? 'selected' : ''; ?>>Mexicana</option>
                                        <option value="Japonesa" <?php echo ($restaurant['food_type'] ?? '') === 'Japonesa' ? 'selected' : ''; ?>>Japonesa</option>
                                        <option value="China" <?php echo ($restaurant['food_type'] ?? '') === 'China' ? 'selected' : ''; ?>>China</option>
                                        <option value="Americana" <?php echo ($restaurant['food_type'] ?? '') === 'Americana' ? 'selected' : ''; ?>>Americana</option>
                                        <option value="Argentina" <?php echo ($restaurant['food_type'] ?? '') === 'Argentina' ? 'selected' : ''; ?>>Argentina</option>
                                        <option value="Mediterránea" <?php echo ($restaurant['food_type'] ?? '') === 'Mediterránea' ? 'selected' : ''; ?>>Mediterránea</option>
                                        <option value="Internacional" <?php echo ($restaurant['food_type'] ?? '') === 'Internacional' ? 'selected' : ''; ?>>Internacional</option>
                                        <option value="Mariscos" <?php echo ($restaurant['food_type'] ?? '') === 'Mariscos' ? 'selected' : ''; ?>>Mariscos</option>
                                        <option value="Vegetariana" <?php echo ($restaurant['food_type'] ?? '') === 'Vegetariana' ? 'selected' : ''; ?>>Vegetariana</option>
                                        <option value="Steakhouse" <?php echo ($restaurant['food_type'] ?? '') === 'Steakhouse' ? 'selected' : ''; ?>>Steakhouse</option>
                                        <option value="Café" <?php echo ($restaurant['food_type'] ?? '') === 'Café' ? 'selected' : ''; ?>>Café</option>
                                        <option value="Otro" <?php echo ($restaurant['food_type'] ?? '') === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Descripción del restaurante, especialidades, ambiente..."><?php echo htmlspecialchars($restaurant['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keywords" class="form-label">Palabras Clave SEO</label>
                            <input type="text" class="form-control" id="keywords" name="keywords" 
                                   placeholder="Ej: restaurante, pizza, comida italiana, Roma Norte"
                                   value="<?php echo htmlspecialchars($restaurant['keywords'] ?? ''); ?>">
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
                                      placeholder="Dirección completa del restaurante"><?php echo htmlspecialchars($restaurant['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required
                                           placeholder="+52 55 1234 5678"
                                           value="<?php echo htmlspecialchars($restaurant['phone'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email de Contacto *</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           placeholder="contacto@restaurante.com"
                                           value="<?php echo htmlspecialchars($restaurant['email'] ?? ''); ?>">
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
                                    <input type="time" class="form-control" id="opening_time" name="opening_time" required
                                           value="<?php echo htmlspecialchars($restaurant['opening_time'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="closing_time" class="form-label">Hora de Cierre *</label>
                                    <input type="time" class="form-control" id="closing_time" name="closing_time" required
                                           value="<?php echo htmlspecialchars($restaurant['closing_time'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Restaurant Status -->
                        <hr class="my-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-toggle-on"></i> Estado del Restaurante
                        </h6>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       <?php echo (!empty($restaurant['is_active'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">
                                    <strong>Restaurante Activo</strong>
                                </label>
                                <div class="form-text">
                                    Los restaurantes inactivos no aparecerán en las búsquedas públicas ni podrán recibir reservaciones.
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>superadmin/restaurants" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Restaurante
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
                        <i class="fas fa-info-circle"></i> Información del Restaurante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">ID</h6>
                        <p class="small text-muted mb-0">
                            #<?php echo htmlspecialchars($restaurant['id'] ?? 'N/A'); ?>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Fecha de Registro</h6>
                        <p class="small text-muted mb-0">
                            <?php echo date('d/m/Y H:i', strtotime($restaurant['created_at'] ?? '')); ?>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Última Actualización</h6>
                        <p class="small text-muted mb-0">
                            <?php echo date('d/m/Y H:i', strtotime($restaurant['updated_at'] ?? '')); ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">Estado Actual</h6>
                        <p class="small mb-0">
                            <?php if (!empty($restaurant['is_active'])): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Activo
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times"></i> Inactivo
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb"></i> Consejos de Edición
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Verifique que los datos de contacto sean correctos
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Mantenga actualizadas las palabras clave SEO
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Configure horarios precisos y actuales
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            Desactivar temporalmente si no está operando
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    document.getElementById('restaurantForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor complete todos los campos obligatorios.');
        }
    });
    
    // Real-time validation feedback
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.hasAttribute('required') && this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>