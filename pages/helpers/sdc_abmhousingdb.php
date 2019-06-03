<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_cliente = $_POST['id_cliente'];
    $m2 = $_POST['m2'];
    $sala = $_POST['sala'];
    $fila = $_POST['fila'];
    $rack = $_POST['rack'];
    $observaciones = $_POST['observaciones'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_housing = mysqli_query($con, "INSERT INTO sdc_housing(id_cliente, m2, sala, fila, rack, observaciones) 
                                                VALUES ('$id_cliente', '$m2', '$sala','$fila', '$rack', '$observaciones')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_housing = mysqli_query($con, "UPDATE sdc_housing SET id_cliente='$id_cliente', m2='$m2', sala='$sala', fila='$fila', rack='$rack', observaciones='$observaciones' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            $update_housing = mysqli_query($con, "UPDATE sdc_housing SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>