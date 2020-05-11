<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Mejoras";
$user=$_SESSION['usuario'];

$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$referencia = mysqli_real_escape_string($con,(strip_tags($_GET["ref"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT i.*, p.nombre, p.apellido, o.descripcion as dorig, op.nombre as opn, op.apellido as opa FROM mejora as i 
						 	  LEFT JOIN persona as p on i.responsable = p.id_persona
                              LEFT JOIN persona as op on i.abierto = op.id_persona
                              LEFT JOIN origen as o on i.origen = o.id_origen
							  WHERE i.borrado='0' AND i.id_mejora='$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: mejoras.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){

	$origen = mysqli_real_escape_string($con,(strip_tags($_POST["origen"],ENT_QUOTES)));
	$matriz = mysqli_real_escape_string($con,(strip_tags($_POST["matriz"],ENT_QUOTES)));
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
    // Datos de auditoria
    $aud_instancia = mysqli_real_escape_string($con,(strip_tags($_POST["aud_instancia"],ENT_QUOTES)));
    $prioridad = mysqli_real_escape_string($con,(strip_tags($_POST["prioridad"],ENT_QUOTES)));
	
	// $update_mejora = mysqli_query($con, "UPDATE mejora SET descripcion='$descripcion', responsable='$responsable', abierto='$abierto', tipo='$tipo', causa='$causa', plan='$plan', estado='$estado', eficacia='$eficacia', evidencia='$evidencia', esfuerzo='$esfuerzo', costo='$costo', apertura='$apertura', correccion='$correccion', cierre='$cierre', implementacion='$implementacion', origen='$origen', modificado=NOW(), usuario='$user' WHERE id_mejora='$nik'") or die(mysqli_error());	

	$update_mejora = mysqli_query($con, "UPDATE mejora SET prioridad='$prioridad', aud_instancia='$aud_instancia', descripcion='$descripcion', matriz='$matriz', responsable='$responsable', abierto='$abierto', tipo='$tipo', causa='$causa', plan='$plan', estado='$estado', eficacia='$eficacia', evidencia='$evidencia', esfuerzo='$esfuerzo', costo='$costo', apertura='$apertura', cierre='$cierre', implementacion='$implementacion', origen='$origen', modificado=NOW(), usuario='$user' WHERE id_mejora='$nik'") or die(mysqli_error());	

    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('2', '9','$nik', now(), '$user', '$nik')") or die(mysqli_error());
	if($update_mejora){
		$_SESSION['formSubmitted'] = 1;
		header("Location: mejoras.php");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: mejoras.php");					
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
        Gestión de Mejora Continua
        <small>Editar >> <?php echo $nik; ?></small>
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
            <div class="box-body box-primary">
                <div class="container">
                      <div class="row">
                        <div class="col-sm-2">
                            <label for="origen">Origen</label>
                            <select name="origen" class="form-control">
                            <?php
                                    $origenes = mysqli_query($con, "SELECT * FROM origen WHERE id_origen IN (1,2,4)");
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
                        <div class="col-sm-2">
                            <label for="matriz">Matriz</label>
                            <select name="matriz" class="form-control">
                            <?php
                                    $matrices = mysqli_query($con, "SELECT * FROM mc_matriz");
                                    while($rowpo = mysqli_fetch_array($matrices)){
                                        if($rowpo['id']==$row['matriz']) {
                                            echo "<option value='". $rowpo['id'] . "' selected='selected'>" .$rowpo['nombre'] . "</option>";
                                        }
                                        else {
                                            echo "<option value='". $rowpo['id'] . "'>" .$rowpo['nombre'] . "</option>";			    }
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
                                    $personasn = mysqli_query($con, "SELECT * FROM persona ORDER BY apellido, nombre");
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
                                    $personasn = mysqli_query($con, "SELECT * FROM persona ORDER BY apellido, nombre");
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
                        <label for="causa">Análisis de Causas (NC) / Objetivo de la Mejora (OM)</label>
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
                            <div class="input-group date" data-provide="datepicker1">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                                <input type="text" class="form-control pull-right" name="apertura" id="datepicker1" value="<?php echo $row ['apertura']; ?>">
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-3">
                            <label for="cierre">Fecha de Cierre</label>
                            <div class="input-group date" data-provide="datepicker3">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                                <input type="text" class="form-control pull-right" name="cierre" id="datepicker3" value="<?php echo $row ['cierre']; ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="implementacion">Fecha de Implementación</label>
                               <div class="input-group date" data-provide="datepicker4">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                    <input type="text" class="form-control pull-right" name="implementacion" id="datepicker4" value="<?php echo $row ['implementacion']; ?>">
                                </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="aud_instancia">Ente Auditor / Instancia</label>
                                <select name="aud_instancia" class="form-control">
                                    <option value="">Seleccione...</option>
                                <?php
                                    $instancia = mysqli_query($con, "SELECT i.*, e.razon_social FROM aud_instancias as i INNER JOIN aud_entes as e on i.id_ente = e.id where i.borrado = 0 ORDER BY fecha_inicio desc;");
                                    while($row_instancia = mysqli_fetch_array($instancia)){
                                        $fecha_inicio = date('d/m/Y',strtotime($row_instancia['fecha_inicio']));
                                        $fecha_inicio;
                                        if($row_instancia['id']==$row['aud_instancia']) {
                                            echo "<option value='". $row_instancia['id'] .  "' selected='selected'>" .$row_instancia['razon_social'] . " - " . $row_instancia['nombre']. " - " . $fecha_inicio."</option>";
                                        }
                                        else {
                                            echo "<option value='". $row_instancia['id'] .  "'>" .$row_instancia['razon_social'] . " - " . $row_instancia['nombre']. " - " . $fecha_inicio. "</option>";
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="prioridad">Prioridad</label>
                                <select name="prioridad" class="form-control">
                                    <option value='0'<?php if($row['prioridad'] == '0'){ echo 'selected'; } ?>>Baja</option>
                                    <option value='1'<?php if($row['prioridad'] == '1'){ echo 'selected'; } ?>>Media</option>
                                    <option value='2'<?php if($row['prioridad'] == '2'){ echo 'selected'; } ?>>Alta</option>
                                </select>
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
                        <div class="col-sm-4">
                            <input type="submit" name="save" class="btn  btn-raised btn-success" value="Guardar datos">
                        </div>
                        <div class="col-sm-4">
                            <a href="mejoras.php" class="btn btn-warning btn-raised">Cancelar</a>
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
     $('#datepicker3').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })
     $('#datepicker4').datepicker({
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
