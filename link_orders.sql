INSERT IGNORE INTO `products` (`product_name`, `price`)
SELECT `product_name`, ROUND(MAX(`total_amount` / `quantity`), 2)
FROM `orders`
GROUP BY `product_name`;

INSERT IGNORE INTO `customers` (`customer_name`, `customer_address`)
SELECT DISTINCT `customer_name`, `customer_address`
FROM `orders`;

UPDATE `orders` o
JOIN `customers` c
  ON c.`customer_name` = o.`customer_name`
 AND c.`customer_address` = o.`customer_address`
SET o.`customer_id` = c.`customer_id`;

UPDATE `orders` o
JOIN `products` p
  ON p.`product_name` = o.`product_name`
SET o.`product_id` = p.`product_id`;

INSERT INTO `order_status_history` (`order_id`, `status`, `note`, `changed_at`)
SELECT o.`order_id`, o.`status`, 'Imported from existing order', o.`order_date`
FROM `orders` o
WHERE NOT EXISTS (
  SELECT 1 FROM `order_status_history` h WHERE h.`order_id` = o.`order_id`
);
