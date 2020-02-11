<?php

    include('./fn_guardias.php');
    include('../../conexion.php');

    $result = recalcular_periodo(1,0, $con);
    $result = recalcular_periodo(2,0, $con);
    // $result = recalcular_compensatorio('2019-10-10', '2019-11-11', 64, $con);
    // var_dump($result);
    // include('../../conexion.php');

    // function split_guardias_en_dias($row){
    //     $result = array();
    //     // Si la guardia pasa un día lo divido
    //     if ($row['startDay'] != $row['endDay']) {
    //         $startDay = strtotime($row['start']);
    //         $endDay = strtotime(substr($row['start'],0,10) . ' 23:59:59');
    //         $min = intval((abs($endDay - $startDay) / 60) + 1);
    //         if ($min > 0) {
    //             array_push($result, ['start' => $row['start']
    //                     ,'end' => substr($row['start'],0,10) . ' 23:59:59'
    //                     ,'dnl' => $row['startdnl']
    //                     ,'wd' => $row['startwd']
    //                     ,'id' => $row['id_activacion_guardia']
    //                     ,'id_persona' => $row['persona']
    //                     ,'min' => $min
    //                     ]);
    //         }
    //         $startDay = strtotime(substr($row['end'],0,10) . ' 00:00:00');
    //         $endDay = strtotime($row['end']);
    //         $min = intval(abs($endDay - $startDay) / 60);
    //         if ($min > 0) {
    //             array_push($result, ['start' => substr($row['endDay'],0,10) . ' 00:00:00'
    //                     ,'end' => $row['end']
    //                     ,'dnl' => $row['enddnl']
    //                     ,'wd' => $row['endwd']
    //                     ,'id' => $row['id_activacion_guardia']
    //                     ,'id_persona' => $row['persona']
    //                     ,'min' => $min
    //                     ]);
    //         }
    //     } else {
    //         array_push($result, ['start' => $row['start']
    //                     ,'end' => $row['end']
    //                     ,'dnl' => $row['startdnl']
    //                     ,'wd' => $row['startwd']
    //                     ,'id' => $row['id_activacion_guardia']
    //                     ,'id_persona' => $row['persona']
    //                     ,'min' => intval($row['minute'])
    //                     ]);
    //     }
    //     return $result;        
    // }

    // // recalcularCompensatorios('2019-01-01', '$fechaHasta', 64);
    // $fechaDesde = '2019-10-21';
    // $fechaHasta = '2019-12-31';
    // $id_persona = 42;

    // # recupero los feriados
    // // $query = "SELECT fecha FROM adm_dnl WHERE fecha >= '$fechaDesde' AND fecha <= '$fechaHasta' AND  borrado = 0 ORDER BY 1;";
    // // $sql = mysqli_query($con, $query);
    // // $diasNoLaborables = array();
    // // while($row = mysqli_fetch_assoc($sql)) {
    // //     array_push($diasNoLaborables, $row['fecha']);
    // // }        
    
    // $query = "SELECT id_activacion_guardia, persona, 
    //         cast(concat(startDay, ' ', startTime) as datetime) as start ,
    //         cast(concat(endDay, ' ', endTime) as datetime) as end,
    //         startDay, endDay,                 
    //         WEEKDAY(startDay) as startwd,
    //         WEEKDAY(endDay) as endwd,
    //         (SELECT COUNT(1) FROM adm_dnl WHERE fecha = startDay AND  borrado = 0) as startdnl,
    //         (SELECT COUNT(1) FROM adm_dnl WHERE fecha = endDay AND  borrado = 0) as enddnl,
    //         title, liquida,
    //         TIMESTAMPDIFF(MINUTE, cast(concat(startDay, ' ', startTime) as datetime), cast(concat(endDay, ' ', endTime) as datetime)) as minute
    //         FROM activacion_guardia
    //         WHERE startDay >= '$fechaDesde' AND endDay <= '$fechaHasta'
    //         AND (WEEKDAY(startDay) IN (5,6) 
    //             OR WEEKDAY(endDay) IN (5,6)
    //             OR startDay IN (SELECT fecha FROM adm_dnl WHERE fecha >= '$fechaDesde' AND fecha <= '$fechaHasta' AND  borrado = 0)
    //             OR endDay IN (SELECT fecha FROM adm_dnl WHERE fecha >= '$fechaDesde' AND fecha <= '$fechaHasta' AND  borrado = 0)
    //         )
    //         AND borrado = 0";

    // if ($id_persona>0) {
    //     $query = $query . " AND persona = " . $id_persona;
    // }
    // $sql = mysqli_query($con, $query . ' ORDER BY persona, start');
    
    // // Split de las guarias
    // $guardias = array();
    // while($row = mysqli_fetch_assoc($sql)){
    //     $split = split_guardias_en_dias($row);
    //     foreach ($split as $item) {
    //         array_push($guardias, $item);
    //     }
    // }
    

    // //filtro las que aplican (Sábado después de las 13:00, domingos y feriados)
    // //Si hay una guardia que empiece antes de las 13 y termine después la corto
    // $guardias_aplicables = array();
    // foreach ($guardias as $key => $item) {
    //     $guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)] = array();
    //     //domingos y feriados
    //     if ($item['wd'] == "6" OR $item['dnl'] == "1") {
    //         array_push($guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)], $item);
    //     } else {
    //         //Sábados
    //         $startTime = strtotime($item['start']);
    //         $endTime = strtotime($item['end']);
    //         $condition = strtotime(substr($item['start'],0,10) . ' 13:00:00');
            
    //         if ($startTime >= $condition) {
    //             // empieza despues de las 13hs
    //             array_push($guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)], $item);
    //         } elseif ($startTime < $condition AND $endTime > $condition) {
    //             // si pasa por el medio trunco y recalculo minutos
    //             $item['start'] = substr($item['start'],0,10) . ' 13:00:00';
    //             $item['min'] = intval((abs($endTime - $condition) / 60));
    //             array_push($guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)], $item);
    //         }
    //     }
    // }

    // // Computo el total
    // $compensatorios = array();
    // // Por id
    // foreach ($guardias_aplicables as $key => $guardia_aplicable) {
    //     //Por fecha
    //     foreach ($guardia_aplicable as $keyfecha => $guardia_aplicable_fecha) {
    //         $_tot_min = 0;
    //         foreach ($guardia_aplicable_fecha as $keyitem => $item) {
    //             $_tot_min += $item['min'];
    //         }
    //         $guardia_aplicable[$keyfecha]['tot_min'] = $_tot_min;
    //         $_compensatorio = 1.0;
    //         if (($_tot_min / 60 ) < 4) {
    //             $_compensatorio = 0.5;
    //         }
    //         $guardia_aplicable[$keyfecha]['compensatorio'] = $_compensatorio;
    //     }
    //     var_dump($guardia_aplicable);
    // }    
