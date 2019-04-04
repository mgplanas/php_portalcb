<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

$user=$_SESSION['usuario'];

if(isset($_GET['aksi']) == 'delete'){
    // escaping, additionally removing everything that could be (html/javascript-) code
    $nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
    $t=$_GET["t"];
    
    if($t == 0){ //borro elemento Dispositivo
		    $cek = mysqli_query($con, "SELECT * FROM dispositivo WHERE id_dispositivo='$nik'");
            $cekd = mysqli_fetch_assoc($cek);
            $etiqueta = $cekd['etiqueta'];
            //Elimino Dispositivo
            $delete_dispositivo = mysqli_query($con, "UPDATE dispositivo SET `borrado`='1' WHERE id_dispositivo='$nik'");
            
            $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                                   VALUES ('3', '10', '$nik', now(), '$user', '$etiqueta')") or die(mysqli_error());
            if(!$delete_dispositivo){
                $_SESSION['formSubmitted'] = 9;
                exec('php getNodes.php');
            }
 	}else if($t == 1){ //borro elemento Conexion
		    $cek = mysqli_query($con, "SELECT * FROM vinculo WHERE id_vinculo='$nik'");
            $cekd = mysqli_fetch_assoc($cek);
            $etiqueta = $cekd['etiqueta'];
            //Elimino Conexion
            $delete_conexion = mysqli_query($con, "UPDATE vinculo SET `borrado`='1' WHERE id_vinculo='$nik'");

            $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                                   VALUES ('3', '11', '$nik', now(), '$user', '$etiqueta')") or die(mysqli_error());
            if(!$delete_conexion){
                $_SESSION['formSubmitted'] = 9;
                exec('php getNodes.php');
            }
    }else if($t == 3){ //borro elemento Licencia
		    $cek = mysqli_query($con, "SELECT * FROM licencia WHERE id_licencia='$nik'");
            $cekd = mysqli_fetch_assoc($cek);
            $etiqueta = $cekd['etiqueta'];
            //Elimino Conexion
            $delete_licencia = mysqli_query($con, "UPDATE licencia SET `borrado`='1' WHERE id_licencia='$nik'");

            $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                                   VALUES ('3', '15', '$nik', now(), '$user', '$etiqueta')") or die(mysqli_error());
            if(!$delete_licencia){
                $_SESSION['formSubmitted'] = 9;
            }
}
}
//Alert icons data on top bar
//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$id_rowpg = $rowp['grupo'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['soc']=='0'){
	header('Location: ../site.php');
}

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				
		
