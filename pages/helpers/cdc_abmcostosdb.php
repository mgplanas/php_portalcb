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

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_item = mysqli_query($con, "INSERT INTO cdc_costos(cliente,fecha,servicio,meses_contrato,duracion,inflacion,cm) 
                                                VALUES ('$cliente','$fecha','$servicio','$meses_contrato','$duracion','$inflacion','$cm')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            // $update_clietne = mysqli_query($con, "UPDATE cdc_costos_detalle SET cantidad='$cantidad', costo_recurrente='$costo_recurrente', costo_unica_vez='$costo_unica_vez' 
                                                //   WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            // $removeCosteo = mysqli_query($con, "UPDATE cdc_costos_detalle SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>