?>


<?php
                        $curr_id_persona =0;
                        $nRow = 0;
                        $registro = $arrCompensatorios[$nRow];
                        $allRows = count($arrCompensatorios);
                        while($nRow < $allRows) {
                            
                            $curr_id_persona = intval($registro['id_persona']);
                            echo '<tr>';
                            echo '<td>'. $registro['subgerencia'].' - '. $registro['area'] .'</td>';
                            echo '<td>'. $registro['apellido'].', '. $registro['nombre'] .'</td>';
                            // Total
                            $aux_total = getTotalFromPersona($registro['id_persona'], $arrCompensatoriosTotales);
                            if ($aux_total < 0) {
                                echo '<td style="text-align:center;font-size:16px;"><strong><span class="badge bg-red">' . $aux_total . '</span></strong></td>';
                            }
                            else {
                                echo '<td style="text-align:center;font-size:16px;"><strong>'. getTotalFromPersona($registro['id_persona'], $arrCompensatoriosTotales).'</strong></td>';
                            }
                            
                            $currPeriodo = $periodo_min;
                            while ($nRow < $allRows and intval($registro['id_persona']) == $curr_id_persona) {

                                $regCurPeriodo = intval($registro['id_periodo']);
                                $result = getRegistroCompensatorio(intval($registro['id_persona']), 0, $arrCompensatorios);
                                // Formo las celdas vacias anteriores (si las hay) de sumas y restas por periodo
                                for ($i = $currPeriodo; $i < intval($registro['id_periodo']); $i++) {
                                    echo '<td style="text-align:center;background-color:rgb(153,255,153);"></td><td style="text-align:center;background-color:rgb(248,203,173);"></td>';
                                }
                                
                                //----------------------------
                                //En esta compensatorios o recuperos
                                //----------------------------
                                echo '<td style="text-align:center;background-color:rgb(153,255,153);">'.($registro['compensatorios'] ? $registro['compensatorios'] :"") .'</td>';
                                echo '<td style="text-align:center;background-color:rgb(248,203,173);">'.($registro['recuperos'] ? $registro['recuperos'] :"").'</td>';
                                //----------------------------
                                
                                //Incremento el mes para generar celdas hasta el próximo mes 
                                $currPeriodo = intval($registro['id_periodo']) + 1;
                                
                                $nRow++;
                                if ($nRow >= $allRows) {break;}
                                $registro = $arrCompensatorios[$nRow];
                            }
                            
                            // relleno los periodos que faltan
                            for ($i = $currPeriodo; $i <= $periodo_max; $i++) {
                                echo '<td style="text-align:center;background-color:rgb(153,255,153);"></td><td style="text-align:center;background-color:rgb(248,203,173);"></td>';
                            }             
                            echo '</tr>';       
                        }
                    ?>