?>
<style>
.dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}
</style>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SI-ARSAT</title>
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
<!-- daterange picker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

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
  </style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="../site.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">SI</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SI</b>-ARSAT</span>
    </a>

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
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nuevo proyecto guardado correctamente.</div>';
		$_SESSION['formSubmitted'] = 0;
	}	
	else if ($_SESSION['formSubmitted']=='3'){
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nueva persona guardada correctamente.</div>';
		$_SESSION['formSubmitted'] = 0;
	}
	else if ($_SESSION['formSubmitted']=='9'){
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al ejecutar el vuelco a la base de datos.</div>';
		$_SESSION['formSubmitted'] = 0;
	}?>	
	<section class="content-header">
      <h1>
        Gestión de Inventario SI
        <small>General</small>
      </h1>
    </section>
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
              <li class="active"><a href="#tab_1" data-toggle="tab">Dispositivos</a></li>
              <li><a href="#tab_2" data-toggle="tab">Conexiones</a></li>
              <li><a href="#tab_3" data-toggle="tab">Licencias</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="box">
                    <div class="box-header">
                        <div class="col-sm-12" style="text-align:right;">
                            <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-dispositivo"><i class="fa fa-list"></i> Nuevo Dispositivo</button>
                        </div>
                    </div>
                    <div class="box-body">
                      <table id="mis_proyectos" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                          <th width="2">Nro</th>
                          <th>Tipo</th>
                          <th>Etiqueta</th>
                          <th>Descripcion</th>
                          <th width="110px">Ubicación</th>
                          <th>Marca</th>
                          <th>Modelo</th>
                          <th>Interfaces</th>
                          <th>IP Address</th>
                          <th>Serial</th>
                          <th width="110px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT i.*, t.nombre, u.ubicacion FROM dispositivo as i 
                                      LEFT JOIN tipo_dispositivo as t on i.tipo = t.id_tipoDispositivo
                                      LEFT JOIN ubicacion as u on i.ubicacion = u.id_ubicacion
                                      WHERE i.borrado='0'";

                            $sql = mysqli_query($con, $query.' ORDER BY id_dispositivo ASC');

                            if(mysqli_num_rows($sql) == 0){
                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                            }else{
                                while($row = mysqli_fetch_assoc($sql)){

                                    echo '


                                    <td align="center">'.$row['id_dispositivo'].'</td>';


                                    echo '

                                    </td>								

                                    <td>'.$row['nombre'].'</td>

                                    ';
                                    
                                    echo '
                                    
                                    <td>'.$row['etiqueta'].'</td>'; 
                                    
                                    echo '
                                    </td>
                                    <td>'.$row['descripcion'].'</td>'; 
                                    echo '
                                    </td>
                                    <td>'.$row['ubicacion'].'</td>'; 
                                    echo '
                                    </td>
                                    <td>'.$row['marca'].'</td>'; 
                                    echo '
                                    </td>
                                    <td>'.$row['modelo'].'</td>';
                                    echo '
                                    </td>
                                    <td>'.$row['interfaces'].'</td>'; 
                                    echo '
                                    </td>
                                    <td>'.$row['ip_address'].'</td>'; 
                                    echo '
                                    </td>
                                    <td>'.$row['serial'].'</td>'; 
                                    
                                    echo '
                                    <td align="center">
                                    <a href="edit_dispositivo.php?nik='.$row['id_dispositivo'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="inventario.php?aksi=delete&t=0&nik='.$row['id_dispositivo'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['etiqueta'].'?\')" class="btn btn-danger btn-sm ';
                                    if ($rq_sec['edicion']=='0'){
                                            echo 'disabled';
                                    }
                                    echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                    </tr>
                                    ';
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <tr>
                          <th width="2">Nro</th>
                          <th>Tipo</th>
                          <th>Etiqueta</th>
                          <th>Descripcion</th>
                          <th>Ubicación</th>
                          <th>Marca</th>
                          <th>Modelo</th>
                          <th>Interfaces</th>
                          <th>IP Address</th>
                          <th>Serial</th>
                          <th width="110px">Acciones</th>
                        </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
              </div>
              <div class="tab-pane" id="tab_2">
                <div class="box">
                    <div class="box-header">
                        <div class="col-sm-12" style="text-align:right;">
                            <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-conexion"><i class="fa fa-list"></i> Nueva Conexión</button>
                        </div>
                    </div>
                    <div class="box-body">
                      <table id="mi_grupo" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                          <th width="2">Nro</th>
                          <th width="2">Etiqueta</th>
                          <th>Descripcion</th>
                          <th width="2">VLans</th>
                          <th width="2">Velocidad</th>
                          <th width="2">Segurizado</th>
                          <th width="2">PortChannel</th>
                          <th width="2">Medio</th>
                          <th>Origen</th>
                          <th>Destino</th>    
                          <th width="110px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT v.*, ds.etiqueta as origen, dt.etiqueta as destino FROM vinculo as v
                                      LEFT JOIN dispositivo as ds on v.source = ds.id_dispositivo
                                      LEFT JOIN dispositivo as dt on v.target = dt.id_dispositivo
                                      WHERE v.borrado='0'";

                            $sql = mysqli_query($con, $query.' ORDER BY v.id_vinculo ASC');

                            if(mysqli_num_rows($sql) == 0){
                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                            }else{
                                while($row = mysqli_fetch_assoc($sql)){

                                    echo '


                                    <td align="center">'.$row['id_vinculo'].'</td>';


                                    echo '

                                    </td>								

                                    <td>'.$row['nombre'].'</td>

                                    ';
                                    echo '

                                    								

                                    <td>'.$row['descripcion'].'</td>

                                    ';
                                    echo '

                                    								

                                    <td>'.$row['vlans'].'</td>

                                    ';
                                    echo '

                                   								

                                    <td>'.$row['velocidad'].' Mbps</td>

                                    ';
                                    if($row['segurizado'] == '1'){
                                        echo '<td>Si</td>';
                                    }
                                    else {
                                        echo '<td>No</td>';
                                    }
                                    
                                    if($row['portchannel'] == '1'){
                                        echo '<td>Si</td>';
                                    }
                                    else {
                                        echo '<td>No</td>';
                                    }
                                    
                                    if($row['cobre'] == '1'){
                                        echo '<td>Cobre</td>';
                                    }
                                    else {
                                        echo '<td>Fibra</td>';
                                    }
                                    echo '

                                    
                                    <td>'.$row['origen'].'</td>

                                    ';
                                    echo '

                                   								

                                    <td>'.$row['destino'].'</td>

                                    ';
                                    echo '
                                    <td align="center">
                                    <a href="edit_conexion.php?nik='.$row['id_vinculo'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="inventario.php?aksi=delete&t=1&nik='.$row['id_vinculo'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['etiqueta'].'?\')" class="btn btn-danger btn-sm ';
                                    if ($rq_sec['edicion']=='0'){
                                            echo 'disabled';
                                    }
                                    echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                    </tr>
                                    ';
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <tr>
                          <th width="2">Nro</th>
                          <th>Etiqueta</th>
                          <th>Descripcion</th>
                          <th>VLans</th>
                          <th width="2">Velocidad</th>
                          <th width="2">Segurizado</th>
                          <th width="2">PortChannel</th>
                          <th width="2">Medio</th>
                          <th>Origen</th>
                          <th>Destino</th>    
                          <th width="110px">Acciones</th>                       </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
              </div>
              <div class="tab-pane" id="tab_3">
                <div class="box">
                    <div class="box-header">
                        <div class="col-sm-12" style="text-align:right;">
                            <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-licencia"><i class="fa fa-list"></i> Nueva Licencia</button>
                        </div>
                    </div>
                    <div class="box-body">
                      <table id="mi_grupo" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                          <th width="2">Nro</th>
                          <th width="100">Etiqueta</th>
                          <th>Descripcion</th>
                          <th width="100">Fabricante</th>
                          <th width="100">Partner</th>
                          <th width="2">Soporte</th>
                          <th width="100">Serial</th>
                          <th width="2">Vencimiento</th>
                          <th width="200">Dispositivo</th>
                          <th width="110px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT l.id_licencia, d.etiqueta as dispositivo, l.etiqueta, l.descripcion, l.fabricante, l.partner,                      l.serial, l.soporte, l.vencimiento
                                        FROM licencia as l
                                        LEFT JOIN dispositivo as d ON l.dispositivo=d.id_dispositivo
                                        WHERE l.borrado='0'";

                            $sql = mysqli_query($con, $query.' ORDER BY l.id_licencia ASC');

                            if(mysqli_num_rows($sql) == 0){
                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                            }else{
                                while($row = mysqli_fetch_assoc($sql)){

                                    echo '


                                    <td align="center">'.$row['id_licencia'].'</td>';


                                    echo '

                                    </td>								

                                    <td>'.$row['etiqueta'].'</td>

                                    ';
                                    echo '

                                    								

                                    <td>'.$row['descripcion'].'</td>

                                    ';
                                    echo '

                                    								

                                    <td>'.$row['fabricante'].'</td>

                                    ';
                                    echo '

                                   								

                                    <td>'.$row['partner'].'</td>

                                    ';
                                    if($row['soporte'] == '1'){
                                        echo '<td>Si</td>';
                                    }
                                    else {
                                        echo '<td>No</td>';
                                    }
                                    
                                    
                                    echo '

                                    
                                    <td>'.$row['serial'].'</td>

                                    ';
                                    echo '

                                   								

                                    <td>'.$row['vencimiento'].'</td>

                                    ';
                                    echo '

                                   								

                                    <td>'.$row['dispositivo'].'</td>

                                    ';
                                    echo '
                                    <td align="center">
                                    <a href="edit_licencia.php?nik='.$row['id_licencia'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="inventario.php?aksi=delete&t=3&nik='.$row['id_licencia'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['etiqueta'].'?\')" class="btn btn-danger btn-sm ';
                                    if ($rq_sec['edicion']=='0'){
                                            echo 'disabled';
                                    }
                                    echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                    </tr>
                                    ';
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <tr>
                          <th width="2">Nro</th>
                          <th width="100">Etiqueta</th>
                          <th>Descripcion</th>
                          <th width="100">Fabricante</th>
                          <th width="100">Partner</th>
                          <th width="2">Soporte</th>
                          <th width="100">Serial</th>
                          <th width="2">Vencimiento</th>
                          <th width="200">Dispositivo</th>
                          <th width="110px">Acciones</th>
                        </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div> 
                  <!-- /.TAB 3 CONTENT -->
               </div>
           <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
    <div class="modal fade" id="modal-dispositivo">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Inventario >> Nuevo Dispositivo</h2>
          </div>
          <div class="modal-body">
            <div class="box box-primary">
        <!-- /.box-header -->
        <?php
          if(isset($_POST['Addd'])){
                $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));
                $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
                $etiqueta = mysqli_real_escape_string($con,(strip_tags($_POST["etiqueta"],ENT_QUOTES)));
                $ubicacion = mysqli_real_escape_string($con,(strip_tags($_POST["ubicacion"],ENT_QUOTES)));
                $marca = mysqli_real_escape_string($con,(strip_tags($_POST["marca"],ENT_QUOTES)));
                $modelo = mysqli_real_escape_string($con,(strip_tags($_POST["modelo"],ENT_QUOTES)));
                $interfaces = mysqli_real_escape_string($con,(strip_tags($_POST["interfaces"],ENT_QUOTES)));
                $ip_address = mysqli_real_escape_string($con,(strip_tags($_POST["ip_address"],ENT_QUOTES)));
                $serial = mysqli_real_escape_string($con,(strip_tags($_POST["serial"],ENT_QUOTES)));
                
                $insert_dispositivo = mysqli_query($con, "INSERT INTO dispositivo (tipo, descripcion, etiqueta, ubicacion, marca, modelo, interfaces, ip_address, serial, creado, user) VALUES ('$tipo', '$descripcion', '$etiqueta', '$ubicacion', '$marca', '$modelo', '$interfaces', '$ip_address', '$serial', NOW(), '$user')") or die(mysqli_error());	

                $lastInsert = mysqli_insert_id($con);
                $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                           VALUES ('1', '10', '$lastInsert', now(), '$user', '$etiqueta')") or die(mysqli_error());
                unset($_POST);
                if($insert_dispositivo){
                    $_SESSION['formSubmitted'] = 2;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                    exec('php getNodes.php');
                }else{
                    $_SESSION['formSubmitted'] = 9;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                }
            }
            ?>
        <!-- form start -->
        <form method="post" role="form" action="">
          <div class="box-body">
            <div class="form-group">
              <label for="etiqueta">Etiqueta</label>
              <input type="text" class="form-control" name="etiqueta" placeholder="Etiqueta del dispositivo" required>
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" rows="2" name="descripcion" placeholder="Descripción ..." required></textarea>
            </div>
            <div class="form-group">
              <label>Tipo de dispositivo</label>
              <select name="tipo" class="form-control">
                    <?php
                        $tipos = mysqli_query($con, "SELECT * FROM tipo_dispositivo");
                        while($rowtd = mysqli_fetch_array($tipos)){
                            echo "<option value='". $rowtd['id_tipoDispositivo'] . "'>" .$rowtd['nombre'] . "</option>";				
                        }
                    ?>
              </select>
            </div>
            <div class="form-group">
              <label>Ubicación del dispositivo</label>
              <select name="ubicacion" class="form-control">
                    <?php
                        $ubicaciones = mysqli_query($con, "SELECT * FROM ubicacion");
                        while($rowub = mysqli_fetch_array($ubicaciones)){
                            echo "<option value='". $rowub['id_ubicacion'] . "'>" .$rowub['ubicacion'] . "</option>";				
                        }
                    ?>
              </select>
            </div>
            <div class="form-group">
              <label for="marca">Marca</label>
              <input type="text" class="form-control" name="marca" placeholder="Marca del dispositivo" required>
            </div>
            <div class="form-group">
              <label for="modelo">Modelo</label>
              <input type="text" class="form-control" name="modelo" placeholder="Modelo del dispositivo" required>
            </div>
            <div class="form-group">
              <label for="interfaces">Cantidad de Interfaces</label>
              <input type="text" class="form-control" name="interfaces" placeholder="Cantidad de interfaces del dispositivo">
            </div>
            <div class="form-group">
              <label for="ip_address">Dirección IP</label>
              <input type="text" class="form-control" name="ip_address" placeholder="Dirección IP de management">
            </div>
            <div class="form-group">
              <label for="serial">Nro de Serie</label>
              <input type="text" class="form-control" name="serial" placeholder="Nro de serie del dispositivo">
            </div>
              <div class="form-group">
                <div class="col-sm-3">
                    <input type="submit" name="Addd" class="btn  btn-raised btn-success" value="Guardar datos">
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
          </div>
        </form>
      </div>

          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    
    <div class="modal fade" id="modal-conexion">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Inventario >> Nueva Conexión</h2>
          </div>
          <div class="modal-body">
            <div class="box box-primary">
        <!-- /.box-header -->
        <?php
          if(isset($_POST['Addc'])){
                $medio = $_POST["medio"];
                $cobre = '0';
                $fibra = '0';
                if($medio == '1'){
                    $cobre = '1';
                }else{
                    $fibra = '1';
                }
              
                $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
                $nombre = mysqli_real_escape_string($con,(strip_tags($_POST["etiqueta"],ENT_QUOTES)));
                $vlans = mysqli_real_escape_string($con,(strip_tags($_POST["vlans"],ENT_QUOTES)));
                $velocidad = mysqli_real_escape_string($con,(strip_tags($_POST["velocidad"],ENT_QUOTES)));
                $segurizado = mysqli_real_escape_string($con,(strip_tags($_POST["segurizado"],ENT_QUOTES)));
                $portchannel = mysqli_real_escape_string($con,(strip_tags($_POST["portchannel"],ENT_QUOTES)));
                $source = mysqli_real_escape_string($con,(strip_tags($_POST["source"],ENT_QUOTES)));
                $target = mysqli_real_escape_string($con,(strip_tags($_POST["target"],ENT_QUOTES)));
                
                $insert_conexion = mysqli_query($con, "INSERT INTO vinculo (descripcion, nombre, vlans, velocidad, segurizado, portchannel, cobre, fibra, source, target, creado, user) VALUES ('$descripcion', '$nombre', '$vlans', '$velocidad', '$segurizado', '$portchannel', '$cobre', '$fibra', '$source', '$target', NOW(), '$user')") or die(mysqli_error());

                $lastInsert = mysqli_insert_id($con);
                $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                           VALUES ('1', '11', '$lastInsert', now(), '$user', '$etiqueta')") or die(mysqli_error());
                unset($_POST);
                if($insert_conexion){
                    $_SESSION['formSubmitted'] = 2;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                    exec('php getNodes.php');
                }else{
                    $_SESSION['formSubmitted'] = 9;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                }
            }
            ?>
        <!-- form start -->
        <form method="post" role="form" action="">
          <div class="box-body">
            <div class="form-group">
              <label for="etiqueta">Etiqueta</label>
              <input type="text" class="form-control" name="etiqueta" placeholder="Etiqueta de la conexión" required>
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" rows="2" name="descripcion" placeholder="Descripción ..." required></textarea>
            </div>
            <div class="form-group">
              <label for="vlans">VLans que trafican</label>
              <input type="text" class="form-control" name="vlans" placeholder="VLan ID separadas por coma">
            </div>
            <div class="form-group">
                <label>velocidad</label>
                <select name="velocidad" class="form-control">
                    <option value='10'>10 Mbps</option>
                    <option value='100'>100 Mbps</option>
                    <option value='1000'>1 Gbps</option>
                    <option value='10000'>10 Gbps</option>
                </select>
            </div>
            <div class="form-group">
                <label>Medio</label>
                <select name="medio" class="form-control">
                    <option value='1'>Cobre</option>
                    <option value='2'>Fibra</option>
                </select>
            </div>
            <div class="checkbox">
              <label>
                  <input name="segurizado" type="checkbox" value="1"> Está segurizado?
             </label>
            </div>
            <div class="checkbox">
              <label>
                  <input name="portchannel" type="checkbox" value="1"> Es un Port Channel?
             </label>
            </div>
            
            <div class="form-group">
              <label>Origen</label>
              <select name="source" class="form-control">
                    <?php
                        $dev_orig = mysqli_query($con, "SELECT * FROM dispositivo");
                        while($rowdo = mysqli_fetch_array($dev_orig)){
                            echo "<option value='". $rowdo['id_dispositivo'] . "'>" .$rowdo['etiqueta'] . "</option>";				
                        }
                    ?>
              </select>
            </div>
            <div class="form-group">
              <label>Destino</label>
              <select name="target" class="form-control">
                    <?php
                        $dev_dest = mysqli_query($con, "SELECT * FROM dispositivo");
                        while($rowdd = mysqli_fetch_array($dev_dest)){
                            echo "<option value='". $rowdd['id_dispositivo'] . "'>" .$rowdd['etiqueta'] . "</option>";				
                        }
                    ?>
              </select>
            </div>
              <div class="form-group">
                <div class="col-sm-3">
                    <input type="submit" name="Addc" class="btn  btn-raised btn-success" value="Guardar datos">
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
          </div>
        </form>
      </div>

          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-licencia">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Inventario >> Nueva Licencia</h2>
          </div>
          <div class="modal-body">
            <div class="box box-primary">
        <!-- /.box-header -->
        <?php
          if(isset($_POST['Addlic'])){
                $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
                $etiqueta = mysqli_real_escape_string($con,(strip_tags($_POST["etiqueta"],ENT_QUOTES)));
                $fabricante = mysqli_real_escape_string($con,(strip_tags($_POST["fabricante"],ENT_QUOTES)));
                $partner = mysqli_real_escape_string($con,(strip_tags($_POST["partner"],ENT_QUOTES)));
                $soporte = mysqli_real_escape_string($con,(strip_tags($_POST["soporte"],ENT_QUOTES)));
                $serial = mysqli_real_escape_string($con,(strip_tags($_POST["serial"],ENT_QUOTES)));
                $vencimiento = mysqli_real_escape_string($con,(strip_tags($_POST["vencimiento"],ENT_QUOTES)));
                $dispositivo = mysqli_real_escape_string($con,(strip_tags($_POST["dispositivo"],ENT_QUOTES)));
                
                $insert_licencia = mysqli_query($con, "INSERT INTO licencia (descripcion, etiqueta, fabricante, partner, soporte, serial, vencimiento, dispositivo, creado, user) VALUES ('$descripcion', '$etiqueta', '$fabricante', '$partner', '$soporte', '$serial', '$vencimiento', '$dispositivo', NOW(), '$user')") or die(mysqli_error());	

                $lastInsert = mysqli_insert_id($con);
                $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                           VALUES ('1', '15', '$lastInsert', now(), '$user', '$etiqueta')") or die(mysqli_error());
                unset($_POST);
                if($insert_licencia){
                    $_SESSION['formSubmitted'] = 2;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                    exec('php getNodes.php');
                }else{
                    $_SESSION['formSubmitted'] = 9;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                }
            }
            ?>
        <!-- form start -->
        <form method="post" role="form" action="">
          <div class="box-body">
            <div class="form-group">
              <label for="etiqueta">Etiqueta</label>
              <input type="text" class="form-control" name="etiqueta" placeholder="Etiqueta del dispositivo" required>
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" rows="2" name="descripcion" placeholder="Descripción ..." required></textarea>
            </div>
           <div class="form-group">
              <label for="fabricante">Fabricante</label>
              <input type="text" class="form-control" name="fabricante" placeholder="Fabricante del producto" required>
            </div>
            <div class="form-group">
              <label for="partner">Partner</label>
              <input type="text" class="form-control" name="partner" placeholder="Partner contratado" required>
            </div>
            <div class="form-group">
                <label>Soporte</label>
                <select name="soporte" class="form-control">
                    <option value='0'>NO</option>
                    <option value='1'>SI</option>
                </select>
            </div>
            <div class="form-group">
              <label>Dispositivo asociado</label>
              <select name="dispositivo" class="form-control">
                    <?php
                        $dispos = mysqli_query($con, "SELECT * FROM dispositivo");
                        while($rowdis = mysqli_fetch_array($dispos)){
                            echo "<option value='". $rowdis['id_dispositivo'] . "'>" .$rowdis['etiqueta'] . "</option>";				
                        }
                    ?>
              </select>
            </div>
            <div class="form-group">
              <label for="serial">Serial</label>
              <input type="text" class="form-control" name="serial" placeholder="Serial / Key">
            </div>
            <div class="form-group">
                <label>Fecha de vencimiento</label>
                <div class="input-group date" data-provide="datepickerv">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" name="vencimiento" id="datepickerv">
                </div>
            </div>
               <div class="form-group">
                <div class="col-sm-3">
                    <input type="submit" name="Addlic" class="btn  btn-raised btn-success" value="Guardar datos">
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
          </div>
        </form>
      </div>

          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Portal de Gestión
    </div>
    <!-- Default to the left -->
    <strong>Seguridad Informática  - <a href="../site.php">ARSAT S.A.</a></strong>
  </footer>

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
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

      
<script>
  $(function () {
    $('#proyectos').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,

    })
  })
</script>
<script>
  $(function () {
    $('#mis_proyectos').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
    })
  })
