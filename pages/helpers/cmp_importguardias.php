<?php
    include("../../conexion.php");

    // SE INSERTAN LOS RESULTADOS EN LA BASE
    function insertarCompensaciones($periodo, $guardias_aplicables, $con){
        
        // Turn autocommit off
        mysqli_autocommit($con,FALSE);
        mysqli_begin_transaction($con);
        
        // ITERACCION POR LAS COMPENSACIONES
        foreach ($guardias_aplicables as $key_id_persona => $guardia_aplicable) {
            // var_dump($guardia_aplicable);
            //Por fecha
            foreach ($guardia_aplicable as $keyfecha => $guardia_aplicable_fecha) {
                
                $regla = '';
                if ($guardia_aplicable_fecha['guardias'][0]['dnl']=='1') { $regla='F'; 
                } elseif ($guardia_aplicable_fecha['guardias'][0]['dow']=='0') { $regla='D'; 
                } else { $regla = 'S'; }                
                
                // Agrego el balance
                $minutos = $guardias_aplicables[$key_id_persona][$keyfecha]['tot_min'];
                $dias = $guardias_aplicables[$key_id_persona][$keyfecha]['compensatorio'];
                
                $sqlCompensatorio = "INSERT INTO adm_cmp_balance (id_periodo, id_persona, fecha, tipo, dias, origen) VALUES ('$periodo', '$key_id_persona','$keyfecha', 'C', '$dias', '$regla')";
                if (!mysqli_query($con, $sqlCompensatorio)){
                    mysqli_rollback($con);
                    mysqli_autocommit($con,TRUE);
                    return false;
                }
                $id_balance = mysqli_insert_id($con);
                
                // Agrego las guardias que dieron origen
                foreach ($guardia_aplicable_fecha['guardias'] as $keyitem => $item) {
                    $sqlGuardias = "INSERT INTO adm_cmp_guardias (id_balance,id_persona, fecha_desde, fecha_hasta, minutos_efectivos, tipo, lote, justificacion) VALUES ('$id_balance', '$key_id_persona', '".$item['desde']."', '".$item['hasta']."', '$minutos', ".$item['g_e'].", 0, '".$item['justificacion']."')";
                    if (!mysqli_query($con, $sqlGuardias)){
                        mysqli_rollback($con);
                        mysqli_autocommit($con,TRUE);
                        return false;
                    }                    
                }
            }
        }         

        mysqli_commit($con);
        mysqli_autocommit($con,TRUE);
        return true;
    }

    // Verifica el periodo si existe, si no existe lo crea
    function verificarPeriodo($desde, $hasta, $con) {
        $id_periodo=0;
        $PerSQL = mysqli_query($con, "SELECT id FROM adm_cmp_periodos WHERE fecha_desde = '$desde'");
        $row = mysqli_fetch_assoc($PerSQL);
        if ($row) {
            $id_periodo = $row['id'];
        } else {
            $query = 'INSERT INTO adm_cmp_periodos (fecha_desde, fecha_hasta) VALUES ('.$desde.','.$hasta.');';
            $result = mysqli_query($con, $query);   
            $id_periodo = mysqli_insert_id($con);    
        }
        return $id_periodo;
    }

    // Devuelve el id_persona en base al legajo
    function getPersonIDByLegajo($legajo, $arr) {
        $id=0;
        foreach ($arr as $key => $value) {
            if ($value['legajo']==$legajo) {
                $id = $value['id_persona'];
            break;
            }
        }
        return $id;
    }

    // Chequo si es feriado
    function esFeriado($fecha, $dnl){
        $res = 0;
        foreach ($dnl as $key => $value) {
            if ($value['fecha']==$fecha) {
                $res = 1;
            break;
            }
        }
        return $res;
    }

    // Cálculo de compensacion.
    function calcularCompensacion($xlsData){

        //filtro las que aplican (Sábado después de las 13:00, domingos y feriados)
        //Si hay una guardia que empiece antes de las 13 y termine después la corto
        $guardias_aplicables = array();
        foreach ($xlsData as $key => $item) {
            $guardias_aplicables[$item['id_persona']][$item['dia']] = array();
            $guardias_aplicables[$item['id_persona']][$item['dia']]['guardias'] = array();
            $startTime = strtotime($item['desde']);
            $endTime = strtotime($item['hasta']);
            
            //domingos y feriados
            if ($item['dow'] == "0" OR $item['dnl'] == 1) {
                
                $item['min'] = intval((abs($endTime - $startTime) / 60));
                array_push($guardias_aplicables[$item['id_persona']][$item['dia']]['guardias'], $item);
            } else {
                //Sábados
                $condition = strtotime($item['dia'] . ' 13:00:00');

                if ($startTime >= $condition) {
                    // empieza despues de las 13hs
                    $item['min'] = intval((abs($endTime - $startTime) / 60));
                    array_push($guardias_aplicables[$item['id_persona']][$item['dia']]['guardias'], $item);
                } elseif ($startTime < $condition AND $endTime > $condition) {
                    // si pasa por el medio trunco y recalculo minutos
                    $item['start'] = $item['dia'] . ' 13:00:00';
                    $item['min'] = intval((abs($endTime - $condition) / 60));
                    array_push($guardias_aplicables[$item['id_persona']][$item['dia']]['guardias'], $item);
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

    //Cargo la lista de [id_persona,legajo]
    $arrIds=[];
    $idsPersonasSQL = mysqli_query($con, "SELECT id_persona, legajo FROM persona WHERE borrado = 0");
    while($row = mysqli_fetch_assoc($idsPersonasSQL)){
        $arrIds[] = $row;
    }
    //Cargo la lista de Feriados
    $arrDNL=[];
    $DNLSQL = mysqli_query($con, "SELECT fecha FROM adm_dnl WHERE  borrado = 0");
    while($row = mysqli_fetch_assoc($DNLSQL)){
        $arrDNL[] = $row;
    }

    $valid_extensions = array('xlsx'); // valid extensions

    $result = new stdClass();
    $result->ok = false;

    // ini_set('display_errors', '0');
    // if ($_POST['op'] == 'READ') {

    //     if($_FILES['image'])
    //     {
    //         error_log('HAY IMAGENES', 1, '/var/log/httpd/php_errors.log');
    //         $filename = $_FILES['image']['name'];
    //         $path = $_FILES['image']['tmp_name'];
    //         $size = $_FILES['image']['size'];
            
    //         // get uploaded file's extension
    //         $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    //         // check's valid format
    //         $result->state = 'VALIDATING INPUT FILE';
    //         if(in_array($ext, $valid_extensions)) { 
                
    //             if($size > 0) {

    //                 // $targets = 'C:/xampp/htdocs/test/' . basename($filename);
    //                 // move_uploaded_file($path, $targets);
            
    //                 //Borro la temporal
    //                 $sqlRes = mysqli_query($con, 'TRUNCATE TABLE adm_cmp_guardias_tmp;');
    //                 $result->state = 'INSERT DATA INTO TEMP TABLE';
                    
    //                 //Importo el excel
    //                 require('./SpreadsheetReader.php');
                
    //                 date_default_timezone_set('UTC');
                
    //                 $Spreadsheet = new SpreadsheetReader($path, $filename);
            
    //                 $Sheets = $Spreadsheet -> Sheets();
    //                 $arr = [];
    //                 $err =[];
    //                 $xlsData=[];
    //                 $id_periodo = 0;

    //                 // SOLAPA DE DISPONIBILIDAD
    //                 $Spreadsheet -> ChangeSheet(2);

    //                 $fila = 0;
    //                 foreach ($Spreadsheet as $Key => $Row) {
    //                     $fila++;
    //                     // EXTRAIGO EL PERIODO
    //                     if ($fila==8) {
    //                         try {
    //                             $periodo_desde = date_format(date_create_from_format('m-d-y H:i:s', $Row[4]. ' 00:00:00'), 'Y-m-d H:i:s');
    //                             $periodo_hasta = date_format(date_create_from_format('m-d-y H:i:s', $Row[7]. ' 23:59:59'), 'Y-m-d H:i:s');
    //                             $id_periodo = verificarPeriodo($periodo_desde, $periodo_hasta, $con);
    //                             if ($id_periodo==0) {
    //                                 array_push($err,'No se pudo encontrar/generar el período ['.$periodo_desde.' - ' . $periodo_hasta. ' ]');
    //                             }
    //                         } catch (\Throwable $th) {
    //                             array_push($err,'Error de formato en el período ['.$periodo_desde.' - ' . $periodo_hasta. ' ]');
    //                         }
    
    //                     }
                        
    //                     // EXTRAIGO LAS ACTIVACIONES
    //                     if ($Row && $Row[3]=='Si') {
    //                         // Voy metiendo la raw data en un array para después procesar
    //                         // Legajo, Nombre, Fecha Desde, Hora desde, Fecha Hasta, Hora hasta, Justificacion, [G(1)|E(2)]
    //                         array_push($xlsData, [$Row[0],$Row[1],$Row[5],$Row[6],$Row[8],$Row[9], $Row[15],1]);
    //                     }
    //                 }

    //                 // SOLAPA DE EMERGENCIAS
    //                 $Spreadsheet -> ChangeSheet(3);
    //                 $fila = 0;
    //                 foreach ($Spreadsheet as $Key => $Row) {
    //                     $fila++;
    //                     if ($fila>11 && $Row && $Row[0]!='') {
    //                         // Legajo, Nombre, Fecha Desde, Hora desde, Fecha Hasta, Hora hasta, Justificacion, [G(1)|E(2)]
    //                         array_push($xlsData, [$Row[0],$Row[1],$Row[4],$Row[5],$Row[7],$Row[8], $Row[13],2]);
    //                     }
    //                 }		

    //                 unset($Spreadsheet);

    //                 // PROCESO LOS DATOS OBTENIDOS
    //                 foreach ($xlsData as $key => $value) {
    //                     try {
    //                         $desde = date_format(date_create_from_format('m-d-y H:i', $value[2]. ' ' . $value[3]), 'Y-m-d H:i:s');
    //                         $hasta = date_format(date_create_from_format('m-d-y H:i', $value[4]. ' ' . $value[5]), 'Y-m-d H:i:s');
    //                         $startTime = strtotime($desde);
    //                         $endTime = strtotime($hasta);
    //                         $dayofweek = date('w', $startTime); //0-6 dom-sab
    //                         $min = intval(abs($endTime - $startTime) / 60);  
    //                     } catch (\Throwable $th) {
    //                         array_push($err,[$value[0],$value[1],$value[2],$value[3],$value[4],$value[5],$value[6],'Error de Formato']);
    //                     }
                        
    //                     // Filtro las que nos son ni Sábado o domingo o feriado
    //                     $esFeriado=esFeriado(substr($desde,0,10), $dnl);
    //                     $condition = strtotime(substr($desde,0,10) . ' 13:00:00');
    //                     if ($dayofweek==0 or ($dayofweek==6 and $endTime>$condition) or $esFeriado==1) {

    //                         // Busco el id de la persona
    //                         $id = getPersonIDByLegajo($value[0], $arrIds);
    //                         if ($id == 0 OR $id == null ) {
    //                             array_push($err,'No se encontró la persona ['.$value[1].'] con el legajo ['.$value[0].']');
    //                         } else {
    //                             array_push($arr, [
    //                                 'id_persona'=>$id,
    //                                 'dia'=>substr($desde,0,10),
    //                                 'desde'=>$desde,
    //                                 'hasta'=>$hasta,
    //                                 'startTime'=>$startTime,
    //                                 'endTime'=>$endTime,
    //                                 'dow'=>$dayofweek,
    //                                 'dnl'=>$esFeriado,
    //                                 'xls_fecha_desde'=>$value[2],
    //                                 'xls_hora_desde'=>$value[3],
    //                                 'xls_fecha_hasta'=>$value[4],
    //                                 'xls_hora_hasta'=>$value[5],
    //                                 'justificacion'=>$value[6],
    //                                 'g_e'=>$value[7]
    //                             ]);
    //                         }
    //                     }
                        
    //                 }
                    
    //                 $result->ok = (count($err)==0);
                    
    //                 // Calculo los días que corresponden a la guardia.
    //                 $resultado = calcularCompensacion($arr);
                    
    //                 // Inserto en la base
    //                 insertarCompensaciones($id_periodo, $resultado, $con);

    //                 $result->state = 'COMPENSATORIOS A SER AGREGADOS';
    //                 // cruzo los datos importados con los reales.
    //                 $result->compensatorios = $resultado;
    //                 $result->error = array_unique( $err );

    //             }
    //             else { $result->error = 'Archivo vacío'; }
    //         } 
    //         else { $result->error = 'Extensión inválida'; }
    //     }
    //     else { $result->error = 'invalid'; }
    // }


    // ini_set('display_errors', '1');
    echo json_encode($result);
        
?>