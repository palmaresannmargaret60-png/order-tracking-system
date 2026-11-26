CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `customer_name`    VARCHAR(100) NOT NULL,
  `customer_address` VARCHAR(255) NOT NULL,
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `uq_customer` (`customer_name`, `customer_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `product_id`   INT(11)       NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(100)  NOT NULL,
  `price`        DECIMAL(10,2) NOT NULL,
  `is_active`    TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `uq_product_name` (`product_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `order_status_history` (
  `history_id` INT(11)      NOT NULL AUTO_INCREMENT,
  `order_id`   INT(11)      NOT NULL,
  `status`     ENUM('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL,
  `note`       VARCHAR(255) DEFAULT NULL,
  `changed_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  KEY `idx_history_order` (`order_id`),
  CONSTRAINT `fk_history_order`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `orders`
  ADD COLUMN `customer_id` INT(11) NULL DEFAULT NULL AFTER `order_number`,
  ADD COLUMN `product_id`  INT(11) NULL DEFAULT NULL AFTER `customer_id`,
  ADD KEY `idx_orders_customer` (`customer_id`),
  ADD KEY `idx_orders_product`  (`product_id`),
  ADD CONSTRAINT `fk_orders_customer`
    FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
    ON UPDATE CASCADE ON DELETE SET NULL;

INSERT IGNORE INTO `products` (`product_name`, `price`) VALUES
('Wireless Mouse',       399.50),
('Mechanical Keyboard', 2499.00),
('USB-C Hub',           1250.50),
('Laptop Stand',         600.00),
('Webcam',              1500.00);
