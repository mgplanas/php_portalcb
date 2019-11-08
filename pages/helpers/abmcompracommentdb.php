<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id_compra = $_POST['id_compra'];
    $id_persona = $_POST['id_persona'];
    $comentario = $_POST['comentario'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_compra = mysqli_query($con, "INSERT INTO adm_compras_comments(id_compra, id_tipo, comentario, fecha, id_persona) 
                                                VALUES ('$id_compra','1','$comentario', now(), '$id_persona')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            break;

        case 'B':
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>