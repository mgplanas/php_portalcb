# CHANGES

## FEAT-RIESGOS
Cambios solicitados por R.Lopez
*Fecha* 2020-11-24
*Requerimiento*
- Visualizar la genrencia en vez de la subgerencia en la edición / alta de riesgos
- Metricas riesgos abiertos y cerrados por proceso

[cod]
- pages/edit_riesgo.php
- pages/riesgos.php
- pages/met_riesgos.php

## FEAT-CON-CRITICIDAD
Agregar campo de criticidad en el seguimiento de contratos
*Fecha* 2020-11-17
*Requerimiento*
Agregar campo de criticidad en el seguimiento de contratos

[DB]
- Se crea la tabla:
    - adm_criticidad
        - Baja|Media|Alta
- Se agrega el campo criticidad a la tabla de contratos
    - ALTER TABLE controls.adm_contratos_vto ADD criticidad INT NOT NULL DEFAULT '1' AFTER oc;

[cod]
- Se agrega a la lista la criticidad
    - [Mod] pages/adm_contratos.php
- Se agrega campo en el ABM
    - [Mod] pages/modals/adm_contratos.php
    - [Mod] pages/modals/adm_contratos.js
    - [Mod] pages/helpers/adm_contratosdb.php
## FEAT-DOCS
Volvar la planilla de Biblioteca de documentos del DC en el portal
*Fecha* 2020-10-20
*Requerimiento*
Poder seguir los documentos del DC en el portal
Poder actualizar como revisión y aprovación.
Cáculo de fechas de vto y avisos
[DB]
- Se crean las tablas:
    - doc_documentos
    - doc_tipos
    - doc_formas_com
    - doc_areas 
    - doc_periodicidad

[cod]
- [new] pages/doc_documentos.php
- [new] modals/abmdoc.php
- [new] modals/abmdoc.js
- [new] modals/abmdoc_aprobar.php
- [new] helpers/abmdocdb.php

 pages/doc_documentos.php        | 458 ++++++++++++++++++++++++++++++++++++++++
 pages/helpers/abmdocdb.php      |  86 ++++++++
 pages/modals/abmdoc.js          | 249 ++++++++++++++++++++++
 pages/modals/abmdoc.php         | 142 +++++++++++++
 pages/modals/abmdoc_aprobar.php |  49 +++++
 pages/setPermiso.php     |  8 ++++++++
 pages/site_sidebar.php   |  3 +++
 site.php                 |  3 +++

## FEAT-CDC
Requerimientos de cambios y desarrollos por esteban tissera
*Fecha* 2020-09-28
*Requerimiento*
OK-	Módulo Hosting: 
    -   Filtrar las VMs de los siguientes clusters ya que pertenecen a los servicios de IAAS:
        -   VMW_VRA_CLIENTES_SALA_1
        -   VMW_HPC
    -   Filtrar las VMs de los siguientes clusters ya que pertenecen a los servicios de correo:
        -   XXX_DNITO_XX
    -   Quitar Campo “DataCenter” de la visualización de la grilla de VMs.
    -   Agregar sigla “GB” a los campos “RAM” y “Storage”
    -   Renombrar campo “Hipervisor” por “Plataforma”
-OK   Módulo IAAS: Agregar visualización de VMs que han sido creadas para dichas reservas
-OK   Nuevo módulo de Servicios de Correo correspondiente a los clusters DNITO. La información se obtiene de las vms de Hosting correspondientes a los clusters DNITO
-OK   Módulo Clientes DC: 
    - Filtrar cantidades servicios de Hosting teniendo en cuenta filtros del punto 1
    - Filtrar Vms enn visualización específica (modal)
    - Visualizar los registros dados de Baja (sólo visualización)  sólo para los que posean un permiso específico para tal fin.
    - Agregar posibilidad de asociar un ejecutivo de cuenta a un cliente
    - Agregar marca de tenencia de Servicio de correo
    -   Visualizar VMs de Servicio de correo al hacer click en la marca

*Cambios*
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

- pages/scd_hosting.php
    -   Filtrar las VMs de los siguientes clusters ya que pertenecen a los servicios de IAAS:
        -   VMW_VRA_CLIENTES_SALA_1
        -   VMW_HPC
    -   Filtrar las VMs de los siguientes clusters ya que pertenecen a los servicios de correo:
        -   XXX_DNITO_XX
    -   Quitar Campo “DataCenter” de la visualización de la grilla de VMs.
    -   Agregar sigla “GB” a los campos “RAM” y “Storage”
    -   Renombrar campo “Hipervisor” por “Plataforma”
- pages/cdc_cliente.php
    - Filtrar cantidades servicios de Hosting teniendo en cuenta filtros del punto 1
    - Visualizar los registros dados de Baja (sólo visualización)  sólo para los que posean un permiso
        - Se agrega boton para ver bajas   
    - Visualizar marca de servicio de correo
    - Visualizar ejecutivos de cuenta 
- pages/modals/cdc_abmcliente.js/php    
- pages/helpers/cdc_abmclientedb.php    
    - Update Marca de Servicio de Correo
    - Ejecutivo de cuenta
- pages/modals/sdc_hosting_view.js    
    - Filtrar Vms enn visualización específica (modal)
- [NUEVO] pages/modals/cdc_clientes_baja_view.php / js
    -   Visualizar los registros dados de Baja (sólo visualización)  sólo para los que posean un permiso
- [NUEVO] pages/modals/sdc_correo_view.js/php
    - Visualizar VM de correo
- [NUEVO] pages/sdc_correo.php
- pages/site_sidemenu.php
- site.php
- pages/sdc_iass.php se agrega columna de cuenta de VM 
- [NUEVO] pages/modals/sdc_iaas_vms_view.js/php
    - Visualizar VM de reserva de iaas
## FEAT-RGO-PROC
Se cambia el label y se verifica la existencia de riesgos asociados antes de borrar un proceso
*Fecha* 2020-09-14
*Cambios*
- edit_riesgos.php
- riesgos.php
- procesos.php


## FIX-COSTOS
Se corrije la exportación a Excel los formatos de números ya que solicitron la visualización de formatos custom en las grillas.
*Fecha* 2020-09-04
*Cambios*
- cdc_costos.php
- helpers/cdc_abmcostos.js
- site.php
- site_sidebar.php
- cdc_abmcostos.php cambio CM por DDL 20-50

## FEAT-DC-CONTRATOS

ABM seguimiento de contratos
*Fecha:* 2020-08-07
*Cambios:*

- [DB] Se crea la tabla adm_contratos_vto
- nuevo permiso para adm
    - [DB] Se crea el campo adm_contratos en permisos
    ALTER TABLE controls.permisos
    ADD admin_contratos INT AFTER admin_riesgos;

- site.php
    - pages/admin.php
    - pages/setPermiso.php
- pages/site_sidebar.php
    - pages/adm_contratos.php

    - pages/modals/abmcompras.js
    - pages/modals/adm_contratos.php
    - pages/modals/adm_contratos.js
    - pages/modals/adm_contratos_oc.php

    - pages/helpers/adm_contratosdb.php

## FEAT-IAAS

Nuevo servicio DC IAAS
*Fecha:* 2020-07-27
*Cambios:*

* Nuevo campo Estado en clientes
- [DB] Se crea el campo con_convenio int = 0 en cdc_cliente

