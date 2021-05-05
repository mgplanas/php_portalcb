<?php
    include("../../../conexion.php");

    $op = $_POST['operacion'];
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $fecha_inicio = mysqli_real_escape_string($con, $_POST['fecha_inicio']);
    $color = isset($_POST['color']) ? mysqli_real_escape_string($con, $_POST['color']) : '';
    $descripcion = isset($_POST['descripcion']) ? mysqli_real_escape_string($con, $_POST['descripcion']) : '';
    $estado = isset($_POST['estado']) ? mysqli_real_escape_string($con, $_POST['estado']) : 1;
    $fecha_fin = isset($_POST['fecha_fin']) ? mysqli_real_escape_string($con, $_POST['fecha_fin']) : null;
    $id_persona = isset($_POST['id_persona']) ? mysqli_real_escape_string($con, $_POST['id_persona']) : null;
    $id_personas = $_POST['id_personas'];
    $is_all_day = isset($_POST['is_all_day']) ? mysqli_real_escape_string($con, $_POST['is_all_day']) : 1;
    $is_background = isset($_POST['is_background']) ? mysqli_real_escape_string($con, $_POST['is_background']) : 0;
    $is_programmed = isset($_POST['is_programmed']) ? mysqli_real_escape_string($con, $_POST['is_programmed']) : 0;
    $observaciones = isset($_POST['observaciones']) ? mysqli_real_escape_string($con, $_POST['observaciones']) : '';
    $subtipo = isset($_POST['subtipo']) ? mysqli_real_escape_string($con, $_POST['subtipo']) : null;
    $tipo = isset($_POST['tipo']) ? mysqli_real_escape_string($con, $_POST['tipo']) : 1;

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'ADD_GUARDIAS_MULTIPLES':
            $sql = "INSERT INTO adm_eventos_cal (borrado,color,descripcion,estado,fecha_fin,fecha_inicio,id,id_persona,is_all_day,is_background,is_programmed,observaciones,subtipo,tipo)
                    VALUES ";
            foreach ($id_personas as $id_persona) {
                $sql .= "(0,'$color','$descripcion','$estado','$fecha_fin','$fecha_inicio','$id','$id_persona','$is_all_day','$is_background','$is_programmed','$observaciones','$subtipo','$tipo'),";
            }
            $sql = rtrim($sql, ",");

            // INSERT
            $insert_evento = mysqli_query($con, $sql) or die(mysqli_error());	
            break;
    
        case 'UPDATE_GUARDIA':
            //UPDATE
            $update_guadia = mysqli_query($con, "UPDATE adm_eventos_cal SET color= '$color', descripcion= '$descripcion', estado= '$estado', fecha_fin= '$fecha_fin', fecha_inicio= '$fecha_inicio', id= '$id', id_persona= '$id_persona', is_all_day= '$is_all_day', is_background= '$is_background', is_programmed= '$is_programmed', observaciones= '$observaciones', subtipo= '$subtipo', tipo= '$tipo' 
                                                    WHERE id='$id'") or die(mysqli_error());	
            break;
    
        case 'REMOVE_GUARDIA':
            //UPDATE
            $update_area = mysqli_query($con, "UPDATE adm_eventos_cal SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;            
        
        default:
            break;
    }

    $result->ok = true;

    echo json_encode($result);

?>