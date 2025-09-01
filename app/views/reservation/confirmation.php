<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="h2 text-success mb-2">¡Reservación Confirmada!</h1>
                <p class="text-muted mb-4">
                    Su reservación ha sido procesada exitosamente. A continuación encontrará los detalles.
                </p>
            </div>
        </div>
    </div>

    <!-- Reservation Details Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check"></i> Detalles de la Reservación
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Número de Reservación:</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="badge bg-primary"><?php echo sprintf('RES-%06d', $reservation['id']); ?></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Restaurante:</strong>
                        </div>
                        <div class="col-sm-6">
                            <?php echo htmlspecialchars($reservation['restaurant_name'] ?? 'Restaurante'); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Cliente:</strong>
                        </div>
                        <div class="col-sm-6">
                            <?php echo htmlspecialchars($reservation['customer_name']); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Teléfono:</strong>
                        </div>
                        <div class="col-sm-6">
                            <?php echo htmlspecialchars($reservation['customer_phone']); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($reservation['customer_email'])): ?>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-6">
                            <?php echo htmlspecialchars($reservation['customer_email']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Fecha:</strong>
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-calendar text-primary"></i>
                            <?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Hora:</strong>
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-clock text-primary"></i>
                            <?php echo date('H:i', strtotime($reservation['reservation_time'])); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Número de Personas:</strong>
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-users text-primary"></i>
                            <?php echo $reservation['party_size']; ?> personas
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Estado:</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Confirmada
                            </span>
                        </div>
                    </div>
                    
                    <?php if (!empty($reservation['special_requests'])): ?>
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Solicitudes Especiales:</strong>
                            <div class="mt-2 p-2 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($reservation['special_requests'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="text-muted mb-2">
                                <i class="fas fa-info-circle"></i>
                                Por favor llegue 15 minutos antes de su hora reservada
                            </p>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                                    <i class="fas fa-home"></i> Volver al Inicio
                                </a>
                                <button class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Info -->
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8 col-xl-6">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle"></i> Importante
                </h6>
                <p class="mb-0">
                    Si necesita modificar o cancelar su reservación, por favor contacte directamente al restaurante 
                    con anticipación. Mantenga este número de reservación como referencia.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .alert {
        display: none !important;
    }
}
</style>