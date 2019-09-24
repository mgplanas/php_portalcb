## RELEASE NOTE FOR REL 0004
### Features
- FEAT-CLI-DC

# RELEASE NOTE FOR REL-

## Features

FEAT-PROY-GTI
>>>>>>> devel

### Pasos

- Entorno
    /bower_components/datatables.net/css/jquery.dataTables.min.css
    /bower_components/datatables.net/images

- BackUp DB                                                                     
- Backup /pages                                                                 
- Cambios en DB       
    - crear tablas: cdc_cliente, cdc_organizacion, sdc_housing, sdc_hosting, sdc_hosting_temp (con index en uuid)
    - migrar datos

<<<<<<< HEAD
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
=======
    ALTER TABLE permisos
    ADD admin_proy INT(11) DEFAULT '0' AFTER guardias; 
    ALTER TABLE permisos
    ADD proy INT(11) DEFAULT '0' AFTER admin_proy;

    -Se actualizan los permisos de aquellos que hoy en día son admin
    update permisos set admin_proy = 1 where admin = 1;

    - Se le dan los permisos de proyecto a los de soc
    update permisos set proy = 1 where soc = 1;

    ALTER TABLE grupo ADD id_gerencia INT NOT NULL DEFAULT '0' AFTER id_grupo;
    - Se actualizan los grupos actuales con la generecia de CiberSeguridad:
    update grupo set id_gerencia = 1;

    - Se agregan el campo en la base de permisos admin_per para administrar personal y accesos
    ALTER TABLE permisos ADD admin_per INT(11) DEFAULT '0' AFTER proy;

    - Se actualizan los permisos de aquellos que hoy en día son admin
    update permisos set admin_per = 1 where admin = 1;



- Cambios en src
    M[pages/admin.php]
    M[pages/setPermiso.php]
    M[pages/proyectos.php]
    M[pages/getProyResp.php]
    M[pages/getProyRespStat.php]
    M[pages/edit_persona.php]
    M[pages/site_sidebar.php]
    M[site.php]
    M[pages/site_header.php]
    M[edit_proyecto.php]
>>>>>>> devel
