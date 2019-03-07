<?php
    include("../conexion.php");

    $persona= mysqli_real_escape_string($con,(strip_tags($_POST["datap"],ENT_QUOTES)));
    $novedad= mysqli_real_escape_string($con,(strip_tags($_POST["dataid"],ENT_QUOTES)));
    $comment = mysqli_real_escape_string($con,(strip_tags($_POST["datac"],ENT_QUOTES)));
    $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["datat"],ENT_QUOTES)));
    
    $insert_comment = mysqli_query($con, "INSERT INTO comentario (novedad, texto, persona, tipo, creado) 
                    VALUES ('$novedad', '$comment', '$persona', '$tipo', now())") or die(mysqli_error());

    
?>