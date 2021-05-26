<?php
    include("../../../conexion.php");

    // Parámetros comunes
    $inicio = $_POST['start'];
    $fin = $_POST['end'];
    $action = $_POST['action'];

    $query = '';
    switch ($action) {
        case 'MIN_ACUM_BY_PERSON':
            $id_persona = $_POST['id_persona'];
            $tipo = $_POST['tipo'];
            $query = "SELECT SUM(TIMESTAMPDIFF(MINUTE,ev.fecha_inicio, ev.fecha_fin )) as suma
                        FROM adm_eventos_cal AS ev 
                        WHERE NOT (ev.fecha_inicio > '$fin' OR ev.fecha_fin < '$inicio')
                            AND ev.tipo = '$tipo'
                            AND ev.id_persona = '$id_persona'
                            AND ev.estado <> 3
                            AND ev.borrado = 0;";
            break;
        default:
            # code...
            break;
    }


    $result = mysqli_query($con,$query);

    $rows = array("data" => array());
    // while($r = mysqli_fetch_array($result)) {
    while($r = mysqli_fetch_assoc($result)) {
        // $rows[] = $r;
        array_push($rows["data"], $r);
    }
    echo json_encode($rows);

    // mysqli_close($con);
?>