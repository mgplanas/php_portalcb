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

#### TODO
- Faltan campos de Grupo y contacto en los formularios
- Quitar agregar persona de las demás páginas
    - Activos
    - Controles
    - iso
    - mejora
    - riesgos
    - proyecto-proyectos
- Botón ver


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
