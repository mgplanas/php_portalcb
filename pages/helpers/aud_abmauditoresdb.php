<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_ente = $_POST['id_ente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO aud_auditores(id_ente, nombre, dni, apellido) VALUES ('$id_ente', '$nombre', '$dni', '$apellido')";
            $insert_ente = mysqli_query($con, $sql) or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_ente = mysqli_query($con, "UPDATE aud_auditores SET nombre='$nombre',  dni='$dni', apellido='$apellido' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_ente = mysqli_query($con, "UPDATE aud_auditores SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>