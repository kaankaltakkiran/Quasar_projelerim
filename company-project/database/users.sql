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
  `user_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

INSERT INTO `users` (`id`, `user_name`, `user_email`, `user_status`) VALUES
(1,	'kaan',	'kaan@gmail.com',	1),
(2,	'veli',	'veli@gmail.com',	1),
(3,	'ayşe',	'ayse@gmail.com',	0),
(4,	'fatma',	'fatma@gmail.com',	0),
(5,	'zeynep',	'zeynep@gmail.com',	1),
(6,	'furkan',	'furkan@gmail.com',	0)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `user_name` = VALUES(`user_name`), `user_email` = VALUES(`user_email`), `user_status` = VALUES(`user_status`);

-- 2024-06-09 20:04:13
