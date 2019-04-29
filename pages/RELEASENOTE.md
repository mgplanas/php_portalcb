## RELEASE NOTE FOR REL 0002
### Features
- FEAT-REL-REORGANIZ
- FEAT-SITE-MENU
- FEAT-FIL-GERENCIA
- FEAT-AVA-FIXES
- REQ-20190416  
- Modificacion Proyectos

### Pasos
- Entorno
    - Backup /etc/httpd/conf/httpd.conf                                         [OK]
    - editar httpd.conf                                                         [OK]                          
        Agregar a lo último: SetEnv PRODUCTION_SERVER 3                         [OK]
    - reiniciar httpd -->  sudo systemctl restart httpd                         [OK]
- BackUp DB                                                                     [OK]
- Backup /pages                                                                 [OK]
- Backup site.php                                                               [OK]
- Cambios en DB                                                                 [OK]
    - Agregar campos en tabla avance_riesgo:
        - justificacion VARCHAR(155) NULL
        - avance INT NULL

UPDATE avance_riesgo                                                            [OK]
INNER JOIN
(
SELECT id_riesgo, MAX(avance) as maxavance
FROM riesgo
GROUP BY id_riesgo
) AS ra ON avance_riesgo.id_riesgo = ra.id_riesgo
SET avance_riesgo.avance = ra.maxavance


- Cambios en src                                                                [OK]
    - Actualizar pages/riesgo.php
    - Actualizar pages/edit_riesgo.php
    - Actualizar pages/novedades.php
    - N[site_sidebar.php]
    - N[site_header.php]
    - M[site.php]
    - M[activos.php]
    - M[controles.php]
    - M[iso27k.php]
    - M[mejoras.php]
    - M[riesgos.php]
    - M[calendario.php]
    - M[novedades.php]
    - M[proyectos.php]
    - M[inventario.php]
    - M[topologia.php]
    - N[met_activos.php]
    - N[met_iso27k.php]
    - N[met_riesgos.php]
    - N[met_controles.php]
    - M[admin.php]
    - M[edit_riesgo.php]