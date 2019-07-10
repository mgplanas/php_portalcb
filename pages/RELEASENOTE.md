## RELEASE NOTE FOR REL 0006
### Features
FEAT-ISO271K


### Pasos
- Entorno
    N/A
- BackUp DB                                                                     
- Backup /pages                                                                 
- Cambios en DB                                                                 
    - Creación tabla relaciones items-iso -> referentes
        CREATE TABLE `iso27k_refs` (
        `id_item_iso27k` int(11) NOT NULL,
        `id_persona` int(11) NOT NULL,
        `borrado` int(11) NOT NULL DEFAULT '0'
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


    - Agregado de campo nivel (titulo, subtitulo, item) y parent a tabla item_iso27k1
        ALTER TABLE controls.item_iso27k
        ADD nivel INT NOT NULL DEFAULT '3' AFTER usuario,
        ADD parent INT;

- Cambios en src        
    M[iso27k.php]
    M[edit_iso27k.php]
