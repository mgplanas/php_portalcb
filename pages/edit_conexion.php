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
$sql = mysqli_query($con, "SELECT v.*, ds.etiqueta as origen, dt.etiqueta as destino FROM vinculo as v
                                      LEFT JOIN dispositivo as ds on v.source = ds.id_dispositivo
                                      LEFT JOIN dispositivo as dt on v.target = dt.id_dispositivo
							          WHERE v.borrado='0' AND v.id_vinculo='$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: inventario.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){
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

    $update_conexion = mysqli_query($con, "UPDATE vinculo SET descripcion='$descripcion', nombre='$nombre', vlans='$vlans', velocidad='$velocidad', segurizado='$segurizado', portchannel='$portchannel', cobre='$cobre', fibra='$fibra', source='$source', target='$target' WHERE id_vinculo='$nik'") or die(mysqli_error());
    
	$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('2', '11','$nik', now(), '$user', '$etiqueta')") or die(mysqli_error());
	if($update_conexion){
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
              <input type="text" class="form-control" name="etiqueta" value="<?php echo $row['nombre']; ?>">
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <?php echo "<textarea class=form-control name=descripcion>{$row['descripcion']}</textarea>"; ?>
            </div>
            <div class="form-group">
              <label for="vlans">VLans que trafican</label>
              <input type="text" class="form-control" name="vlans" value="<?php echo $row['vlans']; ?>">
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
                        if($rowdo['id_dispositivo']==$row['source']) {
                            echo "<option value='". $rowdo['id_dispositivo'] . "' selected='selected'>" .$rowdo['etiqueta'] . "</option>";
                        }
                        else {
                            echo "<option value='". $rowdo['id_dispositivo'] . "'>" .$rowdo['etiqueta'] . "</option>";										
                        }
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
                        if($rowdd['id_dispositivo']==$row['target']) {
                            echo "<option value='". $rowdd['id_dispositivo'] . "' selected='selected'>" .$rowdd['etiqueta'] . "</option>";
                        }
                        else {
                            echo "<option value='". $rowdd['id_dispositivo'] . "'>" .$rowdd['etiqueta'] . "</option>";										
                        }
                    }
				?>  
                </select>
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