- Se visualiza con marca en la grilla
- Se actualiza abm y modal
- [DB] Nuevo campo modalidad en sdc_housing int = 0
- [DB] Nueva tabla sdc_housing_modalidad
- Se actualiza grilla sdc_housing y abms helper y modal
- [DB] Nueva tabla sdc_iaas
- CRUD iaas
    - pages/sdc_iaas.php
    - pages/modals/sdc_abmiaas.php
    - pages/modals/sdc_abmiaas.js
    - pages/helpers/sdc_abmiaasdb.php
    - pages/site_sidemenu.php
    - site.php
- suma de servicios en clientes y ink
    - pages/cdc_clientes
    - pages/modals/sdc_iaas_view.php
    - pages/modals/sdc_iaas_view.js

## FEAT-RGO-PROCESOS
ABM procesos y aplicar a riesgos
*Fecha:* 2020-08-11
*Cambios:*
    
    * [DB] se crea la tabla de procesos
    * pages/procesos.php
    * pages/modal/abmprocesos.php
    * pages/modal/abmprocesos.js
    * pages/helpers/abmprocesosdb.php
    * pages/site_sidemenu.php
    * site.php
    * [DB] se agrega campo proceso a riesgos 
    * `ALTER TABLE controls.riesgo ADD proceso INT AFTER justificacion_cierre;`
    * Se agrega a la lista de riesgos
        * pages/riesgos.php
    * Se agrega en el alta y consulta
        * pages/riesgos.php
    * Se cambia gerencia por subgerencia en grilla , alta y edición
        * pages/riesgos.php
        * pages/edit_riesgo.php

## FEAT-RGO-SORT


##FEAT-RGO-SORT

FIX en ordenamiento de fechas
*Fecha:* 2020-07-28
*Cambios:*

- riegos.php se corrije el formato de sort
## REL200603-2

### SEGUNDA parte de COSTO

NO IMPLEMENTAR SIN ANTES HABER IMPLEMENTADO LA PRIMERA REL200603

*Fecha:* 2020-08-05

*Cambios* :

* Separación de la gestion de categorías aparte.
  * pages/cdc_costos_adm_items.php
  * helpers/cdc_abmcostos_items.js
  * site.php
  * pages/site_sidebar.php
* Elimino la gestión de categorías/sub categorías en planilla de costeo
  * helpers/cdc_abmcostos.js
  * pages/cdc_abmcostos.php
* Cambios menores solicitados por Esteban en la reunión con comercial
  * Agregar número Ceres en pantalla de cotización
    * [DB] Se agrega campo oportunidad_comercial en cdc_costos
    * pages/cdc_abmcostos.php
    * helpers/cdc_abmcostosdb.php
  * Agregar SS de máximo
    * [DB] Se agrega campo solicitud_servicio en cdc_costos
    * `ALTER TABLE controls.cdc_costos ADD oportunidad_comercial VARCHAR(20) AFTER estado, ADD solicitud_servicio VARCHAR(20);`
    * pages/cdc_abmcostos.php
    * helpers/cdc_abmcostosdb.php
  * Formatear los montos con miles
    * pages/cdc_costos.php
    * pages/cdc_abmcostos.php
    * helpers/cdc_abmcostos.js
  * Deshabilitar la cotización en USD
    * pages/cdc_costos.php
    * pages/cdc_abmcostos.php

    
## FEAT-CMP-CANCEL

Nuevo paso Cancelado con indicador
*Fecha:* 2020-07-28
*Cambios:*

- [DB] Agrega nuevo registro en tabla de adm_com_pasos
- Se previene de que ese paso calcule advertencia de promedio
- Se crea indicador de reloj [compras.php]


## FEAT-COSTEO

Gestion de planillas de costos
*Fecha:* 2020-05-21
*Cambios:*

- [DB] Se crea la tabla de items de costos cdc_costos_items
- [DB] Se crea la tabla de items de costos cdc_costos
- [DB] Se crea la tabla de items de costos cdc_costos_detalle
- CHANGES.md                            |  16 +
- pages/cdc_abmcostos.php               | 450 +++++++++++++++++++++++
- pages/cdc_costos.php                  | 314 ++++++++++++++++
- pages/helpers/cdc_abmcostos.js        | 674 ++++++++++++++++++++++++++++++++++
- pages/helpers/cdc_abmcostosdb.php     |  77 ++++
- pages/helpers/cdc_abmcostosdetdb.php  |  51 +++
- pages/helpers/cdc_abmcostositemdb.php |  45 +++
- pages/mejoras.php                     |   4 +-
- pages/modals/cdc_abmcostosdet.php     |  71 ++++
- pages/modals/cdc_abmcostositem.php    |  57 +++
- pages/site_sidebar.php                |   1 +
- site.php                              |   1 +

# FEAT-SI

Gestión y seguiiento de Solicitudes de Infraestructura.
*Fecha:* 2020-05-14
*Cambios:*

- [pages/site_sidebar] Se agrega el item solicitudes infra en site_sidebar y site
- [site] Se agrega el item solicitudes infra en site_sidebar y site
- N[pages/cdc_solicitudes]
- N[pages/modals/cdc_abmsolicitudes.php/js]
- N[pages/helpers/cdc_abmsolicitudesdb.php]

# FIX-VARIOS

FIX Login LDAP server
Muestro columna evidencia en 27001 en xls
Muestro columna evidencia en 9001 en xls

## FEAT-MC-AUDITORES

Gestión de entes de auditoría e instancias de las mismas
*Fecha:* 2020-04-13
*Cambios:*

- [DB] Se crea la tabla aud_entes
- [DB] Se crea la tabla aud_auditores
- [DB] Se crea la tabla aud_instancias
- [DB] Se crea la tabla aud_rel_ins_aud
- [DB] Se agregan campos de ente y instancia en mejoras
  ALTER TABLE controls.mejora
  ADD aud_ente INT AFTER matriz,
  ADD aud_instancia INT;
- N[pages/aud_ente] listado de entes de auditoria
- N[modals/aud_abmente(js/php)] Modal de entes de auditoria
- N[helpers/aud_abmentedb] ABM DB de entes de auditoria
- M[site] Agregado de menu de entes
- M[pages/site_sidebar] Agregado de menu de entes
- N[pages/aud_auditores] listado de auditores de un ente
- N[modals/aud_abmauditores(js/php)] Modal de auditores
- N[helpers/aud_abmauditoresdb] ABM DB de auditores
- N[pages/aud_instancias] listado de instancias de auditorias
- N[modals/aud_abminstancias(js/php)] Modal de intancias
- N[helpers/aud_abminstanciasdb] ABM DB de instancias
- N[pages/aud_insauditores] listado de auditores asignados a la instancia de auditoría
- N[helpers/aud_abminsauditoresdb] ABM DB de asignación de auditores
- M[pages/mejoras] Quito el campo corrección del modal alta y del modal de view
- M[pages/mejoras] Agrego filtro campo origen
- M[pages/edit_mejoras] Quito el campo corrección
- M[pages/mejoras] Agrego campo Prioridad
- M[pages/edit_mejoras] Agrego campo Prioridad
- [DB] Se agrega el campo prioridad
  ALTER TABLE controls.mejora
  ADD prioridad INT NOT NULL DEFAULT '0' AFTER aud_instancia;

## FEAT-RIESGOS-VARIOS

Cambios de permisos en riesgos y varios de Mejora Contínua.

