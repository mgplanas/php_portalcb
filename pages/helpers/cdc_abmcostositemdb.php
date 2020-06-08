<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $parent = $_POST['parent'];
    $nivel = $_POST['nivel'];
    $descripcion = $_POST['descripcion'];
    $unidad = $_POST['unidad'];
    $costo_usd = $_POST['costo_usd']; 

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_item = mysqli_query($con, "INSERT INTO cdc_costos_items(parent, nivel, descripcion, unidad, costo_unidad) 
                                                VALUES ('$parent', '$nivel', '$descripcion', '$unidad', '$costo_usd')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            $result->descripcion = $descripcion;
            $result->parent = $parent;
            break;
        
        case 'M':
            //UPDATE
            // $update_clietne = mysqli_query($con, "UPDATE cdc_costos_detalle SET cantidad='$cantidad', costo_recurrente='$costo_recurrente', costo_unica_vez='$costo_unica_vez' 
                                                //   WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            // $removeCosteo = mysqli_query($con, "UPDATE cdc_costos_detalle SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>