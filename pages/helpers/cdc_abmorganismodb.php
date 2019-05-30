<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $razon_social = $_POST['razon_social'];
    $nombre_corto = $_POST['nombre_corto'];
    $cuit = $_POST['cuit'];
    $sector = $_POST['sector'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_organismo = mysqli_query($con, "INSERT INTO cdc_organismo(razon_social, nombre_corto,cuit, sector) 
                                                VALUES ('$razon_social', '$nombre_corto','$cuit', '$sector')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_organismo = mysqli_query($con, "UPDATE cdc_organismo SET razon_social='$razon_social', nombre_corto='$nombre_corto', cuit='$cuit', sector='$sector' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            $update_organismo = mysqli_query($con, "UPDATE cdc_organismo SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>