SET names utf8;
DROP DATABASE IF EXISTS `eet`;
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
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `children` smallint(5) unsigned DEFAULT NULL,
  `costs` varchar(20) DEFAULT NULL,
  `tax_payer` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `ip`, `last_login`, `lognum`, `browser`, `firstname`, `lastname`, `address`, `children`, `costs`, `tax_payer`) VALUES
(1,	'test',	'a4eee0c1095d3275454a5825301b3bdc8f91aa04',	'127.0.0.1',	'2015-11-25 14:54:52',	NULL,	'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36',	'Test',	'Testový',	'Praha',	13,	'3',	1);

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `price` double unsigned NOT NULL,
  `archivated` tinyint(4) NOT NULL DEFAULT '0',
  `stored` tinyint(4) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `products` (`id`, `name`, `unit`, `price`, `archivated`, `stored`, `user_id`) VALUES
(2,	'Koláč',	'Kus',	25,	0,	1,	1),
(3,	'Zmrzlina',	'Kopeček',	15,	0,	1,	1),
(4,	'Pivo',	'Půlitr',	38,	1,	1,	1),
(5,	'Sýr',	'Kus',	34,	0,	1,	1);

DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `identification_number` varchar(20) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_id` (`user_id`),
  CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `stores` (`id`, `name`, `address`, `identification_number`, `user_id`) VALUES
(1,	'Království stánků',	'Praha 1',	'56235663',	1);

