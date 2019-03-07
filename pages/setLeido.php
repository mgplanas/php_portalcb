<?php
    include("../conexion.php");

    $persona=$_POST['rmvfile'];
    $novedad=$_POST['datap']; 
    
    $insert_leido = mysqli_query($con, "INSERT INTO leido_novedad (novedad, persona, creado) 
                        VALUES ('$novedad', '$persona', now())") or die(mysqli_error());

    
?>