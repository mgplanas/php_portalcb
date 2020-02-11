<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $fechadesde = $_POST['fechadesde'];
    $fechahasta = $_POST['fechahasta'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_area = mysqli_query($con, "INSERT INTO adm_cmp_periodos(fecha_desde, fecha_hasta, borrado) 
                                                VALUES ('$fechadesde', '$fechahasta', 0)") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_area = mysqli_query($con, "UPDATE adm_cmp_periodos SET fecha_desde='$fechadesde', fecha_hasta='$fechahasta' WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_area = mysqli_query($con, "UPDATE adm_cmp_periodos SET borrado=1 WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>