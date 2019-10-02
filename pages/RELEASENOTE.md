# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0014

### Features

FEAT-RAUL

### Pasos

- Entorno

- BackUp DB
- Backup /pages
- Cambios en DB
    # SCRIPT DE NORMALIZACION
    # A) ENERGIA
    # 1) Modificar el registro de Educar (cliente 14) y sacar el, 6PDU
    UPDATE sdc_housing SET energia = "12" WHERE id = 9;

    #2) Actualizo los NULL a 0
    UPDATE sdc_housing SET energia = 0 WHERE energia IS NULL OR energia = "";

    #3) Saco la palabra KVA
    UPDATE sdc_housing SET energia = REPLACE(energia, "KVA", "");

    #4) Cambio el tipo de dato a INT DEFAULT 0



    #B) M2
    #1) Actualizo los NULL a 0
    UPDATE sdc_housing SET m2 = 0 WHERE m2 IS NULL OR m2 = "";

    #3) Convierto coma a punto
    UPDATE sdc_housing SET m2 = REPLACE(m2, ",", ".");

    #) Convierto el campo en DECIMAL(6,2)
    #SELECT id_cliente, CAST(m2 AS DECIMAL(6,2)) as m2 FROM sdc_housing
    ALTER TABLE controls.sdc_housing
    CHANGE m2 m2 DECIMAL(8,2) DEFAULT '0';

- Cambios en src  
        M[../site.php]                  OK
        M[site_sidebar.php]
        M[site_header.php]
        N[site_footer.php]          OK  
        M[activos.php]              OK
        M[admin.php]                OK
        M[calendario.php]           OK
        M[calendario_guardias.php]  OK
        M[cal_controles.php]        OK
        M[cal_riesgos.php]          OK
        M[cdc_cliente.php]          OK
        M[cdc_organismo.php]        OK
        M[clean_content.php]        OK
        M[control.php]              OK
        M[controles.php]            OK
        M[controlfw.php]            OK
        M[edit_activo.php]          OK
        M[edit_conexion.php]        OK
        M[edit_control.php]         OK
        M[edit_dispositivo.php]     OK
        M[edit_iso27k.php]          OK
        M[edit_mejora.php]          OK
        M[edit_persona.php]         OK
        M[edit_proyecto.php]        OK
        M[edit_referencia.php]      OK
        M[edit_riesgo.php]          OK
        M[inventario.php]           OK
        M[iso27k.php]               OK
        M[iso9k.php]                OK
        M[mejoras.php]              OK
        M[metricas.php]             OK
        M[met_activos.php]          OK
        M[met_controles.php]        OK
        M[met_iso27k.php]           OK
        M[met_mejoras.php]          OK
        M[met_riesgos.php]          OK
        M[novedades.php]            OK
        M[proyectos.php]            OK
        M[riesgos.php]              OK
        M[sdc_hosting.php]          OK
        M[sdc_housing.php]          OK
        M[tareas.php]               OK
        M[topologia.php]            OK
        M[site.php]                 OK
        M[index.html]               OK
        M[site.php]
        M[pages/site_sidebar.php]
        M[pages/cdc_dashboard.php]
        M[pages/cdc_dashboard.php]
        M[pages/cdc_dashboard]
        M[pages/sdc_housing.php]
        M[pages/modals/sdc_abmhousing.php]
        N[img/logo_arsat.png]
