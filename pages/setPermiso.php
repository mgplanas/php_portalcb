<?php
    include("../conexion.php");

    $s=$_POST['rmvfile'];
    $f=$_POST['datap']; 
    
    $q_check = mysqli_query($con, "SELECT * FROM permisos WHERE id_permiso='$s'");
	$check = mysqli_fetch_assoc($q_check);

    if ($f=='1'){
    $admin = $check['admin'];
    
    if ($admin == '0'){
        $sel="update permisos set admin='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set admin='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='2'){
    $admin = $check['soc'];
    
    if ($admin == '0'){
        $sel="update permisos set soc='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set soc='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='3'){
    $admin = $check['compliance'];
    
    if ($admin == '0'){
        $sel="update permisos set compliance='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set compliance='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='4'){
    $admin = $check['edicion'];
    
    if ($admin == '0'){
        $sel="update permisos set edicion='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set edicion='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='5'){
    $admin = $check['admin_proy'];
    
    if ($admin == '0'){
        $sel="update permisos set admin_proy='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set admin_proy='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='6'){
    $admin = $check['proy'];
    
    if ($admin == '0'){
        $sel="update permisos set proy='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set proy='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='7'){
    $admin = $check['admin_per'];
    
    if ($admin == '0'){
        $sel="update permisos set admin_per='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set admin_per='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='8'){
    $admin = $check['cli_dc'];
    
    if ($admin == '0'){
        $sel="update permisos set cli_dc='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set cli_dc='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }
    else if ($f=='9'){
    $admin = $check['admin_cli_dc'];
    
    if ($admin == '0'){
        $sel="update permisos set admin_cli_dc='1' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    else{
        $sel="update permisos set admin_cli_dc='0' where id_permiso='$s'";
        $sel1=mysqli_query($con, $sel);
    }
    }    
    
?>