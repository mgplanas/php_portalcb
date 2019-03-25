## RELEASE NOTE FOR
### Features
- FEAT-PER-ABM
- FEAT-CTR-GERENCIA
- FEAT-RGO-VARIOS
- FEAT-PER-TODOS
- FEAT-CTR-CRITICIDAD
- FEAT-FIX-VARIOS

### Pasos
- BackUp DB     OK
- Backup /pages OK
- Cambios en DB
    - Agregar columna "borrado" a tabla persona TINYINT NOT NULL DEFAULT 0      OK
    - Agregar columna "justificacion_cierre" a riesgos VARCHAR(255) NULL        OK       
    - Agregar columna "criticidad" a controles INT(11) DEFAULT 2                OK
    - Actualizar Recrear Triggers con DROP TRIGGER riesgo_BEFORE_UPDATE y riesgo_BEFORE_INSERT; OK
        - Cambiar calculo de trata a ">="                                                       OK
        - Cambiar calculo de valor_inicial/actual a 4 y 10                                      OK
        - Actualizar registros con riesgo = 4: update riesgo set valoracion = "TRATAR" where n_riesgo = 4; OK
        - Actualizar registros con riesgo (4,10) que v_inicial= ALTO:                                      OK
            UPDATE riesgo 
            SET v_inicial = 'MEDIO'
            WHERE n_riesgo >= 4 AND n_riesgo <= 10 AND v_inicial='ALTO';
            
            SELECT id_riesgo, 'MEDIO' as v, amenaza,n_riesgo, v_inicial FROM riesgo where n_riesgo >= 4 AND n_riesgo <= 10 AND v_inicial='ALTO';
        - Actualizar registros con n_resid (4,10) que v_actual= ALTO:                                       OK
            UPDATE riesgo 
            SET v_actual = 'MEDIO'
            WHERE n_resid >= 4 AND n_resid <= 10 AND v_actual='ALTO';
            SELECT id_riesgo, 'MEDIO' as v, amenaza,n_resid, v_actual FROM riesgo where n_resid >= 4 AND n_resid <= 10 AND v_actual='ALTO';
- Cambios en SRC                                                                                            OK
    - Actualizar pages/admin.php
    - Actualizar pages/riesgos.php
    - Actualizar pages/edit_riesgo.php
    - Actualizar pages/controles.php
    - Actualizar pages/edit_control.php
    - Agregar pages/edit_persona.php
    - Actualizar pages/activos.php
    - Actualizar pages/mejoras.php
    - Actualizar pages/iso27k.php