<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

//Alert icons data on top bar
$page_title="Admin";
$user=$_SESSION['usuario'];

if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
	$cek = mysqli_query($con, "SELECT i.*, p.email FROM permisos as i 
	                           LEFT JOIN persona as p on i.id_persona = p.id_persona WHERE id_permiso='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $email = $cekd['email'];
    
    if(mysqli_num_rows($cek) == 0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
	}else{
		//Elimino Activo
		
        $delete_permiso = mysqli_query($con, "UPDATE permisos SET `borrado`='1' WHERE id_permiso='$nik'");
      
        $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('3', '8', '$nik', now(), '$user', '$email')") or die(mysqli_error());
		if(!$delete_permiso){
			$_SESSION['formSubmitted'] = 9;
            header("Location: admin.php");
		}
	}
}

if(isset($_GET['aksip']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
    
		
    $delete_persona = mysqli_query($con, "UPDATE persona SET borrado=1 WHERE id_persona='$nik'");
    
    $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                            VALUES ('3', '2', '$nik', now(), '$user', '$email')") or die(mysqli_error());
    if(!$delete_persona){
        $_SESSION['formSubmitted'] = 9;
        header("Location: admin.php");
    }
}

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['admin_per']=='0' AND $rq_sec['admin']=='0' ){
	header('Location: ../site.php');
}

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona where borrado = 0 ORDER BY apellido, nombre");
$subgerencias = mysqli_query($con, "SELECT * FROM subgerencia ORDER BY nombre ASC");
?>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GITyS-ARSAT[<?=$page_title?>]</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->

    <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
    <!-- <link rel="stylesheet" href="../css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">  -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
    .example-modal .modal {
        position: relative;
        top: auto;
        bottom: auto;
        right: auto;
        left: auto;
        display: block;
        z-index: 1;
    }

    .example-modal .modal {
        background: transparent !important;
    }

    /* Seleccion de row en datatable */
    .rowselected {
        background-color: #acbad4;
    }

    table thead th.noventa{
        transform: rotate(-90deg);
        transform-origin: center !important;
        font-size: 12px !important;
        vertical-align: bottom;
    }

    table#tbgerencias.dataTable tbody tr:hover {
        background-color: #acbad4;
        cursor: pointer;
    }

    table#tbgerencias.dataTable tbody tr:hover > .sorting_1 {
        background-color: #acbad4;
        cursor: pointer;
    }    
    table#tbsubgerencias.dataTable tbody tr:hover {
        background-color: #acbad4;
        cursor: pointer;
    }

    table#tbsubgerencias.dataTable tbody tr:hover > .sorting_1 {
        background-color: #acbad4;
        cursor: pointer;
    }  
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            <!-- Header Navbar -->
            <?php include_once('./site_header.php'); ?>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <?php include_once('./site_sidebar.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <?php
              if ($_SESSION['formSubmitted']=='1'){
                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos editados correctamente.</div>';
                $_SESSION['formSubmitted'] = 0;
              }
              else if ($_SESSION['formSubmitted']=='2'){
                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nuevo Activo guardado correctamente.</div>';
                $_SESSION['formSubmitted'] = 0;
              }	
              else if ($_SESSION['formSubmitted']=='3'){
                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nueva persona guardada correctamente.</div>';
                $_SESSION['formSubmitted'] = 0;
              }
              else if ($_SESSION['formSubmitted']=='9'){
                echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al ejecutar el vuelco a la base de datos.</div>';
                $_SESSION['formSubmitted'] = 0;
              }
            ?>
            <!-- HEADER -->
            <section class="content-header">
                <h1>
                    Administración del Portal
                    <small>General</small>
                </h1>
            </section>
            <!-- FIN HEADER -->
            <!-- Main content -->
            <section class="content container-fluid">
              <!--------------------------
              | Your Page Content Here |
              -------------------------->
              <section class="content">
                  <div class="row">
                      <div class="col-xs-12">
                          <div class="nav-tabs-custom">
                              <ul class="nav nav-tabs">
                                  <li class="active"><a href="#tab_1" data-toggle="tab">Permisos Usuarios</a></li>
                                  <li><a href="#tab_0" data-toggle="tab">Personas</a></li>
                                  <li><a href="#tab_3" data-toggle="tab">Estructura</a></li>
                                  <li><a href="#tab_2" data-toggle="tab">Log Auditoría</a></li>

                              </ul>
                              <div class="tab-content">

                                <!-- TAB PERMISOS -->
                                <div class="tab-pane active" id="tab_1">
                                  <table id="permisos" class="table table-bordered table-hover">
                                        <div class="col-sm-12" style="text-align:right;">
                                            <button type="button" class="btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modal-acceso"><i class="glyphicon glyphicon-user"></i>
                                                Nuevo Acceso</button>
                                        </div>
                                        <thead>
                                            <tr>
                                                <th width="1">Nro</th>
                                                <th width="1">Legajo</th>
                                                <th width="100">Usuario</th>
                                                <th width="100">Correo</th>
                                                <th width="1">Admin</th>
                                                <th width="1">Admin Per</th>
                                                <th width="1">Proyectos</th>
                                                <th width="1">Admin Proy</th>
                                                <th width="1">SOC</th>
                                                <th width="1">Compliance</th>
                                                <th width="1">Ver Activación</th>
                                                <th width="1">Cli. DC</th>
                                                <th width="1">Admin CDC</th>
                                                <th width="1">Compras</th>
                                                <th width="1">Admin Compras</th>
                                                <th width="1">Admin Riesgo</th>
                                                <th width="1">Cto</th>
                                                <th width="1">Admin Cto</th>
                                                <th width="1">Admin Doc</th>
                                                <th width="1">Doc</th>
                                                <th width="1">Storage</th>
                                                <th width="1">Storage Admin</th>
                                                <th width="1">Storage OP</th>
                                                <th width="1">Edición</th>
                                                <th width="1">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $query = "SELECT i.*, p.nombre, p.apellido, p.legajo, p.email FROM permisos as i 
                                                        LEFT JOIN persona as p on i.id_persona = p.id_persona
                                                        WHERE i.borrado='0' AND p.borrado = '0'
                                                        ORDER BY p.apellido, p.nombre  ASC";
                                              
                                              $sql = mysqli_query($con, $query);
                                              if(mysqli_num_rows($sql) == 0){
                                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                                }
                                                else
                                                {
                                                          
                                                while($row = mysqli_fetch_assoc($sql)){
                                                  echo '<td align="center">'.$row['id_permiso'].'</td>';
                                                  echo '<td>'.$row['legajo'].'</td>';
                                                  echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                                                  echo '<td>'.$row['email'].'</td>';
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><a href=#><input name="admin" type="checkbox" onclick="updatePerm(1, '.$row['id_permiso'].');" value="1"'; if($row['admin'] == '1'){ echo 'checked'; } echo'></a></label>
                                                          </div>
                                                        </td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><a href=#><input name="admin_per" type="checkbox" onclick="updatePerm(7, '.$row['id_permiso'].');" value="1"'; if($row['admin_per'] == '1'){ echo 'checked'; } echo'></a></label>
                                                          </div>
                                                        </td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><a href=#><input name="proy" type="checkbox" onclick="updatePerm(6, '.$row['id_permiso'].');" value="1"'; if($row['proy'] == '1'){ echo 'checked'; } echo'></a></label>
                                                          </div>
                                                        </td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><a href=#><input name="admin_proy" type="checkbox" onclick="updatePerm(5, '.$row['id_permiso'].');" value="1"'; if($row['admin_proy'] == '1'){ echo 'checked'; } echo'></a></label>
                                                          </div>
                                                        </td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="soc" type="checkbox" onclick="updatePerm(2, '.$row['id_permiso'].');" value="1"'; if($row['soc'] == '1'){ echo 'checked'; } echo'></label>
                                                          </div>
                                                        </td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="compliance" type="checkbox" onclick="updatePerm(3, '.$row['id_permiso'].');" value="1"'; if($row['compliance'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="veractivacion" type="checkbox" onclick="updatePerm(20, '.$row['id_permiso'].');" value="1"'; if($row['ver_activacion_guardias'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="clidc" type="checkbox" onclick="updatePerm(8, '.$row['id_permiso'].');" value="1"'; if($row['cli_dc'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="adminclidc" type="checkbox" onclick="updatePerm(9, '.$row['id_permiso'].');" value="1"'; if($row['admin_cli_dc'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="compras" type="checkbox" onclick="updatePerm(10, '.$row['id_permiso'].');" value="1"'; if($row['compras'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="admin_compras" type="checkbox" onclick="updatePerm(11, '.$row['id_permiso'].');" value="1"'; if($row['admin_compras'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="admin_compras" type="checkbox" onclick="updatePerm(12, '.$row['id_permiso'].');" value="1"'; if($row['admin_riesgos'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="contratos" type="checkbox" onclick="updatePerm(14, '.$row['id_permiso'].');" value="1"'; if($row['contratos'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="admin_contratos" type="checkbox" onclick="updatePerm(13, '.$row['id_permiso'].');" value="1"'; if($row['admin_contratos'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                            <div class="checkbox">
                                                                <label><input name="admin_doc" type="checkbox" onclick="updatePerm(15, '.$row['id_permiso'].');" value="1"'; if($row['admin_doc'] == '1'){ echo 'checked'; } echo'></label>
                                                            </div></td>'; 
                                                  echo '<td>
                                                            <div class="checkbox">
                                                                <label><input name="doc" type="checkbox" onclick="updatePerm(16, '.$row['id_permiso'].');" value="1"'; if($row['doc'] == '1'){ echo 'checked'; } echo'></label>
                                                            </div></td>'; 
                                                  echo '<td>
                                                            <div class="checkbox">
                                                                <label><input name="storage" type="checkbox" onclick="updatePerm(17, '.$row['id_permiso'].');" value="1"'; if($row['storage'] == '1'){ echo 'checked'; } echo'></label>
                                                            </div></td>'; 
                                                  echo '<td>
                                                            <div class="checkbox">
                                                                <label><input name="storage_admin" type="checkbox" onclick="updatePerm(18, '.$row['id_permiso'].');" value="1"'; if($row['storage_admin'] == '1'){ echo 'checked'; } echo'></label>
                                                            </div></td>'; 
                                                  echo '<td>
                                                            <div class="checkbox">
                                                                <label><input name="storage_admin" type="checkbox" onclick="updatePerm(19, '.$row['id_permiso'].');" value="1"'; if($row['storage_op'] == '1'){ echo 'checked'; } echo'></label>
                                                            </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="edicion" type="checkbox" onclick="updatePerm(4, '.$row['id_permiso'].');" value="1"'; if($row['edicion'] == '1'){ echo 'checked'; } echo'></label>
                                                          </div></td>'; 

                                                  echo '<td align="center">
                                                          <a href="admin.php?aksi=delete&nik='.$row['id_permiso'].'" title="Deshabilitar usuario" onclick="return confirm(\'Esta seguro de borrar los permisos de '.$row['email'].'?\')" class="btn btn-danger btn-sm ';
                                                                if ($rq_sec['edicion']=='0'){
                                                                        echo 'disabled';
                                                                }
                                                                echo '"><i class="glyphicon glyphicon-ban-circle"></i></a>
                                                        </td>';
                                                  echo '</tr>';
                                                }
                                              }
                                              ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade" id="modal-acceso">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Nuevo Acceso</h2>
                                                <?php
                                                  if(isset($_POST['Addp'])){
                                                    $persona = mysqli_real_escape_string($con,(strip_tags($_POST["persona"],ENT_QUOTES)));//Escanpando caracteres
                                                    $lectura = mysqli_real_escape_string($con,(strip_tags($_POST["lectura"],ENT_QUOTES)));//Escanpando caracteres
                                                    $edicion = mysqli_real_escape_string($con,(strip_tags($_POST["edicion"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $compliance = mysqli_real_escape_string($con,(strip_tags($_POST["compliance"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $soc = mysqli_real_escape_string($con,(strip_tags($_POST["soc"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $proy = mysqli_real_escape_string($con,(strip_tags($_POST["proy"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $admin_proy = mysqli_real_escape_string($con,(strip_tags($_POST["admin_proy"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $clidc = mysqli_real_escape_string($con,(strip_tags($_POST["clidc"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $compras = mysqli_real_escape_string($con,(strip_tags($_POST["compras"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $admin_compras = mysqli_real_escape_string($con,(strip_tags($_POST["admin_compras"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $admin_riesgo = mysqli_real_escape_string($con,(strip_tags($_POST["admin_riesgos"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $contratos = mysqli_real_escape_string($con,(strip_tags($_POST["contratos"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $admin_contratos = mysqli_real_escape_string($con,(strip_tags($_POST["admin_contratos"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $admin_doc = mysqli_real_escape_string($con,(strip_tags($_POST["admin_doc"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $doc = mysqli_real_escape_string($con,(strip_tags($_POST["doc"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $storage = mysqli_real_escape_string($con,(strip_tags($_POST["storage"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $storage_admin = mysqli_real_escape_string($con,(strip_tags($_POST["storage_admin"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $storage_op = mysqli_real_escape_string($con,(strip_tags($_POST["storage_op"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $ver_activacion_guardias = mysqli_real_escape_string($con,(strip_tags($_POST["veractivacion"],ENT_QUOTES)));//Escanpando caracteres 
                                                    //Inserto Control
                                                    $insert_acceso = mysqli_query($con, "INSERT INTO permisos (id_persona, lectura, edicion, compliance, soc, proy, admin_proy, cli_dc,compras, admin_compras, admin_riesgos, contratos, admin_contratos,admin_doc, doc, storage, storage_admin, storage_op, ver_activacion_guardias) VALUES ('$persona','$lectura','$edicion', '$compliance', '$soc', '$proy', '$admin_proy', '$clidc', '$compras', '$admin_compras', '$admin_riesgo', '$contratos', '$admin_contratos', '$admin_doc', '$doc', '$storage', '$storage_admin', '$storage_op', '$ver_activacion_guardias')") or die(mysqli_error());	
                                                    $lastInsert = mysqli_insert_id($con);
                                                    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                                  VALUES ('1', '16', '$lastInsert', now(), '$user')") or die(mysqli_error());
                                                    unset($_POST);
                                                    if($insert_acceso){
                                                      $_SESSION['formSubmitted'] = 3;
                                                      echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                    }else{
                                                      $_SESSION['formSubmitted'] = 9;
                                                      echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                    }				
                                                  }				
                                                  ?>
                                            </div>
                                            <div class="modal-body">
                                                <!-- form start -->
                                                <form method="post" role="form" action="">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label>Persona</label>
                                                            <select name="persona" class="form-control">
                                                                <?php
                                                                    $personasn = mysqli_query($con, "SELECT * FROM persona WHERE id_persona NOT IN (SELECT id_persona FROM permisos)");
                                                                    while($rowps = mysqli_fetch_array($personasn)){
                                                                        echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                                                        }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <label>Permisos</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="lectura" type="checkbox" value="1">
                                                                Solo lectura
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="edicion" type="checkbox" value="1">
                                                                Edición (Borrar ítems)
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="compliance" type="checkbox" value="1">
                                                                Compliance (Riesgos, Activos, ISO 27001, Controles)
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="veractivacion" type="checkbox" value="1">
                                                                Ver Activacion de Guardias
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="soc" type="checkbox" value="1"> SOC
                                                                (Operaciones de Ciberseguridad)
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="proy" type="checkbox" value="1"> Proyectos
                                                                (Gestión de proyectos)
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="admin_proy" type="checkbox" value="1"> Admin. Proy.
                                                                (Administrador de proyectos)
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="clidc" type="checkbox" value="1"> Clientes DC
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="compras" type="checkbox" value="1"> Compras
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="admin_compras" type="checkbox" value="1"> Admin. Compras
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="admin_riesgos" type="checkbox" value="1"> Admin. Riesgo
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="contratos" type="checkbox" value="1"> Contratos
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="admin_contratos" type="checkbox" value="1"> Admin. Contratos
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="admin_doc" type="checkbox" value="1"> Admin. Doc
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="doc" type="checkbox" value="1"> Doc.
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="storage" type="checkbox" value="1"> Storage.
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="storage_admin" type="checkbox" value="1"> Storage Admin.
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="storage_op" type="checkbox" value="1"> Storage Operator.
                                                            </label>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-3">
                                                                <input type="submit" name="Addp"
                                                                    class="btn  btn-raised btn-success"
                                                                    value="Guardar datos">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <button type="button"
                                                                    class="btn btn-default pull-left"
                                                                    data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>

                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN TAB PERMISOS -->
                                <!-- TAB PERSONA -->
                                <div class="tab-pane" id="tab_0">
                                  <table id="personas" class="table table-bordered table-hover">
                                    <div class="col-sm-12" style="text-align:right;">
                                      <button id="modal-abm-persona-btn-alta" type="button" class="btn-sm btn-primary" data-toggle="modal"
                                              data-target="#modal-persona"><i
                                              class="glyphicon glyphicon-user"></i> Nueva Persona</button>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th width="1"></th>
                                            <th width="1">Legajo</th>
                                            <th width="1">Nombre</th>
                                            <th width="1">email</th>
                                            <th width="1">Cargo</th>
                                            <th width="1">Gerencia</th>
                                            <th width="1">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        $query = "SELECT p.id_persona, p.nombre, p.apellido, p.legajo, p.email, p.contacto, p.cargo, g.id_gerencia, p.subgerencia, p.area, g.nombre as gerencia, u.nombre as grupo, u.id_grupo 
                                        FROM persona as p 
                                        LEFT JOIN gerencia as g on p.gerencia = g.id_gerencia
                                        LEFT JOIN grupo as u on p.grupo = u.id_grupo
                                        WHERE p.borrado = 0
                                        ORDER BY p.apellido ASC";
                                        
                                        $sql = mysqli_query($con, $query);
                                        if(mysqli_num_rows($sql) == 0){
                                            echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                        }
                                        else
                                        {
                                          while($row = mysqli_fetch_assoc($sql)){
                                            echo '<td><a data-id="'.$row['id_persona'].'" 
                                            data-legajo="'.$row['legajo'].'"
                                            data-nombre="'.$row['nombre'].'"
                                            data-apellido="'.$row['apellido'].'"
                                            data-email="'.$row['email'].'"
                                            data-contacto="'.$row['contacto'].'"
                                            data-cargo="'.$row['cargo'].'"
                                            data-gerencia="'.$row['gerencia'].'"
                                            data-idgerencia="'.$row['id_gerencia'].'"
                                            data-grupo="'.$row['id_grupo'].'"
                                            title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                                            </td>';

                                            echo '<td>'.$row['legajo'].'</td>';
                                            echo '<td>'.$row['apellido']. ' ' .$row['nombre']. '</td>';
                                            echo '<td>'.$row['email'].'</td>';
                                            echo '<td>'.$row['cargo'].'</td>'; 
                                            echo '<td>'.$row['gerencia'].'</td>'; 
                                            echo '<td align="center">
                                                    <a data-id="'.$row['id_persona'].'" 
                                                    data-legajo="'.$row['legajo'].'"
                                                    data-nombre="'.$row['nombre'].'"
                                                    data-apellido="'.$row['apellido'].'"
                                                    data-email="'.$row['email'].'"
                                                    data-contacto="'.$row['contacto'].'"
                                                    data-cargo="'.$row['cargo'].'"
                                                    data-gerencia="'.$row['gerencia'].'"
                                                    data-idgerencia="'.$row['id_gerencia'].'"
                                                    data-idsubgerencia="'.$row['subgerencia'].'"
                                                    data-idarea="'.$row['area'].'"
                                                    data-grupo="'.$row['id_grupo'].'"
                                                    title="Editar persona" class="modal-abm-persona-btn-edit btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                                    <a href="admin.php?aksip=delete&nik='.$row['id_persona'].'" title="Borrar persona" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['apellido']. ' ' .$row['nombre'].'?\')" class="btn btn-danger btn-sm ';
                                            echo '"><i class="glyphicon glyphicon-trash"></i></a></td>';
                                            echo '</tr>';
                                          }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th width="1"></th>
                                            <th width="1">Legajo</th>
                                            <th width="1">Nombre</th>
                                            <th width="1">email</th>
                                            <th width="1">Cargo</th>
                                            <th width="1">Gerencia</th>
                                            <th width="1">Acciones</th>
                                        </tr>
                                    </tfoot>
                                  </table>
                                </div>

                                <!-- MODAL ADD PERSONA -->
                                <?php
                                    include_once('./modals/abmpersona.php');
                                ?>

                                <!-- FIN MODAL PERSONA -->
                                
                                <!-- FIN TAB PERSONA -->

                                <!-- TAB ESTRUCTURA -->
                                <div class="tab-pane" id="tab_3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Gerencias</h3>
                                                    <a class="btn text-right" id='modal-abm-gerencia-btn-alta'><i class="glyphicon glyphicon-plus-sign" title="Agregar gerencia"style="color:green; font-size: 20px;"></i></a>
                                                </div>
                                                <div class="box-body no-padding">
                                                    <table class="table table-hover display" id="tbgerencias">
                                                        <thead>
                                                            <tr>
                                                            <th>ID</th>
                                                            <th>Sigla</th>
                                                            <th>Responsable</th>
                                                            <th>Nombre</th>
                                                            <th>Gerente</th>
                                                            <th style="width: 40px; text-align: center"><i class="glyphicon glyphicon-flash"></i></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Sub Gerencias</h3>
                                                    <a class="btn text-right" id="modal-abm-subgerencia-btn-alta"><i class="glyphicon glyphicon-plus-sign" title="Agregar gerencia"style="color:green; font-size: 20px;"></i></a>
                                                </div>
                                                <div class="box-body no-padding">
                                                    <table class="table table-hover display" id="tbsubgerencias">
                                                        <thead>
                                                            <tr>
                                                            <th>ID</th>
                                                            <th>Sigla</th>
                                                            <th>Responsable</th>
                                                            <th>Nombre</th>
                                                            <th>Sub Gerente</th>
                                                            <th style="width: 40px; text-align: center"><i class="glyphicon glyphicon-flash"></i></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>                                            
                                        </div>
                                        <div class="col-md-4">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Areas</h3>
                                                    <a class="btn text-right" id="modal-abm-area-btn-alta"><i class="glyphicon glyphicon-plus-sign" title="Agregar área"style="color:green; font-size: 20px;"></i></a>
                                                </div>
                                                <div class="box-body no-padding">
                                                    <table class="table table-hover display" id="tbareas">
                                                        <thead>
                                                            <tr>
                                                            <th>ID</th>
                                                            <th>Sigla</th>
                                                            <th>Responsable</th>
                                                            <th>Nombre</th>
                                                            <th>Responsable</th>
                                                            <th style="width: 40px; text-align: center"><i class="glyphicon glyphicon-flash"></i></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>                                            
                                        </div>

                                    </div>
                                </div>

                                <!-- MODAL ABM GERENCIA -->
                                <?php 
                                    include_once('./modals/abmgerencia.php'); 
                                    include_once('./modals/abmsubgerencia.php'); 
                                    include_once('./modals/abmarea.php'); 
                                ?>
                                <!-- FIN MODAL ABM GERENCIAS -->                                
                                <!-- FIN TAB ESRUCTURA -->

                                <!-- TAB LOG -->
                                <div class="tab-pane" id="tab_2">
                                    <!-- /contenido de tab 2 -->
                                    <table id="auditoria" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="1">Nro</th>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Usuario</th>
                                                <th>Objeto</th>
                                                <th>Ítem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $query = "SELECT i.id_auditoria, i.fecha, i.id_item, i.i_titulo, p.nombre, p.apellido, it.titulo, e.tipo FROM auditoria as i 
                                                                        LEFT JOIN persona as p on i.usuario = p.email
                                                                        LEFT JOIN item as it on i.item = it.id_item
                                                                        LEFT JOIN evento as e on i.evento = e.id_evento
                                                                        ORDER BY i.id_auditoria DESC";
                                              
                                              $sql = mysqli_query($con, $query);
                                              if(mysqli_num_rows($sql) == 0){
                                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                              }else{
                                                while($row = mysqli_fetch_assoc($sql)){
                                                                              
                                                    echo '<td align="center">'.$row['id_auditoria'].'</td>';
                                                    echo '<td>'.$row['fecha'].'</td>';
                                                    echo '<td>'.$row['tipo'].'</td>';
                                                    echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                                                    echo '<td>'.$row['titulo'].'</td>';
                                                    echo '<td>'.$row['i_titulo'].'</td></tr>';
                                                  
                                                }
                                              }
                                              ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th width="1">Nro</th>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Usuario</th>
                                                <th>Objeto</th>
                                                <th>Ítem</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- FIN TAB LOG -->
                              </div>
                              <!-- /.tab-content -->
                          </div>
                          <!-- /.box-body -->
                      </div>
                  </div>
              </section>
            </section>
            <!-- /.content -->
        </div>
    <!-- Main Footer -->
    <?php include_once('./site_footer.php'); ?>

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 3 -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- export -->
    <script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
    <script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
    <script src="../bower_components/datatables.net/js/jszip.min.js"></script>
    <script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
    <script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
    <script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
    <script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>    
    <!-- MODAL ABM GERENCIAS  -->
    <script src="./modals/abmestructura.js"></script>
    <script src="./modals/abmpersona.js"></script>

    <!--
<script src="../bower_components/datatables.net/js/jszip.min.js"></script>
<script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>
<script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
<script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
 -->
    
    <script>
    window.onload = function() {
        history.replaceState("", "", "admin.php");
    }
    </script>
    <script>
    $(function() {
        $('#auditoria').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'info': true,
            'autoWidth': false,
        })
    })
    </script>
    <script>
        $(function () {
            $('#personas').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            'dom'         : 'Bfrtip',
            'buttons'     : [{
                  extend: 'pdfHtml5',
                  orientation: 'landscape',
                  pageSize: 'A4',
                         
                     },
                      {
            extend: 'excel',
            text: 'Excel',
            }]
            })
        })
    </script>
    <script>
    $(function() {
        $('#permisos').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': false,
            'info': true,
            'autoWidth': false,
            'dom'         : 'frtip',
            'scrollX'     :true,
            'columnDefs': [
                { 'searchable': false, 'targets': [0,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24] }
            ]

        })
    })
    </script>


    <script>
        $(function(){
        $(".ver-itemDialog").click(function(){
            $('#txtid').val($(this).data('id'));
            $('#txtlegajo').val($(this).data('legajo'));
            $('#txtnombre').val($(this).data('nombre'));
            $('#txtapellido').val($(this).data('apellido'));
            $('#txtemail').val($(this).data('email'));
            $('#txtcontacto').val($(this).data('contacto'));
            $('#txtcargo').val($(this).data('cargo'));
            $('#txtgerencia').val($(this).data('gerencia'));
            if ($(this).data('idgerencia')==1) {
                $('#grupoverdiv').show();
                $('#txtgrupo').val($(this).data('grupo'));
            } else {
                $('#grupoverdiv').hide();
            }
            $("#modal-ver-persona").modal("show");
        });
        });
</script>
    <script>

    function updatePerm(perm, id_permiso) {

        var datap = perm;
        var rmvfile = id_permiso;

        if (confirm("Está seguro de cambiar los permisos?") == true) {
            if (id_permiso != '') {
                $.ajax({
                    type: 'post',
                    url: 'setPermiso.php',
                    data: {
                        rmvfile: rmvfile,
                        datap: datap
                    },
                    success: function(msg) {}
                });
            }
        }
    }
    </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
    </body>

</html>