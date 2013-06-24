CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_identifier` varchar(45) NOT NULL,
  `app_code` int(11) NOT NULL,
  `app_serialized` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `app_identifier_UNIQUE` (`app_identifier`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8


