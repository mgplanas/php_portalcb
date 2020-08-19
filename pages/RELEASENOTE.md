# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL200603

### Features

- FEAT-IAAS

### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

    OK ADD field con_convenio INT NOT NULL = 0 en CDC_CLIENTE
    OK ADD field modalidad INT NOT NULL = 0 en SDC_HOUSING
    OK ADD table sdc_housing_modalidad
        CREATE TABLE `sdc_housing_modalidad` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `descripcion` varchar(25) NOT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
    OK MIGRAR DATOS DE MODALIDADES
        id	descripcion	borrado
        1	Rack	0
        2	Sala	0

    OK ADD table scd_iaas
        CREATE TABLE `sdc_iaas` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `id_cliente` int(11) NOT NULL,
        `plataforma` varchar(20) NOT NULL DEFAULT 'VRA',
        `reserva` varchar(50) NOT NULL,
        `ram_capacidad` int(11) NOT NULL,
        `storage_capacidad` int(11) NOT NULL,
        `ram_uso` int(11) NOT NULL,
        `storage_uso` int(11) NOT NULL,
        `observaciones` varchar(255) DEFAULT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

    MIGRAR PLANILLA DE VRA

- Cambios en src
- Se actualiza grilla sdc_housing y abms helper y modal
    - pages/sdc_iaas.php
    - pages/modals/sdc_abmiaas.php
    - pages/modals/sdc_abmiaas.js
    - pages/helpers/sdc_abmiaasdb.php
    - pages/site_sidemenu.php
    - site.php
- suma de servicios en clientes y ink
    - pages/cdc_clientes
    - pages/modals/sdc_iaas_view.php
    - pages/modals/sdc_iaas_view.js 

 CHANGES.md                         |  77 +++++++++++++
 pages/RELEASENOTE.md               |  50 +++++++--
 pages/cdc_cliente.php              |  65 ++++++-----
 pages/helpers/cdc_abmclientedb.php |   7 +-
 pages/helpers/sdc_abmhousingdb.php |   7 +-
 pages/helpers/sdc_abmiaasdb.php    |  46 ++++++++
 pages/modals/cdc_abmcliente.js     |  11 +-
 pages/modals/cdc_abmcliente.php    |  17 ++-
 pages/modals/sdc_abmhousing.js     |   6 +-
 pages/modals/sdc_abmhousing.php    |  53 ++++++---
 pages/modals/sdc_abmiaas.js        | 131 ++++++++++++++++++++++
 pages/modals/sdc_abmiaas.php       |  88 +++++++++++++++
 pages/modals/sdc_iaas_view.js      |  69 ++++++++++++
 pages/modals/sdc_iaas_view.php     |  56 ++++++++++
 pages/sdc_housing.php              |   9 +-
 pages/sdc_iaas.php                 | 261 ++++++++++++++++++++++++++++++++++++++++++++
 pages/site_sidebar.php             |   1 +
 site.php                           |   1 +
