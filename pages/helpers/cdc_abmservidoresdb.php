<?php
    include("../../conexion.php");

    $op = $_POST['operacion'];

    $id = $_POST['id'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $serie = $_POST['serie'];
    $memoria = $_POST['memoria'];
    $sockets = $_POST['sockets'];
    $nucleos = $_POST['nucleos'];
    $ubicacion_sala = $_POST['sala'];
    $ubicacion_fila = $_POST['fila'];
    $ubicacion_rack = $_POST['rack'];
    $ubicacion_unidad = $_POST['unidad'];
    $IP = $_POST['ip'];
    $vcenter = $_POST['vcenter'];
    $cluster = $_POST['cluster'];
    $hostname = $_POST['hostname'];
    $infra = $_POST['infra'];
    $orquestador = $_POST['orquestador'];
    $eol = $_POST['eol'];
    $eos = $_POST['eos'];

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $insert_server = mysqli_query($con, "INSERT INTO cdc_inv_servidores(infra, orquestador, eol, eos, marca, modelo, serie, memoria, sockets, nucleos, ubicacion_sala, ubicacion_fila, ubicacion_rack, ubicacion_unidad, IP, vcenter, cluster, hostname, cliente) 
                                                VALUES ('$infra', '$orquestador', ". ($eol == '' ? 'null' : "'".$eol."'") .", "($eos == '' ? 'null' : "'".$eos."'") .", '$marca', '$modelo', '$serie', '$memoria', '$sockets', '$nucleos', '$ubicacion_sala', '$ubicacion_fila', '$ubicacion_rack', '$ubicacion_unidad', '$IP', '$vcenter', '$cluster', '$hostname', '$cliente')") or die(mysqli_error());	
            $lastInsert = mysqli_insert_id($con);
            $result->id = $lastInsert;
            break;
        
        case 'M':
            //UPDATE
            $update_server = mysqli_query($con, "UPDATE cdc_inv_servidores SET
            marca = '$marca',
            modelo = '$modelo',
            serie = '$serie',
            memoria = '$memoria',
            sockets = '$sockets',
            nucleos = '$nucleos',
            ubicacion_sala = '$ubicacion_sala',
            ubicacion_fila = '$ubicacion_fila',
            ubicacion_rack = '$ubicacion_rack',
            ubicacion_unidad = '$ubicacion_unidad',
            IP = '$IP',
            vcenter = '$vcenter',
            cluster = '$cluster',
            orquestador = '$orquestador',
            infra = '$infra',
            eos = ". ($eos == '' ? 'null' : "'".$eos."'") .",
            eol = ". ($eol == '' ? 'null' : "'".$eol."'") .",
            hostname = '$hostname'
            WHERE id='$id'") or die(mysqli_error());	
            break;

        case 'B':
            //UPDATE
            $update_server = mysqli_query($con, "UPDATE cdc_inv_servidores SET borrado='1' WHERE id='$id'") or die(mysqli_error());	
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>