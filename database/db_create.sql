CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `first_name` VARCHAR(30) NOT NULL,
  `last_name` VARCHAR(30) NOT NULL,
  `role_id` INT(11) NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`username`, `email`)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE
  `users`
ADD
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE
SET
  NULL ON UPDATE CASCADE;
CREATE TABLE IF NOT EXISTS `products` (
    `code_product` INT(11) NOT NULL AUTO_INCREMENT,
    `code_category` INT(11) NULL,
    `name` VARCHAR(100) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `selling_price` DOUBLE NOT NULL,
    `photo` TEXT NOT NULL,
    PRIMARY KEY (`code_product`)
  ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE
  `products`
ADD
  FOREIGN KEY (`code_category`) REFERENCES `categories` (`code_category`) ON DELETE
SET
  NULL ON UPDATE CASCADE,
ADD
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE TABLE IF NOT EXISTS `suppliers` (
    `code_supplier` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(25) NOT NULL,
    `informations` VARCHAR(100) NOT NULL,
    `num_street` VARCHAR(10) NOT NULL,
    `name_street` VARCHAR(50) NOT NULL,
    `postcode` VARCHAR(6) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`code_supplier`),
    UNIQUE KEY (`name`)
  ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE TABLE IF NOT EXISTS `stocks` (
    `code_stock` INT(11) NOT NULL AUTO_INCREMENT,
    `code_product` INT(11) NOT NULL,
    `quantity` INT(11) NOT NULL,
    PRIMARY KEY (`code_stock`),
    UNIQUE KEY (`code_product`)
  ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE
  `stocks`
ADD
  FOREIGN KEY (`code_product`) REFERENCES `products` (`code_product`) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE TABLE IF NOT EXISTS `product_supplier` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `code_product` INT(11) NOT NULL,
    `code_supplier` INT(11) NOT NULL,
    `purchase_price` DOUBLE NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`code_product`, `code_supplier`)
  ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE
  `product_supplier`
ADD
  FOREIGN KEY (`code_product`) REFERENCES `products` (`code_product`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD
  FOREIGN KEY (`code_supplier`) REFERENCES `suppliers` (`code_supplier`) ON DELETE CASCADE ON UPDATE CASCADE;