<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Métricas"; 
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = '0'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

//Querys para charts
// ACTIVOS
$sqlTmpActivos = "SELECT 1 as total 
                  FROM controls.activo 
                  INNER JOIN persona as p ON activo.responsable = p.id_persona
                  WHERE activo.tipo=':tipoActivo' AND activo.borrado='0'
                  AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";
//$sqlTemp = strtr($sqlTmpActivos, $sqlTmpActivosTipo1);
$qa_1 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '1', ':per_id_gerencia' => $per_id_gerencia)));
$a_1 = mysqli_num_rows($qa_1);
$qa_2 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '2', ':per_id_gerencia' => $per_id_gerencia)));
$a_2 = mysqli_num_rows($qa_2);
$qa_3 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '3', ':per_id_gerencia' => $per_id_gerencia)));
$a_3 = mysqli_num_rows($qa_3);

$qa_4 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '4', ':per_id_gerencia' => $per_id_gerencia)));
$a_4 = mysqli_num_rows($qa_4);
$qa_5 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '5', ':per_id_gerencia' => $per_id_gerencia)));
$a_5 = mysqli_num_rows($qa_5);
$qa_6 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '6', ':per_id_gerencia' => $per_id_gerencia)));
$a_6 = mysqli_num_rows($qa_6);
$qa_7 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '7', ':per_id_gerencia' => $per_id_gerencia)));
$a_7 = mysqli_num_rows($qa_7);

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
        Métricas 
        <small>Activos</small>
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
                    <div class="small-box bg-aqua">
                        <div class="inner">
                        <h3>
                          <?php
                              $query_count_activos = "SELECT 1 as total 
                                          FROM activo 
                                          INNER JOIN persona as p ON activo.responsable = p.id_persona
                                          WHERE activo.borrado='0'
                                          AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia );";
                              $count_activos = mysqli_query($con, $query_count_activos);
                              $total_activos = mysqli_num_rows($count_activos);
                              echo '
                              <td> ' . $total_activos . ' </td>
                              <td>';
                            ?>
                        </h3>

                        <p>Total de Activos</p>
                        </div>
                        <div class="icon">
                        <i class="fa fa-archive"></i>
                        </div>
                        <a href="./activos.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                        <h3 class="box-title">Activos por tipo</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        </div>
                        <div class="box-body">
                        <canvas id="pieChartATipo" style="height:250px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>                    
                </div>      
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="box box-info">
                      <div class="box-header with-border">
                        <h3 class="box-title">Activos por gerencia</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>                        
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body no-padding">
                        <table class="table table-striped">
                          <tr>
                            <th>Gerencia</th>
                            <th>Activos</th>
                            <th style="width: 40px"></th>
                          </tr>
                          <?php
                            // Activos por gerencia
                            $sqlTmpActivosXGcia = "SELECT g.nombre as label, count(1) as value 
                                              FROM activo as a 
                                              INNER JOIN persona as p ON a.responsable = p.id_persona
                                              LEFT JOIN gerencia as g ON p.gerencia= g.id_gerencia
                                              WHERE a.borrado='0'
                                              AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )
                                              GROUP BY g.nombre 
                                              ORDER BY count(1) DESC";
                            $result = mysqli_query($con, strtr($sqlTmpActivosXGcia, array(':per_id_gerencia' => $per_id_gerencia)));                          

                            if(mysqli_num_rows($result) == 0){
                              echo '<tr><td colspan="3">No hay datos.</td></tr>';
                            }else{
                              while($row = mysqli_fetch_assoc($result)){
                                echo '<tr>';
                                echo '<td>' . $row['label'] . '</td>';
                                echo '<td><div class="progress progress-xs"><div class="progress-bar progress-bar-primary" style="width: ' . $row['value']/$total_activos*100 . '%"></div></div></td>';
                                echo '<td class="text-center">' . $row['value'] . '</td>';
                                echo '</tr>';
                              }
                            }
                          ?>
                        </table>
                      </div>
                      <!-- /.box-body -->
                    </div>                  
                </div>                         
            </div>
        </section>
    <!-- /.content -->
  </div>
  <!-- Main Footer -->
  <?php include_once('./site_footer.php'); ?>

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
    var pieChartCanvas1 = $('#pieChartATipo').get(0).getContext('2d')
    var pieChartATipo       = new Chart(pieChartCanvas1)
    var PieData1        = [
      {
        value    :  <?php echo $a_1; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Datos/Información'
      },
      {
        value    : <?php echo $a_2; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Equipamiento'
      },
      {
        value    : <?php echo $a_3; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Instalaciones'
      },
      {
        value    :  <?php echo $a_4; ?>,
        color    : '#f569ff',
        highlight: '#f569ff',
        label    : 'Personal'
      },
      {
        value    : <?php echo $a_5; ?>,
        color    : '#00ff5a',
        highlight: '#00ff5a',
        label    : 'Servicios'
      },
      {
        value    : <?php echo $a_6; ?>,
        color    : '#f3ff12',
        highlight: '#f3ff12',
        label    : 'Software'
      },
      {
        value    : <?php echo $a_7; ?>,
        color    : '#f39cff',
        highlight: '#f39cff',
        label    : 'Suministros'
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
    pieChartATipo.Doughnut(PieData1, pieOptions)
  })
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>