<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $id_organismo = ($_POST['id_organismo'] > 0 ? $_POST['id_organismo']: '');
    $razon_social = $_POST['razon_social'];
    $nombre_corto = $_POST['nombre_corto'];
    $cuit = $_POST['cuit'];
    $sector = $_POST['sector'];
    $convenio = $_POST['convenio'];
    $ejecutivo_cuenta = $_POST['ejecutivo_cuenta'];
    $con_servicio_correo = $_POST['servicio_correo'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_cliente = mysqli_query($con, "INSERT INTO cdc_cliente(id_organismo, razon_social, nombre_corto,cuit, sector, con_convenio, con_servicio_correo, ejecutivo_cuenta) 
                                                VALUES ('$id_organismo', '$razon_social', '$nombre_corto','$cuit', '$sector', '$convenio', '$con_servicio_correo', '$ejecutivo_cuenta')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_clietne = mysqli_query($con, "UPDATE cdc_cliente SET id_organismo='$id_organismo', razon_social='$razon_social', nombre_corto='$nombre_corto', cuit='$cuit', sector='$sector' , con_convenio='$convenio', con_servicio_correo='$con_servicio_correo', ejecutivo_cuenta='$ejecutivo_cuenta'
                                                    WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        case 'B':
            //UPDATE
            $update_cliente = mysqli_query($con, "UPDATE cdc_cliente SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>