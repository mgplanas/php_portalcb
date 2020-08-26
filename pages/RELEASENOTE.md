# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL200603

### Features

- FEAT-DC-CONTRATOS
### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

    CREATE TABLE `adm_contratos_vto` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_subgerencia` int(11) NOT NULL,
    `id_proveedor` int(11) DEFAULT NULL,
    `tipo_mantenimiento` varchar(255) DEFAULT NULL,
    `vencimiento` datetime NOT NULL,
    `oc` varchar(20) DEFAULT NULL,
    `borrado` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

    ALTER TABLE controls.permisos
    ADD admin_contratos INT AFTER admin_riesgos;

    ALTER TABLE controls.permisos
    ADD contratos INT AFTER admin_contratos;
    
- Cambios en src

    CHANGES.md                        |  28 +++++++++++
    pages/RELEASENOTE.md              |  42 +++++++++-------
    pages/admin.php                   |  21 ++++++--
    pages/helpers/adm_contratosdb.php |  48 +++++++++++++++++++
    pages/modals/abmcompra.js         |   2 +-
    pages/modals/adm_contratos.php    |  84 ++++++++++++++++++++++++++++++++
    pages/modals/adm_contratos_oc.php |  71 +++++++++++++++++++++++++++
    pages/setPermiso.php              |  11 +++++
    pages/site_sidebar.php            |   1 +
    - Incluir mejoras.php