</script>
<script>
  $(function () {
    $('#mi_grupo').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
    })
  })
</script>       
<script>
    window.onload = function() {
        history.replaceState("", "", "inventario.php");
    }
</script>
<script>
$(function(){
  $(".ver-itemDialog").click(function(){
    $('#itemId').val($(this).data('id'));
	$('#titulo').val($(this).data('titulo'));
	$('#descripcion').val($(this).data('descripcion'));
	$('#responsable').val($(this).data('responsable'));
    $('#inicio').val($(this).data('inicio'));
    $('#due_date').val($(this).data('due_date'));
    $('#porcentaje').val($(this).data('porcentaje')+" %");
    $('#avance').val($(this).data('avance'));
   

	if($(this).data('categoria') == '1') {
		$('#categoria').val('Proyecto Nuevo')}
	else if($(this).data('categoria') == '2'){
		$('#categoria').val('Proyecto de Mejora')}
	else if($(this).data('categoria') == '3'){
		$('#categoria').val('Tarea')};
    
    if($(this).data('prioridad') == '1') {
		$('#prioridad').val('Alta')}
	else if($(this).data('prioridad') == '2'){
		$('#prioridad').val('Media')}
	else if($(this).data('prioridad') == '3'){
		$('#prioridad').val('Baja')};
      
    if($(this).data('estado') == '1') {
		$('#estado').val('No Iniciada')}
	else if($(this).data('estado') == '2'){
		$('#estado').val('En Curso')}
	else if($(this).data('estado') == '3'){
		$('#estado').val('Aplazada')}
    else if($(this).data('estado') == '4'){
		$('#estado').val('Completada')};
	console.log("llega");
	$("#ver-itemDialog").modal("show");
	
  });
});
</script>
<script type="text/javascript">
  $(function () {

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
     $('#datepickerv').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayHighlight: true
    })
  })
</script>
<script>
function myFunction() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("proyectos");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
</script>
<script>
$('a[data-toggle="tab"]').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    var id = $(e.target).attr("href");
    localStorage.setItem('selectedTab', id)
});

var selectedTab = localStorage.getItem('selectedTab');
if (selectedTab != null) {
    $('a[data-toggle="tab"][href="' + selectedTab + '"]').tab('show');
}
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>