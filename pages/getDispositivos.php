<?php
    include("../conexion.php");

    $dispositivos = "SELECT i.*, t.nombre FROM controls.dispositivo as i
                    INNER JOIN tipo_dispositivo as t on i.tipo = t.id_tipoDispositivo
                    WHERE borrado='0';";
    $query = mysqli_query($con, $dispositivos) or die('Query failed: ' . mysql_error());

   $data = array();

    while ( $row = mysqli_fetch_assoc($query))  {
	   $data[]=$row;
    }
    

    //write to json file
    $file = fopen("test.json","w");
    echo fwrite($file,json_encode($data));
    fclose($file);

    //echo
    echo json_encode($data);
    
    
?>