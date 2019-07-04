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
	$cek = mysqli_query($con, "SELECT * FROM mejora WHERE id_mejora='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $titulo = $cekd['problema'];
    
    if(mysqli_num_rows($cek) == 0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
	}else{
		//Elimino mejora
		
        $delete_mejora = mysqli_query($con, "UPDATE mejora SET `borrado`='1' WHERE id_mejora='$nik'");
      
        $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('3', '9', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
		if(!$delete_mejora){
			$_SESSION['formSubmitted'] = 9;
		}
	}
}

//Alert icons data on top bar


//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				

if ($rq_sec['compliance']=='0'){
	header('Location: ../site.php');
}
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
  <link rel="stylesheet" href="../bower_components/datatables.net/css/jquery.dataTables.min.css">

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
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nueva mejora guardada correctamente.</div>';
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
        Gestión de Mejora Continua
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
              <li class="active"><a href="#tab_1" data-toggle="tab">Listado</a></li>
              <li><a href="#tab_2" data-toggle="tab">Indicadores</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">  
          <div class="box">
            <div class="box-header">
				<div class="col-sm-6" style="text-align:left">
					<h2 class="box-title">Listado de Mejoras</h2>
				</div>
 				<div class="col-sm-6" style="text-align:right;">
					<button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-mejora"><i class="fa fa-refresh"></i> Nueva Mejora</button>
				</div>
            </div>
		<div class="modal fade" id="modal-mejora">
          <div class="modal-dialog" style="width:850px;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Mejoras >> Nueva Mejora</h2>
              </div>
              <div class="modal-body">
                <div class="box box-primary">
            <!-- /.box-header -->
			<?php
				
				if(isset($_POST['Add'])){
					$origen = mysqli_real_escape_string($con,(strip_tags($_POST["origen"],ENT_QUOTES)));
                    $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
                    $responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
                    $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));
                    $abierto = mysqli_real_escape_string($con,(strip_tags($_POST["abierto"],ENT_QUOTES)));
                    $causa = mysqli_real_escape_string($con,(strip_tags($_POST["causa"],ENT_QUOTES)));
                    $plan = mysqli_real_escape_string($con,(strip_tags($_POST["plan"],ENT_QUOTES)));
                    $estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));
                    $eficacia = mysqli_real_escape_string($con,(strip_tags($_POST["eficacia"],ENT_QUOTES)));
                    $evidencia = mysqli_real_escape_string($con,(strip_tags($_POST["evidencia"],ENT_QUOTES)));
                    $esfuerzo = mysqli_real_escape_string($con,(strip_tags($_POST["esfuerzo"],ENT_QUOTES)));
                    $costo = mysqli_real_escape_string($con,(strip_tags($_POST["costo"],ENT_QUOTES)));
                    $apertura = mysqli_real_escape_string($con,(strip_tags($_POST["apertura"],ENT_QUOTES)));
                    $cierre = mysqli_real_escape_string($con,(strip_tags($_POST["cierre"],ENT_QUOTES)));
                    $implementacion = mysqli_real_escape_string($con,(strip_tags($_POST["implementacion"],ENT_QUOTES)));
                    
					$insert_mejora = mysqli_query($con, "INSERT INTO mejora SET descripcion='$descripcion', responsable='$responsable', abierto='$abierto', tipo='$tipo', causa='$causa', plan='$plan', estado='$estado', eficacia='$eficacia', evidencia='$evidencia', esfuerzo='$esfuerzo', costo='$costo', apertura='$apertura', cierre='$cierre', implementacion='$implementacion', origen='$origen', creado=NOW(), usuario='$user' ") or die(mysqli_error());	

                    $lastInsert = mysqli_insert_id($con);
					$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('1', '9', '$lastInsert', now(), '$user', '$lastInsert')") or die(mysqli_error());
					unset($_POST);
					if($insert_mejora){
						$_SESSION['formSubmitted'] = 2;
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
					}else{
						$_SESSION['formSubmitted'] = 9;
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
					}
				}
				?>
            <!-- form start -->
            <form method="post" role="form" action="">
              <div class="box-body">
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-4">
                            <label for="origen">Origen</label>
                            <select name="origen" class="form-control">
                            <?php
                                    $origenes = mysqli_query($con, "SELECT * FROM origen");
                                    while($rowpo = mysqli_fetch_array($origenes)){
                                        if($rowpo['id_origen']==$row['origen']) {
                                            echo "<option value='". $rowpo['id_origen'] . "' selected='selected'>" .$rowpo['descripcion'] . "</option>";
                                        }
                                        else {
                                            echo "<option value='". $rowpo['id_origen'] . "'>" .$rowpo['descripcion'] . "</option>";			    }
                                    }
                            ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                           <label for="tipo">Tipo</label>
                            <select name="tipo" class="form-control">
                                <option value='1'<?php if($row['tipo'] == '1'){ echo 'selected'; } ?>>NC-No Conformidad sin AC</option>
                                <option value='2'<?php if($row['tipo'] == '2'){ echo 'selected'; } ?>>AC-Acción Correctiva</option>
                                <option value='3'<?php if($row['tipo'] == '3'){ echo 'selected'; } ?>>AM-Acción Mejora</option>
                             </select>
                        </div>
                      </div>
                    </div><br>
                   <div class="container">
                      <div class="row">
                        <div class="col-sm-4">
                           <label for="abierto">Abierto por</label>
                            <select name="abierto" class="form-control">
                            <?php
                                    $personasn = mysqli_query($con, "SELECT * FROM persona");
                                    while($rowps = mysqli_fetch_array($personasn)){
                                        if($rowps['id_persona']==$row['abierto']) {
                                            echo "<option value='". $rowps['id_persona'] . "' selected='selected'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";
                                        }
                                        else {
                                            echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                        }
                                    }
                            ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                           <label for="responsable">Responsable</label>
                            <select name="responsable" class="form-control">
                            <?php
                                    $personasn = mysqli_query($con, "SELECT * FROM persona");
                                    while($rowps = mysqli_fetch_array($personasn)){
                                        if($rowps['id_persona']==$row['responsable']) {
                                            echo "<option value='". $rowps['id_persona'] . "' selected='selected'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";
                                        }
                                        else {
                                            echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                        }
                                    }
                            ?>
                        </select>
                      </div>
                    </div>
                    </div><br>
                   <div class="form-group">
                        <label for="descripcion">Identificación del problema / descripción de la mejora</label>
                         <?php echo "<textarea class=form-control name=descripcion>{$row['descripcion']}</textarea>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="causa">Análisis de causas</label>
                          <?php echo "<textarea class=form-control name=causa>{$row['causa']}</textarea>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="plan">Plan de Acción</label>
                         <?php echo "<textarea class=form-control name=plan>{$row['plan']}</textarea>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="eficacia">Verificación de la eficacia del plan de acción</label>
                         <?php echo "<textarea class=form-control name=eficacia>{$row['eficacia']}</textarea>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="evidencia">Evidencia / Cierre</label>
                         <?php echo "<textarea class=form-control name=evidencia>{$row['evidencia']}</textarea>"; ?>
                    </div>
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-3">
                            <label for="esfuerzo">Esfuerzo</label>
                            <select name="esfuerzo" class="form-control">
                                <option value='1'<?php if($row['esfuerzo'] == '1'){ echo 'selected'; } ?>>Muy Bajo</option>
                                <option value='2'<?php if($row['esfuerzo'] == '2'){ echo 'selected'; } ?>>Moderado</option>
                                <option value='3'<?php if($row['esfuerzo'] == '3'){ echo 'selected'; } ?>>Muy Alto</option>
                             </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="costo">% Avance</label>
                            <input type="text" class="form-control" name="costo" value="<?php echo $row ['costo']; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-3">
                            <label for="apertura"> Fecha Apertura</label>
                            <div class="input-group date" data-provide="datepicker5">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                                <input type="text" class="form-control pull-right" name="apertura" id="datepicker5" value="<?php echo $row ['apertura']; ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="correccion">Fecha Corrección</label>
                            <div class="input-group date" data-provide="datepicker6">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                                <input type="text" class="form-control pull-right" name="correccion" id="datepicker6" value="<?php echo $row ['correccion']; ?>">
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-3">
                            <label for="cierre">Fecha de Cierre</label>
                            <div class="input-group date" data-provide="datepicker7">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                                <input type="text" class="form-control pull-right" name="cierre" id="datepicker7" value="<?php echo $row ['cierre']; ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="implementacion">Fecha de Implementación</label>
                               <div class="input-group date" data-provide="datepicker8">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                    <input type="text" class="form-control pull-right" name="implementacion" id="datepicker8" value="<?php echo $row ['implementacion']; ?>">
                                </div>
                        </div>
                      </div>
                    </div><br>
                    <div class="form-group">
                           <label for="estado">Estado</label>
                            <select name="estado" class="form-control">
                                <option value='0'<?php if($row['estado'] == '0'){ echo 'selected'; } ?>>Abierto</option>
                                <option value='1'<?php if($row['estado'] == '1'){ echo 'selected'; } ?>>Cerrado</option>
                             
                             </select>
                    </div><br>
				 <div class="form-group">
					<div class="col-sm-3">
						<input type="submit" name="Add" class="btn  btn-raised btn-success" value="Guardar datos">
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
        <!-- /.modal Activo-->
		<div class="modal fade" id="modal-persona">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Nueva Persona</h2>
				<?php
				$gerencias = mysqli_query($con, "SELECT * FROM gerencia ORDER BY nombre ASC");
				if(isset($_POST['Addp'])){
					$legajo = mysqli_real_escape_string($con,(strip_tags($_POST["legajo"],ENT_QUOTES)));//Escanpando caracteres
					$nombre = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres
					$apellido = mysqli_real_escape_string($con,(strip_tags($_POST["apellido"],ENT_QUOTES)));//Escanpando caracteres 
					$cargo = mysqli_real_escape_string($con,(strip_tags($_POST["cargo"],ENT_QUOTES)));//Escanpando caracteres 
					$gerencia = mysqli_real_escape_string($con,(strip_tags($_POST["gerencia"],ENT_QUOTES)));//Escanpando caracteres 
					$email = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres 
					//Inserto Control
					$insert_persona = mysqli_query($con, "INSERT INTO persona(legajo, nombre, apellido, cargo, gerencia, email) VALUES ('$legajo','$nombre','$apellido', '$cargo', '$gerencia', '$email')") or die(mysqli_error());	
					$lastInsert = mysqli_insert_id($con);
					$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
											   VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
					unset($_POST);
					if($insert_persona){
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
                  <label for="legajo">Legajo</label>
                  <input type="text" class="form-control" name="legajo" placeholder="Legajo">
                </div>
                <div class="form-group">
                  <label for="nombre">Nombre</label>
                  <input type="text" class="form-control" name="nombre" placeholder="Nombre">
                </div>
				<div class="form-group">
                  <label for="apellido">Apellido</label>
                  <input type="text" class="form-control" name="apellido" placeholder="Apellido">
                </div>
				<div class="form-group">
                  <label for="email">Dirección E-mail</label>
                  <input type="text" class="form-control" name="email" placeholder="E-mail corporativo">
                </div>
				<div class="form-group">
                  <label for="cargo">Cargo</label>
                  <input type="text" class="form-control" name="cargo" placeholder="Cargo">
                </div>
				
				<div class="form-group">
                  <label>Gerencia</label>
                  <select name="gerencia" class="form-control">
						<?php
							while($rowg = mysqli_fetch_array($gerencias)){
									echo "<option value=". $rowg['id_gerencia'] . ">" .$rowg['nombre'] . "</option>";
									}
						?>
                  </select>
                </div>
				<div class="form-group">
					<div class="col-sm-3">
						<input type="submit" name="Addp" class="btn  btn-raised btn-success" value="Guardar datos">
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
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
        <!-- /.modal Persona -->
            <!-- /.box-header -->
		<div id="ver-itemDialog" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						<h2 class="modal-title">Mejora Continua >> Ver Ítem</h2>
                        <input type="text" class="form-control" name="estado" id="estado" value="" readonly>
					</div>
					<div class="box box-primary">
						<div class="modal-body">
							<div class="container">
                              <div class="row">
                                <div class="col-sm-3">
                                    <label for="origen">Origen</label>
								    <input type="text" class="form-control" name="origen" id="origen" value="" readonly>
                                </div>
                                <div class="col-sm-3">
                                   <label for="tipo">Tipo</label>
								    <input type="text" class="form-control" name="tipo" id="tipo" value="" readonly>
                                </div>
                              </div>
                            </div><br>
                           <div class="container">
                              <div class="row">
                                <div class="col-sm-3">
                                   <label for="abierto">Abierto por</label>
								    <input type="text" class="form-control" name="abierto" id="abierto" value="" readonly>
                                </div>
                                <div class="col-sm-3">
                                   <label for="responsable">Responsable</label>
								    <input type="text" class="form-control" name="responsable" id="responsable" value="" readonly>
                              </div>
                            </div>
                            </div><br>
                           <div class="form-group">
								<label for="descripcion">Identificación del problema / descripción de la mejora</label>
								<textarea class="form-control" rows="3" name="descripcion" id="descripcion" value="" readonly></textarea>
							</div>
                            <div class="form-group">
								<label for="causa">Análisis de causas</label>
								<textarea class="form-control" rows="3" name="causa" id="causa" value="" readonly></textarea>
							</div>
							<div class="form-group">
								<label for="plan">Plan de Acción</label>
								<textarea class="form-control" rows="3" name="plan" id="plan" value="" readonly></textarea>
							</div>
                            <div class="form-group">
								<label for="eficacia">Verificación de la eficacia del plan de acción</label>
								<textarea class="form-control" rows="3" name="eficacia" id="eficacia" value="" readonly></textarea>
							</div>
							<div class="form-group">
								<label for="evidencia">Evidencia / Cierre</label>
								<textarea class="form-control" rows="3" name="evidencia" id="evidencia" value="" readonly></textarea>
							</div>
							<div class="container">
                              <div class="row">
                                <div class="col-sm-3">
                                    <label for="esfuerzo">Esfuerzo</label>
								    <input type="text" class="form-control" name="esfuerzo" id="esfuerzo" value="" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label for="costo">% Avance</label>
								    <input type="text" class="form-control" name="costo" id="costo" value="" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="container">
                              <div class="row">
                                <div class="col-sm-3">
                                    <label for="apertura"> Fecha Apertura</label>
								    <input type="text" class="form-control" name="apertura" id="apertura" value="" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label for="correccion">Fecha Corrección</label>
								    <input type="text" class="form-control" name="correccion" id="correccion" value="" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="container">
                              <div class="row">
                                <div class="col-sm-3">
                                    <label for="cierre">Fecha de Cierre</label>
								    <input type="text" class="form-control" name="cierre" id="cierre" value="" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label for="implementacion">Fecha de Implementación</label>
								    <input type="text" class="form-control" name="implementacion" id="implementacion" value="" readonly>
                                </div>
                              </div>
                            </div>
							
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
					</div>	
 				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->	
		
			<div class="box-body">
              <table id="mejoras" class="display" width="100%">
                <thead>
                <tr>
                  <th width="1">Ver</th>
				          <th width="2">Nro</th>
                  <th width="120">Origen</th>
                  <th>Estado</th>
                  <th>Responsable</th>
				          <th>Tipo</th>
                  <th>Esfuerzo</th>
                  <th>% Avance</th>
                  <th>Descripcion</th>
                  
                  <th>Abierto</th>
                  <th>Fecha Apertura</th>
                  <th>Fecha Cierre</th>
                  <th>Fecha Implementacion</th>
                  <th>Causa</th>
                  <th>Plan</th>
                  <th>Eficacia</th>
                  <th>Evidencia</th>

                  <th width="110px">Acciones</th>
                </tr>
                </thead>
                <tbody>
					      <?php
                $query = "SELECT i.*, p.nombre, p.apellido, o.descripcion as dorig, op.nombre as opn, op.apellido as opa FROM mejora as i 
                                    LEFT JOIN persona as p on i.responsable = p.id_persona
                                    LEFT JOIN persona as op on i.abierto = op.id_persona
                                    LEFT JOIN origen as o on i.origen = o.id_origen
                          WHERE i.borrado='0' ";
                // AGREGO EL FILTRO DE GERENCIA DEL USUARIO=CIBERSEGURIDAD O LA GERENCIA DEL REFERENTE
                if ( $per_id_gerencia != 1) {
                  $query = $query . " AND p.gerencia = $per_id_gerencia ";
                }                          
                $sql = mysqli_query($con, $query . 'ORDER BY i.id_mejora ASC');

                if(mysqli_num_rows($sql) == 0){
                  echo '<tr><td colspan="8">No hay datos.</td></tr>';
                }else{
                  $no = 1;
                  while($row = mysqli_fetch_assoc($sql)){
                    echo '<tr>';

                    echo '<td>
                    <a data-id="'.$row['id_mejora'].'" 
                      data-origen="'.$row['dorig'].'"
                      data-tipo="'.$row['tipo'].'"
                      data-estado="'.$row['estado'].'"
                      data-responsable="'.$row['apellido'].' '.$row['nombre'].'"
                      data-abierto="'.$row['opa'].' '.$row['opn'].'"
                      data-apertura="'.$row['apertura'].'"
                      data-cierre="'.$row['cierre'].'"
                      data-abierto="'.$row['abierto'].'"
                      data-esfuerzo="'.$row['esfuerzo'].'"
                      data-costo="'.$row['costo'].'"
                      data-implementacion="'.$row['implementacion'].'"
                      data-descripcion="'.$row['descripcion'].'"
                      data-causa="'.$row['causa'].'"
                      data-plan="'.$row['plan'].'"
                      data-eficacia="'.$row['eficacia'].'"
                      data-evidencia="'.$row['evidencia'].'"
                      title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                    </td>';
                    echo '
                    <td align="center">'.$no.'</td>';
                    echo '
                    </td>								
                    <td>'.$row['dorig'].'</td>
                    <td>';
                    if($row['estado'] == '0'){
                      echo 'Abierto';
                    }
                    else {
                      echo 'Cerrado';
                    }
                    echo '
                    </td>
                    <td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                    
                    if($row['tipo'] == '1'){
                      echo '<td>NC-No Conformidad sin AC</td>';
                    }
                    else if ($row['tipo'] == '2' ){
                      echo '<td>AC-Acción Correctiva</td>';
                    }
                    else if ($row['tipo'] == '3' ){
                      echo '<td>AM-Acción Mejora</td>';
                    }
                                  
                    if($row['esfuerzo'] == '0'){
                      echo '<td><span class="label label-default">TBD</span></td>';
                    }
                    else if ($row['esfuerzo'] == '1' ){
                      echo '<td><span class="label label-success">Muy Bajo</span></td>';
                    }
                                  else if ($row['esfuerzo'] == '2' ){
                      echo '<td><span class="label label-warning">Moderado</span></td>';
                    }
                    else if ($row['esfuerzo'] == '3' ){
                      echo '<td><span class="label label-danger">Muy Alto</span></td>';
                    }
                                  
                    if($row['costo'] == '0'){
                      echo '<td>TBD</td>';
                    }
                    else {
                      echo '<td>'.$row['costo'].'</td>';
                    }
                    echo '<td>'.$row['descripcion'].'</td>';

                    // OCULTOS
                    echo '<td>'.$row['opa'].' '.$row['opn'].'</td>';
                    echo '<td>'.$row['apertura'].'</td>';
                    echo '<td>'.$row['cierre'].'</td>';
                    echo '<td>'.$row['implementacion'].'</td>';
                    echo '<td>'.$row['causa'].'</td>';
                    echo '<td>'.$row['plan'].'</td>';
                    echo '<td>'.$row['eficacia'].'</td>';
                    echo '<td>'.$row['evidencia'].'</td>';                    
                    
                    echo '
                    <td align="center">
                    <a href="edit_mejora.php?nik='.$row['id_mejora'].'&ref='.$no.'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="mejoras.php?aksi=delete&nik='.$row['id_mejora'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['descripcion'].'?\')" class="btn btn-danger btn-sm ';
                                  if ($rq_sec['edicion']=='0'){
                                          echo 'disabled';
                                  }
                                  echo '"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                    </tr>
                    ';
                    $no++;
                  }
                }
                ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            
         
          <!-- /.box -->

          <!-- /.box -->
        </div>
                  
        <!-- /.col -->
      </div>
      <div class="tab-pane" id="tab_2">
     TBD - Indicadores de mejoras<!-- /contenido de tab 2 -->
      </div>
      <!-- /.row -->
    </div>
    </section>
    <!-- /.content -->
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
<!-- export -->
<script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
<script src="../bower_components/datatables.net/js/jszip.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
<script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
<script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>
<script src="../bower_components/datatables.net/js/buttons.colVis.min.js"></script>
      
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
    $('#datepicker5').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })
    $('#datepicker6').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })
     $('#datepicker7').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })
     $('#datepicker8').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })

  })
