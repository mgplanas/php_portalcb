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
