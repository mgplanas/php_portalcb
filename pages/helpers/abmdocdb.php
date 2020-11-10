<?php
    include("../../conexion.php");
    session_start();
    $user=$_SESSION['id_usuario'];
    $op = $_POST['operacion'];
    $id = mysqli_real_escape_string($con,(strip_tags($_POST['id'],ENT_QUOTES)));
    $tipodoc =mysqli_real_escape_string($con,(strip_tags($_POST['tipodoc'],ENT_QUOTES)));
    $version =mysqli_real_escape_string($con,(strip_tags($_POST['version'],ENT_QUOTES)));
    $nombre =mysqli_real_escape_string($con,(strip_tags($_POST['nombre'],ENT_QUOTES)));
    $doclink =mysqli_real_escape_string($con,(strip_tags($_POST['doclink'],ENT_QUOTES)));
    $owner =mysqli_real_escape_string($con,(strip_tags($_POST['owner'],ENT_QUOTES)));
    $area =mysqli_real_escape_string($con,(strip_tags($_POST['area'],ENT_QUOTES)));
    $vigencia =mysqli_real_escape_string($con,(strip_tags($_POST['vigencia'],ENT_QUOTES)));
    $frecuencia =mysqli_real_escape_string($con,(strip_tags($_POST['frecuencia'],ENT_QUOTES)));
    $next =mysqli_real_escape_string($con,(strip_tags($_POST['next'],ENT_QUOTES)));
    $periodicidad =mysqli_real_escape_string($con,(strip_tags($_POST['periodicidad'],ENT_QUOTES)));
    $forma =mysqli_real_escape_string($con,(strip_tags($_POST['forma'],ENT_QUOTES)));
    $comunicado =mysqli_real_escape_string($con,(strip_tags($_POST['comunicado'],ENT_QUOTES)));
    $proxima_actualizacion = mysqli_real_escape_string($con,(strip_tags($_POST['proxima_actualizacion'],ENT_QUOTES)));
    $aprobado_minuta = mysqli_real_escape_string($con,(strip_tags($_POST['aprobado_minuta'],ENT_QUOTES)));

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO doc_documentos (id_tipo,version,nombre,link,id_owner,id_area,vigencia,frecuencia_revision,proxima_actualizacion,id_periodicidad_com,id_forma_com,comunicado) VALUES ('$tipodoc','$version','$nombre','$doclink','$owner','$area','$vigencia','$frecuencia','$next','$periodicidad','$forma','$comunicado');";	
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            // UPDATE
            $sql = "UPDATE doc_documentos SET 
                id_tipo = '$tipodoc',
                version = '$version',
                nombre = '$nombre',
                link = '$doclink',
                id_owner = '$owner',
                id_area = '$area',
                vigencia = '$vigencia',
                frecuencia_revision = '$frecuencia',
                proxima_actualizacion = '$next',
                id_periodicidad_com = '$periodicidad',
                id_forma_com = '$forma',
                comunicado = '$comunicado' WHERE id='$id';";	
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
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