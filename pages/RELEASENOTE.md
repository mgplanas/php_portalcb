# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL200603

### Features

- FEAT-CDC
### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB
[DB] se crea vw_sdc_hosting
CREATE VIEW vw_sdc_hosting AS
  SELECT H.id, H.id_cliente, H.tipo, H.nombre, H.displayName, H.proyecto, H.datacenter, DATE_FORMAT(H.fecha, "%Y-%m-%d") as fecha, H.hipervisor, H.hostname, H.pool, H.uuid, H.VCPU, H.RAM, H.storage, H.SO , C.razon_social as cliente, O.razon_social as organismo, C.sector 
    FROM sdc_hosting as H 
    INNER JOIN cdc_cliente as C ON H.id_cliente = C.id 
    LEFT JOIN cdc_organismo as O ON C.id_organismo = O.id 
    WHERE H.borrado = 0 
    AND H.pool NOT IN ("VMW_VRA_CLIENTES_SALA_1","VMW_HPC") 
    AND H.pool NOT LIKE ("%_DNITO_%");

[DB] se crea vw_sdc_correo
CREATE VIEW vw_sdc_correo AS
  SELECT H.id, H.id_cliente, H.tipo, H.nombre, H.displayName, H.proyecto, H.datacenter, DATE_FORMAT(H.fecha, "%Y-%m-%d") as fecha, H.hipervisor, H.hostname, H.pool, H.uuid, H.VCPU, H.RAM, H.storage, H.SO , C.razon_social as cliente, O.razon_social as organismo, C.sector 
    FROM sdc_hosting as H 
    INNER JOIN cdc_cliente as C ON H.id_cliente = C.id 
    LEFT JOIN cdc_organismo as O ON C.id_organismo = O.id 
    WHERE H.borrado = 0 
    AND H.pool LIKE ("%_DNITO_%");

[DB] Se crea vw_sdc_iaas
CREATE VIEW vw_sdc_iaas AS
  SELECT H.id, H.id_cliente, H.tipo, H.nombre, H.displayName, H.proyecto, H.datacenter, DATE_FORMAT(H.fecha, "%Y-%m-%d") as fecha, H.hipervisor, H.hostname, H.pool, H.uuid, H.VCPU, H.RAM, H.storage, H.SO , C.razon_social as cliente, O.razon_social as organismo, C.sector 
    FROM sdc_hosting as H 
    INNER JOIN cdc_cliente as C ON H.id_cliente = C.id 
    LEFT JOIN cdc_organismo as O ON C.id_organismo = O.id 
    WHERE H.borrado = 0 
    AND H.pool IN ("VMW_VRA_CLIENTES_SALA_1","VMW_HPC");

[DB] se agregan campos en cdc_cliente
ALTER TABLE controls.cdc_cliente
 ADD con_servicio_correo INT NOT NULL DEFAULT '0' AFTER con_convenio,
 ADD ejecutivo_cuenta INT;

- Cambios en src

- CHANGES.md                              |  88 +++++++++++
- pages/cdc_cliente.php                   |  31 +++-
- pages/helpers/cdc_abmclientedb.php      |   8 +-
- pages/modals/cdc_abmcliente.js          |  15 +-
- pages/modals/cdc_abmcliente.php         |  19 ++-
- pages/modals/cdc_clientes_baja_view.js  |  39 +++++
- pages/modals/cdc_clientes_baja_view.php |  46 ++++++
- pages/modals/sdc_correo_view.js         | 129 ++++++++++++++++
- pages/modals/sdc_correo_view.php        | 103 +++++++++++++
- pages/modals/sdc_hosting_view.js        |   5 +-
- pages/modals/sdc_iaas_vms_view.js       | 127 +++++++++++++++
- pages/modals/sdc_iaas_vms_view.php      |  93 +++++++++++
- pages/sdc_correo.php                    | 265 ++++++++++++++++++++++++++++++++
- pages/sdc_hosting.php                   |   7 +-
- pages/sdc_iaas.php                      |  13 +-
- pages/site_sidebar.php                  |   1 +
- site.php                                |   1 +