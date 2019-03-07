<?php
/*Datos de conexion a la base de datos*/
$db_host_ossim = "10.0.0.85";
$db_user_ossim = "reports";
$db_pass_ossim = "arsat123";
//$db_name_ossim = "alienvault_siem";

$cono = mysqli_connect($db_host_ossim, $db_user_ossim, $db_pass_ossim);


if(mysqli_connect_errno()){
	echo 'No se pudo conectar a la base de datos : '.mysqli_connect_error();
} //else{echo 'Good Job!';}

mysqli_set_charset($cono,"utf8");
?>
