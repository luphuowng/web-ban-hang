CREATE DATABASE IF NOT EXISTS `store`;
USE `store`;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`
(
    `id`         int         NOT NULL AUTO_INCREMENT,
    `name`       varchar(50) NOT NULL,
    `active`     tinyint(1)       DEFAULT '1',
    `created_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `categories_name_uindex` (`name`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `name`       varchar(50)  NOT NULL,
    `email`      varchar(255) NOT NULL,
    `address`    varchar(255) NOT NULL,
    `phone`      varchar(15)  NOT NULL,
    `active`     tinyint(1)        DEFAULT '1',
    `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `customers_email_uindex` (`email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`
(
    `id`          int         NOT NULL AUTO_INCREMENT,
    `category_id` int         NOT NULL,
    `name`        varchar(50) NOT NULL,
    `price`       int         NOT NULL,
    `stock`       tinyint     NOT NULL,
    `image`       text        NOT NULL,
    `description` text,
    `active`      tinyint(1)       DEFAULT '1',
    `created_at`  timestamp   NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `products_category_id_name_uindex` (`category_id`, `name`),
    CONSTRAINT `products_categories_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`         int         NOT NULL AUTO_INCREMENT,
    `name`       varchar(50) NOT NULL,
    `email`      varchar(50) NOT NULL,
    `password`   varchar(50) NOT NULL,
    `role`       varchar(50) NOT NULL,
    `address`    varchar(255)     DEFAULT NULL,
    `phone`      varchar(15) NOT NULL,
    `active`     tinyint(1)       DEFAULT '1',
    `created_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_uindex` (`email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 22
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`
(
    `id`          int       NOT NULL AUTO_INCREMENT,
    `user_id`     int       NOT NULL,
    `customer_id` int       NOT NULL,
    `order_time`  timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `status`      tinyint(1)     DEFAULT '0',
    `active`      tinyint(1)     DEFAULT '1',
    `created_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `orders_customers_fk` (`customer_id`),
    KEY `orders_users_fk` (`user_id`),
    CONSTRAINT `orders_customers_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
    CONSTRAINT `orders_users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details`
(
    `id`         int       NOT NULL AUTO_INCREMENT,
    `product_id` int       NOT NULL,
    `order_id`   int       NOT NULL,
    `quality`    tinyint   NOT NULL,
    `active`     tinyint(1)     DEFAULT '1',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `order_details_orders_fk` (`product_id`),
    CONSTRAINT `order_details_orders_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    CONSTRAINT `order_details_products_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;




