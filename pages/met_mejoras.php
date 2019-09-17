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

///////////////////////////////////////////////////////////
// Consultas para KPIS
///////////////////////////////////////////////////////////
$sql_kpi = "SELECT M.tipo, M.origen, M.estado, COUNT(*) as cuenta
FROM mejora as M 
INNER JOIN persona as p ON M.responsable = p.id_persona
WHERE M.borrado = 0 AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )
GROUP BY M.tipo, M.origen, M.estado;";
$q_kpi = mysqli_query($con, $sql_kpi);
$row_kpi = mysqli_fetch_assoc($q_kpi);
//var_dump($sql_kpi);exit;
function extraerSum($result, $tipo, $origen, $estado) {
  $cuenta = 0;
  mysqli_data_seek($result, 0);
  while($row = mysqli_fetch_assoc($result)){
    if ( 
      ($tipo == -1 || $tipo == $row['tipo']) &&  
      ($origen == -1 || $origen == $row['origen']) &&  
      ($estado == -1 || $estado == $row['estado'])   
    ) {
      $cuenta += $row['cuenta'];
    }
  }

  return $cuenta;
}
// AM => Tipo 3 
// NC=> tipo 1
// Abierto=> Estado 0
// Cerrado=> Estado 1
// Auditor Interno=> Origen 1
// Auditor Externo=> Origen 2
// Auditor negocio=> Origen 4
// AM
// TOTALES ABIERTOS/CERRADOS
$v_kpi_AM_A = extraerSum($q_kpi, 3, -1, 0);
$v_kpi_AM_C = extraerSum($q_kpi, 3, -1, 1);

// TOTALES POR ORIGEN / ABIERTOS-CERRADOS
$v_kpi_AM_AI_A = extraerSum($q_kpi, 3, 1, 0);
$v_kpi_AM_AE_A = extraerSum($q_kpi, 3, 2, 0);
$v_kpi_AM_NE_A = extraerSum($q_kpi, 3, 4, 0);
$v_kpi_AM_AI_C = extraerSum($q_kpi, 3, 1, 1);
$v_kpi_AM_AE_C = extraerSum($q_kpi, 3, 2, 1);
$v_kpi_AM_NE_C = extraerSum($q_kpi, 3, 4, 1);

//NC
// TOTALES ABIERTOS/CERRADOS
$v_kpi_NC_A = extraerSum($q_kpi, 1, -1, 0);
$v_kpi_NC_C = extraerSum($q_kpi, 1, -1, 1);

// TOTALES POR ORIGEN / ABIERTOS-CERRADOS
$v_kpi_NC_AI_A = extraerSum($q_kpi, 1, 1, 0);
$v_kpi_NC_AE_A = extraerSum($q_kpi, 1, 2, 0);
$v_kpi_NC_NE_A = extraerSum($q_kpi, 1, 4, 0);
$v_kpi_NC_AI_C = extraerSum($q_kpi, 1, 1, 1);
$v_kpi_NC_AE_C = extraerSum($q_kpi, 1, 2, 1);
$v_kpi_NC_NE_C = extraerSum($q_kpi, 1, 4, 1);




// ///////////////////////////////////////////////////////////
// // Consultas para KPIS
// ///////////////////////////////////////////////////////////
// $sql_kpi_total_OC = "SELECT COUNT(*) as cuenta FROM mejora as M 
//                INNER JOIN persona as p ON M.responsable = p.id_persona
//                WHERE M.tipo = :tipo 
//                AND M.borrado = 0 
//                AND M.estado = :status 
//                AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";


// $q_kpi_AM_A = mysqli_query($con, strtr($sql_kpi_total_OC, array(':tipo'=> '3', ':status' => '0', ':per_id_gerencia' => $per_id_gerencia)));
// $v_kpi_AM_A = mysqli_fetch_assoc($q_kpi_AM_A);
// $q_kpi_AM_C = mysqli_query($con, strtr($sql_kpi_total_OC, array(':tipo'=> '3', ':status' => '1', ':per_id_gerencia' => $per_id_gerencia)));
// $v_kpi_AM_C = mysqli_fetch_assoc($q_kpi_AM_C);


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
<!-- ChartJS -->
<script src="../js/chart.js"></script>
<script src="../js/chart.min.js"></script>
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
        <small>Mejora Contínua</small>
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
                <!-- CantidadAM Abiertos/Cerrados-->
                <div class="box box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title">AM - Cantidad Abiertos / Cerrados</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div class="box-body">
                    <canvas id="kpi_AM_OC" height="250" style="display: block; height:250px"></canvas>
                  </div>
                  <!-- /.box-body -->
                </div>
              </div>     
              <div class="col-lg-4 col-xs-6">
                <!-- CantidadAM Abiertos/Cerrados-->
                <div class="box box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title">AM - Por Origen</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div class="box-body">
                    <canvas id="kpi_AM_O_OC" height="212" style="display: block; height:250px"></canvas>
                  </div>
                  <!-- /.box-body -->
                </div>
              </div>                      
              <div class="col-lg-5 col-xs-6">
                <!-- CantidadAM Abiertos/Cerrados-->
                <div class="box box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title">AM - Por Responsable</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div class="box-body">
                    <canvas id="kpi_AM_R_OC" height="212" style="display: block; height:250px"></canvas>
                  </div>
                  <!-- /.box-body -->
                </div>
              </div>                      
            </div>    
            <div class="row">
              <div class="col-lg-3 col-xs-6">
                <!-- CantidadAM Abiertos/Cerrados-->
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">NC Cantidad Abiertos / Cerrados</h3>
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div>
                    <div class="box-body">
                      <canvas id="kpi_NC_OC" height="250" style="display: block; height:250px"></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
              </div> 
              <div class="col-lg-4 col-xs-6">
                <!-- CantidadAM Abiertos/Cerrados-->
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <h3 class="box-title">NC - Por Origen</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div class="box-body">
                    <canvas id="kpi_NC_O_OC" height="212" style="display: block; height:250px"></canvas>
                  </div>
                  <!-- /.box-body -->
                </div>
              </div> 
              <div class="col-lg-5 col-xs-6">
                <!-- CantidadAM Abiertos/Cerrados-->
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <h3 class="box-title">NC - Por Responsable</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div class="box-body">
                    <canvas id="kpi_NC_R_OC" height="212" style="display: block; height:250px"></canvas>
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
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>

