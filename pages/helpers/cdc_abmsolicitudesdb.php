<?php
    include("../../conexion.php");
    session_start();
    $user=$_SESSION['id_usuario'];
    $op = $_POST['operacion'];

    $id = mysqli_real_escape_string($con,(strip_tags($_POST['id'],ENT_QUOTES)));
    $id_cliente = mysqli_real_escape_string($con,(strip_tags($_POST['id_cliente'],ENT_QUOTES)));
    $fecha = mysqli_real_escape_string($con,(strip_tags($_POST['fecha'],ENT_QUOTES)));
    $titulo = mysqli_real_escape_string($con,(strip_tags($_POST['titulo'],ENT_QUOTES)));
    $estado = mysqli_real_escape_string($con,(strip_tags($_POST['estado'],ENT_QUOTES)));
    $convenio = mysqli_real_escape_string($con,(strip_tags($_POST['convenio'],ENT_QUOTES)));
    $propuesta = mysqli_real_escape_string($con,(strip_tags($_POST['propuesta'],ENT_QUOTES)));
    $propuesta_detalle = mysqli_real_escape_string($con,(strip_tags($_POST['propuesta_detalle'],ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST['descripcion'],ENT_QUOTES)));
    $ss = mysqli_real_escape_string($con,(strip_tags($_POST['ss'],ENT_QUOTES)));
    $chw = mysqli_real_escape_string($con,(strip_tags($_POST['chw'],ENT_QUOTES)));
    $chw_detalle = mysqli_real_escape_string($con,(strip_tags($_POST['chw_detalle'],ENT_QUOTES)));
    $tiene_sc = mysqli_real_escape_string($con,(strip_tags($_POST['tiene_sc'],ENT_QUOTES)));
    $sc = mysqli_real_escape_string($con,(strip_tags($_POST['sc'],ENT_QUOTES)));
    $costo = mysqli_real_escape_string($con,(strip_tags($_POST['costo'],ENT_QUOTES)));
    $solicitante = mysqli_real_escape_string($con,(strip_tags($_POST['solicitante'],ENT_QUOTES)));
    $contactos = mysqli_real_escape_string($con,(strip_tags($_POST['contactos'],ENT_QUOTES)));
    $requirente = mysqli_real_escape_string($con,(strip_tags($_POST['requirente'],ENT_QUOTES)));
    
    $gmtTimezone = new DateTimeZone('GMT');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $dtfechaSol = date('Y-m-d',strtotime(str_replace('/', '-', $fecha)));

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO cdc_solicitudes(
                fecha
                ,id_cliente
                ,estado
                ,tiene_convenio
                ,tiene_pc
                ,pc_descripcion
                ,titulo
                ,descripcion
                ,ss
                ,compra_hw
                ,descripcion_compra
                ,tiene_sc
                ,sc_numero
                ,costo
                ,nombre_solicitante
                ,contacto_solicitante
                ,requirente
            ) VALUES (
                '$dtfechaSol',
                '$id_cliente',
                '$estado',
                '$convenio',
                '$propuesta',
                '$propuesta_detalle',
                '$titulo',
                '$descripcion',
                '$ss',
                '$chw',
                '$chw_detalle',
                '$tiene_sc',
                '$sc',
                '$costo',
                '$solicitante',
                '$contactos',
                '$requirente'                
            )";	
                
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
            $lastInsert = mysqli_insert_id($con);
            
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            // SET tags = '$tags'
            $sql ="UPDATE cdc_solicitudes
            SET 
                fecha = '$dtfechaSol',
                id_cliente = '$id_cliente',
                estado = '$estado',
                tiene_convenio = '$convenio',
                tiene_pc = '$propuesta',
                pc_descripcion = '$propuesta_detalle',
                titulo = '$titulo',
                descripcion = '$descripcion',
                ss = '$ss',
                compra_hw = '$chw',
                descripcion_compra = '$chw_detalle',
                tiene_sc = '$tiene_sc',
                sc_numero = '$sc',
                costo = '$costo',
                nombre_solicitante = '$solicitante',
                requirente = '$requirente',
                contacto_solicitante = '$contactos'            
                WHERE id='$id'";
            $lastInsert = mysqli_query($con,$sql) or die(mysqli_error());	

            break;

        case 'B':
            //UPDATE
            $update_gerencia = mysqli_query($con, "UPDATE cdc_solicitudes SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>