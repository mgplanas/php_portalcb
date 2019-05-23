<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_subgerencia = $_POST['id_subgerencia'];
    $nombre = $_POST['nombre'];
    $sigla = $_POST['sigla'];
    $responsable = $_POST['responsable'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_area = mysqli_query($con, "INSERT INTO area(id_subgerencia, nombre, sigla, responsable, borrado) 
                                                VALUES ('$id_subgerencia', '$nombre','$sigla', '$responsable',  0)") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_area = mysqli_query($con, "UPDATE area SET id_subgerencia='$id_subgerencia', nombre='$nombre', sigla='$sigla', responsable='$responsable' 
                                                    WHERE id_subgerencia='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            $update_area = mysqli_query($con, "UPDATE area SET borrado='1' WHERE id_subgerencia='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>