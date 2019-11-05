<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Activos"; 
$user=$_SESSION['usuario'];

$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT i.*, p.nombre, p.apellido FROM activo as i 
						 	  LEFT JOIN persona as p on i.responsable = p.id_persona
							  WHERE i.borrado='0' AND i.id_activo='$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: activos.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){

	$titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));
	$descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
	$responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
	$clasificacion = mysqli_real_escape_string($con,(strip_tags($_POST["clasificacion"],ENT_QUOTES)));
	$tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));
	$ubicacion = mysqli_real_escape_string($con,(strip_tags($_POST["ubicacion"],ENT_QUOTES)));
	$soporte = mysqli_real_escape_string($con,(strip_tags($_POST["soporte"],ENT_QUOTES)));
	$direccion = mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));
  $pdp ="0";
  if ($_POST["pdp"]) {
    $pdp = mysqli_real_escape_string($con,(strip_tags($_POST["pdp"],ENT_QUOTES)));
  }
	$update_activo = mysqli_query($con, "UPDATE activo SET descripcion='$descripcion', responsable='$responsable', clasificacion='$clasificacion', pdp='$pdp', tipo='$tipo', ubicacion='$ubicacion', soporte='$soporte', direccion='$direccion', modificado=NOW() 
										 WHERE id_activo='$nik'") or die(mysqli_error());	
	$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('2', '1','$nik', now(), '$user', '$titulo')") or die(mysqli_error());
	if($update_activo){
		$_SESSION['formSubmitted'] = 1;
		header("Location: activos.php");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: activos.php");					
	}
}
//Alert icons data on top bar
$user=$_SESSION['usuario'];

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
$query_controles = "SELECT 1 as total FROM controles WHERE controles.responsable='$id_rowp'";
$count_controles = mysqli_query($con, $query_controles); 
$rowc = mysqli_num_rows($count_controles);

//Count Proyectos
$query_proyectos = "SELECT 1 as total FROM proyecto WHERE proyecto.responsable='$id_rowp'";
$count_proyectos = mysqli_query($con, $query_proyectos); 
$rowcp = mysqli_num_rows($count_proyectos);

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);	
				
		
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
            <!-- Header Navbar -->
            <?php include_once('./site_header.php'); ?>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <?php include_once('./site_sidebar.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Gestión de Activos
        <small>Editar >> <?php echo $row ['titulo']; ?></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	<div class="box box-primary">
            <!-- /.box-header -->
			
            <!-- form start -->
            <form method="post" role="form" action="">
              <div class="box-body">
                <div class="form-group">
                  <label for="titulo">Nombre</label>
                  <input type="text" class="form-control" name="titulo" value="<?php echo $row ['titulo']; ?>"readonly>
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <?php echo "<textarea class=form-control name=descripcion>{$row['descripcion']}</textarea>"; ?>
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
                  <label>Tipo</label>
                  <select name="tipo" class="form-control">
                    <option value='1'<?php if($row['tipo'] == '1'){ echo 'selected'; } ?>>Datos/Información</option>
                    <option value='2'<?php if($row['tipo'] == '2'){ echo 'selected'; } ?>>Equipamiento</option>
                    <option value='3'<?php if($row['tipo'] == '3'){ echo 'selected'; } ?>>Instalaciones</option>
                    <option value='4'<?php if($row['tipo'] == '4'){ echo 'selected'; } ?>>Personal</option>
                    <option value='5'<?php if($row['tipo'] == '5'){ echo 'selected'; } ?>>Servicio</option>
                    <option value='6'<?php if($row['tipo'] == '6'){ echo 'selected'; } ?>>Software</option>
                    <option value='7'<?php if($row['tipo'] == '7'){ echo 'selected'; } ?>>Suministros</option>
                   </select>
                </div>
				<div class="form-group">
						<label>Ubicación</label>
						<select name="ubicacion" class="form-control">
							<option value='1'<?php if($row['ubicacion'] == '1'){ echo 'selected'; } ?>>Centro de Datos - Benavidez</option>
							<option value='2'<?php if($row['ubicacion'] == '2'){ echo 'selected'; } ?>>Centro de Datos - Tucumán</option>
							<option value='3'<?php if($row['ubicacion'] == '3'){ echo 'selected'; } ?>>Otro</option>
						</select>
				</div>
				<div class="form-group">
                  <label for="direccion">Dirección</label>
                  <input type="text" class="form-control" name="direccion" value="<?php echo $row ['direccion']; ?>">
                </div>
				<div class="panel box box-warning">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Solo aplicable para Activos de Información
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse <?php if($row['tipo'] !== '1'){ echo 'collapse'; } ?>">
                    <div class="box-body">
                      <div class="form-group">
						<label for="soporte">Sistema o Formato</label>
						<input type="text" class="form-control" name="soporte" value="<?php echo $row ['soporte']; ?>">
					</div>
					<div class="form-group">
						<label>Clasificación Información</label>
						<select name="clasificacion" class="form-control">
							<option value='1'<?php if($row['clasificacion'] == '1'){ echo 'selected'; } ?>>Pública</option>
							<option value='2'<?php if($row['clasificacion'] == '2'){ echo 'selected'; } ?>>Interna</option>
							<option value='3'<?php if($row['clasificacion'] == '3'){ echo 'selected'; } ?>>Confidencial</option>
						</select>
					</div>
          <div class="form-group">
            <label for="Ley_PDP">Ley 25.326 PDP</label>
				      <div class="checkbox">
                <label>
					        <input name="pdp" type="checkbox" value="1"  <?php if($row['pdp'] == '1'){ echo 'checked'; } ?>> Contiene datos Personales?
				        </label>
              </div>
          </div>
                  </div>
                </div>
				
				 <div class="form-group">
					<div class="col-sm-2">
						<input type="submit" name="save" class="btn  btn-raised btn-success" value="Guardar datos">
					</div>
					<div class="col-sm-2">
						<a href="activos.php" class="btn btn-warning btn-raised">Cancelar</a>
					</div>
				</div>
			  </div>
            </form>
          </div>
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

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>