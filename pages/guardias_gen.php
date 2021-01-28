<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="GenGuardias";
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				

if ($rq_sec['admin']!='1') {
	header('Location: ../index.html');
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $gmtTimezone = new DateTimeZone('GMT');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $nivel=$_POST['nivel'];
    $persona=$_POST['persona'];
    $fechaIni=$_POST['fechaIni'];
    $fechaIni = date('Y-m-d',strtotime(str_replace('/', '-', $fechaIni)));
    $fechaFin=$_POST['fechaFin'];
    $fechaFin = date('Y-m-d',strtotime(str_replace('/', '-', $fechaFin)));
    $dias=$_POST['dias'];
    $hora = "00:00:00";

    $dtFecha = date_create($fechaIni);
    $dtFechaIni = date_create($fechaIni);
    $dtFechaFin = date_create($fechaFin);
    $flag_apply = true;
    $counter = 1;
    while ($dtFecha <= $dtFechaFin) {
        $dtFechaNext = date_create(date_format($dtFecha, "Y-m-d"));
        date_add($dtFechaNext, date_interval_create_from_date_string("1 day"));
        
        if ($flag_apply) {
            $fmtIni = date_format($dtFecha,'Y-m-d');
            $fmtFin = date_format($dtFechaNext,'Y-m-d');
            echo "INSERT INTO item_calendario (tipo, persona, startDay, endDay, startTime, endTime) VALUES ('$nivel','$persona', '$fmtIni','$fmtFin','00:00:00','00:00:00');";
            echo "<br>";
        }
        if ($counter>0 && $counter%$dias==0) {
            $flag_apply = !$flag_apply;
        }
        $counter++;
        date_add( $dtFecha, date_interval_create_from_date_string("1 day"));
    }
    die();

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
  <link rel="stylesheet" href="../bower_components/datatables.net/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
   <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
	<section class="content-header">
      <h1>Generacion de guardias</h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	 <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <div class="col-sm-6" style="text-align:left">
                <h2 class="box-title">Generador de guardias</h2>
              </div>
            </div>

            <!-- /.box-header -->
            <form method="post" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Nivel</label>
                        <select name="nivel" class="form-control" id="modal-gen-guardia-nivel">
                            <?php
                                $tipo = mysqli_query($con, "SELECT * FROM tipo_calendario WHERE allDay=1;" );
                                while($rowtipo = mysqli_fetch_array($tipo)){
                                    echo "<option value='". $rowtipo['id_tipo_calendario'] . "'>" .$rowtipo['titulo'] . "</option>";										
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Persona</label>
                        <select name="persona" class="form-control" id="modal-gen-guardia-responsable">
                            <?php
                                echo "<option value='0'>Ninguno</option>";										
                                $personasn = mysqli_query($con, "SELECT * FROM persona  WHERE borrado=0 ORDER BY apellido, nombre");
                                while($rowps = mysqli_fetch_array($personasn)){
                                    echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fechaIni">Fecha de inicio</label>
                                <div class="input-group date" data-provide="gen-guardia-fecha-ini">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" required="required" name="fechaIni" id="gen-guardia-fecha-ini">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fechaFin">Fecha de Fin</label>
                                <div class="input-group date" data-provide="gen-guardia-fecha-fin">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" required="required" name="fechaFin" id="gen-guardia-fecha-fin">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dias">dias</label>
                                <input type="number" id="gen-guardia-dias" class="form-control" name="dias" placeholder="0">
                            </div>
                        </div>          
                    </div>          
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="submit" name="AddItem" class="btn btn-raised btn-success" value="Guardar" id='modal-gen-guardia-submit'>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
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
<!-- <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> -->
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
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>
  $(function() {
      /** add active class and stay opened when selected */
      var url = window.location;

      // for sidebar menu entirely but not cover treeview
      $('ul.sidebar-menu a').filter(function() {
        return this.href == url;
      }).parent().addClass('active');

      // for treeview
      $('ul.treeview-menu a').filter(function() {
        return this.href == url;
      }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');    


      $('#gen-guardia-fecha-ini').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
    $('#gen-guardia-fecha-fin').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
  });
</script>
</body>
</html>