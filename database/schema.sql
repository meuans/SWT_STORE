-- Create database with utf8mb4 encoding
CREATE DATABASE IF NOT EXISTS `asian_store`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `asian_store`;

-- Create items table
CREATE TABLE IF NOT EXISTS `items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `area` VARCHAR(100) NULL,
  `price_cents` INT NOT NULL,
  `thumbnail_url` TEXT NULL,
  `category` VARCHAR(100) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


