# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0014

### Features

FEAT-PROY-AIND

### Pasos

- Entorno

- BackUp DB
- Backup /pages
- Cambios en DB
        ALTER TABLE controls.gerencia
 ADD borrado INT NOT NULL DEFAULT '0' AFTER sigla;

- Cambios en src  
    M[pages/proyectos.php]
    M[pages/getProyResp.php]
    M[pages/getProyRespStat.php]
