<?php
    include("../conexion.php");

    $vinculos = "SELECT * FROM controls.vinculo WHERE borrado='0';";
    $query = mysqli_query($con, $vinculos) or die('Query failed: ' . mysql_error());

    $link = array();

   while ( $row = mysqli_fetch_assoc($query))  {
	$link[]=$row;
    }
   
    echo json_encode($link);
    
?>