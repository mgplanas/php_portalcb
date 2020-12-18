<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_cliente = $_POST['id_cliente'];
    $m2 = $_POST['m2'];
    $sala = $_POST['sala'];
    $fila = $_POST['fila'];
    $rack = $_POST['rack'];
    $evidencia = $_POST['evidencia'];
    $energia = $_POST['energia'];
    $alta = $_POST['alta'];
    $observaciones = $_POST['observaciones'];
    $modalidad = $_POST['modalidad'];
    $telco = $_POST['telco'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_housing = mysqli_query($con, "INSERT INTO sdc_housing(id_cliente, m2, sala, fila, rack, energia, fecha_alta, evidencia, observaciones, modalidad, telco) 
                                                VALUES ('$id_cliente', '$m2', '$sala','$fila', '$rack', '$energia', '$alta', '$evidencia', '$observaciones', '$modalidad', '$telco')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_housing = mysqli_query($con, "UPDATE sdc_housing SET id_cliente='$id_cliente', m2='$m2', sala='$sala', fila='$fila', rack='$rack', energia='$energia', evidencia='$evidencia', fecha_alta='$alta', observaciones='$observaciones' , modalidad='$modalidad' , telco='$telco'
                                                    WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$id', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            $update_housing = mysqli_query($con, "UPDATE sdc_housing SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$id', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>