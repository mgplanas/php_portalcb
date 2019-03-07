<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$user=$_SESSION['usuario'];

$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT i.*, p.nombre, p.apellido, c.tipo FROM riesgo as i 
								LEFT JOIN categoria as c on i.categoria = c.id_categoria 
								LEFT JOIN persona as p on i.responsable = p.id_persona 
								WHERE i.borrado='0' AND i.id_riesgo='$nik'");

							  
if(mysqli_num_rows($sql) == 0){
	header("Location: riesgos.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){
	$amenaza = mysqli_real_escape_string($con,(strip_tags($_POST["amenaza"],ENT_QUOTES)));
	$vulnerabilidad = mysqli_real_escape_string($con,(strip_tags($_POST["vulnerabilidad"],ENT_QUOTES)));
	$responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
	$categoria = mysqli_real_escape_string($con,(strip_tags($_POST["categoria"],ENT_QUOTES)));
	$probabilidad = mysqli_real_escape_string($con,(strip_tags($_POST["probabilidad"],ENT_QUOTES)));//Escapando caracteres
	$i_conf = mysqli_real_escape_string($con,(strip_tags($_POST["i_conf"],ENT_QUOTES)));//Escapando caracteres
	$i_int = mysqli_real_escape_string($con,(strip_tags($_POST["i_int"],ENT_QUOTES)));//Escapando caracteres
	$i_disp = mysqli_real_escape_string($con,(strip_tags($_POST["i_disp"],ENT_QUOTES)));//Escapando caracteres
	$control = mysqli_real_escape_string($con,(strip_tags($_POST["control"],ENT_QUOTES)));//Escapando caracteres
	$estrategia = mysqli_real_escape_string($con,(strip_tags($_POST["estrategia"],ENT_QUOTES)));//Escapando caracteres
	$plan = mysqli_real_escape_string($con,(strip_tags($_POST["plan"],ENT_QUOTES)));//Escapando caracteres
	$p_resid = mysqli_real_escape_string($con,(strip_tags($_POST["p_resid"],ENT_QUOTES)));//Escapando caracteres
	$i_resid = mysqli_real_escape_string($con,(strip_tags($_POST["i_resid"],ENT_QUOTES)));//Escapando caracteres
	$observacion = mysqli_real_escape_string($con,(strip_tags($_POST["observacion"],ENT_QUOTES)));//Escapando caracteres
    $alta = mysqli_real_escape_string($con,(strip_tags($_POST["alta"],ENT_QUOTES)));//Escapando caracteres
    $identificado = mysqli_real_escape_string($con,(strip_tags($_POST["identificado"],ENT_QUOTES)));//Escapando caracteres
    $vencimiento = mysqli_real_escape_string($con,(strip_tags($_POST["vencimiento"],ENT_QUOTES)));//Escapando caracteres
    $estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escapando caracteres
    $incidente = mysqli_real_escape_string($con,(strip_tags($_POST["incidente"],ENT_QUOTES)));//Escapando caracteres
    $avance = mysqli_real_escape_string($con,(strip_tags($_POST["avance"],ENT_QUOTES)));//Escapando caracteres
    
	$preventivo = '0';
	$detectivo = '0';
	
	if ($_POST["t_control"] == 1){
		$preventivo = '1';
	}else {
		$detectivo = '1';}
				
	$update_riesgo = mysqli_query($con, "UPDATE riesgo SET amenaza='$amenaza', vulnerabilidad='$vulnerabilidad', creado = NOW(),
	responsable='$responsable', categoria='$categoria', probabilidad='$probabilidad', i_conf='$i_conf', i_int='$i_int',
	i_disp='$i_disp', control='$control', estrategia='$estrategia', plan='$plan', p_resid='$p_resid', i_resid='$i_resid',
	observacion='$observacion', c_prev='$preventivo', c_detec='$detectivo', usuario='$user', alta='$alta', identificado='$identificado', vencimiento='$vencimiento', estado='$estado', incidente='$incidente', avance='$avance'
    WHERE id_riesgo='$nik'") or die(mysqli_error());
	
	//Update activos/riesgo en tabla de relación
	$delete_relacion = mysqli_query($con, "DELETE FROM riesgo_activo WHERE id_riesgo='$nik'");
    
    foreach ($_POST['activos'] as $selectedOption){
		$update_relacion = mysqli_query($con, "INSERT INTO riesgo_activo (id_riesgo, id_activo, creado) 
							   VALUES ('$nik', '$selectedOption',now())") or die(mysqli_error());
	}

	//auditoría
	$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
							   VALUES ('2', '4', '$nik', now(), '$user', '$amenaza')") or die(mysqli_error());
	unset($_POST);
	if($update_riesgo){
		$_SESSION['formSubmitted'] = 1;
		header("Location: riesgos.php");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: riesgos.php");
	}
}

//Alert icons data on top bar
//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

//Count riesgos
$riesgos = "SELECT 1 as total FROM riesgo WHERE riesgo.responsable='$id_rowp' AND riesgo.borrado='0'";
$count_riesgos = mysqli_query($con, $riesgos );
$rowr = mysqli_num_rows($count_riesgos);

//Count activos
$query_count_activos = "SELECT 1 as total FROM activo WHERE activo.responsable='$id_rowp' AND activo.borrado='0'";
$count_activos = mysqli_query($con, $query_count_activos);
$rowa = mysqli_num_rows($count_activos);

//Count Controles
$query_controles = "SELECT 1 as total FROM controles WHERE controles.responsable='$id_rowp' AND controles.borrado='0'";
$count_controles = mysqli_query($con, $query_controles); 
$rowc = mysqli_num_rows($count_controles);

//Count Proyectos
$query_proyectos = "SELECT 1 as total FROM proyecto WHERE proyecto.responsable='$id_rowp' AND proyecto.borrado='0'";
$count_proyectos = mysqli_query($con, $query_proyectos); 
$rowcp = mysqli_num_rows($count_proyectos);

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
		
?>
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
  <!-- Select2 -->
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">

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
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bolt"></i>
              <span class="label label-success"><?php echo $rowr; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo $rowr; ?> riesgos asignados</li>
              <li>
                <!-- inner menu: contains the messages -->
                
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="riesgos.php">Gestionar los riesgos</a></li>
            </ul>
          </li>
          <!-- /.messages-menu -->

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-archive"></i>
              <span class="label label-warning"><?php echo $rowa; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Eres responsable de <?php echo $rowa; ?> activos</li>
              
              <li class="footer"><a href="activos.php">Ver Activos</a></li>
            </ul>
          </li>
          <!-- Tasks Menu -->
          <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-retweet"></i>
              <span class="label label-danger"><?php echo $rowc; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo $rowc; ?> controles asignados</li>
              
              <li class="footer">
                <a href="controles.php">Gestionar controles</a>
              </li>
            </ul>
          </li>
		  <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-list"></i>
              <span class="label label-info"><?php echo $rowcp; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo $rowcp; ?> proyectos asignados</li>
              
              <li class="footer">
                <a href="controles.php">Gestionar proyectos</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="../dist/img/icon_user.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $_SESSION['usuario']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="../dist/img/icon_user.png" class="img-circle" alt="User Image">
				<p>
                   <?php echo ''.$rowp['nombre']. ' '.$rowp['apellido']. '';?>
                  <small><?php echo ''.$rowp['cargo']. '';?></small>
                </p>
              </li>
           <!-- Menu Footer-->
              <li class="user-footer">
               <div class="pull-right">
                  <a href="../out.php" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button 
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../dist/img/icon_user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p> 
			<?php echo ''.$rowp['nombre']. '';?><br>
			<?php echo ''.$rowp['apellido']. '';?>
		  </p>
          <!-- Status
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <!-- Optionally, you can add icons to the links -->
        <li><a href="../site.php"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
        <li><a href="activos.php"><i class="fa fa-archive"></i> <span>Activos</span></a></li>
		<li><a href="controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
		<li><a href="iso27k.php"><i class="fa fa-crosshairs"></i> <span>Ítems ISO 27001</span></a></li>
        <li><a href="mejoras.php"><i class="fa fa-refresh"></i> <span>Mejora Continua</span></a></li>
		<li class="active"><a href="riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
          <?php if ($rq_sec['admin']=='1' OR $rq_sec['soc']=='1'){
            echo '<li><a href="calendario.php"><i class="fa fa-calendar"></i> <span>Calendario</span></a></li>';
            echo '<li><a href="novedades.php"><i class="fa fa-envelope"></i> <span>Novedades</span></a></li>';
            echo '<li><a href="proyectos.php"><i class="fa fa-list"></i> <span>Proyectos</span></a></li>';
            echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-book"></i><span>Inventario</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="inventario.php"><i class="fa fa-list"></i>Listado</a></li>
                <li><a href="topologia.php"><i class="fa fa-map-o"></i> <span>Topología</span></a></li>
              </ul>
            </li>';
        }?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Gestión de Riesgos
        <small>Editar >> Riesgo >> #<?php echo ''.$row['id_riesgo']. '';?></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
		<div class="box box-primary">
            <!-- /.box-header -->
			<form method="post" role="form" action="">
            <!-- form start -->
			<div class="row">
				<div class="box box-primary">
					
					<div class="box-body">
						<div class="form-group">
                            <label>Fecha de alta</label>
                            <div class="input-group date" data-provide="datepicker1">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" class="form-control pull-right" name="alta" value="<?php echo $row ['alta']; ?>" id="datepicker1" placeholder="dd/mm/yyyy">
                            </div>
                      </div>
                        <div class="form-group">
							<label for="amenaza"> Amenaza</label>
							<?php echo "<textarea class=form-control name=amenaza>{$row['amenaza']}</textarea>"; ?>
							</div>
						<div class="form-group">
						  <label for="vulnerabilidad"> Vulnerabilidad</label>
						  <?php echo "<textarea class=form-control name=vulnerabilidad>{$row['vulnerabilidad']}</textarea>"; ?>
						</div>
						<div class="form-group">
							<label>Activos afectados</label>
							<select class="form-control select2" name="activos[]" multiple="multiple" data-placeholder="Activos" style="width: 100%;">
							  <?php
								$q_activos = mysqli_query($con, "SELECT a.*, r.id_activo as ida FROM activo as a 
								left join (select * from riesgo_activo where id_riesgo = '$nik' AND borrado='0') as r on a.id_activo = r.id_activo WHERE a.borrado='0'") ;

								while($rowqa = mysqli_fetch_array($q_activos)){

									if($rowqa['ida']) {
										echo "<option value='". $rowqa['id_activo'] . "' selected='selected'>" .$rowqa['titulo'] . "</option>";
										}
									else {
										echo "<option value=". $rowqa['id_activo']. ">". $rowqa['titulo'].  "</option>";
										}
									
								}
								?>
							</select>
						</div>
                        <div class="form-group">
						  <label>Responsable</label>
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
                        <div class="form-group">
						  <label>Identificado por</label>
						  <select name="identificado" class="form-control">
								<?php
									$personasn = mysqli_query($con, "SELECT * FROM persona");
									while($rowps = mysqli_fetch_array($personasn)){
										if($rowps['id_persona']==$row['identificado']) {
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
				</div>
			<div class="col-md-6">
				<div class="box box-default">
					  <div class="box-body">
					   <div class="form-group">
							<label class="label-custom label-custom-info">Categoria de Riesgo</label>
							<select name="categoria" class="form-control">
								<?php
								$q_categoria = mysqli_query($con, "SELECT * FROM categoria ORDER BY tipo ASC");
								while($rowqc = mysqli_fetch_array($q_categoria)){
									if($rowqc['id_categoria']==$row['categoria']) {
										echo "<option value='". $rowqc['id_categoria'] . "' selected='selected'>" .$rowqc['tipo'] . "</option>";
									}
									else {
										echo "<option value='". $rowqc['id_categoria'] . "'>" .$rowqc['tipo'] . "</option>";										
									} 
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label class="label-custom label-custom-info">Probabilidad de ocurrencia</label>
								<select name="probabilidad" class="form-control">	
									<option value='1'<?php if($row['probabilidad'] == '1'){ echo 'selected'; } ?>>1 - Improbable</option>
									<option value='2'<?php if($row['probabilidad'] == '2'){ echo 'selected'; } ?>>2 - Moderada</option>
									<option value='3'<?php if($row['probabilidad'] == '3'){ echo 'selected'; } ?>>3 - Muy probable</option>
									<option value='4'<?php if($row['probabilidad'] == '4'){ echo 'selected'; } ?>>4 - Casi cierta</option>
								</select>
						</div>
						<div class="form-row">
							<div class="col-sm-4">
								<label class="label-custom label-custom-info" style="text-align:center;">Impacto en confidencialidad</label>
							</div>
							<div class="col-sm-4">
								<label class="label-custom label-custom-info" style="text-align:center;">Impacto en integridad</label>
							</div>
							<div class="col-sm-4">
								<label class="label-custom label-custom-info" style="text-align:center;">Impacto en disponibilidad</label>
							</div>
						</div>
						<div class="form-row">
								<div class="col-sm-4">
										<select name="i_conf" class="form-control">	
									<option value='1'<?php if($row['i_conf'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
									<option value='2'<?php if($row['i_conf'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
									<option value='3'<?php if($row['i_conf'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
									<option value='4'<?php if($row['i_conf'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
									</select>
								</div>
								<div class="col-sm-4">
									<select name="i_int" class="form-control">	
									<option value='1'<?php if($row['i_int'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
									<option value='2'<?php if($row['i_int'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
									<option value='3'<?php if($row['i_int'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
									<option value='4'<?php if($row['i_int'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
									</select>
								</div>
								<div class="col-sm-4">
									<select name="i_disp" class="form-control">	
									<option value='1'<?php if($row['i_disp'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
									<option value='2'<?php if($row['i_disp'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
									<option value='3'<?php if($row['i_disp'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
									<option value='4'<?php if($row['i_disp'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
									</select>
								</div>
						</div>
                        <br clear="all" /><br />
                        <div class="form-row">
								<div class="col-sm-4">
									<div class="form-group">
								        <label class="label-custom label-custom-info">Impacto Resultante</label>
									   <input type="text" name="i_result" value="<?php echo $row ['i_result']; ?>" class="form-control" readonly>
                                    </div>	
								</div>
								<div class="col-sm-4">
									<div class="form-group">
								        <label class="label-custom label-custom-info">Nivel de riesgo inherente</label>
									   <input type="text" name="n_riesgo" value="<?php echo $row ['n_riesgo']; ?>" class="form-control" readonly>
                                    </div>	
								</div>
								<div class="col-sm-4">
									<div class="form-group">
								        <label class="label-custom label-custom-info">Valoración Inicial</label>
									   <input type="text" name="v_inicial" value="<?php echo $row ['v_inicial']; ?>" class="form-control" readonly>
                                    </div>	
								</div>
						</div>
                        <br/>
                         <div class="form-group">
								<label class="label-custom label-custom-info">Control existente / propuesto</label>
									<input type="text" name="control" value="<?php echo $row ['control']; ?>" class="form-control" placeholder="Control existente ..."required>
							</div>
							<div class="row">
								<div class="col-lg-6" style="text-align:center;">
									<input type="radio" name="t_control" value="1" <?php if($row['c_prev'] == '1'){ echo 'checked'; } ?>> Preventivo
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
								<div class="col-lg-6" style="text-align:center;">
								  <input type="radio" name="t_control" value="2" <?php if($row['c_detec'] == '1'){ echo 'checked'; } ?>> Detectivo
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
							</div><br> 
					</div>
			</div>
            </div>
			<div class="col-md-6">
				<div class="box box-default">
						<div class="box-body">
                            <div class="row">
								<div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="label-custom label-custom-info">Estrategia propuesta</label>
                                        <select name="estrategia" class="form-control">	
                                            <option value='ACEPTAR'<?php if($row['estrategia'] == 'ACEPTAR'){ echo 'selected'; } ?>>ACEPTAR</option>
                                            <option value='REDUCIR'<?php if($row['estrategia'] == 'REDUCIR'){ echo 'selected'; } ?>>REDUCIR</option>
                                            <option value='TRANSFERIR'<?php if($row['estrategia'] == 'TRANSFERIR'){ echo 'selected'; } ?>>TRANSFERIR</option>
                                            <option value='EVITAR'<?php if($row['estrategia'] == 'EVITAR'){ echo 'selected'; } ?>>EVITAR</option>
                                        </select>
                                    </div>
                                </div>
                       <div class="col-lg-6">         
                            <div class="form-group">
                                    <label>Fecha de resolución</label>
                                    <div class="input-group date" data-provide="datepicker2">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="text" class="form-control pull-right" name="vencimiento" value="<?php echo $row ['vencimiento']; ?>" id="datepicker2" placeholder="dd/mm/yyyy">
                                    </div>
                              </div>
                                </div>
                            </div>

							<div class="form-group">
								<label class="label-custom label-custom-info">Plan de tratamiento</label>
									<input type="text" name="plan" value="<?php echo $row ['plan']; ?>" class="form-control" required>
							</div>
							<div class="row">
								<div class="col-lg-4">
									<label class="label-custom label-custom-info" style="text-align:center;">Probabilidad residual</label>
									<select name="p_resid" class="form-control">	
										<option value='1'<?php if($row['p_resid'] == '1'){ echo 'selected'; } ?>>1 - Improbable</option>
										<option value='2'<?php if($row['p_resid'] == '2'){ echo 'selected'; } ?>>2 - Moderada</option>
										<option value='3'<?php if($row['p_resid'] == '3'){ echo 'selected'; } ?>>3 - Muy probable</option>
										<option value='4'<?php if($row['p_resid'] == '4'){ echo 'selected'; } ?>>4 - Casi cierta</option>
									</select>
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
								<div class="col-lg-4">
								  <div class="input-group">
									<label class="label-custom label-custom-info" style="text-align:center;">Impacto residual</label>
									<select name="i_resid" class="form-control">	
										<option value='1'<?php if($row['i_resid'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
										<option value='2'<?php if($row['i_resid'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
										<option value='3'<?php if($row['i_resid'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
										<option value='4'<?php if($row['i_resid'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
										</select>
								  </div>
								  <!-- /input-group -->
								</div>
                                <div class="col-lg-4">
                                <div class="form-group">
								<label class="label-custom label-custom-info">Nivel residual</label>
									<input type="text" name="n_resid" value="<?php echo $row ['n_resid']; ?>" class="form-control" readonly>
							</div>
                                </div>
                <!-- /.col-lg-6 -->
							</div><br/>

                            <div class="row">
								<div class="col-lg-4">
									<label class="label-custom label-custom-info" style="text-align:center;">Estado</label>
									<select name="estado" class="form-control">	
										<option value='0'<?php if($row['estado'] == '0'){ echo 'selected'; } ?>>Abierto</option>
										<option value='1'<?php if($row['estado'] == '1'){ echo 'selected'; } ?>>Cerrado</option>
										
									</select>
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
								<div class="col-lg-4">
								  <div class="input-group">
									<label class="label-custom label-custom-info" style="text-align:center;">Incidente</label>
									<select name="incidente" class="form-control">	
										<option value='0'<?php if($row['incidente'] == '0'){ echo 'selected'; } ?>>No</option>
										<option value='1'<?php if($row['incidente'] == '1'){ echo 'selected'; } ?>>Si</option>
										</select>
								  </div>
								  <!-- /input-group -->
								</div>
                                <div class="col-lg-4">
                                    <div class="form-group">
								<label class="label-custom label-custom-info">Valoración actual</label>
									<input type="text" name="v_actual" value="<?php echo $row ['v_actual']; ?>" class="form-control" readonly>
							</div>
                                </div>
                <!-- /.col-lg-6 -->
							</div>
                            <br>
                            <div class="form-group">
								<label class="label-custom label-custom-info">% Avance</label>
									<input type="text" name="avance" value="<?php echo $row ['avance']; ?>" class="form-control" >
							</div>
						</div>
				</div>
			</div>	
            </div>
			<div class="form-group">
					<label class="label-custom label-custom-info">Observaciones</label>
					<div class="col-sm-12">
						<?php echo "<textarea class=form-control name=observacion>{$row['observacion']}</textarea>"; ?>
					</div>
			</div>
		<div class="modal-footer">	
				<div class="col-sm-6">
					<input type="submit" name="save" class="btn btn-raised btn-success" value="Guardar datos">
				</div>
				<div class="col-sm-6">
					<a href="riesgos.php" class="btn btn-default pull-left">Cancelar</a>
				</div>
		</div>
		</form>	
          </div> 
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
<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()
  })
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>