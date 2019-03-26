<?php
    include("../conexion.php");

    //$proy_resp = "SELECT CONCAT(r.nombre, ' ', r.apellido) as persona, COUNT(*) as total FROM controls.proyecto as p
      $proy_resp = "SELECT r.apellido as persona, COUNT(*) as total FROM controls.proyecto as p
      
                    INNER JOIN persona as r ON p.responsable=r.id_persona
                    WHERE p.borrado='0' AND estado!='4'
                    group by responsable;";
    $query = mysqli_query($con, $proy_resp) or die('Query failed: ' . mysql_error());

    $data1 = array();

   while ( $row = mysqli_fetch_assoc($query))  {
	$data1[]=$row;
    }
    
    echo json_encode($data1);
    
?>