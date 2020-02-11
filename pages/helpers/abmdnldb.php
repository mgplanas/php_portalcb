<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_area = mysqli_query($con, "INSERT INTO adm_dnl(fecha, descripcion, borrado) 
                                                VALUES ('$fecha', '$descripcion', 0)") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_area = mysqli_query($con, "UPDATE adm_dnl SET fecha='$fecha', descripcion='$descripcion' WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_area = mysqli_query($con, "UPDATE adm_dnl SET borrado=1 WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>