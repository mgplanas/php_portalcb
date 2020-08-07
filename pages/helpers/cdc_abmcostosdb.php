<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_cliente = $_POST['id_cliente'];
    $cliente = $_POST['cliente'];
    $fecha = $_POST['fecha'];
    $servicio = $_POST['servicio'];
    $meses_contrato = $_POST['meses_contrato'];
    $duracion = $_POST['duracion'];
    $costo_unica_vez = $_POST['costo_unica_vez'];
    $costo_recurrente = $_POST['costo_recurrente'];
    $cotizacion_usd = $_POST['cotizacion_usd'];
    $inflacion = $_POST['inflacion'];
    $cm = $_POST['cm'];
    $costeo = $_POST['costeo'];
    $oportunidad_comercial = $_POST['oportunidad_comercial'];
    $solicitud_servicio = $_POST['solicitud_servicio'];

    $gmtTimezone = new DateTimeZone('GMT');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha = date('Y-m-d',strtotime(str_replace('/', '-', $fecha)));

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_item = mysqli_query($con, "INSERT INTO cdc_costos(cliente,fecha,servicio,meses_contrato,duracion,inflacion,cm, cotizacion_usd, solicitud_servicio, oportunidad_comercial) 
                                                VALUES ('$cliente','$fecha','$servicio','$meses_contrato','$duracion','$inflacion','$cm', '$cotizacion_usd', '$solicitud_servicio', '$oportunidad_comercial')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $result->id = $id;
            mysqli_autocommit($con, false);
            $resultado = true;
            $upd_qry = "UPDATE cdc_costos SET costo_recurrente='$costo_recurrente', costo_unica_vez='$costo_unica_vez', cliente='$cliente',fecha='$fecha',servicio='$servicio',meses_contrato='$meses_contrato',duracion='$duracion',inflacion='$inflacion',cm='$cm', cotizacion_usd='$cotizacion_usd', solicitud_servicio='$solicitud_servicio', oportunidad_comercial='$oportunidad_comercial' WHERE id='$id'";
            $resultado = mysqli_query($con, $upd_qry);
            
            if ($resultado) {
              
                $resultado = mysqli_query($con, "DELETE FROM cdc_costos_detalle WHERE id_costo ='$id'");
                if ($resultado && $costeo) {
          
                    foreach ($costeo as $itc) {
                        $sDet = "INSERT INTO cdc_costos_detalle(id_costo_item, id_costo, costo_usd ,cantidad, costo_recurrente, costo_unica_vez) VALUES ";
                        $sDet .="(" . $itc['id_costo_item'] . ", " . $itc['id_costo'] . ", " . $itc['costo_usd'] . " ," . $itc['cantidad'] . ", " . $itc['costo_recurrente'] . ", '" . $itc['costo_unica_vez'] . "'); ";
                        
                        $resultado = mysqli_query($con, $sDet);
                    }
                }
            }
                              
            if ($resultado) {
              mysqli_commit($con);
            } else {
              mysqli_rollback($con);
            }
            mysqli_autocommit($con, true);
            break;

        case 'B':
            //UPDATE
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>