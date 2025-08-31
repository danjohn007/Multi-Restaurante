-- Multi-Restaurant Reservation System Database Schema
-- Author: GitHub Copilot
-- Version: 1.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `multi_restaurante` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `multi_restaurante`;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','hostess') NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_restaurant_id` (`restaurant_id`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `restaurants`
-- --------------------------------------------------------

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `logo_url` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `food_type` varchar(50) DEFAULT NULL,
  `keywords` text COMMENT 'SEO keywords separated by commas',
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_food_type` (`food_type`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `tables`
-- --------------------------------------------------------

CREATE TABLE `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `capacity` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `restaurant_table` (`restaurant_id`, `table_number`),
  KEY `idx_restaurant_id` (`restaurant_id`),
  KEY `idx_capacity` (`capacity`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `customers`
-- --------------------------------------------------------

CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birth_day` int(2) DEFAULT NULL COMMENT 'Day of birth (1-31)',
  `birth_month` int(2) DEFAULT NULL COMMENT 'Month of birth (1-12)',
  `birth_year` int(4) DEFAULT NULL COMMENT 'Year of birth (optional)',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_phone` (`phone`),
  KEY `idx_email` (`email`),
  KEY `idx_birth_day_month` (`birth_day`, `birth_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `reservations`
-- --------------------------------------------------------

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `party_size` int(11) NOT NULL,
  `table_ids` varchar(255) DEFAULT NULL COMMENT 'Comma-separated table IDs',
  `status` enum('pending','confirmed','seated','completed','cancelled','no_show') DEFAULT 'pending',
  `special_requests` text,
  `assigned_waiter` varchar(50) DEFAULT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_out_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_restaurant_id` (`restaurant_id`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_reservation_date` (`reservation_date`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `bills`
-- --------------------------------------------------------

CREATE TABLE `bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text,
  `payment_method` varchar(50) DEFAULT NULL,
  `closed_by_user_id` int(11) DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reservation_id` (`reservation_id`),
  KEY `idx_restaurant_id` (`restaurant_id`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_closed_at` (`closed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `customer_segments`
-- --------------------------------------------------------

CREATE TABLE `customer_segments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `restaurant_id` int(11) NOT NULL,
  `segment_type` enum('top_spending','top_visits','reactivation','birthday','custom') NOT NULL,
  `criteria` text COMMENT 'JSON criteria for segment',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_restaurant_id` (`restaurant_id`),
  KEY `idx_segment_type` (`segment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `marketing_campaigns`
-- --------------------------------------------------------

CREATE TABLE `marketing_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `segment_id` int(11) DEFAULT NULL,
  `campaign_type` enum('email','whatsapp','sms') NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text,
  `sent_count` int(11) DEFAULT 0,
  `clicks_count` int(11) DEFAULT 0,
  `redemption_count` int(11) DEFAULT 0,
  `status` enum('draft','sent','completed') DEFAULT 'draft',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_restaurant_id` (`restaurant_id`),
  KEY `idx_segment_id` (`segment_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Foreign Key Constraints
-- --------------------------------------------------------

ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE SET NULL;

ALTER TABLE `tables`
  ADD CONSTRAINT `fk_tables_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservations_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

ALTER TABLE `bills`
  ADD CONSTRAINT `fk_bills_reservation` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bills_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bills_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bills_closed_by_user` FOREIGN KEY (`closed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `customer_segments`
  ADD CONSTRAINT `fk_segments_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

ALTER TABLE `marketing_campaigns`
  ADD CONSTRAINT `fk_campaigns_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_campaigns_segment` FOREIGN KEY (`segment_id`) REFERENCES `customer_segments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_campaigns_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- --------------------------------------------------------
-- Sample Data
-- --------------------------------------------------------

-- Insert superadmin user
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `first_name`, `last_name`) VALUES
('superadmin', 'superadmin@multirestaurante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin', 'Super', 'Administrador');

-- Insert sample restaurants
INSERT INTO `restaurants` (`name`, `description`, `food_type`, `keywords`, `opening_time`, `closing_time`, `address`, `phone`, `email`) VALUES
('La Parrilla Mexicana', 'Auténtica comida mexicana con sabores tradicionales', 'Mexicana', 'tacos, quesadillas, carnitas, mexico, tradicional', '11:00:00', '23:00:00', 'Av. Insurgentes 123, Ciudad de México', '+52 55 1234 5678', 'contacto@parrillamexicana.com'),
('Pasta e Basta', 'Cocina italiana casera con ingredientes frescos', 'Italiana', 'pasta, pizza, lasagna, italiana, casero', '12:00:00', '22:00:00', 'Roma Norte 456, Ciudad de México', '+52 55 8765 4321', 'info@pastabasta.com'),
('Sushi Zen', 'Experiencia japonesa auténtica con sushi fresco', 'Japonesa', 'sushi, sashimi, ramen, japonesa, fresco', '13:00:00', '23:30:00', 'Polanco 789, Ciudad de México', '+52 55 5555 0000', 'reservas@sushizen.com');

-- Insert restaurant admins
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `restaurant_id`, `first_name`, `last_name`, `phone`) VALUES
('admin_parrilla', 'admin@parrillamexicana.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 'Carlos', 'Hernández', '+52 55 1111 2222'),
('admin_pasta', 'admin@pastabasta.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 2, 'Giuseppe', 'Rossi', '+52 55 3333 4444'),
('admin_sushi', 'admin@sushizen.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 3, 'Takeshi', 'Yamamoto', '+52 55 5555 6666');

-- Insert hostess users
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `restaurant_id`, `first_name`, `last_name`, `phone`) VALUES
('hostess1_parrilla', 'hostess1@parrillamexicana.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hostess', 1, 'María', 'García', '+52 55 7777 8888'),
('hostess1_pasta', 'hostess1@pastabasta.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hostess', 2, 'Sofia', 'Benedetti', '+52 55 9999 0000'),
('hostess1_sushi', 'hostess1@sushizen.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hostess', 3, 'Yuki', 'Tanaka', '+52 55 1212 3434');

-- Insert sample tables for each restaurant
INSERT INTO `tables` (`restaurant_id`, `table_number`, `capacity`, `valid_from`, `valid_until`) VALUES
-- La Parrilla Mexicana tables
(1, 'M1', 2, '2024-01-01', '2025-12-31'),
(1, 'M2', 2, '2024-01-01', '2025-12-31'),
(1, 'M3', 4, '2024-01-01', '2025-12-31'),
(1, 'M4', 4, '2024-01-01', '2025-12-31'),
(1, 'M5', 6, '2024-01-01', '2025-12-31'),
(1, 'M6', 8, '2024-01-01', '2025-12-31'),
-- Pasta e Basta tables
(2, 'P1', 2, '2024-01-01', '2025-12-31'),
(2, 'P2', 2, '2024-01-01', '2025-12-31'),
(2, 'P3', 4, '2024-01-01', '2025-12-31'),
(2, 'P4', 4, '2024-01-01', '2025-12-31'),
(2, 'P5', 6, '2024-01-01', '2025-12-31'),
-- Sushi Zen tables
(3, 'S1', 2, '2024-01-01', '2025-12-31'),
(3, 'S2', 2, '2024-01-01', '2025-12-31'),
(3, 'S3', 4, '2024-01-01', '2025-12-31'),
(3, 'S4', 4, '2024-01-01', '2025-12-31'),
(3, 'S5', 6, '2024-01-01', '2025-12-31'),
(3, 'S6', 8, '2024-01-01', '2025-12-31');

-- Insert sample customers
INSERT INTO `customers` (`first_name`, `last_name`, `phone`, `email`, `birth_day`, `birth_month`, `birth_year`) VALUES
('Juan', 'Pérez', '+52 55 1111 1111', 'juan.perez@email.com', 15, 5, 1985),
('Ana', 'López', '+52 55 2222 2222', 'ana.lopez@email.com', 22, 8, 1990),
('Miguel', 'Rodríguez', '+52 55 3333 3333', 'miguel.rodriguez@email.com', 10, 12, 1988),
('Carmen', 'Martínez', '+52 55 4444 4444', 'carmen.martinez@email.com', 3, 3, 1992),
('Roberto', 'González', '+52 55 5555 5555', 'roberto.gonzalez@email.com', 28, 9, 1987);

-- Insert sample reservations
INSERT INTO `reservations` (`restaurant_id`, `customer_id`, `customer_name`, `customer_phone`, `reservation_date`, `reservation_time`, `party_size`, `table_ids`, `status`) VALUES
(1, 1, 'Juan Pérez', '+52 55 1111 1111', CURDATE(), '19:00:00', 2, '1', 'confirmed'),
(2, 2, 'Ana López', '+52 55 2222 2222', CURDATE(), '20:00:00', 4, '9', 'confirmed'),
(3, 3, 'Miguel Rodríguez', '+52 55 3333 3333', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '18:30:00', 2, '12', 'pending');

-- Insert sample bills (completed reservations)
INSERT INTO `bills` (`reservation_id`, `restaurant_id`, `customer_id`, `total_amount`, `payment_method`, `closed_by_user_id`, `closed_at`) VALUES
(1, 1, 1, 850.00, 'Tarjeta', 4, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 2, 2, 1200.00, 'Efectivo', 5, DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- Insert sample customer segments
INSERT INTO `customer_segments` (`name`, `description`, `restaurant_id`, `segment_type`, `criteria`) VALUES
('Top Clientes por Gasto', 'Clientes con mayor gasto total', 1, 'top_spending', '{"min_amount": 1000}'),
('Clientes Frecuentes', 'Clientes con más de 5 visitas', 1, 'top_visits', '{"min_visits": 5}'),
('Cumpleañeros del Mes', 'Clientes que cumplen años este mes', 1, 'birthday', '{"current_month": true}');

COMMIT;

-- Create indexes for better performance
CREATE INDEX idx_reservations_date_time ON reservations(reservation_date, reservation_time);
CREATE INDEX idx_bills_amount ON bills(total_amount);
CREATE INDEX idx_customers_phone_email ON customers(phone, email);
CREATE FULLTEXT INDEX idx_restaurants_search ON restaurants(name, description, keywords);

-- Default password for all sample users is: "password123"
-- Password hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
