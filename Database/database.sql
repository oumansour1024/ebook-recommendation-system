-

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Création de la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS `dbApp` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `dbApp`;

-- Création de l'utilisateur et attribution des privilèges
CREATE USER  IF NOT EXISTS 'use_dbApp'@'%' IDENTIFIED BY 'V3ry$tr0ngP@ssw0rd!2024';
GRANT ALL PRIVILEGES ON `dbApp`.* TO 'use_dbApp'@'%';
FLUSH PRIVILEGES;

-- Table principale des utilisateurs
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL DEFAULT (UUID()),
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_verified` tinyint(1) DEFAULT 0,
  `mfa_enabled` tinyint(1) DEFAULT 0,
  `mfa_secret` varchar(32) DEFAULT NULL,
  `failed_login_attempts` int(11) DEFAULT 0,
  `last_failed_login` timestamp NULL DEFAULT NULL,
  `account_locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_account_status` (`is_active`,`account_locked_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Table principale des utilisateurs';

