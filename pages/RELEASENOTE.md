# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL 0004

### Features
- FEAT-CLI-DC

### Pasos

- Entorno
    /bower_components/datatables.net/css/jquery.dataTables.min.css
    /bower_components/datatables.net/images
    update php.ini set upload_max_filesize=8M

- BackUp DB                                                                     
- Backup /pages                                                                 
- Cambios en DB       
    - crear tablas: cdc_cliente, cdc_organizacion, sdc_housing, sdc_hosting, sdc_hosting_temp (con index en uuid)
    - migrar datos

    ALTER TABLE permisos
    ADD cli_dc INT(11) DEFAULT '0' AFTER admin_per;
- Cambios en src  
        M[pages/site_sidebar.php]
        M[site.php]
        N[pages/cdc_dashboard.php]
        N[pages/cdc_cliente.php]
        N[pages/cdc_organismo.php]
        N[pages/sdc_housing.php]
        N[pages/sdc_hosting.php]    
        N[pages/helpers/sdc_abmhousingdb.php] 
        N[pages/helpers/sdc_importhosting.php]
        N[pages/modals/sdc_abmhousing.js] 
        N[pages/modals/sdc_abmhousing.php]
        N[pages/modals/sdc_importhosting.js]  
        N[pages/modals/sdc_importhosting.php]     
        M[pages/admin.php]
        M[pages/setPermiso.php]
        M[pages/admin.php]
        M[pages/calendario.php]
        M[pages/inventario.php]
        M[pages/iso27k.php]
        M[pages/iso9k.php]
        M[pages/novedades.php]
        M[pages/topologia.php]


