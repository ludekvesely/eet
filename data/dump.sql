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
