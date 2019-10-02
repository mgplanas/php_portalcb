<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Control";
$user=$_SESSION['usuario'];

$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT * FROM controles as i LEFT JOIN persona as p on responsable = p.id_persona
                            WHERE i.borrado='0' AND i.id_control='$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: controles.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){

	$titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));//Escanpando caracteres
    $contenido = mysqli_real_escape_string($con,(strip_tags($_POST["contenido"],ENT_QUOTES)));//Escanpando caracteres
    $responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));//Escanpando caracteres 
    $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));//Escanpando caracteres 
    $criticidad = mysqli_real_escape_string($con,(strip_tags($_POST["criticidad"],ENT_QUOTES)));//Escanpando caracteres 
	
	$update_controles = mysqli_query($con, "UPDATE controles SET responsable='$responsable', contenido='$contenido', titulo='$titulo', tipo='$tipo', modificado=NOW(), usuario='$user', criticidad='$criticidad' WHERE id_control='$nik'") or die(mysqli_error());
    
	$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('2', '5','$nik', now(), '$user', '$titulo')") or die(mysqli_error());
	if($update_controles){
		$_SESSION['formSubmitted'] = 1;
		header("Location: controles.php");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: controles.php");					
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
        Gestión de controles
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
                  <label for="titulo">Titulo</label>
                  <input type="text" class="form-control" name="titulo" value="<?php echo $row ['titulo']; ?>">
                </div>
                <div class="form-group">
                  <label for="contenido">Items a controlar</label>
                  <?php echo "<textarea class=form-control name=contenido>{$row['contenido']}</textarea>"; ?>
               </div>
               <div class="form-group">
                  <label>Criticidad</label>
                  <select name="criticidad" class="form-control">
                  <?php
                    if($row['criticidad']=='0') { echo "<option value='0' selected='selected'>Crítico</option>"; } 
                    else { echo "<option value='0'>Crítico</option>"; }
                    if($row['criticidad']=='1') { echo "<option value='1' selected='selected'>Semi Crítico</option>"; } 
                    else { echo "<option value='1'>Semi Crítico</option>"; }
                    if($row['criticidad']=='2') { echo "<option value='2' selected='selected'>No Crítico</option>"; } 
                    else { echo "<option value='2'>No Crítico</option>"; }
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
                  <label>Tipo</label>
                  <select name="tipo" class="form-control">
                    <option value='1'<?php if($row['tipo'] == '1'){ echo 'selected'; } ?>>Preventivo y/o disuasivo</option>
                    <option value='2'<?php if($row['tipo'] == '2'){ echo 'selected'; } ?>>Detectivo y/o correctivo</option>
                  </select>
                </div>
                <div class="form-group">
                <div class="row">
                <div class="col-sm-6">
                  <label for="mesinicio">Mes de Inicio</label>
                    <?php if($row['mesinicio'] == '1'){ echo '<input type="text" class="form-control" name="mesinicio" value="Enero" readonly>';}
                      else if($row['mesinicio'] =='2'){ echo '<input type="text" class="form-control" name="mesinicio" value="Febrero" readonly>';}
                      else if($row['mesinicio'] =='3'){ echo '<input type="text" class="form-control" name="mesinicio" value="Marzo" readonly>';}
                      else if($row['mesinicio'] =='4'){ echo '<input type="text" class="form-control" name="mesinicio" value="Abril" readonly>';}
                      else if($row['mesinicio'] =='5'){ echo '<input type="text" class="form-control" name="mesinicio" value="Mayo" readonly>';}
                      else if($row['mesinicio'] =='6'){ echo '<input type="text" class="form-control" name="mesinicio" value="Junio" readonly>';}
                      else if($row['mesinicio'] =='7'){ echo '<input type="text" class="form-control" name="mesinicio" value="Julio" readonly>';}
                      else if($row['mesinicio'] =='8'){ echo '<input type="text" class="form-control" name="mesinicio" value="Agosto" readonly>';}
                      else if($row['mesinicio'] =='9'){ echo '<input type="text" class="form-control" name="mesinicio" value="Septiembre" readonly>';}
                      else if($row['mesinicio'] =='10'){ echo '<input type="text" class="form-control" name="mesinicio" value="Octubre" readonly>';}
                      else if($row['mesinicio'] =='11'){ echo '<input type="text" class="form-control" name="mesinicio" value="Noviembre" readonly>';}
                      else if($row['mesinicio'] =='12'){ echo '<input type="text" class="form-control" name="mesinicio" value="Diciembre" readonly>';}
                    ?>
                  </div>
                  <div class="col-sm-6">
                  <label for="periodo">Periodo</label>
                    <?php if($row['periodo'] == '1'){
                        echo '<input type="text" class="form-control" name="periodo" value="Mensual" readonly>';
                      }
                      else if ($row['periodo'] == '3' ){
                        echo '<input type="text" class="form-control" name="periodo" value="Trimestral" readonly>';
                      }
                      else if ($row['periodo'] == '6' ){
                        echo '<input type="text" class="form-control" name="periodo" value="Semestral" readonly>';
                      }
                                    else if ($row['periodo'] == '12' ){
                        echo '<input type="text" class="form-control" name="periodo" value="Anual" readonly>';
                      }
                    ?>
                  </div>
                </div>
                </div>
				 <div class="form-group">
					<div class="col-sm-2">
						<input type="submit" name="save" class="btn  btn-raised btn-success" value="Guardar datos">
					</div>
					<div class="col-sm-2">
						<a href="controles.php" class="btn btn-warning btn-raised">Cancelar</a>
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