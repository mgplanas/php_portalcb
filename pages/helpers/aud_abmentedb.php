<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $razon_social = $_POST['razon_social'];
    $observaciones = $_POST['observaciones'];
    $cuit = $_POST['cuit'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO aud_entes(razon_social, cuit, observaciones) 
            VALUES ('$razon_social', '$cuit', '$observaciones')";
            $insert_ente = mysqli_query($con, $sql) or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_ente = mysqli_query($con, "UPDATE aud_entes SET razon_social='$razon_social',  cuit='$cuit', observaciones='$observaciones' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_ente = mysqli_query($con, "UPDATE aud_entes SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>