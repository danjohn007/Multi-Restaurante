<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Multi-Restaurante'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>public/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="fas fa-utensils"></i> Multi-Restaurante
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>search">
                            <i class="fas fa-search"></i> Buscar
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name'] ?? $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>superadmin">
                                        <i class="fas fa-tachometer-alt"></i> Panel Superadmin
                                    </a></li>
                                <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin">
                                        <i class="fas fa-tachometer-alt"></i> Panel Admin
                                    </a></li>
                                <?php elseif ($_SESSION['user_role'] === 'hostess'): ?>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>hostess">
                                        <i class="fas fa-tachometer-alt"></i> Panel Hostess
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>auth/login">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content"><?php
        // Content will be loaded here by individual views
    ?>