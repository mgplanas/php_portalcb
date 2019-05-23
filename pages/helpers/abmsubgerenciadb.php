<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_gerencia = $_POST['id_gerencia'];
    $nombre = $_POST['nombre'];
    $sigla = $_POST['sigla'];
    $responsable = $_POST['responsable'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_subgerencia = mysqli_query($con, "INSERT INTO subgerencia(id_gerencia, nombre, sigla, responsable, borrado) 
                                                VALUES ('$id_gerencia', '$nombre','$sigla', '$responsable',  0)") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_gerencia = mysqli_query($con, "UPDATE subgerencia SET id_gerencia='$id_gerencia', nombre='$nombre', sigla='$sigla', responsable='$responsable' 
                                                    WHERE id_subgerencia='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            $update_subgerencia = mysqli_query($con, "UPDATE subgerencia SET borrado='1' WHERE id_subgerencia='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>