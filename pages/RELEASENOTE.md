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