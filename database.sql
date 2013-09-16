-- CREATE DATABASE `connector`;

SET CHARSET 'UTF8';

CREATE TABLE IF NOT EXISTS `connector`.`person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;