<?php
    include("../../conexion.php");
    session_start();
    $user=$_SESSION['id_usuario'];
    $op = $_POST['operacion'];

    $id = mysqli_real_escape_string($con,(strip_tags($_POST['id'],ENT_QUOTES)));
    $fecha = mysqli_real_escape_string($con,(strip_tags($_POST['fecha'],ENT_QUOTES)));
    $solicitud = mysqli_real_escape_string($con,(strip_tags($_POST['solicitud'],ENT_QUOTES)));
    $concepto = mysqli_real_escape_string($con,(strip_tags($_POST['concepto'],ENT_QUOTES)));
    $presupuesto = mysqli_real_escape_string($con,(strip_tags($_POST['presupuesto'],ENT_QUOTES)));
    $plazo = mysqli_real_escape_string($con,(strip_tags($_POST['plazo'],ENT_QUOTES)));
    $gerencia = mysqli_real_escape_string($con,(strip_tags($_POST['gerencia'],ENT_QUOTES)));
    $subgerencia = mysqli_real_escape_string($con,(strip_tags($_POST['subgerencia'],ENT_QUOTES)));
    $solicitante = mysqli_real_escape_string($con,(strip_tags($_POST['solicitante'],ENT_QUOTES)));
    $moneda = mysqli_real_escape_string($con,(strip_tags($_POST['moneda'],ENT_QUOTES)));
    $capexopex = mysqli_real_escape_string($con,(strip_tags($_POST['capexopex'],ENT_QUOTES)));
    $plazo_unidad = mysqli_real_escape_string($con,(strip_tags($_POST['plazo_unidad'],ENT_QUOTES)));

    // $fecha_limite= mysqli_real_escape_string($con,(strip_tags($_POST['fecha_limite'],ENT_QUOTES)));
    $id_estado= mysqli_real_escape_string($con,(strip_tags($_POST['id_estado'],ENT_QUOTES)));
    $id_paso_actual= mysqli_real_escape_string($con,(strip_tags($_POST['id_paso_actual'],ENT_QUOTES)));
    $id_paso_actual_original= mysqli_real_escape_string($con,(strip_tags($_POST['id_paso_actual_original'],ENT_QUOTES)));
    $fecha_oc= mysqli_real_escape_string($con,(strip_tags($_POST['fecha_oc'],ENT_QUOTES)));
    $fecha_fin_contrato= mysqli_real_escape_string($con,(strip_tags($_POST['fecha_fin_contrato'],ENT_QUOTES)));
    $nro_oc= mysqli_real_escape_string($con,(strip_tags($_POST['nro_oc'],ENT_QUOTES)));
    $oc_monto= mysqli_real_escape_string($con,(strip_tags($_POST['oc_monto'],ENT_QUOTES)));
    $oc_id_moneda= mysqli_real_escape_string($con,(strip_tags($_POST['oc_id_moneda'],ENT_QUOTES)));
    $id_proveedor= mysqli_real_escape_string($con,(strip_tags($_POST['id_proveedor'],ENT_QUOTES)));
    $id_proceso= mysqli_real_escape_string($con,(strip_tags($_POST['id_proceso'],ENT_QUOTES)));
    // $tags= mysqli_real_escape_string($con,(strip_tags($_POST['tags'],ENT_QUOTES)));

    $gmtTimezone = new DateTimeZone('GMT');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $dtfechaSol = date('Y-m-d',strtotime(str_replace('/', '-', $fecha)));
    if ($fecha_oc) { $fecha_oc = date('Y-m-d',strtotime(str_replace('/', '-', $fecha_oc))); };
    if ($fecha_fin_contrato) { 
        $fecha_fin_contrato = date('Y-m-d',strtotime(str_replace('/', '-', $fecha_fin_contrato))); 
    } else {
        $fecha_fin_contrato = new DateTime($fecha_oc);;
        date_add($fecha_fin_contrato, date_interval_create_from_date_string($plazo . ' ' . ($plazo_unidad=='1' ? 'months' : 'years')));
        $fecha_fin_contrato = $fecha_fin_contrato->format('Y-m-d');
    };
    
    $dtfechaSol = date('Y-m-d',strtotime(str_replace('/', '-', $fecha)));

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO adm_compras(id_gerencia,id_subgerencia,nro_solicitud,concepto,pre_id_moneda,pre_monto,id_solicitante,fecha_solicitud,fecha_limite,capex_opex,modificado,modif_user,plazo_unidad,plazo_valor,id_estado,id_paso_actual,fecha_oc,nro_oc,oc_monto,oc_id_moneda,id_proveedor,id_proceso, fecha_fin_contrato
             ) VALUES (
                '$gerencia'
                ,'$subgerencia'
                ,'$solicitud'
                ,'$concepto'
                ,'$moneda'
                ,'$presupuesto'
                ,'$solicitante'
                ,'$dtfechaSol'
                ,'$dtfechaSol'
                ,'$capexopex'
                , now()
                , $user
                ,'$plazo_unidad'
                ,'$plazo'
                ,'$id_estado'
                ,'$id_paso_actual'
                ,'$fecha_oc'
                ,'$nro_oc'
                ,'$oc_monto'
                ,'$oc_id_moneda'
                ,'$id_proveedor'
                ,'$id_proceso'                
                ,'$fecha_fin_contrato'                
                )";	
                
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
            $lastInsert = mysqli_insert_id($con);
            
            // agrego historico de pasos
            $sql = "INSERT INTO adm_compras_pasos_hist(id_compra ,id_paso ,fecha ,id_persona
                    ) VALUES ('$lastInsert'
                    ,'$id_paso_actual'
                    ,now()
                    , $user)";
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
            
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
                ,id_proveedor = '$id_proveedor'
                ,id_proceso = '$id_proceso'
                ,id_paso_actual = '$id_paso_actual'
                ,id_gerencia = '$gerencia'
                ,id_estado = '$id_estado'
                ,fecha_solicitud = '$dtfechaSol'
                ,fecha_oc = '$fecha_oc'
                ,concepto = '$concepto'
                ,capex_opex = '$capexopex' 
                ,fecha_fin_contrato = '$fecha_fin_contrato' 
                WHERE id='$id'";
            $lastInsert = mysqli_query($con,$sql) or die(mysqli_error());	

            if ($id_paso_actual != $id_paso_actual_original) {
                // agrego historico de pasos
                $sql = "INSERT INTO adm_compras_pasos_hist(id_compra ,id_paso ,fecha ,id_persona
                        ) VALUES ('$lastInsert'
                        ,'$id_paso_actual'
                        ,now()
                        , $user)";
                $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());                
            }

            break;

        case 'B':
            //UPDATE
            $update_gerencia = mysqli_query($con, "UPDATE adm_compras SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>