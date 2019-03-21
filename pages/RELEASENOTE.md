## RELEASE NOTE FOR
### Features
- FEAT-PER-ABM
- FEAT-CTR-GERENCIA
- FEAT-RGO-VARIOS
- FEAT-PER-TODOS
- FEAT-CTR-CRITICIDAD
- FEAT-FIX-VARIOS

### Pasos
- BackUp DB
- Backup /pages
- Cambios en DB
    - Agregar columna "borrado" a tabla persona TINYINT NOT NULL DEFAULT 0
    - Agregar columna "justificacion_cierre" a riesgos VARCHAR(255) NULL 
    - Agregar columna "criticidad" a controles INT(11) DEFAULT 2
    - Actualizar Recrear Triggers con DROP TRIGGER riesgo_BEFORE_UPDATE y riesgo_BEFORE_INSERT;
        - Cambiar calculo de trata a ">="
        - Cambiar calculo de valor_inicial/actual a 4 y 10
- Cambios en SRC
    - Actualizar pages/admin.php
    - Actualizar pages/riesgos.php
    - Actualizar pages/edit_riesgo.php
    - Actualizar pages/controles.php
    - Actualizar pages/edit_control.php
    - Agregar pages/edit_persona.php
    - Actualizar pages/activos.php
    - Actualizar pages/mejoras.php
    - Actualizar pages/iso27k.php