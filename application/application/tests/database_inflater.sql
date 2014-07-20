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

CREATE TRIGGER my_compra_OnInsert BEFORE INSERT ON `my_compra`
    FOR EACH ROW SET NEW.fecha_compra = IFNULL(NOW(), NEW.fecha_compra);

DROP TABLE IF EXISTS `my_compra_producto`;
CREATE TABLE IF NOT EXISTS `my_compra_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(128) NOT NULL,
  `descripcion` text NOT NULL,
  `unidades` int(11) NOT NULL,
  `precio` float NOT NULL,
  `codigo_barras` varchar(128) NULL,
  `codigo_rfid` varchar(128) NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_ultima_salida` datetime NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_supermercado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compra` (`id_compra`),
  KEY `id_producto` (`id_producto`),
  KEY `unidades` (`unidades`),
  KEY `codigo_barras` (`codigo_barras`),
  KEY `codigo_rfid` (`codigo_rfid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TRIGGER my_compra_producto_OnInsert BEFORE INSERT ON `my_compra_producto`
    FOR EACH ROW SET NEW.fecha_entrada = IFNULL(NOW(), NEW.fecha_entrada);

DROP TABLE IF EXISTS `my_user_frigorifico_producto`;
CREATE TABLE IF NOT EXISTS `my_user_frigorifico_producto` (
  `id_frigorifico` int(11) NOT NULL,
  `id_producto_compra` int(11) NOT NULL,
  `unidades` int(11) NOT NULL,
  PRIMARY KEY (`id_frigorifico`,`id_producto_compra`),
  KEY (`unidades`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `my_supermercado`;
CREATE TABLE IF NOT EXISTS `my_supermercado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(128) NOT NULL,
  `state` char(1) NOT NULL DEFAULT 'A',
  `fecha_alta` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `fecha_cierre` datetime,
  PRIMARY KEY (`id`),
  KEY (titulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TRIGGER my_supermercado_OnInsert BEFORE INSERT ON `my_supermercado`
    FOR EACH ROW SET NEW.fecha_alta = IFNULL(NOW(), NEW.fecha_alta), NEW.fecha_modificacion = IFNULL(NOW(), NEW.fecha_modificacion);

DROP TABLE IF EXISTS `my_supermercado_producto`;
CREATE TABLE IF NOT EXISTS `my_supermercado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(128) NOT NULL,
  `codigo_barras` varchar(128) NULL,
  `codigo_rfid` varchar(128) NULL,
  `descripcion` text NOT NULL,
  `precio` float NOT NULL,
  `unidades` int(11) NOT NULL,
  `state` char(1) NOT NULL DEFAULT 'A',
  `fecha_alta` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `fecha_baja` datetime,
  `id_supermercado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo_barras` (`codigo_barras`),
  KEY `codigo_rfid` (`codigo_rfid`),
  KEY `state` (`state`),
  KEY `id_supermercado` (`id_supermercado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TRIGGER my_supermercado_producto_OnInsert BEFORE INSERT ON `my_supermercado_producto`
    FOR EACH ROW SET NEW.fecha_alta = IFNULL(NOW(), NEW.fecha_alta),
    NEW.fecha_modificacion = IFNULL(NOW(), NEW.fecha_modificacion);

DROP TABLE IF EXISTS `my_user`;
CREATE TABLE IF NOT EXISTS `my_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` char(60) NOT NULL,
  `permission` tinyint(4) NOT NULL,
  `state` char(1) NOT NULL DEFAULT 'A',
  `last_ip` char(15) NOT NULL,
  `last_access` datetime NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TRIGGER my_user_OnInsert BEFORE INSERT ON `my_user`
    FOR EACH ROW SET NEW.last_access = IFNULL(NOW(), NEW.last_access);

DROP TABLE IF EXISTS `my_user_frigorifico`;
CREATE TABLE IF NOT EXISTS `my_user_frigorifico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(128) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `fecha_baja` datetime,
  `id_user` int(11) NOT NULL,
  `state` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TRIGGER my_user_frigorifico_OnInsert BEFORE INSERT ON `my_user_frigorifico`
    FOR EACH ROW SET NEW.fecha_alta = IFNULL(NOW(), NEW.fecha_alta), NEW.fecha_modificacion = IFNULL(NOW(), NEW.fecha_modificacion);

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

CREATE TRIGGER my_user_remember_token_OnInsert BEFORE INSERT ON `my_user_remember_token`
    FOR EACH ROW SET NEW.creation_date = IFNULL(NOW(), NEW.creation_date);

ALTER TABLE `my_compra`
  ADD CONSTRAINT `my_compra_ibfk_1` FOREIGN KEY (`id_frigorifico`) REFERENCES `my_user_frigorifico` (`id`);

ALTER TABLE `my_compra_producto`
  ADD CONSTRAINT `my_compra_producto_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `my_compra` (`id`),
  ADD CONSTRAINT `my_compra_producto_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `my_supermercado_producto` (`id`),
  ADD CONSTRAINT `my_compra_producto_ibfk_3` FOREIGN KEY (`id_supermercado`) REFERENCES `my_supermercado` (`id`);

ALTER TABLE `my_supermercado_producto`
  ADD CONSTRAINT `my_supermercado_producto_ibfk_1` FOREIGN KEY (`id_supermercado`) REFERENCES `my_supermercado` (`id`);

ALTER TABLE `my_user_frigorifico`
  ADD CONSTRAINT `my_user_frigorifico_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `my_user` (`id`);

ALTER TABLE `my_user_remember_token`
  ADD CONSTRAINT `my_user_remember_token_ibfk_1` FOREIGN KEY (`email`) REFERENCES `my_user` (`email`);

ALTER TABLE `my_user_frigorifico_producto`
  ADD CONSTRAINT `my_user_frigorifico_producto_ibfk_1` FOREIGN KEY (`id_frigorifico`) REFERENCES `my_user_frigorifico` (`id`);

ALTER TABLE `my_user_frigorifico_producto`
  ADD CONSTRAINT `my_user_frigorifico_producto_ibfk_2` FOREIGN KEY (`id_producto_compra`) REFERENCES `my_compra_producto` (`id`);



SET FOREIGN_KEY_CHECKS = 1;