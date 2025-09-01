<div class="container py-4">
    <!-- Success Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success d-flex align-items-center">
                <i class="fas fa-check-circle fa-3x me-3"></i>
                <div>
                    <h4 class="alert-heading mb-1">¡Reservación Confirmada!</h4>
                    <p class="mb-0">Su reservación ha sido procesada exitosamente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check text-success"></i> Detalles de la Reservación
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($reservation) && $reservation): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Información de la Reservación</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID Reservación:</strong></td>
                                        <td>#<?php echo $reservation['id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha:</strong></td>
                                        <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Hora:</strong></td>
                                        <td><?php echo date('H:i', strtotime($reservation['reservation_time'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Personas:</strong></td>
                                        <td><?php echo $reservation['party_size']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td>
                                            <span class="badge bg-success">
                                                <?php echo ucfirst($reservation['status'] ?? 'confirmed'); ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="text-muted">Información del Cliente</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nombre:</strong></td>
                                        <td><?php echo htmlspecialchars($reservation['customer_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono:</strong></td>
                                        <td><?php echo htmlspecialchars($reservation['customer_phone']); ?></td>
                                    </tr>
                                    <?php if (!empty($reservation['customer_email'])): ?>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?php echo htmlspecialchars($reservation['customer_email']); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($reservation['special_requests'])): ?>
                                        <tr>
                                            <td><strong>Solicitudes:</strong></td>
                                            <td><?php echo htmlspecialchars($reservation['special_requests']); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5>Reservación no encontrada</h5>
                            <p class="text-muted">No se pudo cargar la información de la reservación.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Restaurant Info Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-store"></i> Información del Restaurante
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($restaurant) && $restaurant): ?>
                        <div class="text-center mb-3">
                            <img src="<?php 
                                if (!empty($restaurant['logo_url'])) {
                                    if (strpos($restaurant['logo_url'], 'http') === 0) {
                                        echo htmlspecialchars($restaurant['logo_url']);
                                    } else {
                                        echo BASE_URL . 'uploads/restaurants/' . htmlspecialchars($restaurant['logo_url']);
                                    }
                                } else {
                                    echo BASE_URL . 'public/images/restaurant-placeholder.svg';
                                }
                            ?>" 
                                 class="img-fluid rounded" 
                                 alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                 style="object-fit: cover; max-height: 120px;"
                                 onerror="this.src='<?php echo BASE_URL; ?>public/images/restaurant-placeholder.svg'">
                        </div>
                        
                        <h5 class="text-center"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                        
                        <?php if (!empty($restaurant['description'])): ?>
                            <p class="text-muted text-center mb-3">
                                <?php echo htmlspecialchars($restaurant['description']); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if (!empty($restaurant['address'])): ?>
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <small><?php echo htmlspecialchars($restaurant['address']); ?></small>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($restaurant['phone'])): ?>
                            <div class="mb-2">
                                <i class="fas fa-phone text-primary"></i>
                                <small><?php echo htmlspecialchars($restaurant['phone']); ?></small>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($restaurant['opening_time'] && $restaurant['closing_time']): ?>
                            <div class="mb-2">
                                <i class="fas fa-clock text-primary"></i>
                                <small>
                                    <?php echo date('H:i', strtotime($restaurant['opening_time'])); ?> - 
                                    <?php echo date('H:i', strtotime($restaurant['closing_time'])); ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i> Volver al Inicio
                        </a>
                        
                        <?php if (isset($restaurant) && $restaurant): ?>
                            <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-store"></i> Ver Restaurante
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>/reserve" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-plus"></i> Nueva Reservación
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Important Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle"></i> Información Importante</h6>
                <ul class="mb-0">
                    <li>Por favor llegue 10 minutos antes de su hora de reservación</li>
                    <li>Si necesita cancelar o modificar su reservación, contacte al restaurante directamente</li>
                    <li>Conserve este número de reservación para futuras referencias</li>
                    <li>En caso de retraso, contacte al restaurante para mantener su mesa</li>
                </ul>
            </div>
        </div>
    </div>
</div>