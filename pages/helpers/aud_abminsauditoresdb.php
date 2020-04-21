<?php
    include("../../conexion.php");

    $id_instancia = $_POST['id_instancia'];
    $auditores = $_POST['auditores'];

    $result = new stdClass();
    $result->ok = false;

    // BORRO LAS RELACIONES ACTULES DE LA INSTANCIA
    $sql = "DELETE FROM aud_rel_ins_aud WHERE id_instancia = '$id_instancia';";
    $res = mysqli_query($con, $sql) or die(mysqli_error());	

    foreach ($auditores as $id_auditor) {
        $sql = "INSERT INTO aud_rel_ins_aud (id_instancia, id_auditor) VALUES ('$id_instancia','$id_auditor');";
        $insert_ente = mysqli_query($con, $sql) or die(mysqli_error());	
    }

        
    $result->ok = true;

    echo json_encode($result);

?>