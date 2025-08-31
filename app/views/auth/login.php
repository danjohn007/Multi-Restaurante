<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>auth/login" method="POST" id="loginForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="fas fa-user"></i> Usuario o Email
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   required 
                                   placeholder="Ingrese su usuario o email">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       placeholder="Ingrese su contraseña">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">
                                Recordar mi sesión
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p class="mb-0 text-muted">¿Olvidaste tu contraseña?</p>
                        <a href="#" class="btn btn-link">Recuperar contraseña</a>
                    </div>
                </div>
            </div>
            
            <!-- Demo Credentials Info -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Credenciales de Demostración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-primary">Superadmin</h6>
                            <small class="text-muted">
                                <strong>Usuario:</strong> superadmin<br>
                                <strong>Contraseña:</strong> password123
                            </small>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-success">Admin Restaurante</h6>
                            <small class="text-muted">
                                <strong>Usuario:</strong> admin_parrilla<br>
                                <strong>Contraseña:</strong> password123
                            </small>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-warning">Hostess</h6>
                            <small class="text-muted">
                                <strong>Usuario:</strong> hostess1_parrilla<br>
                                <strong>Contraseña:</strong> password123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    
    if (!username || !password) {
        e.preventDefault();
        alert('Por favor, complete todos los campos.');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';
});
</script>