- Permiso de riesgo solo van a editar los que estén en este grupo [riesgos_adm]

ALTER TABLE controls.permisos
ADD admin_riesgos INT(11) AFTER admin_compras;

M[setPermiso.php]
M[admin.php]

- Agregar columna de usuario en el avance.
  M[edit_riesgo.php]
- Solo se puede cerrar el riesgo si estás en el grupo del punto 1.
- Agregar campo matriz en mejora continua

CREATE TABLE `mc_matriz` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`nombre` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

(mejora) ADD field matriz INT NULL

1 ISO27001
2 ISO9001
3 BCRA

M[mejora.php]
M[edit_mejora.php]

- Cambio titulos columnas Hosting
  M[sdc_hosting]
- Cambios en servidores
  ALTER TABLE controls.cdc_inv_servidores
  ADD infra VARCHAR(50) AFTER ubicacion_unidad,
  ADD orquestador VARCHAR(20) AFTER vcenter,
  ADD eos DATE AFTER cliente,
  ADD eol DATE;

M[cdc_servidores]
M[modals\abmservidores.php]
M[modals\abmservidores.js]
M[helpers\abmservidoresdb.php]

## FEAT-PROY-REPROG

Reprogramación de proyectos mediante avance.
*Cambios:*

- Se agrega la categoría de Reprogramación
  [pages/edit_proyectos.php]
- Se agega campo fecha en el avance y se muestra cuando se selecciona solo la categoría Reprogramacion
  [pages/edit_proyectos.php]

ALTER TABLE controls.proyecto
ADD repro_date VARCHAR(10) AFTER porcentaje_estimado;

- Se habilita la modificacion del titulo
- Visualizo fecha de reprogramación en grilla proyectos
  [pages/proyectos]
- Se pone los botones export en scs_hosting/housing
  [pages/sds_hosting/Housing]

## FIX-CONTROLES

Con el cambio de año comenzaron a aparecer errores referidos a fechas.
*Cambios:*

- Se saca el filtro de fechas para el cálculo de cantidad de controles pendientes.
  [pages/controles.php]
- Generar referencias de controles según la periodicidad y el último mes generado para el año actual
  [helpers/gen_controles.php]
- Modificar vista de referencias. Incluir Año y ordenarlos por fecha decremental
  [pages/controles.php]
- Arreglar gráfico de indicadores de cumplimiento. Poner al año anterior vs año actual
  [pages/controles.php]
- Arreglar grafico s inicio y metricas y filtrar por año actual al mes actual.
  [pages/met_controles.php]
  [pages/site.php]
- Arreglar calendario de controles
  [pages/cal_controles.php]

## FEAT-INVENTARIO

Inventario de servidores DC
*Cambios:*

- Agrego item menú en cdc
  [pages/site_sidebar.php]
  [pages/cdc_servidores.php]
  [pages/modals/cdc_abmservers.php]
  [pages/modals/cdc_abmservers.js]
  [pages/helpers/cdc_abmservidoresdb.js]
  [site.php]
- ABM de servicores DC. Se reutilizan los permisos de cdc y admin cdc

## FEAT-COM-VARIOS

*Cambios:*

- Se agregan Estado "STAND BY"
  [DB] Se agrega estado en tabla adm_com_pasos el estado STAND BY (6)
- Se crea otro indicador.
  [pages/compras.php]
- Borrar compras
  [pages/compras.php]
  [pages/modals/abmcompras.js]

## FEAT-BCRA

Se implementa la matriz BCRA (Compliance)
*Fecha:* 2019-11-22
*Cambios:*

- [DB] Se crean las tablas de referentes items y version
  M[site.php]
  M[pages/bcra.php]
  M[pages/site_sidebar.php]
  M[pages/modals/abmbcra.php]
  M[pages/modals/abmbcra.js]
  M[pages/helpers/abmbcradb.php]

## FEAT-RGO-METRICA

Se agregan dos cuadros en las métricas de riesgo donde figuran los Riesgos Abiertos y cerrados por gerencia pero vigentes (del año)
En cuento a los cerrados son aquellos cerrados que se han abierto en el mismo año también:
"_ Incluir dos gráficos similares a los existentes (adjunto) pero que muestres los Riesgos Abiertos y Cerrados en el año vigente (Ejemplo Abiertos en 2019 y Cerrados en 2019 (solo de los que se abrieron en ese mismo año)"
*Fecha:* 2019-11-20
*Cambios:*

- Se agregan las métricas de riesgos
  M[pages/met_riesgos.php]
- Se agregan las métricas de compras y se actualiza el menú lateral
  M[pages/met_compras.php]
  M[pages/site_sidebar.php]
  N[site.php]
- Riesgos Muesto v_actual en vez de inicial
  M[pages/riesgos.php]

## FEAT-COMPRAS

Se agrega el módulo de administración y seguimiento de compras
*Fecha:* 2019-11-05
*Cambios:*

- Se agrega el perfil compras y admin_compras para el acceso y administración del módulo de compras.
  M [pages/admin.php]
  M [pages/setPermiso.php]
- Se agrega el menú lateral
  M [site.php]
  M [pages/site_sidebar.php]
  N [pages/compras.php]
- ABM Compras modal
  N [pages/modals/abmcompra.php]
  N [pages/modals/abmcompra.js]
  N [pages/helpers/abmcompradb.php]
- ABM Comentrios
  N [pages/compras.php]
  N [pages/helpers/abmcompracomentariodb.php]
- Indicadores
  N [pages/compras.php]
- Adjudicadas
  N [pages/compras.php]
- Plazo en indicadores
  N [pages/modals/abmcompra.php]
  db: fecha_fin_contrato
- Alta proveedores
  N [pages/helpers/abmroveedordb.php]
- Quito paso siguiente
- Calculo promedio

## FIX RIESGOS FECHA VENCIMEINTO

- Corrijo el cálculo y la cantidad de días
  M [pages/riesgos.php]

- Corrijo el cálculo y la cantidad de días
M [pages/riesgos.php]
## FIX-VARIAS

*Fecha:* 2019-11-01
*Cambios:*

- Sacar el botón de borrar en mejora
  M[pages/mejoras.php]
- Cambiar la numeración secuencial por el id en controles
  M [pages/controles.php]
- En la edicion de activos el combo de tipos no muestra todos los registros. Sí lo hace en el alta
  M [pages/edit_activos.php]
- Riegos: Sacar los campos en la grilla de Categoría, Riesgo y Acción e Incluir Referente y Fecha Alta
  M [pages/riesgos.php]

## FEAT-PROY-MEJORAS

### Cambios a proyectos solicitados por DC.

*Fecha:* 2019-11-04
*Cambios:*

- Agregar estado Cancelado
- Se agregan las más categorias en el avance. Se reutiliza el campo "reunion"

0) Avance
1) Reunión
2) Riesgo
3) Problema
   M [pages/edit_proyecto]

- Cambio campo Porcentaje de Avance a "porcentaje de avance real"
- Agrego campo Porcentaje de avance estimado
- Quito columna "avance"
  M [pages/edit_proyecto]
  M [pages/proyectos]

## FIX-HOUSING

### Se corrige orden de columnas servicios housing.

*Fecha:* 2019-10-04
*Cambios:*

- Se corrige orden de columnas servicios housing. Organismos->cliente
  M[pages/sdc_housing.php]

