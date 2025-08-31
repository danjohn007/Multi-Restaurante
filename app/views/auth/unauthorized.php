<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Acceso No Autorizado
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-ban fa-5x text-danger mb-3"></i>
                        <h4>Lo sentimos, no tienes permisos para acceder a esta página</h4>
                        <p class="text-muted">
                            Es posible que no tengas los permisos necesarios o que tu sesión haya expirado.
                        </p>
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i> Ir al Inicio
                        </a>
                        <a href="<?php echo BASE_URL; ?>auth/login" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>