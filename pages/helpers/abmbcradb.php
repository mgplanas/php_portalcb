<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $version = $_POST['versionid'];
    
    
	$grupo = mysqli_real_escape_string($con,(strip_tags($_POST["grupo"],ENT_QUOTES)));
	$subgrupo = mysqli_real_escape_string($con,(strip_tags($_POST["subgrupo"],ENT_QUOTES)));
	$responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
        $referentes = (isset($_POST["referentes"]) ? $_POST["referentes"] : []);
	$madurez = mysqli_real_escape_string($con,(strip_tags($_POST["madurez"],ENT_QUOTES)));
	$descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
	$implementacion = mysqli_real_escape_string($con,(strip_tags($_POST["implementacion"],ENT_QUOTES)));
	$evidencia = mysqli_real_escape_string($con,(strip_tags($_POST["evidencia"],ENT_QUOTES)));
        $codigo = mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));
        $user=mysqli_real_escape_string($con,(strip_tags($_POST["usuario"],ENT_QUOTES)));
        $escenarios = mysqli_real_escape_string($con,(strip_tags($_POST["escenarios"],ENT_QUOTES)));
        $documentacion=mysqli_real_escape_string($con,(strip_tags($_POST["documentacion"],ENT_QUOTES)));

    $result = new stdClass();
    $result->ok = false;

    mysqli_autocommit($con, false);
    $resultado = true;
  
    switch ($op) {
        case 'A':
            // INSERT
            $resultado = mysqli_query($con, "INSERT INTO item_bcra (codigo,descripcion,madurez,implementacion,responsable,evidencia,modificado,borrado,usuario,escenarios, documentacion,nivel,parent,version) 
                                      VALUES ('$codigo', '$descripcion', '$madurez', '$implementacion', '$responsable', '$evidencia', NOW(), 0, '$user', '$escenarios', '$documentacion' , 2, '$subgrupo', '$version')");
            $result->err = $con->error;
            if ($resultado) {
                $lastInsert = mysqli_insert_id($con);

                if ($referentes && count($referentes,COUNT_NORMAL)>0) {
                    $sqlInsRef = "INSERT INTO bcra_refs (id_item_bcra, id_persona,  borrado) VALUES ";
                    $refCounter = 0;
                    foreach ($referentes as $ref) {
                        if ($refCounter > 0) $sqlInsRef .= ", ";
                        $sqlInsRef .= "('$lastInsert', '$ref', 0)";
                        $refCounter++;
                    }  
                    $resultado = mysqli_query($con, $sqlInsRef);
                    $result->err = $con->error;
                }
                if ($resultado) {
                    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                            VALUES ('1', '6','$id', now(), '$user', '$codigo')");
                }  
            }

            
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $resultado = mysqli_query($con, "UPDATE item_bcra SET codigo='$codigo',  descripcion='$descripcion', parent='$subgrupo', responsable='$responsable', madurez='$madurez', implementacion='$implementacion', escenarios='$escenarios', documentacion='$documentacion', evidencia='$evidencia', modificado=NOW(), usuario='$user' WHERE id_item_bcra='$id'");
            $result->err = $con->error;
            if ($resultado) {

                $resultado = mysqli_query($con, "DELETE FROM bcra_refs WHERE id_item_bcra ='$id'");
                $result->err = $con->error;
                if ($resultado) {

                    if ($referentes && count($referentes,COUNT_NORMAL)>0) {
                        $sqlInsRef = "INSERT INTO bcra_refs (id_item_bcra, id_persona,  borrado) VALUES ";
                        $refCounter = 0;
                        foreach ($referentes as $ref) {
                            if ($refCounter > 0) $sqlInsRef .= ", ";
                            $sqlInsRef .= "('$id', '$ref', 0)";
                            $refCounter++;
                        }  
                        $resultado = mysqli_query($con, $sqlInsRef);
                        $result->err = $con->error;
                    }
                    if ($resultado) {
                        $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                VALUES ('2', '6','$id', now(), '$user', '$codigo')");
                    }  
                }
            }
            break;

        case 'B':
            //UPDATE
            $resultado = mysqli_query($con, "UPDATE item_bcra SET borrado='1' WHERE id_item_bcra='$id'");
            $result->err = $con->error;
            if ($resultado) {
                $resultado = mysqli_query($con, "DELETE FROM bcra_refs WHERE id_item_bcra ='$id'");
                $result->err = $con->error;
                if ($resultado) {
                    $resultado = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                        VALUES ('3', '6', '$lastInsert', now(), '$user')");
                }                
            }
            break;

        default:
            break;
    }

    // AGREG LA ACTUALIZACION DE LA FECHA DE LA VERSION
    if ($resultado) {
        $resultado = mysqli_query($con, "UPDATE bcra_version SET modificacion=NOW() WHERE id='$version'");
        $result->err = $con->error;
    }

    if ($resultado) {
        mysqli_commit($con);
    } else {
        mysqli_rollback($con);
    }
    mysqli_autocommit($con, true);
    
    $result->ok = $resultado;
    $result->version = $version;


    echo json_encode($result);

?>