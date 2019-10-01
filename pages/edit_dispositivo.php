<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Inventario"; 
$user=$_SESSION['usuario'];

$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT i.*, t.nombre, u.ubicacion FROM dispositivo as i 
                                      LEFT JOIN tipo_dispositivo as t on i.tipo = t.id_tipoDispositivo
                                      LEFT JOIN ubicacion as u on i.ubicacion = u.id_ubicacion
							          WHERE i.borrado='0' AND i.id_dispositivo='$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: inventario.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){

	$tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
    $etiqueta = mysqli_real_escape_string($con,(strip_tags($_POST["etiqueta"],ENT_QUOTES)));
    $ubicacion = mysqli_real_escape_string($con,(strip_tags($_POST["ubicacion"],ENT_QUOTES)));
    $marca = mysqli_real_escape_string($con,(strip_tags($_POST["marca"],ENT_QUOTES)));
    $modelo = mysqli_real_escape_string($con,(strip_tags($_POST["modelo"],ENT_QUOTES)));
    $interfaces = mysqli_real_escape_string($con,(strip_tags($_POST["interfaces"],ENT_QUOTES)));
    $ip_address = mysqli_real_escape_string($con,(strip_tags($_POST["ip_address"],ENT_QUOTES)));
    $serial = mysqli_real_escape_string($con,(strip_tags($_POST["serial"],ENT_QUOTES)));
	
	$update_dispositivo = mysqli_query($con, "UPDATE dispositivo SET tipo='$tipo', descripcion='$descripcion', etiqueta='$etiqueta', ubicacion='$ubicacion', marca='$marca', modelo='$modelo', interfaces='$interfaces', ip_address='$ip_address', serial='$serial' WHERE id_dispositivo='$nik' ") or die(mysqli_error());	
    
	$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('2', '10','$nik', now(), '$user', '$etiqueta')") or die(mysqli_error());
	if($update_dispositivo){
		$_SESSION['formSubmitted'] = 1;
		header("Location: inventario.php");
        exec('php getNodes.php');
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: inventario.php");					
	}
}
//Alert icons data on top bar
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['soc']=='0'){
	header('Location: ../site.php');
}
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
        Gestión de Inventario SI
        <small>Editar >> <?php echo $row ['etiqueta']; ?></small>
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
              <div class="box-body">
                <div class="form-group">
              <label for="etiqueta">Etiqueta</label>
              <input type="text" class="form-control" name="etiqueta" value="<?php echo $row['etiqueta']; ?>">
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <?php echo "<textarea class=form-control name=descripcion>{$row['descripcion']}</textarea>"; ?>
            </div>
            <div class="form-group">
              <label>Tipo de dispositivo</label>
              <select name="tipo" class="form-control">
                  <?php
                        $tipos = mysqli_query($con, "SELECT * FROM tipo_dispositivo");
                        while($rowtd = mysqli_fetch_array($tipos)){
                            if($rowtd['id_tipoDispositivo']==$row['tipo']) {
                                echo "<option value='". $rowtd['id_tipoDispositivo'] . "' selected='selected'>" .$rowtd['nombre'] . "</option>";
                            }
                            else {
                                echo "<option value='". $rowtd['id_tipoDispositivo'] . "'>" .$rowtd['nombre'] . "</option>";										
                            }
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
                            if($rowub['id_ubicacion']==$row['ubicacion']) {
                                echo "<option value='". $rowub['id_ubicacion'] . "' selected='selected'>" .$rowub['ubicacion'] . "</option>";
                            }
                            else {
                                echo "<option value='". $rowub['id_ubicacion'] . "'>" .$rowub['ubicacion'] . "</option>";										
                            }
                        }
				?>  
              </select>
            </div>
            <div class="form-group">
              <label for="marca">Marca</label>
              <input type="text" class="form-control" name="marca" value="<?php echo $row ['marca']; ?>">
            </div>
            <div class="form-group">
              <label for="modelo">Modelo</label>
              <input type="text" class="form-control" name="modelo" value="<?php echo $row ['modelo']; ?>">
            </div>
            <div class="form-group">
              <label for="interfaces">Cantidad de Interfaces</label>
              <input type="text" class="form-control" name="interfaces" value="<?php echo $row ['interfaces']; ?>" >
            </div>
            <div class="form-group">
              <label for="ip_address">Dirección IP</label>
              <input type="text" class="form-control" name="ip_address" value="<?php echo $row ['ip_address']; ?>">
            </div>
            <div class="form-group">
              <label for="serial">Nro de Serie</label>
              <input type="text" class="form-control" name="serial" value="<?php echo $row ['serial']; ?>">
            </div>   
 				 <div class="form-group">
					<div class="col-sm-3">
						<input type="submit" name="save" class="btn  btn-raised btn-success" value="Guardar datos">
					</div>
					<div class="col-sm-3">
						<a href="inventario.php" class="btn btn-warning btn-raised">Cancelar</a>
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
    $('#datepicker1').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })
      
    $('#datepicker2').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })

  })
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>