## RELEASE NOTE FOR REL 0006
### Features
FEAT-ISO271K

### Pasos
- Entorno
    N/A
- BackUp DB                                                                     
- Backup /pages                                                                 
- Cambios en DB                                                                 
    - Creación tabla relaciones items-iso -> referentes
        CREATE TABLE `iso27k_refs` (
        `id_item_iso27k` int(11) NOT NULL,
        `id_persona` int(11) NOT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0'
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


    - Agregado de campo nivel (titulo, subtitulo, item) y parent a tabla item_iso27k1
        ALTER TABLE controls.item_iso27k
        ADD nivel INT NOT NULL DEFAULT '3' AFTER usuario,
        ADD parent INT,
        ADD version INT NOT NULL DEFAULT '1';

    - Versionado de la matriz. Creación de tabla de versionado y agregado de FK en item_iso27k
        CREATE TABLE `iso27k_version` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `numero` varchar(20) NOT NULL,
        `descripcion` varchar(255) DEFAULT NULL,
        `modificacion` datetime NOT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    - Agregar registro de versión manual
        1, 4.0, '', NOW(), 0

    - se cambia la restriccion de indice unico a UNIQUE KEY `codigo_UNIQUE` (`version`,`codigo`)

    - se amplian los campos varchar a 1000 para albergar más información
        ALTER TABLE controls.item_iso27k
        CHANGE descripcion descripcion VARCHAR(1000),
        CHANGE implementacion implementacion VARCHAR(1000),
        CHANGE evidencia evidencia VARCHAR(1000);

- Cambios en src        
    N[/datatables.net/css/rowGroup.dataTables.min.css] 
    N[/datatables.net/js/dataTables.rowGroup.min.js]   |  25 ++
    N[css/bootstrap-select.min.css]                       |   6 +
    N[js/bootstrap-select.min.js]                         |   9 +
    M[iso27k.php]
    M[edit_iso27k.php]
    M[pages/edit_iso27k.php]                              | 140 +++++--
    N[pages/helpers/abmiso27kdb.php]                      | 118 ++++++
    N[pages/helpers/getAsyncDataFromDB.php]               |  21 +
    M[pages/iso27k.php]                                   | 431 +++++++--------------
    M[pages/met_iso27k.php]                               |   3 +-
    N[pages/modals/abmiso27k.js]                          | 176 +++++++++
    N[pages/modals/abmiso27k.php]                         | 115 ++++++
    M[site.php]                                           |   3 +-
