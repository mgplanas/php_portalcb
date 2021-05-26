<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Activaciones";
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'  AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");

$hoy=date("d.m.y");			
		
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
  <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- FullCalendar -->
  <link rel="stylesheet" href="../bower_components/fullcalendar/dist/packages/core/main.css">
  <link rel="stylesheet" href="../bower_components/fullcalendar/dist/packages-premium/timeline/main.css">
  <link rel="stylesheet" href="../bower_components/fullcalendar/dist/packages-premium/resource-timeline/main.css">
  <link href='../bower_components/fullcalendar/dist/packages/core/main.css' rel='stylesheet' />
  <link href='../bower_components/fullcalendar/dist/packages/daygrid/main.css' rel='stylesheet' />
  <link href='../bower_components/fullcalendar/dist/packages/timegrid/main.css' rel='stylesheet' />
  <link href='../bower_components/fullcalendar/dist/packages-premium/timeline/main.css' rel='stylesheet' />
  <link href='../bower_components/fullcalendar/dist/packages-premium/resource-timeline/main.css' rel='stylesheet' />
  <link rel="stylesheet" href="../bower_components/datatables.net/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="./calendar/calendar.css">
  <!-- <link rel="stylesheet" href="../css/bootstrap-select.min.css"> -->

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
<!-- fullCalendar -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>

<script src='../bower_components/fullcalendar/dist/packages/core/main.js'></script>
<script src='../bower_components/fullcalendar/dist/packages/interaction/main.js'></script>
<script src='../bower_components/fullcalendar/dist/packages/daygrid/main.js'></script>
<script src='../bower_components/fullcalendar/dist/packages/timegrid/main.js'></script>
<script src='../bower_components/fullcalendar/dist/packages-premium/timeline/main.js'></script>
<script src='../bower_components/fullcalendar/dist/packages-premium/resource-common/main.js'></script>
<script src='../bower_components/fullcalendar/dist/packages-premium/resource-timeline/main.js'></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

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
        <div class="row">
            <input type="hidden" id="per_id_persona" value="<?=$rowp['id_persona'] ?>"/>
            <input type="hidden" id="per_nombre" value="<?=$rowp['apellido'] . ', ' . $rowp['nombre']?>"/>
            <?php include_once('./calendar/components/nav/nav-buttons.php'); ?>
        </div>
    </section>
    <!-- Main content -->
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- CALENDAR -->
                <div class="box collapse in" id="calendar_container" >
                    <div class="box-body no-padding">
                        <!-- THE CALENDAR -->
                        <div class="row">
                            <div class="col-md-12">
                                <div id="calendar"></div>
                            </div>
                        </div>
                        <!-- BOTONES -->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /. CALENDAR -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- TABLA REGISTROS -->
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="col-md-8">
                            <h3 class="box-title">Tareas registradas</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="button" id="modal-abm-registro-btn-add" class="btn btn-default"><i class="fa fa-calendar-plus-o"></i> Agregar</button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="tbRegistroHs" class="table table-hover" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th><i class="fa fa-clock-o" title="Duración"></i></th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /. TABLA REGISTROS -->
            <div class="col-md-6">
                <!-- TABLA LICENCIAS -->
                <div class="box">
                    <div class="box-header with-border">
                        <div class="col-md-8">
                            <h3 class="box-title">Licencias</h3>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="modal-abm-cal-lic-btn-add-vacaciones" class="btn btn-default"><i class="fa fa-plane"></i> Registrar Vacaciones</button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="tbLicencia" class="table table-hover" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th><i class="fa fa-clock-o" title="Duración"></i></th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /. TABLA LICENCIAS -->
                <!-- TABLA GUARDIAS -->
                <div class="box">
                    <div class="box-header with-border">
                        <div class="col-md-8">
                            <h3 class="box-title">Guardias</h3>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="tbGuardias" class="table table-hover" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th><i class="fa fa-clock-o" title="Duración"></i></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /. TABLA GUARDIAS -->
            </div>
        </div>

    </section>  
    <?php include_once('./calendar/activaciones/activacion.modal.php'); ?>
    <?php include_once('./calendar/licencias/licencia.modal.php'); ?>
    <!-- /.End content -->
  </div>
  <!-- Main Footer -->
  <?php include_once('./site_footer.php'); ?>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery UI 1.11.4 -->
<script src="../bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Moment -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<!-- Sweet alert2 -->
<script src="../js/sweetalert2.all.min.js"></script>
<!-- Custom -->
<script type="module" src="./calendar/user_calendar.js"></script>
</body>
</html>