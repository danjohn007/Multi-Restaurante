# Sistema de Reservaciones Multi-Restaurante

Sistema completo de reservaciones multi-tenant para restaurantes con gestión de clientes, análisis de consumo y marketing dirigido.

## Características Principales

### 🏢 Multi-Tenant
- Soporte para múltiples restaurantes en una sola instalación
- Gestión independiente por restaurante
- Panel de superadministrador para gestión global

### 👥 Roles de Usuario
- **Superadmin**: Gestión global de restaurantes y métricas
- **Admin de Restaurante**: Gestión completa del restaurante
- **Hostess**: Check-in, asignación de mesas y facturación

### 🔍 Búsqueda Pública
- Frase fija: "Busca por restaurante o por tu comida favorita"
- Búsqueda por nombre, tipo de comida y palabras clave
- Filtros por tipo de cocina
- Resultados con relevancia

### 🪑 Gestión de Mesas
- CRUD completo de mesas con capacidad y vigencia
- Verificación de disponibilidad en tiempo real
- Selección de mesa(s) en el proceso de reservación

### 📊 Analytics y Métricas
- Análisis RFM (Recency, Frequency, Monetary)
- Mejores clientes por monto y visitas
- Segmentación por comportamiento
- Reportes y gráficas interactivas

### 📧 Marketing Dirigido
- Segmentos automáticos: Top gasto, Top visitas, Reactivación, Cumpleañeros
- Campañas de email y WhatsApp
- Exportación de listas en CSV
- Métricas de campaña

## Tecnologías

- **Backend**: PHP 7+ (MVC puro, sin frameworks)
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Gráficas**: Chart.js
- **Calendario**: FullCalendar.js
- **Autenticación**: Sesiones PHP con password_hash()
- **URLs**: Amigables con .htaccess
- **Instalación**: Auto-configurable en cualquier subdirectorio

## Instalación

### 1. Requisitos
- Apache con mod_rewrite
- PHP 7.0 o superior
- MySQL 5.7 o superior
- Extensiones PHP: PDO, PDO_MySQL

### 2. Instalación
```bash
# Clonar el repositorio
git clone https://github.com/danjohn007/Multi-Restaurante.git
cd Multi-Restaurante

# Configurar base de datos en config/config.php
# Crear la base de datos
mysql -u root -p -e "CREATE DATABASE multi_restaurante"

# Importar estructura y datos de ejemplo
mysql -u root -p multi_restaurante < database/install.sql

# Configurar Apache DocumentRoot hacia la carpeta public/
# O usar .htaccess para redirección automática
```

### 3. Verificar Instalación
Acceder a `tu-dominio.com/test_connection.php` para verificar:
- ✅ Conexión a base de datos
- ✅ Auto-detección de URL base
- ✅ Configuración del sistema

### 4. Acceso
- **URL**: `tu-dominio.com/public/` (o configurar DocumentRoot)
- **Superadmin**: usuario: `superadmin`, contraseña: `password123`
- **Admin Restaurante**: usuario: `admin_parrilla`, contraseña: `password123`
- **Hostess**: usuario: `hostess1_parrilla`, contraseña: `password123`

## Estructura del Proyecto

```
Multi-Restaurante/
├── app/
│   ├── controllers/          # Controladores MVC
│   ├── models/              # Modelos de datos
│   └── views/               # Vistas HTML
├── config/
│   └── config.php           # Configuración general
├── database/
│   └── install.sql          # Schema y datos de ejemplo
├── includes/
│   ├── Database.php         # Conexión a BD
│   └── Router.php           # Enrutador
├── public/                  # Carpeta pública
│   ├── css/                 # Estilos
│   ├── js/                  # JavaScript
│   ├── images/              # Imágenes
│   └── index.php            # Punto de entrada
├── test_connection.php      # Test de instalación
├── .htaccess               # Reescritura de URLs
└── README.md               # Este archivo
```

## Funcionalidades por Módulo

### Módulo Público
- [x] Búsqueda con frase fija
- [x] Listado de restaurantes con filtros
- [x] Vista de disponibilidad de mesas
- [x] Proceso de reservación público

### Panel Superadmin
- [x] CRUD de restaurantes
- [x] Asignación de palabras clave (SEO)
- [x] Métricas globales del sistema
- [x] Gestión de usuarios administradores

### Panel Admin Restaurante
- [x] Gestión de perfil del restaurante
- [x] CRUD de mesas con vigencia
- [x] Alta de usuarios Hostess
- [x] Reportes y analytics
- [x] Gestión de reservaciones

### Panel Hostess
- [x] Check-in de reservaciones
- [x] Asignación y edición de mesas
- [x] Registro de consumo por cliente
- [x] Gestión de perfiles de clientes
- [x] Cierre de cuentas

### Marketing
- [x] Segmentación automática de clientes
- [x] Creación de campañas
- [x] Exportación de listas CSV
- [x] Métricas de campañas

## Segmentos de Marketing

1. **Top Gasto**: Clientes con mayor gasto total
2. **Top Visitas**: Clientes más frecuentes
3. **Reactivación**: Clientes inactivos por X días
4. **Cumpleañeros**: Clientes que cumplen años

## URLs del Sistema

### Públicas
- `/` - Página principal con búsqueda
- `/search` - Resultados de búsqueda
- `/restaurant/{id}` - Vista del restaurante
- `/restaurant/{id}/reserve` - Reservar mesa

### Autenticación
- `/auth/login` - Iniciar sesión
- `/auth/logout` - Cerrar sesión

### Paneles de Administración
- `/superadmin` - Panel superadministrador
- `/admin` - Panel administrador restaurante
- `/hostess` - Panel hostess

### API
- `/api/restaurants/search` - Búsqueda AJAX
- `/api/restaurants/{id}/availability` - Disponibilidad de mesas

## Contribuir

1. Fork del proyecto
2. Crear rama para nueva característica (`git checkout -b feature/nueva-caracteristica`)
3. Commit de cambios (`git commit -am 'Agregar nueva característica'`)
4. Push a la rama (`git push origin feature/nueva-caracteristica`)
5. Crear Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Soporte

Para soporte y preguntas:
- Crear un issue en GitHub
- Email: soporte@multirestaurante.com

---

**Multi-Restaurante** - Sistema de Reservaciones Profesional v1.0.0