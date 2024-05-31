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
  `user_age` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

INSERT INTO `users` (`id`, `user_name`, `user_age`) VALUES
(1,	'Kaan',	24),
(2,	'Veli',	10),
(3,	'Ahmet',	30),
(4,	'Ayşe',	27),
(5,	'Fatma',	36),
(6,	'Selma',	18),
(7,	'Burak',	12)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `user_name` = VALUES(`user_name`), `user_age` = VALUES(`user_age`);

-- 2024-05-31 09:12:29