## RELEASE NOTE FOR
### Features
- FEAT-PIE-RIESGO
- FEAT-PIE-CONTROLES
- FEAT-CTR-MESINICIO

### Pasos
- BackUp DB
- Backup /pages
- Cambios en DB
    - Agregar columna "mesinicio" a tabla controles INT(11) NOT NULL DEFAULT 1
    - Actualizar todos los controles existentes con el mes 1: UPDATE controles SET mesinicio = 1
- Cambios en SRC
    - Actualizar site.php
    - Actualizar pages/controles.php
    - Actualizar pages/edit_control.php