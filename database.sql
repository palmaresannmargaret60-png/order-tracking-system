-- Online Order Tracking System - matches your PHP code (order-tracking.zip)
-- Relationships:
--   products (1) ----< orders               (orders.product_name -> products.product_name)
--   orders   (1) ----< order_status_history (history.order_id   -> orders.order_id)
-- Import in phpMyAdmin with your database selected.
-- WARNING: the DROP TABLE lines replace your current tables and data.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `order_status_history`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
SET FOREIGN_KEY_CHECKS = 1;

-- products: same list as PRODUCTS in includes/functions.php
-- (IPHONE is the old order that is not in that list; kept so the old order still saves)
CREATE TABLE `products` (
  `product_id`   int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `price`        decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `uq_product_name` (`product_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`product_name`, `price`) VALUES
('Wireless Mouse',      399.50),
('Mechanical Keyboard', 2499.00),
('USB-C Hub',           1250.50),
('Laptop Stand',        600.00),
('Webcam',              1500.00),
('IPHONE',              15000.00);

CREATE TABLE `orders` (
  `order_id`         int(11) NOT NULL AUTO_INCREMENT,
  `order_number`     varchar(30) NOT NULL,
  `customer_name`    varchar(100) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `product_name`     varchar(100) NOT NULL,
  `quantity`         int(11) NOT NULL DEFAULT 1,
  `total_amount`     decimal(10,2) NOT NULL,
  `status`           enum('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `order_date`       datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `uq_order_number` (`order_number`),
  KEY `idx_orders_product` (`product_name`),
  KEY `idx_orders_status` (`status`),
  CONSTRAINT `fk_orders_product`
    FOREIGN KEY (`product_name`) REFERENCES `products` (`product_name`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` (`order_id`, `order_number`, `customer_name`, `customer_address`, `product_name`, `quantity`, `total_amount`, `status`, `order_date`) VALUES
(1, 'ORD-20261101-1001', 'Maria Santos',   'Lapu-Lapu City, Cebu', 'Wireless Mouse',      2,   799.00, 'Delivered',  '2026-11-13 12:31:15'),
(2, 'ORD-20261105-2002', 'Juan Dela Cruz', 'Mandaue City, Cebu',   'Mechanical Keyboard', 1,  2499.00, 'Shipped',    '2026-11-13 12:31:15'),
(3, 'ORD-20261110-3003', 'Ana Reyes',      'Cebu City',            'USB-C Hub',           1,  1250.50, 'Processing', '2026-11-13 12:31:15'),
(5, 'ORD-20261113-5926', 'JM',             'ANILAO',               'IPHONE',              1, 15000.00, 'Pending',    '2026-11-13 13:05:43');
ALTER TABLE `orders` AUTO_INCREMENT = 6;

CREATE TABLE `order_status_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id`   int(11) NOT NULL,
  `status`     enum('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL,
  `remarks`    varchar(255) DEFAULT NULL,
  `changed_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`history_id`),
  KEY `idx_history_order` (`order_id`),
  CONSTRAINT `fk_history_order`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `order_status_history` (`order_id`, `status`, `remarks`, `changed_at`)
SELECT `order_id`, `status`, 'Existing order', `order_date` FROM `orders`;