## FEAT-CALENDAR

### Adaptación caliendario de guardias para nuevas gerencias/areas

*Fecha:* 2019-05-20
*Cambios:*

- Corrigo seleccion personas filtro borrado
  - M[pages/calendario.php]
- Agrego ABM de estructura de gerencia, subgerencia y área a la DB y personas
  - M[pages/admin.php]
  - N[helpers/*.php]
  - N[modals/*.php]
- Agrego los campos id_subgerencia, id_area a tabla de Persona. Modifico ABM
- TODO BAJA Estructura (Ver si posee personas asociadas)
  - Poner cantidad de subelementos en la grilla
  - Poner boton de baja en grillas
  - Mostar aviso de resultado de operación al cerrar el modal
  - Ver tema de auditoría

## FEAT-PROY-AIND

### Vista global de indicadores para proyectos.

*Fecha:* 2019-10-03
*Cambios:*

- Un Combo para filtar los indicadores por gerencia si es admin.
  M[pages/proyectos.php]
- Paso la generacion de gráficos a dinámito ajax.
  M[pages/proyectos.php]
- Actualizo las consultas de los gráficos de asignacion y estado de proyectos para que tomen todas las gerencias (=0).
  M[pages/getProyResp.php]
  M[pages/getProyRespStat.php]

## FEAT-CAL-RIESGOS

### Nuevas funcionalidades en el calendario de Riesgos.

*Fecha:* 2019-09-06
*Cambios:*

- Se agrega un nuevo calendario de riesgos para representar los cerrados
  M[pages/cal_riegos.php]
- Se agregan las columnas de acumulado previo y posterior en el calendario de riesgos
  M[pages/cal_riegos.php]
- Se agregan las columnas de acumulado previo y posterior en el calendario de riesgos cerrados
  M[pages/cal_riegos.php]
- Se corrige el calendario de acumulado de controles
  M[pages/cal_controles.php]
- Cuando se cierra el riesgo mediante un avance planchar la fecha de vencimiento del riesgo
  M[pages/edit_riesgo.php]

## FEAT-PROY-AVIEW

Vista para ver todos los proyectos
*Fecha:* 2019-10-02
*Cambios:*

- Se agrega la columna gerencia en el tab de proyectos si es Rol[admin]
  M[proyetos.php]

## FEAT-RAUL

Dashboad CLientes
*Fecha:* 2019-09-28
*Cambios:*

- Pasar el módulo de activos dentro de los permisos de compliance
  M[pages/site_sidebar.php]
  M[site.php]
- Cambio SI por GITyS en header, footer y login
- Header -incluyo el logo en el header
  M[pages/site_header.php]
- cambio en todas las páginas eso y tag de TITLE GITyS-ARSAT[$page_tile] 
  $page_title="Activos";

<title>GITyS-ARSAT[<?=$page_title?>]</title>
extraigo footer a site_footer.php:
<?php include_once('./site_footer.php'); ?>
N[site_footer.php]          OK
M[activos.php]              OK
M[admin.php]                OK
M[calendario.php]           OK
M[calendario_guardias.php]  OK
M[cal_controles.php]        OK
M[cal_riesgos.php]          OK
M[cdc_cliente.php]          OK
M[cdc_organismo.php]        OK
M[clean_content.php]        OK
M[control.php]              OK
M[controles.php]            OK
M[controlfw.php]            OK
M[edit_activo.php]          OK
M[edit_conexion.php]        OK
M[edit_control.php]         OK
M[edit_dispositivo.php]     OK
M[edit_iso27k.php]          OK
M[edit_mejora.php]          OK
M[edit_persona.php]         OK
M[edit_proyecto.php]        OK
M[edit_referencia.php]      OK
M[edit_riesgo.php]          OK
M[inventario.php]           OK
M[iso27k.php]               OK
M[iso9k.php]                OK
M[mejoras.php]              OK
M[metricas.php]             OK
M[met_activos.php]          OK
M[met_controles.php]        OK
M[met_iso27k.php]           OK
M[met_mejoras.php]          OK
M[met_riesgos.php]          OK
M[novedades.php]            OK
M[proyectos.php]            OK
M[riesgos.php]              OK
M[sdc_hosting.php]          OK
M[sdc_housing.php]          OK
M[tareas.php]               OK
M[topologia.php]            OK
M[site.php]                 OK
M[index.html]               OK
- Agrego página de dashboard en CLI-DC
M[site.php]
M[pages/site_sidebar.php]
M[pages/cdc_dashboard.php]
- Agrego DB de totales: servicios, cpu, ram, storage, VMs, Clientes
M[pages/cdc_dashboard.php]
- Normalizo DB sdc_Housing
# SCRIPT DE NORMALIZACION
# A) ENERGIA
# 1) Modificar el registro de Educar (cliente 14) y sacar el, 6PDU
UPDATE sdc_housing SET energia = "12" WHERE id = 9;

#2) Actualizo los NULL a 0
UPDATE sdc_housing SET energia = 0 WHERE energia IS NULL OR energia = "";

#3) Saco la palabra KVA
UPDATE sdc_housing SET energia = REPLACE(energia, "KVA", "");

#4) Cambio el tipo de dato a INT DEFAULT 0

#B) M2
#1) Actualizo los NULL a 0
UPDATE sdc_housing SET m2 = 0 WHERE m2 IS NULL OR m2 = "";

#3) Convierto coma a punto
UPDATE sdc_housing SET m2 = REPLACE(m2, ",", ".");

#) Convierto el campo en DECIMAL(6,2)
#SELECT id_cliente, CAST(m2 AS DECIMAL(6,2)) as m2 FROM sdc_housing
ALTER TABLE controls.sdc_housing
CHANGE m2 m2 DECIMAL(8,2) DEFAULT '0';

- Se crean los totales para los servicios de Housing
  M[pages/cdc_dashboard]
- Se adaptan los formularios de ABM para adaptarse a los nuevos cambios.
  M[pages/sdc_housing.php]
  M[pages/modals/sdc_abmhousing.php]
- Aplico permisos de "compliance" a los tableros del inicio.
  M[site.php]

## FEAT-CLI-DC2

Correcciones y mejoras POST Producción
*Fecha:* 2019-09-27
*Cambios:*

- Agregar permiso para permitir el borrado
  ALTER TABLE controls.permisos
  ADD admin_cli_dc INT DEFAULT '0' AFTER cli_dc;
- Modificar grilla de permisos y asignación
  M[pages/admin.php]
  M[pages/setPermiso.php]
- Limitar la edicion solo para admin_cli_dc
  M[pages/cdc_cliente.php]
  M[pages/cdc_organismo.php]
  M[pages/sdc_housing.php]
- Agregar opcion de borrado en todas las grillas (menos hosting)
  M[pages/sdc_housing.php]
  M[pages/cdc_cliente.php]
  M[pages/cdc_organismo.php]
- Agregar el refresco de la página al actualizar info en todas las paginas
  M[pages/modals/sdc_ambhousing.js]
  M[pages/modals/sdc_ambcliente.js]
  M[pages/modals/sdc_amborgnaismo.js]
- al importar borrar todo antes
  M[pages/helpers/sdc_importhosting.php]
  M[pages/modals/sdc_importhosting.js]

  DUPLICAR ESTRUCTURA DE TABLA HOSTING A SDC_HOSTING_BCK SIN CONSTRAINS
  CREATE TABLE `sdc_hosting_bck` (
  `id` int(11),
  `id_cliente` int(11) NOT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `displayName` varchar(255) DEFAULT NULL,
  `proyecto` varchar(255) DEFAULT NULL,
  `datacenter` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `hipervisor` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `pool` varchar(255) DEFAULT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `VCPU` double DEFAULT NULL,
  `RAM` double DEFAULT NULL,
  `storage` double DEFAULT NULL,
  `SO` varchar(255) DEFAULT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
- Corregir tema de desaparicion de menu en firefox
  Problema específico en máquina de Tissera
- Se corrige el cáculo de fechas de vto en proyectos
  M[pages/proyectos.php]
  -Arreglo links de gestion de proyectos
  M[pages/calendario_guardias.php]
  M[pages/controlfw.php]
  M[pages/edit_activo.php]
  M[pages/edit_conexion.php]
  M[pages/edit_control.php]
  M[pages/edit_dispositivo.php]
  M[pages/edit_iso27k.php]
  M[pages/edit_mejora.php]
  M[pages/edit_persona.php]
  M[pages/edit_proyecto.php]
  M[pages/edit_referencia.php]
  M[pages/edit_riesgo.php]

## FIX-CLI-DC

Correcciones POST Producción
*Fecha:* 2019-09-27
*Cambios:*
<<<<<<< HEAD
<<<<<<< HEAD

- Se corrige el menú lateral del inicio (Se saca la opción de dashboard en modulo de clientes)
  M[site.php]
- Se agrega un script al final de cada una de las páginas que hace que se mantenga en menú del side bar correspondiente abierto
  -[sdc_hosting/housing]
  -[cdc_cliente/organismo]
- housing refrescar la pagina al guardar o modiciar
  M[modals/sdc_abmhousing.js]

 devel

=======
=======
>>>>>>> FEAT-IAAS

- Se corrige el menú lateral del inicio (Se saca la opción de dashboard en modulo de clientes)
  M[site.php]
- Se agrega un script al final de cada una de las páginas que hace que se mantenga en menú del side bar correspondiente abierto
  -[sdc_hosting/housing]
  -[cdc_cliente/organismo]
- housing refrescar la pagina al guardar o modiciar
  M[modals/sdc_abmhousing.js]

>>>>>>> devel
>>>>>>>
<<<<<<< HEAD
>>>>>>> FEAT-CMP-CANCEL
=======
>>>>>>> FEAT-IAAS
>>>>>>
>>>>>
>>>>
>>>
>>

## FEAT-SIDEBAR

Modificaciones post produccion
*Fecha:* 2019-09-26
*Cambios:*

- Se agrupan los items del menu de controles, riesgos, isos y mejoras dentro del permiso compliance
  M[pages/site_sidebar.php]
  M[site.php]

## FEAT-CDC-HOUSING

Modificaciones post produccion
*Fecha:* 2019-09-26
*Cambios:*

- Se cambia la visualización de housing por la cantidad de servicios que posea el cleinte
  M[pages/cdc_cliente.php]
- Se cambia el popup para visualizar grilla con la cantidad de servicios de housing.
  M[pages/modals/sdc_housing_view.js]
  M[pages/modals/sdc_housing_view.php]

## FEAT-CLI-DC

### MIGRACION CLIENTES DC.

Gererar portal para consulta de clientes/servicios dc basados en una base access
*Fecha:* 2019-05-28
*Cambios:*

- Se crea la estructura de menu lateral
  M[pages/site_sidebar.php]
  M[site.php]
  N[pages/cdc_dashboard.php]
  N[pages/cdc_cliente.php]
  N[pages/cdc_organismo.php]
  N[pages/sdc_housing.php]
  N[pages/sdc_hosting.php]
- Se crean los modales para vicualización de servicios de housing/hosting
  N[pages/modals/*.js]
  N[pages/modals/*.php]
  N[pages/helpers/*.php]
- AM Clientes
  N[pages/modals/cdc_abmcliente.php]
  N[pages/modals/cdc_abmcliente.js]
  N[pages/helpers/cdc_abmclientedb.php]
- AM Organismos
  N[pages/modals/cdc_abmorganismo.php]
  N[pages/modals/cdc_abmorganismo.js]
  N[pages/helpers/cdc_abmorganismodb.php]
- AM Servicio de Housing
  N[pages/modals/sdc_abmhousing.php]
  N[pages/modals/sdc_abmhousing.js]
  N[pages/helpers/sdc_abmhousingdb.php]
  M[pages/sdc_housing.php]
- Importación masiva de Servicios de Hosting via csv
  M[pages/sdc_hosting.php]
  bd: sdc_hosting_temp
- Se crean los campos energía, evidencia y fecha_alta para housing
  N[pages/modals/sdc_abmhousing.php]
  N[pages/modals/sdc_abmhousing.js]
  N[pages/helpers/sdc_abmhousingdb.php]
  M[pages/sdc_housing.php]
- Agrego permisos especial para dar acceso a módulo de clientes DC
  ALTER TABLE permisos
  ADD cli_dc INT(11) DEFAULT '0' AFTER admin_per;
- Se agrega en la tabla de permisos la columna de Admin proy con la funcionalidad de actualizar onclick
  M[pages/admin.php]
  M[pages/setPermiso.php]
- Se actualiza los permisos del menu lateral
  M[pages/sitte_sidebar.php]
  M[site.php]
- Arreglo permisos en páginas:
  M[admin.php]
  M[calendario.php]
  M[inventario.php]
  M[iso27k.php]
  M[iso9k.php]
  M[novedades.php]
  M[topologia.php]

## FIX-MC

### Correcciónes MC

<<<<<<< HEAD
<<<<<<< HEAD
 - Se indica el id en vez de la referencia al editar
 - Se muestra la totalidad de los responsables en editar_mejora por si estubo dado de baja.
=======
<<<<<<< HEAD
=======
>>>>>>> FEAT-IAAS

>>>>>>> devel
>>>>>>> =======
>>>>>>>
>>>>>>> - Se indica el id en vez de la referencia al editar
>>>>>>> - Se muestra la totalidad de los responsables en editar_mejora por si estubo dado de baja.
>>>>>>>   devel
>>>>>>>
>>>>>>
>>>>>
>>>>
>>>
>>
<<<<<<< HEAD
>>>>>>> FEAT-CMP-CANCEL
=======
>>>>>>> FEAT-IAAS

## FEAT-PROY-GTI (CON FEAT-ADMIN-PER)

### Extender la funcionalidad de proyectos al toda la empresa.

*Fecha:* 2019-09-17
*Cambios:*

- Se agregan el campo en la base de permisos admin_proy para administrar proyectos y "proyectos" para dar acceso al módulo independientemente el permiso SOC
  ALTER TABLE controls.permisos
  ADD admin_proy INT(11) AFTER guardias; [DEFAULT 0]
  ALTER TABLE controls.permisos
  ADD proy INT(11) DEFAULT '0' AFTER admin_proy;
- Se agrega en la tabla de permisos la columna de Admin proy con la funcionalidad de actualizar onclick
  M[pages/admin.php]
  M[pages/setPermiso.php]
- Se actualizan los permisos de aquellos que hoy en día son admin
  update permisos set admin_proy = 1 where admin = 1;
- Se actualiza la página de proyectos para que tome como admin el campo admin_proy
  M[pages/proyectos.php]
- Aplico el filtro de gerencias en proyectos a la solapa de Proyectos, completados e indicadores
- Proyetos
- Completos
- indicadores:
- cuenta vencidos
- cuenta total
- No iniciados
- En curso
- Completados
- Asignación de proyectos M[pages/getProyResp.php] (le paso id_gerencia por POST)
- Estado de proyectos M[pages/getProyRespStat.php] (le paso id_gerencia por POST)
  M[pages/proyectos.php]
- Limpio el campo de grupo al cambio de gerencia en persona. M[pages/edit_persona.php]
  TEST   - Se aplica el acceso al módulo de proyectos para los permisos "proy"
- Se le dan los permisos de proyecto a los de soc
  update permisos set proy = 1 where soc = 1;
  M[pages/site_sidebar.php]
  M[site.php]
- Permitir en el AM de peronas poder seleccionar el grupo.
  M[pages/admin.php]  OK
  M[pages/edit_persona.php]   OK
- Agrega el campo id_gerencia a la base de datos para dar la posibilidad de generar grupos por gerencia.
  ALTER TABLE controls.grupo
  ADD id_gerencia INT NOT NULL DEFAULT '0' AFTER id_grupo;
  Se actualizan los grupos actuales con la generecia de CiberSeguridad:
  update grupo set id_gerencia = 1;
- Se agrega la columna del permiso "proy"
  M[admin.php]
  M[setPermiso.php]
- Se aplica el filtro estrico de gerencias.
- Proyetos
- Completos
- indicadores:
- cuenta vencidos
- cuenta total
- No iniciados
- En curso
- Completados
- Asignación de proyectos M[pages/getProyResp.php] (le paso id_gerencia por POST)
- Estado de proyectos M[pages/getProyRespStat.php] (le paso id_gerencia por POST)
- Se actualiza el modal de "nuevo acceso" M[admin.php]
- FEAT-ADMIN-PER:
- Se agregan el campo en la base de permisos admin_per para administrar personal y accesos
  ALTER TABLE controls.permisos
  ADD admin_per INT(11) DEFAULT '0' AFTER proy;
- Se agrega en la tabla de permisos la columna de Admin proy con la funcionalidad de actualizar onclick
  M[pages/admin.php]
  M[pages/setPermiso.php]
- Se actualizan los permisos de aquellos que hoy en día son admin
  update permisos set admin_per = 1 where admin = 1;
- Se actualiza la página de sit_header para que tome como admin el campo admin_per
  M[pages/site_header.php]
  M[site.php]
- Se adapta el modal de nuevo proyecto y edición de proyecto para que se visualicen los grupos de la gerencia a la cual pertenecen
  M[proyectos.php]
  M[edit_proyecto.php]
- Ordeno los accessos por apellido
  M[admin.php]
- Cambio el link de los indicadores del header
  M[site_header.php]
  M[site.php]

<<<<<<< HEAD
<<<<<<< HEAD
 devel

=======
>>>>>>> devel
>>>>>>>
>>>>>>> FEAT-CMP-CANCEL
=======
>>>>>>> devel
>>>>>>>
>>>>>>> FEAT-IAAS
>>>>>>
>>>>>
>>>>
>>>
>>

## FEAT-KPI-MC

### Indicadores

#### Status de las Acciones de Mejora (AM)/ No Conformidades (NC)    [Gráficos de Torta]

1) Cantidad de AM  - Abiertos
2) Cantidad de AM  - Cerrados
3) Cantidad de NC  - Abiertos
4) Cantidad de NC  - Cerrados

#### *[Graficos de barras]* Abiertos + histograma por año de cerrados

B)	Origen de las Acciones de Mejora (AM)/ No Conformidades (NC)
B1) Auditoria Externa

1) Cantidad de AM por Auditor Externo (AE) – Abiertos
2) Cantidad de AM por Auditor Externo (AE) – Cerrados
3) Cantidad de NC por Auditor Externo (AE) – Abiertos
4) Cantidad de NC por Auditor Externo (AE) - Cerrados

#### Auditoria Interna

1) Cantidad de AM por Auditor Interno (AI) – Abiertos
2) Cantidad de AM por Auditor Interno (AI) – Cerrados
3) Cantidad de NC por Auditor Interno (AI) – Abiertos
4) Cantidad de NC por Auditor Interno (AI) – Cerrados

#### Negocio

1) Cantidad de AM por Negocio – Abiertos
2) Cantidad de AM por Negocio – Cerrados
3) Cantidad de NC por Negocio – Abiertos
4) Cantidad de NC por Negocio – Cerrados

En vez de por área por responsable

#### Acciones de Mejora por Área (AM)/ No Conformidades (NC):

1) Cantidad de AM por Área  - Abiertos
2) Cantidad de AM  por Área – Cerrados
3) Cantidad de NC por Área  - Abiertos
4) Cantidad de NC  por Área – Cerrados

*Fecha:* 2019-09-16
*Cambios:*
<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> FEAT-CMP-CANCEL
=======

>>>>>>> FEAT-IAAS
- Se agrega una nueva página de metricas para mejoras N[met_mejoras]
- Se actualizan los links en site y sidebar M[/site.php] M[site_sidebar.php]
- Se crean los graficos para AMy NC totales y por origen
- Creo filtros de año + fecha de apertura
- Se pasan todas la generación de gráficos a jquery + ajax.post
- Aplicar filtro de todas las fechas de apertura para un año específico. (por si elije todas las fechas)
- Aplicar filtro de todas las fechas de todos los anios.
- Se corrige label Análisis de Causas M[/pages/edit_mejora.php]
- Se limitan los orígenes de mejora a solo AI,AE y NE [mejoras.php] [edit_mejora.php]
- Se muestra como número de mejora el mismo id [mejora.php]

<<<<<<< HEAD
<<<<<<< HEAD
 devel

=======
>>>>>>> devel
>>>>>>>
>>>>>>> FEAT-CMP-CANCEL
=======
>>>>>>> devel
>>>>>>>
>>>>>>> FEAT-IAAS
>>>>>>
>>>>>
>>>>
>>>
>>

## FEAT-CTRL-OBS

### Identificación de nuevos estados en las referencia de los controles para determinar aquellos que han sido observados.

*Fecha:* 2019-09-04
*Cambios:*

- Se agregan los estados 3 y 4 ("Controlado con obs alta" y "Controlado con obs baja"en la edición de las instancias de controles
  M[pages/edit_referencia.php]
- Se actualiza la visualización en la grilla de instancias del control.
- Se actualizan los indicadores de cumplimiento (Gráfico de tortas)
  M[pages/control.php]
- Se actualizan los graficos de métricas
  M[pages/met_controles.php]
  M[site.php]
- Se agrega el campo de búsqueda en la grilla de controles
  M[pages/controles.php]
- Se corrige el calendario de controles para alojar los nuevos estados.
  M[pages/cal_controles]

## FIX-ISO9K1

### Nuevo módulo para alojar la matriz de ISO9001 basado en la misma funcionalidad de la iso27k1.

*Fecha:* 2019-07-23
*Cambios:*

- Se duplican las estructuras de datos para alojar la informacion de la ISO9K1
  M[BBDD]
- Se duplica la existete funcionalidad de la iso 27k1 para la 90001
  N[helpers/abmiso9k.php]
  N[modals/abmiso9k.js]
  N[modals/abmiso9k.php]
  N[iso9k.php]
- Creo estructura de menu para alojar las matrices
  M[site_sidebar.php]

## FIX-ISO27K

### Correcciones a los últimos cambios en la matriz de la iso27k.

*Fecha:* 2019-07-23
*Cambios:*

- Se oculta el botón de borrado
  M[iso27k.php]
- Se actualiza la fecha de la versión de la matriz al actualizar cualquier item
  M[helpers/abmiso27kdb.php]
- Se oculta la funcionalidad de alta
  M[iso27k.php]
- Error al abrir popup cuando hay un solo referente
  M[iso27k.php]

## FEAT-PROY-TAREAS

### Cambios en la Proyectos.

*Fecha:* 2019-07-22
*Cambios:*

- Cambio L&F de las tablas
- Agregado de filtro en campo categoría en tabla Completados
  M[proyetos.php]

## FEAT-ISO271K

### Cambios en la matriz de cumplimiento.

*Fecha:* 2019-07-04
*Cambios:*

- Cambio L&F de las tablas
  M[iso271k.php]
- Poder seleccionar multiples referentes.
  M[edit_iso27k.php]
- Agrego columna referentes a la grilla y cambio label referente por responsable
  M[iso271k.php]
- Agrego campos de nivel y parent para agrupar los items
  M[iso271k.php]
  N[datatable.rougroup.css/js]
- Agrego campo de versión y selector de la misma
  M[iso271k.php]
  M[edit-iso27.php]
- Corrijo metricas del dashboard principal y particular de iso
  M[site.php]
  M[met_iso27k.php]
- Corrijo redireccion de edit_iso27k.php
  M[edit_iso27k.php]
- Muestro acciones solo para la última version de la matriz
  M[iso27k.php]
- ABM ItemISO
  N[modals]
  N[helpers]
  N[modals/abmiso27k.js/php]
  N[helpers/abmiso27kdb.php]
  N[helpers/getAsyncDataFromDB.php]
  N[..css/boostrap-select.min.css]
  N[..js/boostrap-select.min.js]

## FEAT-PROYECTOS

### Identificación de proyectos y nuevas métricas de avance.

*Fecha:* 2019-07-02
*Cambios:*

- Cambio L&F de las tablas
  M[proyecto.php]
- Agregar Campo "Tipo de proyecto" a las tablas
  M[proyecto.php]
  -Agregar campo tipo en formularios de alta/modif
  M[proyecto.php]
  M[edit_proyecto.php]
- Agregar Marca de Reunión en el avance y campo de tiempo en minutos.
  M[edit_proyecto.php]

## FEAT-FIL-GERENCIA2

### Continuar con la implementacion de los filtros de gerencia en cada módulo.

*Fecha:* 2019-06-13
*Cambios:*

- Implementación de filtro de gerencia en el modulo de Mejoras
  M[mejora.php]
- Implementación de filtro de gerencia en el modulo de Activos
  M[activos.php]
- Se agrega el indicador de la gerencia en el dropdown del perfil
  M[site_header.php]

## FEAT-METRICAS

### Metricas propuestas por Lucciani.

*Fecha:* 2019-06-11
*Cambios:*

- Riesgos abiertos por gerencia y nivel
  M[met_riesgos.php]
- Riesgos abiertos por gerencia y nivel
  M[met_riesgos.php]
- Riesgo: Calendario de riesgos por gerencia
  N[cal_riesgos.php]
  M[riesgos.php]
- Activos: Activos por gerencia.
  M[met_activos.php]
- Controles: Nuevo calendario de controles: Cantidad de controles por gerencia
  M[cal_controles.php]
- Arreglo del gráfico de torta. Estaba desactualizado con los tipos de activo
  M[met_activos.php]
  M[site.php]

## FEAT-MEJORAS

### Cambios menores.

*Fecha:* 2019-05-31
*Cambios:*
<<<<<<< HEAD
=======

- Pongo número de referencia en el título en vez del id
  M[mejoras.php]
  M[edit_mejora.php]
- Cambio L&F datatable, agrego ordering
  M[mejoras.php]
  -filtro por estado, responsable y tipo
  M[mejoras.php]
  -agrego demás campos en la grilla (ocultos) para que se puedan exportar
  M[mejoras.php]
>>>>>>> FEAT-IAAS

- Pongo número de referencia en el título en vez del id
  M[mejoras.php]
  M[edit_mejora.php]
- Cambio L&F datatable, agrego ordering
  M[mejoras.php]
  -filtro por estado, responsable y tipo
  M[mejoras.php]
  -agrego demás campos en la grilla (ocultos) para que se puedan exportar
  M[mejoras.php]

<<<<<<< HEAD

## FIX-PROD

=======
>>>>>>> devel
>>>>>>>
>>>>>>
>>>>>
>>>>
>>>
>>

## FIX-PROD

>>>>>>> devel
>>>>>>>
>>>>>>
>>>>>
>>>>
>>>
>>

<<<<<<< HEAD
>>>>>>> FEAT-CMP-CANCEL
=======
>>>>>>> FEAT-IAAS
- Se crea página para mostrar el calendario anual de los cntroles
  N[cal_controles.php]
- Agrego el link en controles.php
  M[controles.php]
  TODO: LIMPIAR FORMULARIO DEL MODAL ANTES DE MOSTRAR DATA

## FIX-PROD

- Se corrige rango de valores de riesgo en gráfico del dashboard y metricas de riesgo
  M[pages/met_riesgos.php]
  M[site.php]
- Se corrige los colores de las matrices
  M[pages/met_riesgos.php]

## REL-0002

- Se corrige descripcion justificacion avance.

## REQ-20190416

### Cambios menores.

*Fecha:* 2019-04-16
*Cambios:*
<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> FEAT-CMP-CANCEL
=======

>>>>>>> FEAT-IAAS
- Mostrar campo justificación en los avances de 100% M[edit_riesgo.php]
- Agregar Filtro en Grilla de Riesgos M[riesgos.php]
- Cambiar paginación de grillas a 20 elementos:
- M[controles.php]
- M[activos.php]
- M[iso27k.php]
- M[mejoras.php]
- M[riesgos.php]
- Controles: Agregar columna pendiente en base a si tiene controles pendientes del mes actual o anteriores.

### FIXES a los avances de riesgos.

*Fecha:* 2019-04-05
*Cambios:*

- Corrección de % de proyectos completados M[proyecto.php] --> YA ESTA CORREGIDO EN PROD
- Valido % de avance 0-100 M[edit_riesgo.php]
- Correccion de cuando se agrega un nuevo avance y el estado es Abierto pide justificacion M[edit_riesgo.php]
- Si se cierra el riesgo se pone como 100% M[edit_riesgo.php]
- Si se ingresa 100% como avance si cierra el riesgo M[edit_riesgo.php]
- Agrego campo % avance en los avances de los riesgos M[edit_riesgo.php]

## FEAT-FIL-GERENCIA

### Varios post release.

*Fecha:* 2019-04-04
*Cambios:*

- Saco la definicion del header en archivos php separados para que sean reutilizables y ordenar el código y mejorar la manutención del mismo.
  - M[site_header.php]
- Actualizacion del sidebar en las páginas. En el site.php hay que actualizarlo manualmente
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
  - M[admin.php]
  - N[met_activos.php]
  - N[met_iso27k.php]
  - N[met_riesgos.php]
  - N[met_controles.php]
- Corrijo colores de indicadores
  - M[site.php]
- Corrijo el acceso al menu de métricas para que se accible para todos.
  - M[site.php]
  - M[site_sidebar.php]
- INDICADOR DE ENTORNO
  - M[site_header.php]
  - M[site.php]
- Filtro gerencia en el dashboard salvo Ciberseguridad
- Filtro gerencia en el métricas
  - M[met_activos.php]
  - M[met_iso27k.php]
  - M[met_riesgos.php]
  - M[met_controles.php]
- Filtro gerencia en matrices de riesgo
  - M[met_riesgos.php]
- TODO - Ver controles.php por diferencia en indicador de contrloes

## FEAT-SITE-MENU

### Varios post release.

*Fecha:* 2019-04-03
*Cambios:*

- Saco la definicion del sidebar y el header en archivos php separados para que sean reutilizables y ordenar el código y mejorar la manutención del mismo.
  - N[site_sidebar.php]
  - N[site_header.php]
- Actualizacion del sidebar en las páginas. En el site.php hay que actualizarlo manualmente
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
  - M[admin.php]
- Nuevo Menú Métricas
  - N[met_activos.php]
  - N[met_iso27k.php]
  - N[met_riesgos.php]
  - N[met_controles.php]
- TODO - SAcar el header

## FEAT-REL-REORGANIZ

### Varios post release.

*Fecha:* 2019-03-25
*Cambios:*

- Se posicionan los botones de exportación arriba de la tabla. M[riesgo.php]
- Se corrige la imposibilidad de eliminar un avance. M[edit_riesgo.php]
- Se corrige el detalle de la confirmación al eliminar un riesgo. M[edit_riesgo.php]
- Se corrige la visualización del campo justificacion al dar de alta un avance sobre un riesgo cerrado. M[edit_riesgo.php]
- Se agregan los filtros acumulativos en el encabezado de la tabla. M[riesgo.php]
- Se elimina el filtro de incidente. M[riesgo.php]
- Se corrige un error en los indicadores de Proyectos. M[getProyResp.php] M[getProyRespStat.php] YA SOLUCIONADO EN PROD
- Se pasan las grillas de Matrices de risesgos al dashboard principal. M[site.php]
- Poder visualizar la gente que ha leído las novedades M[novedades.php]

## FEAT-FIX-VARIOS

### Varios pre release.

*Fecha:* 2019-03-21
*Cambios:*

- Se cambia el label de las matrices para que se lea cantidad de .... M[riesgo.php]
- Se pone como requerido el campo "vencimiento". M[riesgo.php]
- Se cambio el orden de los campos vulnerabilidad x amenaza. M[riesgo.php] M[edit_riesgo.php]
- Se agrega el campo gerencia RO en el alta y la edicion de riesgo.M[riesgo.php] M[edit_riesgo.php]
- Cambio de calculo de tratamiento a >= 4
- Cambio de calculo de valoracion inicial y actual a BAJO=[1,2,3] MEDIO=[4,5,6,7,8,9,10] ALTO=[11,...]

## FEAT-CTR-CRITICIDAD

### Agregado de criticidad en controles.

*Fecha:* 2019-03-21
*Cambios:*

- Se agrega el campo gerencia en la lista de controles. M[controles.php]
- Se filtra la lista de controles en base a la gerencia en la que aparece el responsable salvo la gerencia de Ciberseguridad M[controles.php]
- Se agrega campo criticidad. M[contoles.php] M[edit_control.php]

## FEAT-PER-TODOS

### Correcciones en la administracion de personas.

*Fecha:* 2019-03-20
*Cambios:*

- Se agrega el campo "Grupo" en el Alta de personas solo para la gerencia de Ciberseguridad. M[admin.php]
- Se agrega el campo "Grupo" y "contacto" en la Edicion de personas solo para la gerencia de Ciberseguridad. M[edit_persona.php]
- Se agrega el campo "contacto" en el ABM de personas. M[admin.php]
- Se quita el botón de "agregar persona" en todos las páginas menos adminsitración.
  M[activos.php]
  M[controles.php]
  M[iso27k.php]
  M[mejoras.php]
  M[riesgos.php]

## FEAT-RGO-VARIOS

### Correscciones varias en la página de Riesgo.

*Fecha:* 2019-03-15
*Cambios:*

- Se filtra la lista de riesgos en base a la gerencia en la que aparece el responsable salvo la gerencia de Ciberseguridad M[riesgos.php]
- Se corrige la visualización de los días de vencio / a vencer. Si el riesgo se encuentra abierto se muestran los días. de vecido / a vencer. M[riesgos.php]
- Se agrega la capacidad de borrar un avance. M[edit_riesgo.php]
- Edicion de Avance por medio de Popup. M[edit_riesgo.php]
- Filtro por incidente en la lista de riegos. M[riesgos.php]
- Campo de justificacion obligatorio al cerrar un riesgo. M[edit_riesgo.php]

## FEAT-CTR-GERENCIA

### Agregar el campo Gerencia en controles y Riesgo.

*Fecha:* 2019-03-14
*Cambios:*

- Se arregla el identado del código M[riesgos.php]
- Se agrega la gerencia del referente/responsable a la lista de riesgos con el find de poder filtrar M[riesgos.php]
- Se arregla el identado del código M[controles.php]
- Se agrega la gerencia del referente/responsable a la lista de riesgos con el find de poder filtrar M[controles.php]

## FEAT-PER-ABM

### Agregar la posibilidad de actualizar y borrar una persona.

Hoy en día solo existe la funcionalidad de ALTA.
*Fecha:* 2019-03-13
*Cambios:*

- Se agrega el campo borrado TINYINT [0 | 1] para baja lógica de personas M[DB]
- Se agrega solapa en administración para el ABM de personas . M[admin.php]
  - Alta de persona en popup
  - Baja lógica
  - Se agrega paginado
- Edición de persona. N[edit_persona.php]
- Se corrige los queries donde se asumia que el campo borrado de persona no existía. M[riesgos.php]

## FEAT-CTR-MESINICIO

### Agregar mes de inicio para la generación de los controles del año.

Se debe agregar un selector del mes de inicicio de los controles para que los mismos se generen desde ese mes en base a la periodicidad definida.
*Fecha:* 2019-03-08
*Cambios:*
-Se agrega el dropdown para seleccionar el mes de inicio . [controles.php]
-Se cambia la lógica e generación de referencias en base al mes inicio . [controles.php]

- Se visualiza el campo Mes de Inicio en la edición (No editable). [edit_control.php]

## FEAT-PIE-CONTROLES

### Cambiar el filtro para que muetre los datos del estado de los controles según la fecha de hoy.

Hoy en día el gráfico toma en cuenta los controles generados automáticamente a futuro.
*Fecha:* 2019-03-07
*Cambios:*

- Se agrega las condiciones de borrado = 0 a los queries de cantidad de controles programados y pie. [site.php]

## FEAT-PIE-RIEGOS

### Filtro de estado abierto al gráfico de torta de Nivel de Riesgo residual

*Fecha:* 2019-03-07
*Cambios:*

- Se agrega a las queries de datos de dona de nivel de riesgo el filtro por estado = 0 (Abierto) para que se corresponda con el contador de arriba. [site.php]
