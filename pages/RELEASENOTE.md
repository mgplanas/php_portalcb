# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0019

### Features

- FEAT-BCRA


### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB


# ITEMS
CREATE TABLE `item_bcra` (
  `id_item_bcra` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(8) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` varchar(3000) DEFAULT NULL,
  `escenarios` varchar(255) DEFAULT NULL,
  `responsable` int(11) DEFAULT NULL,
  `madurez` int(11) DEFAULT NULL,
  `implementacion` varchar(2000) DEFAULT NULL,
  `documentacion` varchar(2000) DEFAULT NULL,
  `evidencia` varchar(2000) DEFAULT NULL,
  `modificado` datetime DEFAULT NULL,
  `borrado` int(11) DEFAULT '0',
  `usuario` varchar(155) DEFAULT NULL,
  `nivel` int(11) NOT NULL DEFAULT '2',
  `parent` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_item_bcra`),
  UNIQUE KEY `codigo_UNIQUE` (`version`,`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

# REFERENTES
CREATE TABLE `bcra_refs` (
  `id_item_bcra` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# VERSIONADO
CREATE TABLE `bcra_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `modificacion` datetime NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


# CREAR VERSION 1
1	0.1	Inicial	22/11/2019 00:00:00	0

- Cambios en src

    M[pages/bcra.php]
    M[pages/site_sidebar.php]
    N[site.php]
