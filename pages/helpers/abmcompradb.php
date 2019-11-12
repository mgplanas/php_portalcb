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

    // $fecha_limite= $_POST['fecha_limite'];
    $id_estado= $_POST['id_estado'];
    $id_paso_actual= $_POST['id_paso_actual'];
    $id_siguiente_paso= $_POST['id_siguiente_paso'];
    $fecha_oc= $_POST['fecha_oc'];
    $nro_oc= $_POST['nro_oc'];
    $oc_monto= $_POST['oc_monto'];
    $oc_id_moneda= $_POST['oc_id_moneda'];
    $id_proveedor= $_POST['id_proveedor'];
    $id_proceso= $_POST['id_proceso'];
    // $tags= $_POST['tags'];



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
            // SET tags = '$tags'
            $sql ="UPDATE adm_compras 
            SET pre_monto = '$presupuesto'
                ,pre_id_moneda = '$moneda'
                ,plazo_valor = '$plazo'
                ,plazo_unidad = '$plazo_unidad'
                ,oc_monto = '$oc_monto'
                ,oc_id_moneda = '$oc_id_moneda'
                ,nro_solicitud = '$solicitud'
                ,nro_oc = '$nro_oc'
                ,modificado = now()
                ,modif_user = 1
                ,id_subgerencia = '$subgerencia'
                ,id_solicitante = '$solicitante'
                ,id_siguiente_paso = '$id_siguiente_paso'
                ,id_proveedor = '$id_proveedor'
                ,id_proceso = '$id_proceso'
                ,id_paso_actual = '$id_paso_actual'
                ,id_gerencia = '$gerencia'
                ,id_estado = '$id_estado'
                ,fecha_solicitud = '$fecha'
                ,fecha_oc = " . ($fecha_oc ? $fecha_oc : 'null') . "
                ,concepto = '$concepto'
                ,capex_opex = '$capexopex' 
                WHERE id='$id'";
            $lastInsert = mysqli_query($con,$sql) or die(mysqli_error());	
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