## RELEASE NOTE FOR
### Features
- FEAT-PER-ABM
- FEAT-CTR-GERENCIA
- FEAT-RGO-VARIOS

### Pasos
- BackUp DB
- Backup /pages
- Cambios en DB
    - Agregar columna "borrado" a tabla persona TINYINT NOT NULL DEFAULT 0
    - Agregar columna "justificacion_cierre" a riesgos VARCHAR(255) NULL 
- Cambios en SRC
    - Actualizar pages/admin.php
    - Actualizar pages/riesgos.php
    - Actualizar pages/edit_riesgo.php
    - Actualizar pages/controles.php
    - Agregar pages/edit_persona.php