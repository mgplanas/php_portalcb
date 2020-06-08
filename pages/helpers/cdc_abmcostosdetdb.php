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
            $result->id_costo_item = $id_costo_item;
            $result->id_costo = $id_costo;
            $result->costo_usd = $costo_usd;
            $result->cantidad = $cantidad;
            $result->costo_recurrente = $costo_recurrente;
            $result->costo_unica_vez = $costo_unica_vez;
            break;
        
        case 'M':
            //UPDATE
            $update_clietne = mysqli_query($con, "UPDATE cdc_costos_detalle SET cantidad='$cantidad', costo_recurrente='$costo_recurrente', costo_unica_vez='$costo_unica_vez' 
                                                  WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $removeCosteo = mysqli_query($con, "UPDATE cdc_costos_detalle SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>