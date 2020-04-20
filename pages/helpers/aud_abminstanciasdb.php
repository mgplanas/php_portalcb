<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $observaciones = $_POST['observaciones'];
    $descripcion = $_POST['descripcion'];
    $inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $gmtTimezone = new DateTimeZone('GMT');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_inicio = date('Y-m-d',strtotime(str_replace('/', '-', $inicio)));
    if ($fecha_fin) { $fecha_fin = date('Y-m-d',strtotime(str_replace('/', '-', $fecha_fin))); };

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO aud_instancias (nombre, descripcion, fecha_inicio, fecha_fin, observaciones) VALUES ('$nombre','$descripcion','$fecha_inicio', '$fecha_fin', '$observaciones');";
            $insert_ente = mysqli_query($con, $sql) or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_ente = mysqli_query($con, "UPDATE aud_instancias SET nombre='$nombre',  descripcion='$descripcion', fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', observaciones='$observaciones' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_ente = mysqli_query($con, "UPDATE aud_instancias SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>