<script>
  $(function () {

    // GRAFICO BARRA POR ORIGEN AM
    function fn_ShowKPI_AM_O() {
        var origenes = ["Auditoría Int.", "Auditoría Ext.", "Negocio"];
        var abiertos = [<?=$v_kpi_AM_AI_A?>,<?=$v_kpi_AM_AE_A?>,<?=$v_kpi_AM_NE_A?>];
        var cerrados = [<?=$v_kpi_AM_AI_C?>,<?=$v_kpi_AM_AE_C?>,<?=$v_kpi_AM_NE_C?>];
        
        var chartdata = {
            labels: origenes,
            datasets: [
              {
                    label: 'Abiertos',
                    data: abiertos,
                    backgroundColor: 'rgb(245, 105, 84)'
                  },
                {
                    label: 'Cerrados',
                    data: cerrados,
                    backgroundColor: 'rgb(0, 166, 90)'
                }
            ]
        };
        var options = {
            responsive: true,
            title: {
                display: false,
                position: "top",
                text: "Bar Graph",
                fontSize: 18,
                fontColor: "#111"
            },
            legend: {
                display: true,
                position: "top",
                labels: {
                    fontColor: "#333",
                    fontSize: 16
                }
            },
            scales: {
                    xAxes: [{ stacked: true }],
                    yAxes: [{ stacked: true }]
                  }
        };

        var graphTarget = $("#kpi_AM_O_OC");

        var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: options
        });
  
    } 
    // GRAFICO BARRA POR ORIGEN AM
    function fn_ShowKPI_NC_O() {
        var origenes = ["Auditoría Int.", "Auditoría Ext.", "Negocio"];
        var abiertos = [<?=$v_kpi_NC_AI_A?>,<?=$v_kpi_NC_AE_A?>,<?=$v_kpi_NC_NE_A?>];
        var cerrados = [<?=$v_kpi_NC_AI_C?>,<?=$v_kpi_NC_AE_C?>,<?=$v_kpi_NC_NE_C?>];
        
        var chartdata = {
            labels: origenes,
            datasets: [
              {
                    label: 'Abiertos',
                    data: abiertos,
                    backgroundColor: 'rgb(245, 105, 84)'
                  },
                {
                    label: 'Cerrados',
                    data: cerrados,
                    backgroundColor: 'rgb(0, 166, 90)'
                }
            ]
        };
        var options = {
            responsive: true,
            title: {
                display: false,
                position: "top",
                text: "Bar Graph",
                fontSize: 18,
                fontColor: "#111"
            },
            legend: {
                display: true,
                position: "top",
                labels: {
                    fontColor: "#333",
                    fontSize: 16
                }
            },
            scales: {
                    xAxes: [{ stacked: true }],
                    yAxes: [{ stacked: true }]
                  }
        };

        var graphTarget = $("#kpi_NC_O_OC");

        var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: options
        });
  
    }     
    // TOTALES ABIERTOS/CERRADOS AM
    function fn_ShowKPI_AM() {
      new Chart($("#kpi_AM_OC"),
        {
          "type":"doughnut",
          "data":{
            "labels":["Total Abiertos","Total Cerrados"],
            "datasets":[{
              "label":"My First Dataset",
              "data":[<?=$v_kpi_AM_A ?>, <?=$v_kpi_AM_C; ?>],
              "backgroundColor":["rgb(245, 105, 84)","rgb(0, 166, 90)"]
            }]
          }
        });
    }
    // TOTALES ABIERTOS/CERRADOS NC
    function fn_ShowKPI_NC() {
      new Chart($("#kpi_NC_OC"),
        {
          "type":"doughnut",
          "data":{
            "labels":["Total Abiertos","Total Cerrados"],
            "datasets":[{
              "label":"My First Dataset",
              "data":[<?=$v_kpi_NC_A ?>, <?=$v_kpi_NC_C; ?>],
              "backgroundColor":["rgb(245, 105, 84)","rgb(0, 166, 90)"]
            }]
          }
        });
    }

    // AM Por Responsables
    function fn_ShowKPI_AM_R() {
      // Busco el servicio
      $.ajax({
          type: 'POST',
          url: './helpers/getAsyncDataFromDB.php',
          data: { query: "SELECT CONCAT(p.apellido,', ',p.nombre) as persona, COUNT(IF(estado='0',1,null)) as abiertos, COUNT(IF(estado='1',1,null)) as cerrados FROM mejora as M INNER JOIN persona as p ON M.responsable=p.id_persona WHERE M.tipo = 3 AND M.borrado = 0 AND ( 1 = <?=$per_id_gerencia ?> OR  p.gerencia = <?=$per_id_gerencia ?> ) group by M.responsable ORDER BY (COUNT(IF(estado='0',1,0)) + COUNT(IF(estado='1',1,0))) DESC"},
          dataType: 'json',
          success: function(json) {
              let parsedData = json.data;
              var name = [];
              var abiertos = [];
              var cerrados = [];
              
              // parsedData = JSON.parse(data);
              
              for (var i in parsedData) {
                  name.push(parsedData[i].persona);
                  abiertos.push(parsedData[i].abiertos);
                  cerrados.push(parsedData[i].cerrados);
              }
              var chartdata = {
                  labels: name,
                  datasets: [
                    {
                          label: 'Abiertos',
                          data: abiertos,
                          backgroundColor: 'rgb(245, 105, 84)'
                        },
                      {
                          label: 'Cerrados',
                          data: cerrados,
                          backgroundColor: 'rgb(0, 166, 90)'
                      }
                  ]
              };
              var options = {
                  responsive: true,
                  title: {
                      display: false,
                      position: "top",
                      text: "Bar Graph",
                      fontSize: 18,
                      fontColor: "#111"
                  },
                  legend: {
                      display: true,
                      position: "top",
                      labels: {
                          fontColor: "#333",
                          fontSize: 16
                      }
                  },
                  scales: {
                          xAxes: [{ stacked: true }],
                          yAxes: [{ stacked: true }]
                        }
              };

              var graphTarget = $("#kpi_AM_R_OC");

              var barGraph = new Chart(graphTarget, {
                  type: 'horizontalBar',
                  data: chartdata,
                  options: options
              });
          },
          error: function(xhr, status, error) {
              alert(xhr.responseText, error);
          }
      });
    }     
    // NC Por Responsables
    function fn_ShowKPI_NC_R() {
      // Busco el servicio
      $.ajax({
          type: 'POST',
          url: './helpers/getAsyncDataFromDB.php',
          data: { query: "SELECT CONCAT(p.apellido,', ',p.nombre) as persona, COUNT(IF(estado='0',1,null)) as abiertos, COUNT(IF(estado='1',1,null)) as cerrados FROM mejora as M INNER JOIN persona as p ON M.responsable=p.id_persona WHERE M.tipo = 1 AND M.borrado = 0 AND ( 1 = <?=$per_id_gerencia ?> OR  p.gerencia = <?=$per_id_gerencia ?> ) group by M.responsable ORDER BY (COUNT(IF(estado='0',1,0)) + COUNT(IF(estado='1',1,0))) DESC"},
          dataType: 'json',
          success: function(json) {
              let parsedData = json.data;
              var name = [];
              var abiertos = [];
              var cerrados = [];
              
              // parsedData = JSON.parse(data);
              
              for (var i in parsedData) {
                  name.push(parsedData[i].persona);
                  abiertos.push(parsedData[i].abiertos);
                  cerrados.push(parsedData[i].cerrados);
              }
              var chartdata = {
                  labels: name,
                  datasets: [
                    {
                          label: 'Abiertos',
                          data: abiertos,
                          backgroundColor: 'rgb(245, 105, 84)'
                        },
                      {
                          label: 'Cerrados',
                          data: cerrados,
                          backgroundColor: 'rgb(0, 166, 90)'
                      }
                  ]
              };
              var options = {
                  responsive: true,
                  title: {
                      display: false,
                      position: "top",
                      text: "Bar Graph",
                      fontSize: 18,
                      fontColor: "#111"
                  },
                  legend: {
                      display: true,
                      position: "top",
                      labels: {
                          fontColor: "#333",
                          fontSize: 16
                      }
                  },
                  scales: {
                          xAxes: [{ stacked: true }],
                          yAxes: [{ stacked: true }]
                        }
              };

              var graphTarget = $("#kpi_NC_R_OC");

              var barGraph = new Chart(graphTarget, {
                  type: 'horizontalBar',
                  data: chartdata,
                  options: options
              });
          },
          error: function(xhr, status, error) {
              alert(xhr.responseText, error);
          }
      });
    }     


    fn_ShowKPI_AM();
    fn_ShowKPI_NC();
    fn_ShowKPI_AM_O();
    fn_ShowKPI_NC_O();
    fn_ShowKPI_AM_R();
    fn_ShowKPI_NC_R();
  });
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>