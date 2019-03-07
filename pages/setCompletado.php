<?php
    include("../conexion.php");

    $persona=$_POST['rmvfile'];
    $tarea=$_POST['datap']; 
    
    $update_completado = mysqli_query($con, "UPDATE tarea SET estado='1', completada='$persona' 
                                             WHERE id_tarea='$tarea'") or die(mysqli_error());

?>