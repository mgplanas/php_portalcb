<?php
include("conexion.php");
session_start();

$_SESSION['usuario'] = $user;

$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$login_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                       VALUES ('5', '2', '$id_rowp', now(), '$user', 'Logout Usuario')") or die(mysqli_error());

session_destroy();

header('Location:index.html');

?>