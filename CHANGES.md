# CHANGES

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
>>>>>>> devel


## FIX-CLI-DC

Correcciones POST Producción
*Fecha:* 2019-09-27
*Cambios:*
    - Se corrige el menú lateral del inicio (Se saca la opción de dashboard en modulo de clientes)
    M[site.php]
    - Se agrega un script al final de cada una de las páginas que hace que se mantenga en menú del side bar correspondiente abierto
    -[sdc_hosting/housing]
    -[cdc_cliente/organismo]
    - housing refrescar la pagina al guardar o modiciar
    M[modals/sdc_abmhousing.js]


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

    - Se indica el id en vez de la referencia al editar
    - Se muestra la totalidad de los responsables en editar_mejora por si estubo dado de baja.

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
>>>>>>> devel


## FEAT-KPI-MC

### Indicadores

#### Status de las Acciones de Mejora (AM)/ No Conformidades (NC)    [Gráficos de Torta]

1)	Cantidad de AM  - Abiertos
2)	Cantidad de AM  - Cerrados
3)	Cantidad de NC  - Abiertos
4)	Cantidad de NC  - Cerrados

#### *[Graficos de barras]* Abiertos + histograma por año de cerrados

B)	Origen de las Acciones de Mejora (AM)/ No Conformidades (NC)
B1) Auditoria Externa
1)	Cantidad de AM por Auditor Externo (AE) – Abiertos
2)	Cantidad de AM por Auditor Externo (AE) – Cerrados
3)	Cantidad de NC por Auditor Externo (AE) – Abiertos
4)	Cantidad de NC por Auditor Externo (AE) - Cerrados

#### Auditoria Interna

1)	Cantidad de AM por Auditor Interno (AI) – Abiertos
2)	Cantidad de AM por Auditor Interno (AI) – Cerrados
3)	Cantidad de NC por Auditor Interno (AI) – Abiertos
4)	Cantidad de NC por Auditor Interno (AI) – Cerrados

#### Negocio

1)	Cantidad de AM por Negocio – Abiertos 
2)	Cantidad de AM por Negocio – Cerrados
3)	Cantidad de NC por Negocio – Abiertos
4)	Cantidad de NC por Negocio – Cerrados

En vez de por área por responsable

#### Acciones de Mejora por Área (AM)/ No Conformidades (NC):

1)	Cantidad de AM por Área  - Abiertos
2)	Cantidad de AM  por Área – Cerrados
3)	Cantidad de NC por Área  - Abiertos
4)	Cantidad de NC  por Área – Cerrados

*Fecha:* 2019-09-16
*Cambios:*
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
    - Pongo número de referencia en el título en vez del id
        M[mejoras.php]
        M[edit_mejora.php]
    - Cambio L&F datatable, agrego ordering
        M[mejoras.php]
    -filtro por estado, responsable y tipo
        M[mejoras.php]
    -agrego demás campos en la grilla (ocultos) para que se puedan exportar
        M[mejoras.php]

>>>>>>> devel
## FIX-PROD
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
