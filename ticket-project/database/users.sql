-- Adminer 4.8.1 MySQL 5.5.5-10.6.16-MariaDB-0ubuntu0.22.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

INSERT INTO `users` (`id`, `user_name`, `user_email`, `user_status`) VALUES
(1,	'kaan',	'kaan@gmail.com',	'1'),
(2,	'veli',	'veli@gmail.com',	'0'),
(3,	'ahmet',	'ahmet@gmail.com',	'0'),
(4,	'semih',	'semih@gmail.com',	'1'),
(5,	'zeynep',	'zeynep@gmail.com',	'1'),
(6,	'fatma',	'fatma@gmail.com',	'1')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `user_name` = VALUES(`user_name`), `user_email` = VALUES(`user_email`), `user_status` = VALUES(`user_status`);

-- 2024-06-17 12:51:10
