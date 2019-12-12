# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0021

### Features

- FEAT-INVENTARIO


### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

CREATE TABLE controls.cdc_inv_servidores (
   id INT AUTO_INCREMENT NOT NULL,
   marca VARCHAR(255),
   modelo VARCHAR(255),
   serie VARCHAR(50),
   memoria INT,
   sockets INT,
   nucleos INT,
   ubicacion_sala VARCHAR(20),
   ubicacion_fila INT,
   ubicacion_rack INT,
   ubicacion_unidad INT,
   IP VARCHAR(16),
   vcenter VARCHAR(50),
   cluster VARCHAR(50),
   hostname VARCHAR(50),
   cliente VARCHAR(255),
   borrado INT DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE = InnoDB ROW_FORMAT = DEFAULT;

- Cambios en src
    N[site.php]
