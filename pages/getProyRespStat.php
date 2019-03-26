<?php
    include("../conexion.php");

    //$proy_resp = "SELECT CONCAT(r.nombre, ' ', r.apellido) as persona, COUNT(*) as total FROM controls.proyecto as p
      $proy_resp = "SELECT r.apellido as persona , 
                    COUNT(IF(estado='4',1,null)) as completado,
                    COUNT(IF(estado='3',1,null)) as aplazado,
                    COUNT(IF(estado='2',1,null)) as en_curso,
                    COUNT(IF(estado='1',1,null)) as no_iniciado
                    FROM controls.proyecto as p
                    INNER JOIN persona as r ON p.responsable=r.id_persona
                    WHERE p.borrado='0'
                    group by responsable";
    $query = mysqli_query($con, $proy_resp) or die('Query failed: ' . mysql_error());

    $data = array();

   while ( $row = mysqli_fetch_assoc($query))  {
	$data[]=$row;
    }
    
    echo json_encode($data);
    
?>