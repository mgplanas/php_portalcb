<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $capacidad_fisica = $_POST['capacidad_fisica'];
    $asignacion_recomendada = $_POST['asignacion_recomendada'];
    $asignacion_max = $_POST['asignacion_max'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_iaas = mysqli_query($con, "INSERT INTO sdc_storage( nombre, categoria, capacidad_fisica_tb,per_asignacion_recomendado	,per_estimado_asignacion_max) 
                                                VALUES ('$nombre','$categoria','$capacidad_fisica','$asignacion_recomendada','$asignacion_max')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_iaas = mysqli_query($con, "UPDATE sdc_storage SET id_cliente = '$id_cliente',plataforma = '$plataforma',reserva = '$reserva',ram_capacidad = '$ram_capacidad',ram_uso = '$ram_uso',storage_capacidad = '$storage_capacidad',storage_uso = '$storage_uso',observaciones = '$observaciones' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_iaas = mysqli_query($con, "UPDATE sdc_storage SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>