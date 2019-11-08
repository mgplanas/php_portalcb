<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $solicitud = $_POST['solicitud'];
    $concepto = $_POST['concepto'];
    $presupuesto = $_POST['presupuesto'];
    $plazo = $_POST['plazo'];
    $gerencia = $_POST['gerencia'];
    $subgerencia = $_POST['subgerencia'];
    $solicitante = $_POST['solicitante'];
    $moneda = $_POST['moneda'];
    $capexopex = $_POST['capexopex'];
    $plazo_unidad = $_POST['plazo_unidad'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO adm_compras(
               id_gerencia
               ,id_subgerencia
               ,nro_solicitud
               ,concepto
               ,pre_id_moneda
               ,pre_monto
               ,id_solicitante
               ,id_paso_actual
               ,id_estado
               ,fecha_solicitud
               ,fecha_limite
               ,capex_opex
               ,modificado
               ,modif_user
               ,plazo_unidad
               ,plazo_valor
             ) VALUES ('$gerencia','$subgerencia','$solicitud', '$concepto', '$moneda', '$presupuesto', '$solicitante','1', '1', '$fecha', '$fecha', '$capexopex', now(), 1, '$plazo_unidad', '$plazo')";	
                
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
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