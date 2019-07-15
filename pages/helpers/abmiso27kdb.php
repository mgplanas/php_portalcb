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
	$implementacion = mysqli_real_escape_string($con,(strip_tags($_POST["implementacion"],ENT_QUOTES)));
	$evidencia = mysqli_real_escape_string($con,(strip_tags($_POST["evidencia"],ENT_QUOTES)));
    $codigo = mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));
    $user=mysqli_real_escape_string($con,(strip_tags($_POST["usuario"],ENT_QUOTES)));

    $result = new stdClass();
    $result->ok = false;

    mysqli_autocommit($con, false);
    $resultado = true;
  
    switch ($op) {
        case 'A':
            // INSERT
            // $insert_cliente = mysqli_query($con, "INSERT INTO cdc_cliente(id_organismo, razon_social, nombre_corto,cuit, sector) 
            //                                     VALUES ('$id_organismo', '$razon_social', '$nombre_corto','$cuit', '$sector')") or die(mysqli_error());	
            // $lastInsert = mysqli_insert_id($con);
            // $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
            //                                     VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            // $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $resultado = mysqli_query($con, "UPDATE item_iso27k SET parent='$subgrupo', responsable='$responsable', madurez='$madurez', implementacion='$implementacion', evidencia='$evidencia', modificado=NOW(), usuario='$user' WHERE id_item_iso27k='$id'");
            if ($resultado) {

                $resultado = mysqli_query($con, "DELETE FROM iso27k_refs WHERE id_item_iso27k ='$id'");
                if ($resultado) {

                    if (count($referentes,COUNT_NORMAL)>0) {
                        $sqlInsRef = "INSERT INTO iso27k_refs (id_item_iso27k, id_persona,  borrado) VALUES ";
                        $refCounter = 0;
                        foreach ($referentes as $ref) {
                            if ($refCounter > 0) $sqlInsRef .= ", ";
                            $sqlInsRef .= "('$id', '$ref', 0)";
                            $refCounter++;
                        }  
                        $resultado = mysqli_query($con, $sqlInsRef);
                    }
                    if ($resultado) {
                        $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                VALUES ('2', '6','$id', now(), '$user', '$codigo')");
                    }  
                }
            }

            // $update_clietne = mysqli_query($con, "UPDATE cdc_cliente SET id_organismo='$id_organismo', razon_social='$razon_social', nombre_corto='$nombre_corto', cuit='$cuit', sector='$sector' 
            //                                         WHERE id='$id'") or die(mysqli_error());	
            // $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
            //                                     VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            // $update_cliente = mysqli_query($con, "UPDATE cdc_cliente SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            // $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
            //                                     VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
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