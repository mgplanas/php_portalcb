# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0019

### Features

- DEAT-COMPRAS

### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

    # Creacion adm_monedas

    # Creacion pasos de compras

    # Creacion estados de Compras

    # Creacion proveedores

    # Creacion procesos de compras

    # Crear Compras

    # Comentarios

    # PERMISOS
        ALTER TABLE controls.permisos
        ADD compras INT(11) DEFAULT '0' AFTER admin_cli_dc,
        ADD admin_compras INT(11) DEFAULT '0';

    # Historial de pases

- Cambios en src

ADD bower_components/bootstrap/dist/js/bootstrap.bundle.min.js
ADD bower_components/popper/popper.min.js

M [pages/admin.php]
M [pages/setPermiso.php]
M [site.php]
M [pages/site_sidebar.php]
N [pages/compras.php]
N [pages/modals/abmcompra.js]
N [pages/modals/abmcompra.php]
N [pages/helpers/abmcompradb.php]
N [pages/helpers/abmcompracomentariodb.php]
N [pages/helpers/abmproveedordb.php]
