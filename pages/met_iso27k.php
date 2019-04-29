<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = '0'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

// ISO 270001
$sqlTmpISO27k = "SELECT 1 as total 
                FROM controls.item_iso27k 
                INNER JOIN persona as p ON item_iso27k.responsable = p.id_persona
                WHERE item_iso27k.madurez=:madurez
                AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";
$qiso_def = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '1', ':per_id_gerencia' => $per_id_gerencia)));
$iso_def = mysqli_num_rows($qiso_def);
$qiso_exc = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '2', ':per_id_gerencia' => $per_id_gerencia)));
$iso_exc = mysqli_num_rows($qiso_exc);
$qiso_perf = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '3', ':per_id_gerencia' => $per_id_gerencia)));
$iso_perf = mysqli_num_rows($qiso_perf);



//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				
		
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
	<section class="content-header">
      <h1>
        Métricas 
        <small>ISO 27001</small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	    <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                        <h3><?php
                              $query_count_iso = "SELECT 1 as total 
                              FROM item_iso27k 
                              INNER JOIN persona as p ON item_iso27k.responsable = p.id_persona
                              WHERE madurez='1'
                              AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )";
                              $count_iso = mysqli_query($con, $query_count_iso);
                              $query_count_iso_total = "SELECT 1 as total 
                                                FROM item_iso27k
                                                INNER JOIN persona as p ON item_iso27k.responsable = p.id_persona
                                                WHERE ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )";
                              $count_iso_total = mysqli_query($con, $query_count_iso_total);

                              if (mysqli_num_rows($count_iso_total) > 0) {
                              $mad = round(((mysqli_num_rows($count_iso)) * 100) / (mysqli_num_rows($count_iso_total)), PHP_ROUND_HALF_UP);
                              echo $mad . " %";
                              } else {
                              echo "Sin datos";
                              }
                                    ?></h3>

                        <p>Madurez ISO 27001</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-speedometer"></i>
                        </div>
                        <a href="./iso27k.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>         
            </div>
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- /.content ISO 27001-->
                    <div class="box box-success">
                        <div class="box-header with-border">
                        <h3 class="box-title">Madurez ítems ISO 27001</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        </div>
                        <div class="box-body">
                        <canvas id="pieChartISOM" style="height:250px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>          
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
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- ChartJS -->
<script src="../bower_components/chart.js/Chart.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
	var pieChartCanvas2 = $('#pieChartISOM').get(0).getContext('2d')
    var pieChartISOM       = new Chart(pieChartCanvas2)
    var PieData2        = [
      {
        value    :  <?php echo $iso_exc; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Excluido'
      },
      {
        value    : <?php echo $iso_def; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Definido'
      },
      {
        value    : <?php echo $iso_perf; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'A Perfeccionar'
      }
	]
    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
	
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChartISOM.Doughnut(PieData2, pieOptions)
  })
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>