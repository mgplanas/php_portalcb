<?php
    include("../../conexion.php");
    session_start();
    $user=$_SESSION['id_usuario'];
    $op = $_POST['operacion'];

    $id = mysqli_real_escape_string($con,(strip_tags($_POST['id'],ENT_QUOTES)));
    $proxima_actualizacion = mysqli_real_escape_string($con,(strip_tags($_POST['proxima_actualizacion'],ENT_QUOTES)));
    $version = mysqli_real_escape_string($con,(strip_tags($_POST['version'],ENT_QUOTES)));
    $aprobado_minuta = mysqli_real_escape_string($con,(strip_tags($_POST['aprobado_minuta'],ENT_QUOTES)));

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "";	
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'R':
            //Review
            $sql ="UPDATE doc_documentos 
            SET vigencia = NOW()
                ,revisado = NOW()
                ,revisado_por = '$user'
                ,proxima_actualizacion = '$proxima_actualizacion'
                WHERE id='$id'";
            $lastInsert = mysqli_query($con,$sql) or die(mysqli_error());	
            break;

        case 'P':
            //Aprobar
            $sql ="UPDATE doc_documentos 
            SET version = '$version'
                ,aprobado = NOW()
                ,aprobado_por = '$user'
                ,aprobado_minuta = '$aprobado_minuta'
                WHERE id='$id'";
            $lastInsert = mysqli_query($con,$sql) or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_gerencia = mysqli_query($con, "UPDATE doc_documentos SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>