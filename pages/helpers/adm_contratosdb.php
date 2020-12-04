<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_proveedor = $_POST['id_proveedor'];
    $id_subgerencia = $_POST['id_subgerencia'];
    $oc = $_POST['oc'];
    $tipo_mantenimiento = $_POST['tipo_mantenimiento'];
    $dtvto = $_POST['vencimiento'];
    $criticidad = $_POST['criticidad'];

    $gmtTimezone = new DateTimeZone('GMT');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $vencimiento = date('Y-m-d',strtotime(str_replace('/', '-', $dtvto)));

    $result = new stdClass();
    $result->ok = false;
    $result->sql = "";
    switch ($op) {
        case 'A':
            // INSERT
            $result->sql = "INSERT INTO adm_contratos_vto (id_proveedor, id_subgerencia, oc, tipo_mantenimiento, vencimiento, criticidad)
            VALUES ('$id_proveedor', '$id_subgerencia', '$oc', '$tipo_mantenimiento', '$vencimiento', '$criticidad')";
            $insert_contrato = mysqli_query($con, 
                "INSERT INTO adm_contratos_vto (id_proveedor, id_subgerencia, oc, tipo_mantenimiento, vencimiento, criticidad)
                 VALUES ('$id_proveedor', '$id_subgerencia', '$oc', '$tipo_mantenimiento', '$vencimiento', '$criticidad')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $result->sql ="UPDATE adm_contratos_vto SET id_proveedor='$id_proveedor', id_subgerencia='$id_subgerencia', oc='$oc', tipo_mantenimiento='$tipo_mantenimiento', vencimiento='$vencimiento' , criticidad='$criticidad'
            WHERE id='$id'";
            $update_contrato = mysqli_query($con, "UPDATE adm_contratos_vto SET id_proveedor='$id_proveedor', id_subgerencia='$id_subgerencia', oc='$oc', tipo_mantenimiento='$tipo_mantenimiento', vencimiento='$vencimiento' , criticidad='$criticidad'
                                                    WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_contrato = mysqli_query($con, "UPDATE adm_contratos_vto SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>