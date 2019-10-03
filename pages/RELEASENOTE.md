# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0014

### Features

- FEAT-CALENDAR

### Pasos

- Entorno

- BackUp DB
- Backup /pages
- Cambios en DB
  - Agregado de Responsable y borrado en la tabla de gerencia
        ALTER TABLE gerencia
        ADD responsable INT AFTER sigla,
        ADD borrado INT DEFAULT '0';
  - Creación Tabla subgerencia
        CREATE TABLE `subgerencia` (
        `id_subgerencia` int(11) NOT NULL AUTO_INCREMENT,
        `id_gerencia` int(11) NOT NULL,
        `nombre` varchar(255) NOT NULL,
        `sigla` varchar(20) DEFAULT NULL,
        `responsable` int(11) DEFAULT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id_subgerencia`)
        ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

    -Creacion tabla area
        CREATE TABLE `area` (
        `id_area` int(11) NOT NULL AUTO_INCREMENT,
        `id_subgerencia` int(11) NOT NULL,
        `nombre` varchar(255) NOT NULL,
        `sigla` varchar(20) DEFAULT NULL,
        `responsable` int(11) DEFAULT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id_area`)
        ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

    -Modificar tabla personas para agregar campos de subgerencia y area
        ALTER TABLE persona
        ADD subgerencia INT AFTER contacto,
        ADD area INT;
    - Popular tablas con los datos ya ingresados
        -subgerencias
        -áreas

- Cambios en src
  - M[pages/calendario.php]
  - M[pages/admin.php]
  - N[helpers/*.php]
  - N[modals/abmarea.php]
  - N[modals/abmestructura.js]
  - N[modals/abmgerencia.php]
  - N[modals/abmpersona.js]
  - N[modals/abmpersona.php]
  - N[modals/abmsubgerencia.php]
  - N[helpers/abmareadb.php]
  - N[helpers/abmgerenciadb.php]
  - N[helpers/abmsubgerenciadb.php]
  