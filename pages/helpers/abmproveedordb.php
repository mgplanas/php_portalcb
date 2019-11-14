<?php
    include("../../conexion.php");
    session_start();
    $user=$_SESSION['id_usuario'];
    $op = $_POST['operacion'];

    $razon_social = mysqli_real_escape_string($con,(strip_tags($_POST['razon_social'],ENT_QUOTES)));

    $result = new stdClass();
    $result->ok = false;

    switch ($op) {
        case 'A':
            // INSERT
            $sql = "INSERT INTO adm_com_proveedores(razon_social) VALUES ('$razon_social')";	
                
            $insert_gerencia = mysqli_query($con, $sql) or die(mysqli_error());
            $lastInsert = mysqli_insert_id($con);
            
            $result->id = $lastInsert;
            break;
        
        case 'M':
            break;

        case 'B':
            break;

        default:
            break;
    }
    $result->ok = true;

    echo json_encode($result);

?>