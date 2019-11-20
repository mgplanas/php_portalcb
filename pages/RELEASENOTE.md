# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0019

### Features

- DEAT-COMPRAS

### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

    # Creacion adm_monedas  OK

    # Creacion pasos de compras OK

    # Creacion estados de Compras   OK

    # Creacion proveedores OK 

    # Creacion procesos de compras  OK

    # Crear Compras OK

    # Comentarios   OK

    # PERMISOS  OK
        ALTER TABLE controls.permisos
        ADD compras INT(11) DEFAULT '0' AFTER admin_cli_dc,
        ADD admin_compras INT(11) DEFAULT '0';

    # Historial de pases    OK

- Cambios en src

ADD bower_components/bootstrap/dist/js/bootstrap.bundle.min.js  OK
ADD bower_components/popper/popper.min.js   OK

M [site.php]
M [pages/admin.php]
M [pages/setPermiso.php]
M [pages/site_sidebar.php]
M [pages/riesgos.php]
N [pages/compras.php]
N [pages/modals/abmcompra.js]
N [pages/modals/abmcompra.php]
N [pages/helpers/abmcompradb.php]
N [pages/helpers/abmcompracomentariodb.php]
N [pages/helpers/abmproveedordb.php]
