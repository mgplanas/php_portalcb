# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0021

### Features

- FEAT-MC-AUDITORES


### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

CREATE TABLE `aud_entes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(50) NOT NULL,
  `cuit` varchar(13) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `aud_auditores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ente` int(11) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `dni` varchar(10) DEFAULT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `aud_instancias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ente` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `observaciones` varchar(1000) DEFAULT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `aud_rel_ins_aud` (
  `id_instancia` int(11) NOT NULL,
  `id_auditor` int(11) NOT NULL,
  `borrado` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE controls.mejora
    ADD aud_ente INT AFTER matriz,
    ADD aud_instancia INT;

ALTER TABLE controls.mejora
    ADD prioridad INT NOT NULL DEFAULT '0' AFTER aud_instancia;

- Cambios en src
 CHANGES.md                              |  36 ++++
 pages/RELEASENOTE.md                    |  28 +--
 pages/aud_auditores.php                 | 268 +++++++++++++++++++++++
 pages/aud_ente.php                      | 265 +++++++++++++++++++++++
 pages/aud_insauditores.php              | 371 ++++++++++++++++++++++++++++++++
 pages/aud_instancias.php                | 286 ++++++++++++++++++++++++
 pages/edit_mejora.php                   |  44 ++--
 pages/helpers/aud_abmauditoresdb.php    |  42 ++++
 pages/helpers/aud_abmentedb.php         |  42 ++++
 pages/helpers/aud_abminsauditoresdb.php |  24 +++
 pages/helpers/aud_abminstanciasdb.php   |  49 +++++
 pages/mejoras.php                       |  71 ++++--
 pages/modals/aud_abmauditores.js        |  85 ++++++++
 pages/modals/aud_abmauditores.php       |  46 ++++
 pages/modals/aud_abmente.js             |  79 +++++++
 pages/modals/aud_abmente.php            |  45 ++++
 pages/modals/aud_abminstancias.js       | 135 ++++++++++++
 pages/modals/aud_abminstancias.php      |  75 +++++++
 pages/site_sidebar.php                  |  27 ++-
 site.php                                |  12 ++