# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL 0004

### Features
- FEAT-CLI-DC2

### Pasos

- Entorno

- BackUp DB                                                                     
- Backup /pages                                                                 
- Cambios en DB       
        ALTER TABLE permisos
        ADD admin_cli_dc INT DEFAULT '0' AFTER cli_dc;

        CREATE TABLE `sdc_hosting_bck` (
        `id` int(11),
        `id_cliente` int(11) NOT NULL,
        `tipo` varchar(20) DEFAULT NULL,
        `nombre` varchar(255) DEFAULT NULL,
        `displayName` varchar(255) DEFAULT NULL,
        `proyecto` varchar(255) DEFAULT NULL,
        `datacenter` varchar(255) DEFAULT NULL,
        `fecha` datetime DEFAULT NULL,
        `hipervisor` varchar(255) DEFAULT NULL,
        `hostname` varchar(255) DEFAULT NULL,
        `pool` varchar(255) DEFAULT NULL,
        `uuid` varchar(255) DEFAULT NULL,
        `VCPU` double DEFAULT NULL,
        `RAM` double DEFAULT NULL,
        `storage` double DEFAULT NULL,
        `SO` varchar(255) DEFAULT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0'
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

- Cambios en src  
        M[pages/admin.php]
        M[pages/setPermiso.php]
        M[pages/cdc_cliente.php]
        M[pages/cdc_organismo.php]
        M[pages/sdc_housing.php]
        M[pages/modals/sdc_ambhousing.js]
        M[pages/modals/sdc_ambcliente.js]
        M[pages/modals/sdc_amborgnaismo.js]
        M[pages/helpers/sdc_importhosting.php]
        M[pages/modals/sdc_importhosting.js]