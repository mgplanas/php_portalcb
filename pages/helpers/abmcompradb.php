<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $legajo = $_POST['legajo'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cargo = $_POST['cargo'];
    $gerencia = $_POST['gerencia'];
    $subgerencia = $_POST['subgerencia'];
    $area = $_POST['area'];
    $email = $_POST['email'];
    $grupo = $_POST['grupo'];
    $contacto = $_POST['contacto'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_gerencia = mysqli_query($con, "INSERT INTO persona(legajo, nombre, apellido, cargo, gerencia,subgerencia,area, email, grupo, contacto, borrado) 
                                                VALUES ('$legajo','$nombre','$apellido', '$cargo', '$gerencia', '$subgerencia', '$area','$email', '$grupo', '$contacto', 0)") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_gerencia = mysqli_query($con, "UPDATE persona 
            SET legajo='$legajo', 
            nombre='$nombre', 
            apellido='$apellido', 
            cargo='$cargo', 
            gerencia='$gerencia', 
            subgerencia='$subgerencia', 
            area='$area', 
            email='$email' ,
            contacto='$contacto',
            grupo= '$grupo'
        WHERE id_persona='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            $update_gerencia = mysqli_query($con, "UPDATE persona SET borrado='1' WHERE id_persona='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>