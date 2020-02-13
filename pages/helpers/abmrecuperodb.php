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
    $dias = $_POST['dias'];
    $id_persona = $_POST['persona'];
    $descripcion = $_POST['descripcion'];
    $result = new stdClass();
    $result->ok = false;

    $auxfechasperiodos = [];
    $_flag_ok = true;
    $auxdias = ($dias == 0.5 ? 1 : $dias);
    for ($i=1; $i <= $auxdias ; $i++) { 
        $id_periodo = 0;
        $fechains = date('Y-m-d', strtotime("+". ($i-1) ." day", strtotime($fecha)));
        $id_periodo = verificarPeriodo($fechains, $con);
        if (!$id_periodo) {
            $_flag_ok = false;
            break;
        }
        array_push($auxfechasperiodos, [$id_periodo, $fechains, ($dias > 1 ? 1 : $dias)]);
    }

    if ($_flag_ok==0) {
        $result->err = "Período inexistente";
    } else {
        switch ($op) {
            case 'A':
                // INSERT
                foreach ($auxfechasperiodos as $key => $value) {
                    $insert_area = mysqli_query($con, "INSERT INTO adm_cmp_balance (id_periodo, id_persona, fecha, tipo, dias, origen, observacion) VALUES ('$value[0]', '$id_persona','$value[1]', 'R', '$value[2]', 'M','$descripcion')") or die(mysqli_error());	
                }
                // $lastInsert = mysqli_insert_id($con);
                // $result->id = $lastInsert;
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