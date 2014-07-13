SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `my_compra`;
CREATE TABLE IF NOT EXISTS `my_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_compra` datetime NOT NULL,
  `total` float NOT NULL,
  `id_frigorifico` int(11) NOT NULL,
  PRIMARY KEY (`id`,`fecha_compra`),
  KEY `id_frigorifico` (`id_frigorifico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `my_compra_producto`;
CREATE TABLE IF NOT EXISTS `my_compra_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_entrada` datetime NOT NULL,
  `unidades` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_supermercado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compra` (`id_compra`),
  KEY `id_producto` (`id_producto`),
  KEY `unidades` (`unidades`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `my_compra_retirada`;
CREATE TABLE IF NOT EXISTS `my_compra_retirada` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_retirada` datetime NOT NULL,
  PRIMARY KEY (`id_producto`,`fecha_retirada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `my_supermercado`;
CREATE TABLE IF NOT EXISTS `my_supermercado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(128) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (titulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `my_supermercado_producto`;
CREATE TABLE IF NOT EXISTS `my_supermercado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(128) NOT NULL,
  `codigo_barras` varchar(128) NOT NULL,
  `codigo_rfid` varchar(128) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` float NOT NULL,
  `unidades` int(11) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `fecha_baja` datetime NOT NULL,
  `id_supermercado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_barras` (`codigo_barras`),
  UNIQUE KEY `codigo_rfid` (`codigo_rfid`),
  KEY `id_supermercado` (`id_supermercado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `my_user`;
CREATE TABLE IF NOT EXISTS `my_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` char(60) NOT NULL,
  `permission` tinyint(4) NOT NULL,
  `state` char(1) NOT NULL,
  `last_ip` char(15) NOT NULL,
  `last_access` datetime NOT NULL,
  `login_attempts` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `my_user_frigorifico`;
CREATE TABLE IF NOT EXISTS `my_user_frigorifico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(128) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_baja` datetime NOT NULL,
  `id_user` int(11) NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `my_user_remember_token`;
CREATE TABLE IF NOT EXISTS `my_user_remember_token` (
  `email` varchar(128) NOT NULL,
  `token` varchar(60) NOT NULL,
  `creation_date` datetime NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `state` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`email`,`creation_date`),
  KEY `state` (`state`),
  KEY `attempts` (`attempts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `my_compra`
  ADD CONSTRAINT `my_compra_ibfk_1` FOREIGN KEY (`id_frigorifico`) REFERENCES `my_user_frigorifico` (`id`);

ALTER TABLE `my_compra_producto`
  ADD CONSTRAINT `my_compra_producto_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `my_compra` (`id`),
  ADD CONSTRAINT `my_compra_producto_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `my_supermercado_producto` (`id`);

ALTER TABLE `my_compra_retirada`
  ADD CONSTRAINT `my_compra_retirada_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `my_compra_producto` (`id`);

ALTER TABLE `my_supermercado_producto`
  ADD CONSTRAINT `my_supermercado_producto_ibfk_1` FOREIGN KEY (`id_supermercado`) REFERENCES `my_supermercado` (`id`);

ALTER TABLE `my_user_frigorifico`
  ADD CONSTRAINT `my_user_frigorifico_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `my_user` (`id`);

SET FOREIGN_KEY_CHECKS = 1;