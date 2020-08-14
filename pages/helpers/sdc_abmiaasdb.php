<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_cliente = $_POST['id_cliente'];
    $plataforma = $_POST['plataforma'];
    $reserva = $_POST['reserva'];
    $ram_capacidad = $_POST['ram_capacidad'];
    $ram_uso = $_POST['ram_uso'];
    $storage_capacidad = $_POST['storage_capacidad'];
    $storage_uso = $_POST['storage_uso'];
    $observaciones = $_POST['observaciones'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_iaas = mysqli_query($con, "INSERT INTO sdc_iaas(id_cliente,plataforma,reserva,ram_capacidad,ram_uso,storage_capacidad,storage_uso,observaciones) 
                                                VALUES ('$id_cliente','$plataforma','$reserva','$ram_capacidad','$ram_uso','$storage_capacidad','$storage_uso','$observaciones')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_iaas = mysqli_query($con, "UPDATE sdc_iaas SET id_cliente = '$id_cliente',plataforma = '$plataforma',reserva = '$reserva',ram_capacidad = '$ram_capacidad',ram_uso = '$ram_uso',storage_capacidad = '$storage_capacidad',storage_uso = '$storage_uso',observaciones = '$observaciones' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_iaas = mysqli_query($con, "UPDATE sdc_iaas SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>