</script>
<script>
  $(function () {
    $('#mejoras').DataTable({
      'paging'      : true,
      'pageLength': 20,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'autoWidth'   : true,
      'dom'         : 'frtpB',
      'buttons'     : [{
                  extend: 'pdfHtml5',
                  orientation: 'landscape',
                  pageSize: 'A4',
                         
                     },
                      {
            extend: 'excel',
            text: 'Excel',
            }],
      'columnDefs': [
      {
          'targets': [ 9,10,11,12,13,14,15,16 ],
          'visible': false
      }
  ]
    })
  })
</script>
<script>
    window.onload = function() {
        history.replaceState("", "", "mejoras.php");
    }
</script>
<script>
$(function(){
    $(".ver-itemDialog").click(function(){
   
    $('#itemId').val($(this).data('id'));
    $('#origen').val($(this).data('origen'));
	$('#causa').val($(this).data('causa'));
	$('#descripcion').val($(this).data('descripcion'));
	$('#responsable').val($(this).data('responsable'));
	$('#plan').val($(this).data('plan'));
	$('#evidencia').val($(this).data('evidencia'));
    $('#eficacia').val($(this).data('eficacia'));
    $('#apertura').val($(this).data('apertura'));
    $('#cierre').val($(this).data('cierre'));
    $('#abierto').val($(this).data('abierto'));
    $('#costo').val($(this).data('costo'));
    $('#implementacion').val($(this).data('implementacion'));
	
	if($(this).data('tipo') == '1') {
		$('#tipo').val('NC-No Conformidad sin AC')}
	else if($(this).data('tipo') == '2'){
		$('#tipo').val('AC-Acción Correctiva')}
	else if($(this).data('tipo') == '3'){
		$('#tipo').val('AM-Acción Mejora')};
    
    if($(this).data('esfuerzo') == '1') {
		$('#esfuerzo').val('Muy bajo')}
	else if($(this).data('esfuerzo') == '2'){
		$('#esfuerzo').val('Moderado')}
	else if($(this).data('esfuerzo') == '3'){
		$('#esfuerzo').val('Muy alto')};
 
 	
	if($(this).data('estado') == '0') {
		$('#estado').val('Abierto')}
	else if($(this).data('estado') == '1'){
		$('#estado').val('Cerrado')};
      
	console.log("ejecuta el modal");
      
	$("#ver-itemDialog").modal("show");
	
  });


  let table = $('#mejoras').DataTable();
  $('#mejoras thead tr').clone(true).appendTo( '#mejoras thead' );
  $('#mejoras thead tr:eq(1) th').each( function (colIdx) {
      $(this).removeClass('sorting');
      var table = $('#mejoras').DataTable();

      // Si son las columnas de filtro creo el ddl
      if (colIdx == 3 || colIdx == 4 || colIdx == 5) {
          var select = $('<select style="width: 100%;"><option value=""></option></select>')
          .on( 'change', function () {
              table
                  .column( colIdx )
                  .search( $(this).val() )
                  .draw();
          } )
          .on( 'click' , function(){return false;} )
          // .wrap( "<div></div>" );             // VER
          // Get the search data for the first column and add to the select list
          table
              .column( colIdx )
              .cache( 'search' )
              .sort()
              .unique()
              .each( function ( d ) {
                  select.append( $('<option value="'+d+'">'+d+'</option>') );
              });
          
          var filterhtml = select.parent().prop('outerHTML');
          $(this).html(select);
          // $(this).html(filterhtml);

      }
      else {
          $(this).html("");
      }

    } );


});
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>
