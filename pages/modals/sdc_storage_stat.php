<div class="modal fade" id="modal-abm-storage-stat">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-storage-stat-title'>Métricas</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <div class="box-body">
                    <table id="tbstat" class="display" width="100%">
                        <thead>
                            <tr>
                            <th width="10%" align="center">Categoría</th>
                            <th class="text-right">[TB] Capacidad Asignable</th>
                            <th class="text-right">[TB] Asignado</th>
                            <th class="text-right">[%] Asignado Actual</th>
                            <th class="text-right">[TB] Disponible Estimado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT 
                            cat.nombre as cat_nombre , 
                            capacidad_asignable, 
                            asignado_tb, 
                            (asignado_tb*100/capacidad_asignable) as asignado_actual,
                            disponible_estimado,
                            (capacidad_asignable - asignado_tb) as disponible_estimado_2
                            FROM (
                            SELECT 
                                sto_raw.categoria, 
                                SUM((sto_raw.capacidad_fisica_tb * sto_raw.per_asignacion_recomendado / 100)) as capacidad_asignable, 
                                SUM(CASE WHEN (sto_raw.asignado_tb >= (sto_raw.capacidad_fisica_tb * sto_raw.per_asignacion_recomendado / 100)) THEN 0 
                                    ELSE (sto_raw.capacidad_fisica_tb * sto_raw.per_asignacion_recomendado / 100) - sto_raw.asignado_tb END) as disponible_estimado,
                                SUM(sto_raw.asignado_tb) as asignado_tb, 
                                SUM(sto_raw.capacidad_fisica_tb) as capacidad_fisica_tb, 
                                SUM(sto_raw.per_asignacion_recomendado) as  per_asignacion_recomendado
                            FROM sdc_storage as sto_raw
                                WHERE sto_raw.borrado = 0
                                AND sto_raw.estado = 1
                                GROUP BY sto_raw.categoria
                            ) as sto_gr
                            INNER JOIN sto_categorias as cat ON sto_gr.categoria = cat.id;"; 
                            
                            $sql = mysqli_query($con, $query);

                            if(mysqli_num_rows($sql) > 0){
                                $no = 1;
                                $sum_cap_asignable = 0;
                                $sum_asignado = 0;
                                $sum_disponible = 0;
                                while($row = mysqli_fetch_assoc($sql)){
                                    $sum_cap_asignable += $row['capacidad_asignable'];
                                    $sum_asignado += $row['asignado_tb'];
                                    $sum_disponible += ($row['disponible_estimado_2']>0 ? $row['disponible_estimado_2'] : 0);
                                    $per_disponible = (1-($row['asignado_tb']/$row['capacidad_asignable']))*100;
                                    echo '<tr>';
                                    echo '<td>'. $row['cat_nombre'].'</td>';
                                    echo '<td class="text-right">'. number_format($row['capacidad_asignable'],2,",",".").'</td>';
                                    echo '<td class="text-right">'. number_format($row['asignado_tb'],2,",",".").'</td>';
                                    echo '<td class="text-right">'. setSemaphoreBadge($row['asignado_actual'],$row['asignado_actual'], $_TIPO_RANGOS_ASIGNADOS, true).'</td>';
                                    if ($row['disponible_estimado_2'] >= 0) {
                                        echo '<td class="text-right" title="' . number_format($per_disponible,2,",",".") . '%">'. setSemaphoreBadge($per_disponible,$row['disponible_estimado_2'], $_TIPO_RANGOS_CAPACIDAD, true).'</td>';
                                    } else {
                                        echo '<td class="text-right" title="' . number_format($per_disponible,2,",",".") . '%">'. setSemaphoreBadge($per_disponible,0, $_TIPO_RANGOS_CAPACIDAD, true).'</td>';
                                    }
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <?php
                            echo '<th>Total</th>';
                            echo '<th class="text-right">'. number_format($sum_cap_asignable,2,",",".") .'</th>';
                            echo '<th class="text-right">'. number_format($sum_asignado,2,",",".") .'</th>';
                            echo '<th></th>';
                            echo '<th class="text-right">'. number_format($sum_disponible,2,",",".") .'</th>';
                            ?>
                        </tfoot>   
                    </table>                    
                </div>
            </form>
        </div>
    </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>