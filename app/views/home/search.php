<!-- Search Results Section -->
<section class="py-5">
    <div class="container">
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="fas fa-search text-primary"></i> 
                            <?php echo $searchPhrase; ?>
                        </h4>
                        
                        <form action="<?php echo BASE_URL; ?>search" method="GET" id="searchForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="q" 
                                               placeholder="Nombre del restaurante o tipo de comida"
                                               value="<?php echo htmlspecialchars($query); ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <select name="food_type" class="form-select">
                                        <option value="">Todos los tipos</option>
                                        <?php foreach ($foodTypes as $type): ?>
                                            <option value="<?php echo htmlspecialchars($type); ?>" 
                                                    <?php echo ($selectedFoodType === $type) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($type); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Results Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>
                        <i class="fas fa-list"></i> Resultados de Búsqueda
                        <span class="badge bg-primary"><?php echo count($restaurants); ?></span>
                    </h2>
                    
                    <?php if (!empty($query) || !empty($selectedFoodType)): ?>
                        <div class="search-filters">
                            <?php if (!empty($query)): ?>
                                <span class="badge bg-secondary me-2">
                                    Término: "<?php echo htmlspecialchars($query); ?>"
                                    <a href="<?php echo BASE_URL; ?>search?food_type=<?php echo urlencode($selectedFoodType); ?>" 
                                       class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($selectedFoodType)): ?>
                                <span class="badge bg-info me-2">
                                    Tipo: <?php echo htmlspecialchars($selectedFoodType); ?>
                                    <a href="<?php echo BASE_URL; ?>search?q=<?php echo urlencode($query); ?>" 
                                       class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>
                            
                            <a href="<?php echo BASE_URL; ?>search" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-refresh"></i> Limpiar Todo
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($query) || !empty($selectedFoodType)): ?>
                    <p class="text-muted mb-0">
                        Se encontraron <?php echo count($restaurants); ?> restaurante(s) 
                        <?php if (!empty($query)): ?>
                            que coinciden con "<?php echo htmlspecialchars($query); ?>"
                        <?php endif; ?>
                        <?php if (!empty($selectedFoodType)): ?>
                            del tipo "<?php echo htmlspecialchars($selectedFoodType); ?>"
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Search Results -->
        <div class="row" id="restaurantsList">
            <?php if (empty($restaurants)): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                        <h4>No se encontraron resultados</h4>
                        <p class="mb-3">
                            No hay restaurantes que coincidan con los criterios de búsqueda.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="<?php echo BASE_URL; ?>search" class="btn btn-primary">
                                <i class="fas fa-search"></i> Nueva Búsqueda
                            </a>
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-primary">
                                <i class="fas fa-home"></i> Ver Todos
                            </a>
                        </div>
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
                                
                                <!-- Relevance indicator for search results -->
                                <?php if (isset($restaurant['relevance']) && $restaurant['relevance'] > 0): ?>
                                    <div class="position-absolute top-0 start-0 p-2">
                                        <span class="badge bg-success">
                                            <i class="fas fa-star"></i> Relevante
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <?php echo htmlspecialchars($restaurant['name']); ?>
                                </h5>
                                
                                <p class="card-text text-muted flex-grow-1">
                                    <?php echo htmlspecialchars(substr($restaurant['description'] ?? '', 0, 120)); ?>
                                    <?php if (strlen($restaurant['description'] ?? '') > 120): ?>...<?php endif; ?>
                                </p>
                                
                                <div class="restaurant-info mb-3">
                                    <?php if ($restaurant['opening_time'] && $restaurant['closing_time']): ?>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-clock"></i> 
                                            <?php echo date('H:i', strtotime($restaurant['opening_time'])); ?> - 
                                            <?php echo date('H:i', strtotime($restaurant['closing_time'])); ?>
                                        </small>
                                    <?php endif; ?>
                                    
                                    <?php if ($restaurant['address']): ?>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <?php echo htmlspecialchars(substr($restaurant['address'], 0, 50)); ?>
                                            <?php if (strlen($restaurant['address']) > 50): ?>...<?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                    
                                    <?php if ($restaurant['phone']): ?>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-phone"></i> 
                                            <?php echo htmlspecialchars($restaurant['phone']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Keywords display for search results -->
                                <?php if (!empty($restaurant['keywords']) && (!empty($query) || !empty($selectedFoodType))): ?>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-tags"></i> 
                                            <?php 
                                            $keywords = array_slice(explode(',', $restaurant['keywords']), 0, 3);
                                            echo htmlspecialchars(implode(', ', array_map('trim', $keywords)));
                                            ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                                
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
        
        <!-- Quick Filter Buttons -->
        <?php if (!empty($foodTypes)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-filter"></i> Filtros Rápidos por Tipo de Cocina
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?php echo BASE_URL; ?>search" 
                                   class="btn btn-sm <?php echo empty($selectedFoodType) ? 'btn-primary' : 'btn-outline-secondary'; ?>">
                                    Todos
                                </a>
                                <?php foreach ($foodTypes as $type): ?>
                                    <a href="<?php echo BASE_URL; ?>search?food_type=<?php echo urlencode($type); ?>&q=<?php echo urlencode($query); ?>" 
                                       class="btn btn-sm <?php echo ($selectedFoodType === $type) ? 'btn-primary' : 'btn-outline-secondary'; ?>">
                                        <?php echo htmlspecialchars($type); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
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
                        updateResultsCount(data.count);
                    }
                })
                .catch(error => console.error('Search error:', error));
        }, 500);
    }
    
    searchInput.addEventListener('input', performSearch);
    foodTypeSelect.addEventListener('change', performSearch);
});

