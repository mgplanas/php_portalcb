## RELEASE NOTE FOR REL 0008
### Features
FEAT-ISO9K1


### Pasos
- Entorno
    N/A
- BackUp DB                                                                     
- Backup /pages                                                                 
- Cambios en DB                                                                 

    - Creación de estructuras
CREATE TABLE `item_iso9k` (
  `id_item_iso9k` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(8) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` varchar(3000) DEFAULT NULL,
  `madurez` int(11) DEFAULT NULL,
  `implementacion` varchar(2000) DEFAULT NULL,
  `responsable` int(11) DEFAULT NULL,
  `evidencia` varchar(2000) DEFAULT NULL,
  `modificado` datetime DEFAULT NULL,
  `borrado` int(11) DEFAULT '0',
  `usuario` varchar(155) DEFAULT NULL,
  `nivel` int(11) NOT NULL DEFAULT '3',
  `parent` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_item_iso9k`),
  UNIQUE KEY `codigo_UNIQUE` (`version`,`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `iso9k_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `modificacion` datetime NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `iso9k_refs` (
  `id_item_iso9k` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-Agregar una version
1, 1.0, 1/1/2019, Base

- Cambios en src
    N[helpers/abmiso9k.php]
    N[modals/abmiso9k.js]
    N[modals/abmiso9k.php]
    N[iso9k.php]
    M[site_sidebar.php]                                    
