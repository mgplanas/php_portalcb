<?php
    include("../conexion.php");

    if (isset($_POST['id_gerencia'])) {
        $id_gerencia = $_POST['id_gerencia'];
    } else {
        $id_gerencia = $_GET['id_gerencia'];
    }

    //$proy_resp = "SELECT CONCAT(r.nombre, ' ', r.apellido) as persona, COUNT(*) as total FROM controls.proyecto as p
      $proy_resp = "SELECT r.apellido as persona , 
                    COUNT(IF(estado='4',1,null)) as completado,
                    COUNT(IF(estado='3',1,null)) as aplazado,
                    COUNT(IF(estado='2',1,null)) as en_curso,
                    COUNT(IF(estado='1',1,null)) as no_iniciado
                    FROM proyecto as p
                    INNER JOIN persona as r ON p.responsable=r.id_persona
                    WHERE p.borrado='0'
                    AND ( 0 = $id_gerencia OR r.gerencia = $id_gerencia )
                    AND r.borrado = '0'
                    group by responsable";
    $query = mysqli_query($con, $proy_resp) or die('Query failed: ' . mysql_error());

    $data = array();

   while ( $row = mysqli_fetch_assoc($query))  {
	$data[]=$row;
    }
    
    echo json_encode($data);
    
?>