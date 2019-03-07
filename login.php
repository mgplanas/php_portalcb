<?php
include("conexion.php");

sleep(1);

    $user = strip_tags($_POST["usuariolg"]) .'@arsat.com.ar';
    $pass = stripslashes($_POST["passlg"]);

    $conn = ldap_connect("ldap://srv-int-dc.arsat.com.ar/");
    if (!$conn)
        echo 'Could not connect to LDAP server';
    else {

	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
	if (@ldap_bind($conn,$user,$pass)) {
    	

		session_start();

		$_SESSION['usuario'] = $user;
        $persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
        $rowp = mysqli_fetch_assoc($persona);
        $id_rowp = $rowp['id_persona'];
        $login_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('4', '2', '$id_rowp', now(), '$user', 'Login Usuario')") or die(mysqli_error());

	} else {
    		echo "ldap_error: " . ldap_error($conn);
	}

    }
?>
