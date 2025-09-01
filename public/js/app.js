/**
 * Multi-Restaurant System JavaScript
 * Main application scripts
 */

// Global app configuration
const App = {
    baseUrl: window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/',
    
    // Initialize application
    init: function() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupAjaxDefaults();
    },
    
    // Setup global event listeners
    setupEventListeners: function() {
        // Handle form submissions
        document.addEventListener('submit', this.handleFormSubmit);
        
        // Handle AJAX links
        document.addEventListener('click', this.handleAjaxLinks);
        
        // Handle responsive tables
        this.makeTablesResponsive();
        
        // Setup tooltips
        this.initializeTooltips();
    },
    
    // Initialize components
    initializeComponents: function() {
        // Initialize any third-party components
        this.initializeSelect2();
        this.initializeDatePickers();
        this.initializeTimePickers();
    },
    
    // Setup AJAX defaults
    setupAjaxDefaults: function() {
        // Add CSRF token to all AJAX requests if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            fetch.defaults = {
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                }
            };
        }
    },
    
    // Handle form submissions
    handleFormSubmit: function(e) {
        const form = e.target;
        if (form.classList.contains('ajax-form')) {
            e.preventDefault();
            App.submitFormAjax(form);
        }
    },
    
    // Handle AJAX links
    handleAjaxLinks: function(e) {
        const link = e.target.closest('a[data-ajax]');
        if (link) {
            e.preventDefault();
            App.loadContentAjax(link.href, link.dataset.target);
        }
    },
    
    // Submit form via AJAX
    submitFormAjax: function(form) {
        const formData = new FormData(form);
        formData.append('ajax', '1'); // Add ajax parameter
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        
        fetch(form.action, {
            method: form.method,
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showAlert('success', data.message || 'Operación exitosa');
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500); // Slightly longer delay to see success message
                } else if (data.reload) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                this.showAlert('danger', data.message || 'Error en la operación');
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showAlert('danger', 'Error de conexión');
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    },
    
    // Load content via AJAX
    loadContentAjax: function(url, target) {
        const targetElement = document.querySelector(target);
        if (!targetElement) return;
        
        // Show loading
        targetElement.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
        
        fetch(url + (url.includes('?') ? '&' : '?') + 'ajax=1')
        .then(response => response.text())
        .then(html => {
            targetElement.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            targetElement.innerHTML = '<div class="alert alert-danger">Error al cargar contenido</div>';
        });
    },
    
    // Show alert message
    showAlert: function(type, message, duration = 5000) {
        const alertContainer = document.getElementById('alertContainer') || this.createAlertContainer();
        
        const alertElement = document.createElement('div');
        alertElement.className = `alert alert-${type} alert-dismissible fade show`;
        alertElement.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        alertContainer.appendChild(alertElement);
        
        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => {
                alertElement.remove();
            }, duration);
        }
    },
    
    // Create alert container if not exists
    createAlertContainer: function() {
        const container = document.createElement('div');
        container.id = 'alertContainer';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    },
    
    // Make tables responsive
    makeTablesResponsive: function() {
        const tables = document.querySelectorAll('table:not(.table-responsive table)');
        tables.forEach(table => {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-responsive';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        });
    },
    
    // Initialize tooltips
    initializeTooltips: function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    
    // Initialize Select2 (if available)
    initializeSelect2: function() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccionar...',
                allowClear: true
            });
        }
    },
    
    // Initialize date pickers
    initializeDatePickers: function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            if (!input.value) {
                input.valueAsDate = new Date();
            }
        });
    },
    
    // Initialize time pickers
    initializeTimePickers: function() {
        const timeInputs = document.querySelectorAll('input[type="time"]');
        timeInputs.forEach(input => {
            if (!input.value) {
                const now = new Date();
                input.value = now.toTimeString().slice(0, 5);
            }
        });
    },
    
    // Utility functions
    utils: {
        // Format currency
        formatCurrency: function(amount, currency = 'MXN') {
            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: currency
            }).format(amount);
        },
        
        // Format date
        formatDate: function(date, locale = 'es-MX') {
            return new Intl.DateTimeFormat(locale).format(new Date(date));
        },
        
        // Format time
        formatTime: function(time) {
            return new Date('1970-01-01T' + time + 'Z').toLocaleTimeString('es-MX', {
                timeZone: 'UTC',
                hour12: false,
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        // Debounce function
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },
        
        // Validate email
        isValidEmail: function(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        // Validate phone
        isValidPhone: function(phone) {
            const phoneRegex = /^[\+]?[(]?[\+]?[0-9]{1,3}[)]?[-\s\.]?[0-9]{2,3}[-\s\.]?[0-9]{4}[-\s\.]?[0-9]{4}$/;
            return phoneRegex.test(phone);
        }
    },
    
    // Search functionality
    search: {
        init: function() {
            const searchInputs = document.querySelectorAll('[data-search]');
            searchInputs.forEach(input => {
                input.addEventListener('input', App.utils.debounce(function() {
                    App.search.perform(this);
                }, 300));
            });
        },
        
        perform: function(input) {
            const query = input.value.trim();
            const target = input.dataset.search;
            const url = input.dataset.url || (App.baseUrl + 'search');
            
            if (query.length < 2) return;
            
            fetch(url + '?q=' + encodeURIComponent(query) + '&ajax=1')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    App.search.displayResults(data.results, target);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            });
        },
        
        displayResults: function(results, target) {
            const targetElement = document.querySelector(target);
            if (!targetElement) return;
            
            if (results.length === 0) {
                targetElement.innerHTML = '<div class="alert alert-info">No se encontraron resultados</div>';
                return;
            }
            
            // Display results based on type
            targetElement.innerHTML = results.map(result => 
                `<div class="search-result-item" data-id="${result.id}">
                    ${result.html || result.name}
                </div>`
            ).join('');
        }
    },
    
    // Charts functionality
    charts: {
        init: function() {
            // Initialize Chart.js charts
            this.initializeChartJS();
        },
        
        initializeChartJS: function() {
            const chartElements = document.querySelectorAll('[data-chart]');
            chartElements.forEach(element => {
                const chartType = element.dataset.chart;
                const chartData = JSON.parse(element.dataset.chartData || '{}');
                
                this.createChart(element, chartType, chartData);
            });
        },
        
        createChart: function(element, type, data) {
            if (typeof Chart === 'undefined') return;
            
            const ctx = element.getContext('2d');
            new Chart(ctx, {
                type: type,
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
};

// Restaurant-specific functionality
const Restaurant = {
    // Table availability checker
    checkAvailability: function(restaurantId, date, time, partySize) {
        return fetch(App.baseUrl + `api/restaurants/${restaurantId}/availability?date=${date}&time=${time}&party_size=${partySize}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.tables;
                }
                throw new Error(data.message || 'Error checking availability');
            });
    },
    
    // Update table selection UI
    updateTableSelection: function(tables) {
        const tableContainer = document.getElementById('tableSelection');
        if (!tableContainer) return;
        
        if (tables.length === 0) {
            tableContainer.innerHTML = '<div class="alert alert-warning">No hay mesas disponibles para este horario</div>';
            return;
        }
        
        tableContainer.innerHTML = tables.map(table => `
            <div class="col-md-4 mb-3">
                <div class="card table-card" data-table-id="${table.id}">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mesa ${table.table_number}</h5>
                        <p class="card-text">
                            <i class="fas fa-users"></i> Capacidad: ${table.capacity} personas
                        </p>
                        <button type="button" class="btn btn-outline-primary select-table" 
                                data-table-id="${table.id}" data-table-number="${table.table_number}">
                            Seleccionar
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Add event listeners to table selection buttons
        tableContainer.querySelectorAll('.select-table').forEach(btn => {
            btn.addEventListener('click', function() {
                Restaurant.selectTable(this.dataset.tableId, this.dataset.tableNumber);
            });
        });
    },
    
    // Select table
    selectTable: function(tableId, tableNumber) {
        // Update UI to show selected table
        document.querySelectorAll('.table-card').forEach(card => {
            card.classList.remove('border-primary', 'bg-light');
        });
        
        const selectedCard = document.querySelector(`[data-table-id="${tableId}"]`);
        if (selectedCard) {
            selectedCard.classList.add('border-primary', 'bg-light');
        }
        
        // Update hidden form field
        const tableIdInput = document.getElementById('selectedTableId');
        if (tableIdInput) {
            tableIdInput.value = tableId;
        }
        
        // Show confirmation
        App.showAlert('success', `Mesa ${tableNumber} seleccionada`, 2000);
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    App.init();
    App.search.init();
    App.charts.init();
});

// Export for use in other scripts
window.App = App;
window.Restaurant = Restaurant;