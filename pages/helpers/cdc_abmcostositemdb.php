<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $parent = $_POST['parent'];
    $nivel = $_POST['nivel'];
    $descripcion = $_POST['descripcion'];
    $unidad = $_POST['unidad'];
    $costo_usd = $_POST['costo_usd']; 
    $oculto = $_POST['oculto']; 
    $observaciones = $_POST['observaciones']; 
    $descripcion_item = $_POST['descripcion_item']; 

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_item = mysqli_query($con, "INSERT INTO cdc_costos_items(parent, nivel, descripcion, unidad, costo_unidad, observaciones, oculto, descripcion_item, orden) 
                                                SELECT '$parent', '$nivel', '$descripcion', '$unidad', '$costo_usd', '$observaciones','$oculto','$descripcion_item', (SELECT MAX(orden)+1 FROM cdc_costos_items WHERE borrado = 0 and nivel = '$nivel')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            $result->descripcion = $descripcion;
            $result->parent = $parent;
            break;
        
        case 'M':
            //UPDATE
            $update_clietne = mysqli_query($con, "UPDATE cdc_costos_items SET parent='$parent', nivel='$nivel', descripcion='$descripcion', unidad='$unidad', costo_unidad='$costo_usd', observaciones='$observaciones', oculto='$oculto', descripcion_item='$descripcion_item' 
                                                  WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $delete_clietne = mysqli_query($con, "UPDATE cdc_costos_items SET borrado='1' 
                                                  WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>