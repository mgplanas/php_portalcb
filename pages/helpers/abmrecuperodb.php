<?php
    include("../../conexion.php");
    // Verifica el periodo si existe, si no existe lo crea
    function verificarPeriodo($fecha,$con) {
        $id_periodo=0;
        $sql = "SELECT id FROM adm_cmp_periodos WHERE fecha_desde <= '$fecha' AND fecha_hasta >= '$fecha'";
        $PerSQL = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($PerSQL);
        if ($row) {
            $id_periodo = $row['id'];
        }
        return $id_periodo;
    }

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $id_persona = $_POST['persona'];
    $descripcion = $_POST['descripcion'];
    $result = new stdClass();
    $result->ok = false;

    $id_periodo = verificarPeriodo($fecha, $con);
    if ($id_periodo==0) {
        $result->err = "Período inexistente";
    } else {
        switch ($op) {
            case 'A':
                // INSERT
                $insert_area = mysqli_query($con, "INSERT INTO adm_cmp_balance (id_periodo, id_persona, fecha, tipo, dias, origen) VALUES ('$id_periodo', '$id_persona','$fecha', 'R', 1, 'M')") or die(mysqli_error());	
                $lastInsert = mysqli_insert_id($con);
                $result->id = $lastInsert;
                break;
            
            // case 'M':
            //     //UPDATE
            //     $update_area = mysqli_query($con, "UPDATE adm_dnl SET fecha='$fecha', descripcion='$descripcion' WHERE id='$id'") or die(mysqli_error());	
            //     break;

            // case 'B':
            //     //UPDATE
            //     $update_area = mysqli_query($con, "UPDATE adm_dnl SET borrado=1 WHERE id='$id'") or die(mysqli_error());	
            //     break;

            default:
                break;
        }
        $result->ok = true;

    }
    echo json_encode($result);
?>