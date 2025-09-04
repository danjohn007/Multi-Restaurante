<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-utensils"></i> Multi-Restaurante
                </h1>
                <p class="lead mb-4">
                    El mejor sistema de reservaciones para múltiples restaurantes. 
                    Descubre sabores únicos y reserva tu mesa favorita.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="search-container bg-white rounded-3 p-4 shadow">
                    <h4 class="text-dark mb-3">
                        <i class="fas fa-search text-primary"></i> 
                        <?php echo $searchPhrase; ?>
                    </h4>
                    
                    <form action="<?php echo BASE_URL; ?>search" method="GET" id="searchForm">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           name="q" 
                                           placeholder="Nombre del restaurante o tipo de comida"
                                           value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <select name="food_type" class="form-select">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach ($foodTypes as $type): ?>
                                        <option value="<?php echo htmlspecialchars($type); ?>" 
                                                <?php echo (($_GET['food_type'] ?? '') === $type) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($type); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="clearSearch()">
                                    <i class="fas fa-times"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Restaurants -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-star text-warning"></i> 
                    Restaurantes Destacados
                </h2>
            </div>
        </div>
        
        <div class="row" id="restaurantsList">
            <?php if (empty($restaurants)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i>
                        No hay restaurantes disponibles en este momento.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($restaurants as $restaurant): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm restaurant-card">
                            <div class="card-img-top position-relative">
                                <img src="<?php 
                                    if (!empty($restaurant['logo_url'])) {
                                        // Check if it's already a full URL or relative path
                                        if (strpos($restaurant['logo_url'], 'http') === 0) {
                                            echo htmlspecialchars($restaurant['logo_url']);
                                        } else {
                                            echo BASE_URL . 'uploads/restaurants/' . htmlspecialchars($restaurant['logo_url']);
                                        }
                                    } else {
                                        echo BASE_URL . 'public/images/restaurant-placeholder.jpg';
                                    }
                                ?>" 
                                     class="w-100" 
                                     alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <span class="badge bg-primary">
                                        <?php echo htmlspecialchars($restaurant['food_type'] ?? 'General'); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <?php echo htmlspecialchars($restaurant['name']); ?>
                                </h5>
                                
                                <p class="card-text text-muted flex-grow-1">
                                    <?php echo htmlspecialchars(substr($restaurant['description'] ?? '', 0, 120)); ?>
                                    <?php if (strlen($restaurant['description'] ?? '') > 120): ?>...<?php endif; ?>
                                </p>
                                
                                <div class="mb-3">
                                    <?php if ($restaurant['opening_time'] && $restaurant['closing_time']): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            <?php echo date('H:i', strtotime($restaurant['opening_time'])); ?> - 
                                            <?php echo date('H:i', strtotime($restaurant['closing_time'])); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>" 
                                       class="btn btn-primary">
                                        <i class="fas fa-calendar-alt"></i> Ver Disponibilidad
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>restaurant/<?php echo $restaurant['id']; ?>/reserve" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-utensils"></i> Reservar Mesa
                                    </a>
                                    <a href="https://wa.me/5218143806011?text=<?php echo urlencode('Hola, deseo reservar en ' . htmlspecialchars($restaurant['name'])); ?>" 
                                       class="btn btn-success" 
                                       target="_blank" 
                                       rel="noopener noreferrer">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>¿Por qué elegir Multi-Restaurante?</h2>
                <p class="lead text-muted">La mejor experiencia de reservaciones</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-primary text-white rounded-circle mx-auto mb-3" 
                     style="width: 80px; height: 80px; line-height: 80px; font-size: 24px;">
                    <i class="fas fa-search"></i>
                </div>
                <h4>Búsqueda Fácil</h4>
                <p class="text-muted">Encuentra tu restaurante favorito por nombre, tipo de comida o ubicación.</p>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3" 
                     style="width: 80px; height: 80px; line-height: 80px; font-size: 24px;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4>Reservación Instantánea</h4>
                <p class="text-muted">Reserva tu mesa al instante y recibe confirmación inmediata.</p>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-warning text-white rounded-circle mx-auto mb-3" 
                     style="width: 80px; height: 80px; line-height: 80px; font-size: 24px;">
                    <i class="fas fa-star"></i>
                </div>
                <h4>Mejores Restaurantes</h4>
                <p class="text-muted">Accede a una selección curada de los mejores restaurantes.</p>
            </div>
        </div>
    </div>
</section>

<script>
function clearSearch() {
    document.querySelector('input[name="q"]').value = '';
    document.querySelector('select[name="food_type"]').value = '';
    document.getElementById('searchForm').submit();
}

// Live search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="q"]');
    const foodTypeSelect = document.querySelector('select[name="food_type"]');
    let searchTimeout;
    
    function performSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const formData = new FormData(document.getElementById('searchForm'));
            
            fetch('<?php echo BASE_URL; ?>search?ajax=1&' + new URLSearchParams(formData))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateRestaurantsList(data.restaurants);
                    }
                })
                .catch(error => console.error('Search error:', error));
        }, 500);
    }
    
    searchInput.addEventListener('input', performSearch);
    foodTypeSelect.addEventListener('change', performSearch);
});

function updateRestaurantsList(restaurants) {
    const container = document.getElementById('restaurantsList');
    
    if (restaurants.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-search"></i>
                    No se encontraron restaurantes que coincidan con tu búsqueda.
                </div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = restaurants.map(restaurant => `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm restaurant-card">
                <div class="card-img-top position-relative">
                    <img src="${restaurant.logo_url || '<?php echo BASE_URL; ?>public/images/restaurant-placeholder.jpg'}" 
                         class="w-100" 
                         alt="${restaurant.name}"
                         style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge bg-primary">
                            ${restaurant.food_type || 'General'}
                        </span>
                    </div>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${restaurant.name}</h5>
                    
                    <p class="card-text text-muted flex-grow-1">
                        ${(restaurant.description || '').substring(0, 120)}${restaurant.description && restaurant.description.length > 120 ? '...' : ''}
                    </p>
                    
                    <div class="mb-3">
                        ${restaurant.opening_time && restaurant.closing_time ? `
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> 
                                ${restaurant.opening_time.substring(0,5)} - ${restaurant.closing_time.substring(0,5)}
                            </small>
                        ` : ''}
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>restaurant/${restaurant.id}" 
                           class="btn btn-primary">
                            <i class="fas fa-calendar-alt"></i> Ver Disponibilidad
                        </a>
                        <a href="<?php echo BASE_URL; ?>restaurant/${restaurant.id}/reserve" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-utensils"></i> Reservar Mesa
                        </a>
                        <a href="https://wa.me/5218143806011?text=${encodeURIComponent('Hola, deseo reservar en ' + restaurant.name)}" 
                           class="btn btn-success" 
                           target="_blank" 
                           rel="noopener noreferrer">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}
</script>