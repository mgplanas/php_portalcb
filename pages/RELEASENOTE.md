# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0021

### Features

- FEAT-ADMIN-COMP


### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

## FERIADOS
CREATE TABLE controls.adm_dnl (
   id INT AUTO_INCREMENT NOT NULL,
   fecha DATE NOT NULL,
   descripcion VARCHAR(255),
   borrado INT NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE = InnoDB ROW_FORMAT = DEFAULT;


- Cambios en src
    N[.php]
    M[pages/.php]
