<?php

    include('../../conexion.php');

    function split_guardias_en_dias($row){
        $result = array();
        // Si la guardia pasa un día lo divido
        if ($row['startDay'] != $row['endDay']) {
            $startDay = strtotime($row['start']);
            $endDay = strtotime(substr($row['start'],0,10) . ' 23:59:59');
            $min = intval((abs($endDay - $startDay) / 60) + 1);
            if ($min > 0) {
                array_push($result, ['start' => $row['start']
                        ,'end' => substr($row['start'],0,10) . ' 23:59:59'
                        ,'dnl' => $row['startdnl']
                        ,'wd' => $row['startwd']
                        ,'id' => $row['id_activacion_guardia']
                        ,'id_persona' => $row['persona']
                        ,'min' => $min
                        ]);
            }
            $startDay = strtotime(substr($row['end'],0,10) . ' 00:00:00');
            $endDay = strtotime($row['end']);
            $min = intval(abs($endDay - $startDay) / 60);
            if ($min > 0) {
                array_push($result, ['start' => substr($row['endDay'],0,10) . ' 00:00:00'
                        ,'end' => $row['end']
                        ,'dnl' => $row['enddnl']
                        ,'wd' => $row['endwd']
                        ,'id' => $row['id_activacion_guardia']
                        ,'id_persona' => $row['persona']
                        ,'min' => $min
                        ]);
            }
        } else {
            array_push($result, ['start' => $row['start']
                        ,'end' => $row['end']
                        ,'dnl' => $row['startdnl']
                        ,'wd' => $row['startwd']
                        ,'id' => $row['id_activacion_guardia']
                        ,'id_persona' => $row['persona']
                        ,'min' => intval($row['minute'])
                        ]);
        }
        return $result;        
    }

    function recalcular_periodo($id_periodo, $id_persona, $con) {
        //Extraigo las fechas del período
        $query = "SELECT fecha_desde, fecha_hasta FROM adm_cmp_periodos WHERE id = " . $id_periodo;
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $_periodo_f_desde = $row['fecha_desde'];
        $_periodo_f_hasta = $row['fecha_hasta'];
        unset($row, $result);

        // Caculo los compensatorios
        $compensatorios = recalcular_compensatorio($_periodo_f_desde, $_periodo_f_hasta, $id_persona, $con);
        $recuperos = get_recuperos($_periodo_f_desde, $_periodo_f_hasta, $id_persona, $con);

        // Elimino todo lo referido al período/persona
        $result = mysqli_query($con, "DELETE FROM adm_cmp_balance WHERE id_periodo = " . $id_periodo . ($id_persona>0 ? ' AND id_persona = ' . $id_persona : ''));
        $result = mysqli_query($con, "DELETE FROM adm_cmp_balguardias WHERE id_periodo = " . $id_periodo. ($id_persona>0 ? ' AND id_persona = ' . $id_persona : ''));
        $result = mysqli_query($con, "DELETE FROM adm_cmp_balcompensa WHERE id_periodo = " . $id_periodo. ($id_persona>0 ? ' AND id_persona = ' . $id_persona : ''));

        // Agrego lo nuevo
        // Por id
        foreach ($compensatorios as $k_id_persona => $v_cmpfechas) {
            //Por fecha
            foreach ($v_cmpfechas as $k_fecha => $v_items) {
                var_dump($v_items);
                $just = '';
                if ($v_items['guardias'][0]['dnl']=='1') { $just='F'; 
                } elseif ($v_items['guardias'][0]['wd']=='6') { $just='D'; 
                } else { $just = 'S'; }

                $query = 'INSERT INTO adm_cmp_balance (id_periodo, id_persona, tipo, dias, origen) VALUES ('.$id_periodo.','.$k_id_persona.',"C",'.$v_items["compensatorio"].',"'.$just.'")';
                $result = mysqli_query($con, $query);
                $id_balance = mysqli_insert_id($con);
                foreach ($v_items['guardias'] as $k_item => $item) {
                    $query = 'INSERT INTO adm_cmp_balguardias (id_periodo, id_balance, id_guardia, id_persona) VALUES ('.$id_periodo.','.$id_balance.','.$item["id"].','.$k_id_persona.')';
                    $result = mysqli_query($con, $query);
                }
            }
        }        

        // recuperos
        foreach ($recuperos as $key => $recupero) {
            $query = 'INSERT INTO adm_cmp_balance (id_periodo, id_persona, tipo, dias, origen) VALUES ('.$id_periodo.','.$recupero['id_persona'].',"R",1,"")';
            $result = mysqli_query($con, $query);   
            $id_balance = mysqli_insert_id($con);    
            $query = 'INSERT INTO adm_cmp_balcompensa (id_periodo, id_balance, id_compensa, id_persona) VALUES ('.$id_periodo.','.$id_balance.','.$recupero['id'].','.$recupero['id_persona'].')';
            $result = mysqli_query($con, $query);                 
            $compensatorios[$recupero['id_persona']][substr($recupero['fecha'],0,10)]['recupero'] = 1;
        }              
        return $compensatorios;        
    }

    function get_recuperos($fechaDesde, $fechaHasta, $id_persona, $con) {
        $query = "SELECT id, fecha, id_persona FROM recupero_guardia WHERE fecha >= '$fechaDesde' AND fecha <= '$fechaHasta' AND borrado = 0 ";

        if ($id_persona>0) {
            $query = $query . " AND persona = " . $id_persona;
        }
        $sql = mysqli_query($con, $query . ' ORDER BY id_persona, fecha');
        
        $recuperos = array();
        while($row = mysqli_fetch_assoc($sql)){
            array_push($recuperos, $row);
        }

        return $recuperos;
    }
    function recalcular_compensatorio($fechaDesde, $fechaHasta, $id_persona, $con) {
        $query = "SELECT id_activacion_guardia, persona, 
        cast(concat(startDay, ' ', startTime) as datetime) as start ,
        cast(concat(endDay, ' ', endTime) as datetime) as end,
        startDay, endDay,                 
        WEEKDAY(startDay) as startwd,
        WEEKDAY(endDay) as endwd,
        (SELECT COUNT(1) FROM adm_dnl WHERE fecha = startDay AND  borrado = 0) as startdnl,
        (SELECT COUNT(1) FROM adm_dnl WHERE fecha = endDay AND  borrado = 0) as enddnl,
        title, liquida,
        TIMESTAMPDIFF(MINUTE, cast(concat(startDay, ' ', startTime) as datetime), cast(concat(endDay, ' ', endTime) as datetime)) as minute
        FROM activacion_guardia
        WHERE startDay >= '$fechaDesde' AND endDay <= '$fechaHasta'
        AND (WEEKDAY(startDay) IN (5,6) 
            OR WEEKDAY(endDay) IN (5,6)
            OR startDay IN (SELECT fecha FROM adm_dnl WHERE fecha >= '$fechaDesde' AND fecha <= '$fechaHasta' AND  borrado = 0)
            OR endDay IN (SELECT fecha FROM adm_dnl WHERE fecha >= '$fechaDesde' AND fecha <= '$fechaHasta' AND  borrado = 0)
        )";

        if ($id_persona>0) {
            $query = $query . " AND persona = " . $id_persona;
        }
        $sql = mysqli_query($con, $query . ' ORDER BY persona, start');
        // echo($query);
        // Split de las guarias
        $guardias = array();
        while($row = mysqli_fetch_assoc($sql)){
            $split = split_guardias_en_dias($row);
            foreach ($split as $item) {
                array_push($guardias, $item);
            }
        }
        //var_dump($guardias);


        //filtro las que aplican (Sábado después de las 13:00, domingos y feriados)
        //Si hay una guardia que empiece antes de las 13 y termine después la corto
        $guardias_aplicables = array();
        foreach ($guardias as $key => $item) {
            $guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)] = array();
            $guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)]['guardias'] = array();
            //domingos y feriados
            if ($item['wd'] == "6" OR $item['dnl'] == "1") {
                array_push($guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)]['guardias'], $item);
            } else {
                //Sábados
                $startTime = strtotime($item['start']);
                $endTime = strtotime($item['end']);
                $condition = strtotime(substr($item['start'],0,10) . ' 13:00:00');

                if ($startTime >= $condition) {
                    // empieza despues de las 13hs
                    array_push($guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)]['guardias'], $item);
                } elseif ($startTime < $condition AND $endTime > $condition) {
                    // si pasa por el medio trunco y recalculo minutos
                    $item['start'] = substr($item['start'],0,10) . ' 13:00:00';
                    $item['min'] = intval((abs($endTime - $condition) / 60));
                    array_push($guardias_aplicables[$item['id_persona']][substr($item['start'],0,10)]['guardias'], $item);
                }
            }
        }

        // Por id
        foreach ($guardias_aplicables as $key => $guardia_aplicable) {
            // var_dump($guardia_aplicable);
            //Por fecha
            foreach ($guardia_aplicable as $keyfecha => $guardia_aplicable_fecha) {
                $_tot_min = 0;
                foreach ($guardia_aplicable_fecha['guardias'] as $keyitem => $item) {
                    $_tot_min += $item['min'];
                }
                $guardias_aplicables[$key][$keyfecha]['tot_min'] = $_tot_min;
                $_compensatorio = 1.0;
                if (($_tot_min / 60 ) < 4) {
                    $_compensatorio = 0.5;
                }
                $guardias_aplicables[$key][$keyfecha]['compensatorio'] = $_compensatorio;
            }
            // var_dump($guardia_aplicable);
        }         
    
        return $guardias_aplicables;
    }

    

?>