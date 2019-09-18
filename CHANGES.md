# CHANGES

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
