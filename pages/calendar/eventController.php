<?php
    include("../../conexion.php");

    // Parámetros comunes
    $inicio = $_POST['start'];
    $fin = $_POST['end'];
    $action = $_POST['action'];

    $query = '';
    switch ($action) {
        case 'DNL':
            $query = "SELECT ev.* FROM adm_eventos_cal as ev
                WHERE NOT (ev.fecha_inicio > '$fin' OR ev.fecha_fin < '$inicio')
                    AND ev.tipo = 1
                    AND ev.borrado = 0;";
            break;
        case 'BY_PERSON_ID':
            $id_persona = $_POST['id'];
            $query = "    SELECT ev.*, per.nombre, per.apellido, per.cargo
            FROM adm_eventos_cal as ev
            INNER JOIN persona as per ON ev.id_persona = per.id_persona AND per.id_persona = '$id_persona'
            WHERE NOT (ev.fecha_inicio > '$fin' OR ev.fecha_fin < '$inicio')
            AND ev.borrado = 0
            ORDER BY ev.tipo, ev.subtipo;";
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