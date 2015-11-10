CREATE DATABASE IF NOT EXISTS `eet` DEFAULT CHARACTER SET = `utf8`;
USE `eet`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `ip` varchar(40) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `lognum` int(11) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `ip`, `last_login`, `lognum`, `browser`)
VALUES
  (1, 'test', 'a4eee0c1095d3275454a5825301b3bdc8f91aa04', NULL, NULL, 0, NULL);

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `price` double unsigned NOT NULL,
  `archivated` tinyint(4) NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `products` (`id`, `name`, `unit`, `price`, `archivated`, `user_id`) VALUES
(2,	'Koláč',	'Kus',	25,	0,	1),
(3,	'Zmrzlina',	'Kopeček',	15,	0,	1),
(4,	'Pivo',	'Půlitr',	38,	1,	1);
