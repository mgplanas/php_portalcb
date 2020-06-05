<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    
    $id_costo_item = $_POST['id_costo_item']; 
    $id_costo = $_POST['id_costo']; 
    $costo_usd = $_POST['costo_usd']; 
    $cantidad = $_POST['cantidad']; 
    $costo_recurrente = $_POST['costo_recurrente']; 
    $costo_unica_vez = $_POST['costo_unica_vez'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_cliente = mysqli_query($con, "INSERT INTO cdc_costos_detalle(id_costo_item, id_costo, costo_usd ,cantidad, costo_recurrente, costo_unica_vez) 
                                                VALUES ('$id_costo_item', '$id_costo', '$costo_usd' ,'$cantidad', '$costo_recurrente', '$costo_unica_vez')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            // $update_clietne = mysqli_query($con, "UPDATE cdc_cliente SET id_organismo='$id_organismo', razon_social='$razon_social', nombre_corto='$nombre_corto', cuit='$cuit', sector='$sector' 
            //                                         WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            // $update_cliente = mysqli_query($con, "UPDATE cdc_cliente SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>