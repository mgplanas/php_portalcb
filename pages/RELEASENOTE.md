## RELEASE NOTE FOR REL 0005
### Features
<<<<<<< HEAD
FEAT-METRICAS
FEAT-FIL-GERENCIA2
=======
FEAT-PROYECTOS
>>>>>>> FEAT-PROYECTOS

### Pasos
- Entorno
    N/A
- BackUp DB                                                                     
- Backup /pages                                                                 
- Cambios en DB                                                                 
    - Creacion tabla tipo_proyecto
        CREATE TABLE `tipo_proyeto` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nombre` varchar(20) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        1	Sin Definir
        2	Proyecto Interno
        3	Proyecto Externo

    - Agregado de campo tipo a tabla proyecto   INT NOT NULL DEFAULT 1

    - Agregado de campos reunion y tiempo en avance
        ALTER TABLE controls.avance
            ADD reunion INT AFTER borrado, //NOT NULL DEFAULT 1
            ADD tiempo INT;
- Cambios en src        
    M[edit_proyecto.php]
    M[proyecto.php]                                                        