function updateResultsCount(count) {
    const badge = document.querySelector('.badge.bg-primary');
    if (badge) {
        badge.textContent = count;
    }
}

function updateRestaurantsList(restaurants) {
    const container = document.getElementById('restaurantsList');
    
    if (restaurants.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                    <h4>No se encontraron resultados</h4>
                    <p class="mb-3">No hay restaurantes que coincidan con los criterios de búsqueda.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="<?php echo BASE_URL; ?>search" class="btn btn-primary">
                            <i class="fas fa-search"></i> Nueva Búsqueda
                        </a>
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-primary">
                            <i class="fas fa-home"></i> Ver Todos
                        </a>
                    </div>
                </div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = restaurants.map(restaurant => `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm restaurant-card">
                <div class="card-img-top position-relative">
                    <img src="${restaurant.logo_url && restaurant.logo_url.startsWith('http') ? restaurant.logo_url : (restaurant.logo_url ? '<?php echo BASE_URL; ?>uploads/restaurants/' + restaurant.logo_url : '<?php echo BASE_URL; ?>public/images/restaurant-placeholder.jpg')}" 
                         class="w-100" 
                         alt="${restaurant.name}"
                         style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge bg-primary">
                            ${restaurant.food_type || 'General'}
                        </span>
                    </div>
                    ${restaurant.relevance > 0 ? `
                        <div class="position-absolute top-0 start-0 p-2">
                            <span class="badge bg-success">
                                <i class="fas fa-star"></i> Relevante
                            </span>
                        </div>
                    ` : ''}
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${restaurant.name}</h5>
                    
                    <p class="card-text text-muted flex-grow-1">
                        ${(restaurant.description || '').substring(0, 120)}${restaurant.description && restaurant.description.length > 120 ? '...' : ''}
                    </p>
                    
                    <div class="restaurant-info mb-3">
                        ${restaurant.opening_time && restaurant.closing_time ? `
                            <small class="text-muted d-block">
                                <i class="fas fa-clock"></i> 
                                ${restaurant.opening_time.substring(0,5)} - ${restaurant.closing_time.substring(0,5)}
                            </small>
                        ` : ''}
                        ${restaurant.address ? `
                            <small class="text-muted d-block">
                                <i class="fas fa-map-marker-alt"></i> 
                                ${restaurant.address.substring(0, 50)}${restaurant.address.length > 50 ? '...' : ''}
                            </small>
                        ` : ''}
                        ${restaurant.phone ? `
                            <small class="text-muted d-block">
                                <i class="fas fa-phone"></i> 
                                ${restaurant.phone}
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