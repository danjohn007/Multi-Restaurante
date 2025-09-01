<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-cogs text-primary"></i> 
                        Configuración General del Sistema
                    </h1>
                    <p class="text-muted mb-0">
                        Administración de configuraciones globales, SEO, horarios y contacto
                    </p>
                </div>
                <div>
                    <button class="btn btn-success" id="saveAllSettings">
                        <i class="fas fa-save"></i> Guardar Todas las Configuraciones
                    </button>
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

    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list"></i> Configuraciones
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#general-settings" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                        <i class="fas fa-globe"></i> General
                    </a>
                    <a href="#seo-settings" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-search"></i> SEO
                    </a>
                    <a href="#schedule-settings" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-clock"></i> Horarios
                    </a>
                    <a href="#contact-settings" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-envelope"></i> Contacto
                    </a>
                    <a href="#notification-settings" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-bell"></i> Notificaciones
                    </a>
                    <a href="#maintenance-settings" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-tools"></i> Mantenimiento
                    </a>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general-settings">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-globe"></i> Configuración General
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="generalSettingsForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="app_name" class="form-label">Nombre de la Aplicación</label>
                                            <input type="text" class="form-control" id="app_name" name="app_name" 
                                                   value="<?php echo $settings['app_name'] ?? 'Multi-Restaurante'; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="app_version" class="form-label">Versión</label>
                                            <input type="text" class="form-control" id="app_version" name="app_version" 
                                                   value="<?php echo $settings['app_version'] ?? '1.0.0'; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="app_description" class="form-label">Descripción del Sistema</label>
                                    <textarea class="form-control" id="app_description" name="app_description" rows="3"><?php echo $settings['app_description'] ?? 'Sistema de reservaciones para múltiples restaurantes'; ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="default_timezone" class="form-label">Zona Horaria</label>
                                            <select class="form-select" id="default_timezone" name="default_timezone">
                                                <option value="America/Mexico_City" <?php echo ($settings['default_timezone'] ?? 'America/Mexico_City') === 'America/Mexico_City' ? 'selected' : ''; ?>>México (GMT-6)</option>
                                                <option value="America/New_York" <?php echo ($settings['default_timezone'] ?? '') === 'America/New_York' ? 'selected' : ''; ?>>Nueva York (GMT-5)</option>
                                                <option value="America/Los_Angeles" <?php echo ($settings['default_timezone'] ?? '') === 'America/Los_Angeles' ? 'selected' : ''; ?>>Los Angeles (GMT-8)</option>
                                                <option value="Europe/Madrid" <?php echo ($settings['default_timezone'] ?? '') === 'Europe/Madrid' ? 'selected' : ''; ?>>Madrid (GMT+1)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="default_language" class="form-label">Idioma Predeterminado</label>
                                            <select class="form-select" id="default_language" name="default_language">
                                                <option value="es" <?php echo ($settings['default_language'] ?? 'es') === 'es' ? 'selected' : ''; ?>>Español</option>
                                                <option value="en" <?php echo ($settings['default_language'] ?? '') === 'en' ? 'selected' : ''; ?>>English</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_reservations_per_day" class="form-label">Reservaciones Máximas por Día</label>
                                            <input type="number" class="form-control" id="max_reservations_per_day" name="max_reservations_per_day" 
                                                   value="<?php echo $settings['max_reservations_per_day'] ?? 100; ?>" min="1">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="reservation_advance_days" class="form-label">Días de Anticipación para Reservar</label>
                                            <input type="number" class="form-control" id="reservation_advance_days" name="reservation_advance_days" 
                                                   value="<?php echo $settings['reservation_advance_days'] ?? 30; ?>" min="1" max="365">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                                           <?php echo ($settings['maintenance_mode'] ?? false) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="maintenance_mode">
                                        Modo de Mantenimiento
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="tab-pane fade" id="seo-settings">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-search"></i> Configuración SEO
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="seoSettingsForm">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Título Meta (SEO)</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                           value="<?php echo $settings['meta_title'] ?? 'Multi-Restaurante - Sistema de Reservaciones'; ?>" 
                                           maxlength="60">
                                    <div class="form-text">Recomendado: 50-60 caracteres</div>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Descripción Meta (SEO)</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3" 
                                              maxlength="160"><?php echo $settings['meta_description'] ?? 'Sistema completo de reservaciones para múltiples restaurantes. Gestione mesas, horarios y clientes de manera eficiente.'; ?></textarea>
                                    <div class="form-text">Recomendado: 150-160 caracteres</div>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Palabras Clave Globales (SEO)</label>
                                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="3" 
                                              placeholder="restaurante, reservaciones, mesas, comida, gastronomía"><?php echo $settings['meta_keywords'] ?? 'restaurante, reservaciones, mesas, comida, gastronomía, sistema, gestión, horarios'; ?></textarea>
                                    <div class="form-text">Separe las palabras clave con comas. Estas se aplicarán a todas las páginas del sitio.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="google_analytics_id" class="form-label">ID de Google Analytics</label>
                                            <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id" 
                                                   value="<?php echo $settings['google_analytics_id'] ?? ''; ?>" 
                                                   placeholder="G-XXXXXXXXXX">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="google_tag_manager_id" class="form-label">ID de Google Tag Manager</label>
                                            <input type="text" class="form-control" id="google_tag_manager_id" name="google_tag_manager_id" 
                                                   value="<?php echo $settings['google_tag_manager_id'] ?? ''; ?>" 
                                                   placeholder="GTM-XXXXXXX">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="robots_txt" class="form-label">Contenido robots.txt</label>
                                    <textarea class="form-control" id="robots_txt" name="robots_txt" rows="5"><?php echo $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nSitemap: " . (BASE_URL ?? '') . "sitemap.xml"; ?></textarea>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="enable_sitemap" name="enable_sitemap" 
                                           <?php echo ($settings['enable_sitemap'] ?? true) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="enable_sitemap">
                                        Generar Sitemap.xml automático
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Schedule Settings -->
                <div class="tab-pane fade" id="schedule-settings">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-clock"></i> Configuración de Horarios
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="scheduleSettingsForm">
                                <div class="mb-4">
                                    <h6 class="text-secondary">Horarios Predeterminados del Sistema</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="default_opening_time" class="form-label">Hora de Apertura Predeterminada</label>
                                                <input type="time" class="form-control" id="default_opening_time" name="default_opening_time" 
                                                       value="<?php echo $settings['default_opening_time'] ?? '09:00'; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="default_closing_time" class="form-label">Hora de Cierre Predeterminada</label>
                                                <input type="time" class="form-control" id="default_closing_time" name="default_closing_time" 
                                                       value="<?php echo $settings['default_closing_time'] ?? '22:00'; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Configuración de Reservaciones</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="reservation_interval" class="form-label">Intervalos de Reservación (minutos)</label>
                                                <select class="form-select" id="reservation_interval" name="reservation_interval">
                                                    <option value="15" <?php echo ($settings['reservation_interval'] ?? 30) == 15 ? 'selected' : ''; ?>>15 minutos</option>
                                                    <option value="30" <?php echo ($settings['reservation_interval'] ?? 30) == 30 ? 'selected' : ''; ?>>30 minutos</option>
                                                    <option value="60" <?php echo ($settings['reservation_interval'] ?? 30) == 60 ? 'selected' : ''; ?>>60 minutos</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="max_party_size" class="form-label">Tamaño Máximo de Grupo</label>
                                                <input type="number" class="form-control" id="max_party_size" name="max_party_size" 
                                                       value="<?php echo $settings['max_party_size'] ?? 12; ?>" min="1" max="50">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="min_advance_booking" class="form-label">Tiempo Mínimo de Anticipación (horas)</label>
                                                <input type="number" class="form-control" id="min_advance_booking" name="min_advance_booking" 
                                                       value="<?php echo $settings['min_advance_booking'] ?? 2; ?>" min="0" max="72">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="max_advance_booking" class="form-label">Tiempo Máximo de Anticipación (días)</label>
                                                <input type="number" class="form-control" id="max_advance_booking" name="max_advance_booking" 
                                                       value="<?php echo $settings['max_advance_booking'] ?? 30; ?>" min="1" max="365">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Días de la Semana</h6>
                                    <p class="small text-muted">Configurar qué días de la semana están disponibles para reservaciones por defecto</p>
                                    
                                    <div class="row">
                                        <?php 
                                        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                        $dayKeys = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                        for ($i = 0; $i < 7; $i++): 
                                        ?>
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="<?php echo $dayKeys[$i]; ?>" name="operating_days[]" value="<?php echo $dayKeys[$i]; ?>"
                                                           <?php echo ($settings["operating_days_{$dayKeys[$i]}"] ?? true) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="<?php echo $dayKeys[$i]; ?>">
                                                        <?php echo $days[$i]; ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Settings -->
                <div class="tab-pane fade" id="contact-settings">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-envelope"></i> Configuración de Contacto
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="contactSettingsForm">
                                <div class="mb-4">
                                    <h6 class="text-secondary">Información de Contacto Principal</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="contact_email" class="form-label">Email de Contacto Principal</label>
                                                <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                       value="<?php echo $settings['contact_email'] ?? 'contacto@multirestaurante.com'; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="support_email" class="form-label">Email de Soporte Técnico</label>
                                                <input type="email" class="form-control" id="support_email" name="support_email" 
                                                       value="<?php echo $settings['support_email'] ?? 'soporte@multirestaurante.com'; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="contact_phone" class="form-label">Teléfono de Contacto</label>
                                                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                                                       value="<?php echo $settings['contact_phone'] ?? '+52 55 1234 5678'; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="whatsapp_number" class="form-label">Número de WhatsApp</label>
                                                <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp_number" 
                                                       value="<?php echo $settings['whatsapp_number'] ?? '+52 55 1234 5678'; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="contact_address" class="form-label">Dirección de Oficinas</label>
                                        <textarea class="form-control" id="contact_address" name="contact_address" rows="2"><?php echo $settings['contact_address'] ?? 'Ciudad de México, México'; ?></textarea>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Configuración de Email</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_host" class="form-label">Servidor SMTP</label>
                                                <input type="text" class="form-control" id="smtp_host" name="smtp_host" 
                                                       value="<?php echo $settings['smtp_host'] ?? 'smtp.gmail.com'; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_port" class="form-label">Puerto SMTP</label>
                                                <input type="number" class="form-control" id="smtp_port" name="smtp_port" 
                                                       value="<?php echo $settings['smtp_port'] ?? 587; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_username" class="form-label">Usuario SMTP</label>
                                                <input type="text" class="form-control" id="smtp_username" name="smtp_username" 
                                                       value="<?php echo $settings['smtp_username'] ?? ''; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_password" class="form-label">Contraseña SMTP</label>
                                                <input type="password" class="form-control" id="smtp_password" name="smtp_password" 
                                                       value="<?php echo $settings['smtp_password'] ?? ''; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="smtp_secure" name="smtp_secure" 
                                               <?php echo ($settings['smtp_secure'] ?? true) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="smtp_secure">
                                            Usar conexión segura (TLS)
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Redes Sociales</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="facebook_url" class="form-label">Facebook</label>
                                                <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                                       value="<?php echo $settings['facebook_url'] ?? ''; ?>" 
                                                       placeholder="https://facebook.com/multirestaurante">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="instagram_url" class="form-label">Instagram</label>
                                                <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                                       value="<?php echo $settings['instagram_url'] ?? ''; ?>" 
                                                       placeholder="https://instagram.com/multirestaurante">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="twitter_url" class="form-label">Twitter</label>
                                                <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                                       value="<?php echo $settings['twitter_url'] ?? ''; ?>" 
                                                       placeholder="https://twitter.com/multirestaurante">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="youtube_url" class="form-label">YouTube</label>
                                                <input type="url" class="form-control" id="youtube_url" name="youtube_url" 
                                                       value="<?php echo $settings['youtube_url'] ?? ''; ?>" 
                                                       placeholder="https://youtube.com/multirestaurante">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notification-settings">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-bell"></i> Configuración de Notificaciones
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="notificationSettingsForm">
                                <div class="mb-4">
                                    <h6 class="text-secondary">Notificaciones por Email</h6>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_new_reservation" name="email_new_reservation" 
                                               <?php echo ($settings['email_new_reservation'] ?? true) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_new_reservation">
                                            Enviar email cuando se cree una nueva reservación
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_reservation_cancelled" name="email_reservation_cancelled" 
                                               <?php echo ($settings['email_reservation_cancelled'] ?? true) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_reservation_cancelled">
                                            Enviar email cuando se cancele una reservación
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_reservation_reminder" name="email_reservation_reminder" 
                                               <?php echo ($settings['email_reservation_reminder'] ?? true) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_reservation_reminder">
                                            Enviar recordatorio de reservación
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_new_restaurant" name="email_new_restaurant" 
                                               <?php echo ($settings['email_new_restaurant'] ?? true) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_new_restaurant">
                                            Enviar email cuando se registre un nuevo restaurante
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Notificaciones SMS/WhatsApp</h6>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="sms_new_reservation" name="sms_new_reservation" 
                                               <?php echo ($settings['sms_new_reservation'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="sms_new_reservation">
                                            Enviar SMS/WhatsApp para nuevas reservaciones
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="sms_reservation_reminder" name="sms_reservation_reminder" 
                                               <?php echo ($settings['sms_reservation_reminder'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="sms_reservation_reminder">
                                            Enviar recordatorio por SMS/WhatsApp
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Configuración de Recordatorios</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="reminder_hours_before" class="form-label">Horas antes para enviar recordatorio</label>
                                                <input type="number" class="form-control" id="reminder_hours_before" name="reminder_hours_before" 
                                                       value="<?php echo $settings['reminder_hours_before'] ?? 24; ?>" min="1" max="168">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="notification_recipients" class="form-label">Emails adicionales para notificar</label>
                                                <input type="text" class="form-control" id="notification_recipients" name="notification_recipients" 
                                                       value="<?php echo $settings['notification_recipients'] ?? ''; ?>" 
                                                       placeholder="admin@restaurant.com, manager@restaurant.com">
                                                <div class="form-text">Separar emails con comas</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Settings -->
                <div class="tab-pane fade" id="maintenance-settings">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-tools"></i> Configuración de Mantenimiento
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="maintenanceSettingsForm">
                                <div class="mb-4">
                                    <h6 class="text-secondary">Modo de Mantenimiento</h6>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="maintenance_enabled" name="maintenance_enabled" 
                                               <?php echo ($settings['maintenance_enabled'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="maintenance_enabled">
                                            Activar modo de mantenimiento
                                        </label>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="maintenance_message" class="form-label">Mensaje de Mantenimiento</label>
                                        <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3"><?php echo $settings['maintenance_message'] ?? 'El sistema está en mantenimiento. Volveremos pronto.'; ?></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="maintenance_end_time" class="form-label">Fin del Mantenimiento (opcional)</label>
                                                <input type="datetime-local" class="form-control" id="maintenance_end_time" name="maintenance_end_time" 
                                                       value="<?php echo $settings['maintenance_end_time'] ?? ''; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="maintenance_allowed_ips" class="form-label">IPs Permitidas durante Mantenimiento</label>
                                                <input type="text" class="form-control" id="maintenance_allowed_ips" name="maintenance_allowed_ips" 
                                                       value="<?php echo $settings['maintenance_allowed_ips'] ?? ''; ?>" 
                                                       placeholder="192.168.1.1, 10.0.0.1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Limpieza de Datos</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="auto_delete_old_reservations" class="form-label">Eliminar automáticamente reservaciones después de (días)</label>
                                                <input type="number" class="form-control" id="auto_delete_old_reservations" name="auto_delete_old_reservations" 
                                                       value="<?php echo $settings['auto_delete_old_reservations'] ?? 365; ?>" min="30" max="3650">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="auto_delete_logs" class="form-label">Eliminar logs después de (días)</label>
                                                <input type="number" class="form-control" id="auto_delete_logs" name="auto_delete_logs" 
                                                       value="<?php echo $settings['auto_delete_logs'] ?? 90; ?>" min="7" max="365">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-secondary">Respaldos</h6>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="auto_backup_enabled" name="auto_backup_enabled" 
                                               <?php echo ($settings['auto_backup_enabled'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="auto_backup_enabled">
                                            Activar respaldos automáticos
                                        </label>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="backup_frequency" class="form-label">Frecuencia de Respaldo</label>
                                                <select class="form-select" id="backup_frequency" name="backup_frequency">
                                                    <option value="daily" <?php echo ($settings['backup_frequency'] ?? 'weekly') === 'daily' ? 'selected' : ''; ?>>Diario</option>
                                                    <option value="weekly" <?php echo ($settings['backup_frequency'] ?? 'weekly') === 'weekly' ? 'selected' : ''; ?>>Semanal</option>
                                                    <option value="monthly" <?php echo ($settings['backup_frequency'] ?? 'weekly') === 'monthly' ? 'selected' : ''; ?>>Mensual</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="backup_retention_days" class="form-label">Retener respaldos por (días)</label>
                                                <input type="number" class="form-control" id="backup_retention_days" name="backup_retention_days" 
                                                       value="<?php echo $settings['backup_retention_days'] ?? 30; ?>" min="7" max="365">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                        <button type="button" class="btn btn-outline-primary" id="createBackupBtn">
                                            <i class="fas fa-download"></i> Crear Respaldo Ahora
                                        </button>
                                        <button type="button" class="btn btn-outline-info" id="viewBackupsBtn">
                                            <i class="fas fa-list"></i> Ver Respaldos
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Save all settings
    document.getElementById('saveAllSettings').addEventListener('click', function() {
        const allForms = [
            'generalSettingsForm',
            'seoSettingsForm', 
            'scheduleSettingsForm',
            'contactSettingsForm',
            'notificationSettingsForm',
            'maintenanceSettingsForm'
        ];
        
        const allData = new FormData();
        
        allForms.forEach(formId => {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            
            for (let [key, value] of formData.entries()) {
                allData.append(key, value);
            }
        });
        
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        
        fetch('<?php echo BASE_URL; ?>configuracion/save', {
            method: 'POST',
            body: allData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Configuraciones guardadas exitosamente');
            } else {
                App.showAlert('danger', data.message || 'Error al guardar configuraciones');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
    
    // Create backup
    document.getElementById('createBackupBtn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
        
        fetch('<?php echo BASE_URL; ?>configuracion/create-backup', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                App.showAlert('success', 'Respaldo creado exitosamente');
            } else {
                App.showAlert('danger', data.message || 'Error al crear respaldo');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            App.showAlert('danger', 'Error de conexión');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
    
    // Character count for SEO fields
    function setupCharacterCount(inputId, maxLength) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        const counter = document.createElement('div');
        counter.className = 'form-text text-end';
        input.parentNode.appendChild(counter);
        
        function updateCounter() {
            const length = input.value.length;
            counter.textContent = `${length}/${maxLength} caracteres`;
            counter.className = `form-text text-end ${length > maxLength ? 'text-danger' : 'text-muted'}`;
        }
        
        input.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    setupCharacterCount('meta_title', 60);
    setupCharacterCount('meta_description', 160);
    
    // Tab navigation
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            // Update active state
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});
</script>

<style>
.nav-tabs .nav-link.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-text.text-danger {
    color: #dc3545 !important;
}
</style>