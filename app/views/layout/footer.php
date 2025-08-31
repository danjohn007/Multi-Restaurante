    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-utensils"></i> Multi-Restaurante</h5>
                    <p class="mb-0">Sistema de Reservaciones Multi-Restaurante</p>
                    <small class="text-muted">Versión <?php echo APP_VERSION; ?></small>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <small>&copy; <?php echo date('Y'); ?> Multi-Restaurante. Todos los derechos reservados.</small>
                    </p>
                    <p class="mb-0">
                        <small>
                            <a href="#" class="text-light">Términos de Uso</a> | 
                            <a href="#" class="text-light">Política de Privacidad</a>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js for analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- FullCalendar for scheduling -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo BASE_URL; ?>public/js/app.js"></script>
    
    <!-- Page-specific scripts -->
    <?php if (isset($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?php echo BASE_URL; ?>public/js/<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>