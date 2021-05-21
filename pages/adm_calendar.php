<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Calendario";
$user=$_SESSION['usuario'];

//Get user query
$per_query = "SELECT per.id_persona,per.apellido, per.gerencia, per.subgerencia, per.area, 
CASE WHEN per.id_persona = g.responsable THEN 1 ELSE 0 END as es_gerente,
CASE WHEN per.id_persona = s.responsable THEN 1 ELSE 0 END as es_subgerente,
CASE WHEN per.id_persona = a.responsable THEN 1 ELSE 0 END as es_jefe,
g.responsable as gerente, 
s.responsable as subgerente, 
a.responsable as jefe
FROM persona as per 
LEFT JOIN area as a on per.area = a.id_area
LEFT JOIN subgerencia as s on per.subgerencia = s.id_subgerencia
LEFT JOIN gerencia as g on per.gerencia = g.id_gerencia
WHERE per.email='$user' AND per.borrado=0;";

$persona = mysqli_query($con, $per_query);
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['soc']=='0'){
	header('Location: ../site.php');
}

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
            <input type="hidden" id="per-area" value="<?=$rowp['area'] ?>"/>
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
          <div class="box collapse in" id="calendar_container">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
        <!-- Modal to Event Details -->
        <?php
            // include_once('./modals/abmlicencia.php');
        ?>  
      </div>
      <!-- /.row -->
    </section>  
    <?php include_once('./calendar/guardias/guardia_simple.modal.php'); ?>
    <?php include_once('./calendar/guardias/guardia_multiple.modal.php'); ?>
    <!-- /.End content -->
  </div>
  <!-- Main Footer -->
  <?php include_once('./site_footer.php'); ?>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../bower_components/jquery-ui/jquery-ui.min.js"></script>
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
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Sweet alert2 -->
<script src="../js/sweetalert2.all.min.js"></script>
<!-- <script src="../js/bootstrap-select.min.js"></script> -->
<!-- custom scripts --> 
<!-- <script type="text/javascript" src="../js/script.js"></script>  -->
<script type="module" src="./calendar/adm_calendar.js"></script>
</body>
</html>