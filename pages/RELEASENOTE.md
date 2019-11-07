# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0019

### Features

- DEAT-COMPRAS

### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

        - Creacion adm_monedas
        CREATE TABLE `adm_monedas` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `sigla` varchar(3) NOT NULL DEFAULT 'USD',
                `descripcion` varchar(25) DEFAULT NULL,
                `borrado` int(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        - Creacion pasos de compras
        CREATE TABLE `adm_com_pasos` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `descripcion` varchar(25) DEFAULT NULL,
                `borrado` int(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        - Creacion estados de Compras
        CREATE TABLE `adm_com_estados` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `descripcion` varchar(25) DEFAULT NULL,
                `borrado` int(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;  

        - Creacion proveedores
        CREATE TABLE `adm_com_proveedores` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `razon_social` varchar(25) DEFAULT NULL,
                `borrado` int(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;      
        
        - Creacion procesos de compras
        CREATE TABLE `adm_com_procesos` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                #`sigla` varchar(3) NOT NULL DEFAULT 'USD',
                `descripcion` varchar(25) DEFAULT NULL,
                `borrado` int(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        - Crear Compras
        CREATE TABLE `adm_compras` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_gerencia` int(11) NOT NULL,
                `id_subgerencia` int(11) DEFAULT NULL,
                `nro_solicitud` varchar(20) NOT NULL,
                `concepto` varchar(255) DEFAULT NULL,
                `pre_id_moneda` int(11) DEFAULT NULL,
                `pre_monto` float DEFAULT NULL,
                `id_solicitante` int(11) DEFAULT NULL,
                `id_paso_actual` int(11) DEFAULT NULL,
                `id_siguiente_paso` int(11) DEFAULT NULL,
                `id_estado` int(11) NOT NULL DEFAULT '1',
                `id_proveedor` int(11) DEFAULT NULL,
                `nro_oc` varchar(20) DEFAULT NULL,
                `fecha_solicitud` date NOT NULL,
                `fecha_oc` date DEFAULT NULL,
                `fecha_limite` date DEFAULT NULL,
                `oc_id_moneda` int(11) DEFAULT NULL,
                `oc_monto` float DEFAULT NULL,
                `capex_opex` varchar(1) DEFAULT 'O',
                `id_proceso` int(11) DEFAULT NULL,
                `tags` varchar(255) DEFAULT NULL,
                `borrado` int(11) NOT NULL DEFAULT '0',
                `modificado` int(11) DEFAULT NULL,
                `modif_user` int(11) DEFAULT NULL,
                `plazo_unidad` int(11) DEFAULT NULL,
                `plazo_valor` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


        # PERMISOS
        ALTER TABLE controls.permisos
        ADD compras INT(11) DEFAULT '0' AFTER admin_cli_dc,
        ADD admin_compras INT(11) DEFAULT '0';
        

- Cambios en src
  - M [pages/]
