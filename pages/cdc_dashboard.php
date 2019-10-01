<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Dashboard Clientes DC";
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'  AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				
		
?>
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

  <!-- ChartJS -->
  <script src="../js/chart.js"></script>
  <script src="../js/chart.min.js"></script>

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

    .bg-green-item {
      background-color: #20c67a !important;
    }
    .bg-blue-item {
      background-color: #2093d7 !important;
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
        <h1>Gestión de Clientes</h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
      <!--------------------------
      | Your Page Content Here |
      -------------------------->
      <section class="content">
        <div class="row">
          <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-orange">
                <div class="inner">
                    <h3 id='cdc_dashboard-qcustomer'>0</h3>
                    <p>Total Clientes</p>
                </div>
                <div class="icon"><i class="fa fa-users"></i></div>
                </div>
            </div>
          <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                  <div class="inner">
                      <h3 id='cdc_dashboard-qservices'>0</h3>
                      <p>Total Servicios Hosting (VMs)</p>
                  </div>
                  <div class="icon"><i class="fa fa-server"></i></div>
              </div>
          </div>                        

          <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-blue">
                  <div class="inner">
                      <h3 id='cdc_dashboard-qservicios'>0</h3>
                      <p>Total Servicios de Housing</p>
                  </div>
                  <div class="icon"><i class="fa fa-home"></i></div>
              </div>
          </div>
          <!-- MODAL PLACE HOLDER -->
          <!-- FIN Housing -->        
        </div>
        <div class="row">
          <div class="col-md-4">
            <!-- TORTA DISTRIBUCIÓN TIPO CLIENTE -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Distribución de Clientes por sector</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="cdc_dashboard_dist_cli" height="250" style="display: block; height:250px"></canvas>
              </div>
              <!-- /.box-body -->
            </div>            
          </div>
          <!-- SERVICIOS HOSTING -->
          <div class="col-md-4">
              <!-- small box -->
              <div class="small-box bg-green bg-green-item">
                  <div class="inner">
                      <h3 id='cdc_dashboard-qcpu'>0</h3>
                      <p>Total CPU</p>
                  </div>
                  <div class="icon"><i class="fa fa-cubes"></i></div>
              </div>
              <!-- small box -->
              <div class="small-box bg-green bg-green-item">
                  <div class="inner">
                      <h3 id='cdc_dashboard-qstorage'>0</h3>
                      <p>Total Storage (GB)</p>
                  </div>
                  <div class="icon"><i class="fa fa-database"></i></div>
              </div>
              <!-- small box -->
              <div class="small-box bg-green bg-green-item">
                  <div class="inner">
                      <h3 id='cdc_dashboard-qram'>0</h3>
                      <p>Total RAM (GB)</p>
                  </div>
                  <div class="icon"><i class="fa fa-microchip"></i></div>
              </div>

          </div>          
          <!-- SERVICIOS HOUSING -->
          <div class="col-md-4">
              <!-- small box -->
              <div class="small-box bg-blue bg-blue-item">
                  <div class="inner">
                      <h3 id='cdc_dashboard-qkva'>0</h3>
                      <p>Total Energía (KVA)</p>
                  </div>
                  <div class="icon"><i class="fa fa-bolt"></i></div>
              </div>
              <!-- small box -->
              <div class="small-box bg-blue bg-blue-item">
                  <div class="inner">
                      <h3 id='cdc_dashboard-qm2'>0</h3>
                      <p>Total M2</p>
                  </div>
                  <div class="icon"><i class="fa fa-th-large"></i></div>
              </div>
          </div>          
        </div>
      </section>
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
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- export -->

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
  });
</script>
<script>
  $(function () {
    var chart_dist_cli = null;

    // TOTALES Clientes
    function fn_show_tot_clientes() {
      // consulta de datos
      query = 'SELECT COUNT(1) as qcustomer FROM cdc_cliente where borrado = 0';
      // Busco datos indicadores storage
      $.ajax({
          type: 'POST',
          url: './helpers/getAsyncDataFromDB.php',
          data: { query: query },
          dataType: 'json',
          success: function(json) {
              let item = json.data[0];
              $('#cdc_dashboard-qcustomer').html(item.qcustomer);
          },
          error: function(xhr, status, error) {
              alert(xhr.responseText, error);
          }
      });

    }
    // TOTALES Servicios Hosting
    function fn_show_tot_servicios_hosting() {
      // consulta de datos
      query = 'SELECT CONVERT(SUM(storage),UNSIGNED) as qstorage, SUM(vcpu) as qvcpu, CONVERT(SUM(ram),UNSIGNED) as qram, count(*) as qvms FROM sdc_hosting where borrado=0';
      // Busco datos indicadores storage
      $.ajax({
          type: 'POST',
          url: './helpers/getAsyncDataFromDB.php',
          data: { query: query },
          dataType: 'json',
          success: function(json) {
              let item = json.data[0];
              $('#cdc_dashboard-qservices').html(item.qvms);
              $('#cdc_dashboard-qstorage').html(item.qstorage);
              $('#cdc_dashboard-qram').html(item.qram);
              $('#cdc_dashboard-qcpu').html(item.qvcpu);
          },
          error: function(xhr, status, error) {
              alert(xhr.responseText, error);
          }
      });

    }
    // TOTALES Servicios Housing
    function fn_show_tot_servicios_housing() {
      // consulta de datos
      query = 'SELECT SUM(energia) as qkva, SUM(m2) as qm2, count(*) as qservicios FROM sdc_housing where borrado=0';
      // Busco datos indicadores storage
      $.ajax({
          type: 'POST',
          url: './helpers/getAsyncDataFromDB.php',
          data: { query: query },
          dataType: 'json',
          success: function(json) {
              let item = json.data[0];
              $('#cdc_dashboard-qkva').html(item.qkva);
              $('#cdc_dashboard-qm2').html(item.qm2);
              $('#cdc_dashboard-qservicios').html(item.qservicios);
          },
          error: function(xhr, status, error) {
              alert(xhr.responseText, error);
          }
      });

    }
    // TOTALES ABIERTOS/CERRADOS AM
    function fn_show_dist_cli() {
      if (chart_dist_cli !=null) {
        chart_dist_cli.destroy();
      }
      // consulta de datos
      query = "SELECT COUNT(IF(sector='Publico',1,null)) as publicos, COUNT(IF(sector='Privado',1,null)) as privados FROM cdc_cliente where borrado = 0;";
      $.ajax({
          type: 'POST',
          url: './helpers/getAsyncDataFromDB.php',
          data: { query: query },
          dataType: 'json',
          success: function(json) {
              console.log(json);
              var publicos = json.data[0].publicos;
              var privados = json.data[0].privados;
              chart_dist_cli = new Chart($("#cdc_dashboard_dist_cli"),
              {
                "type":"doughnut",
                "data":{
                  "labels":["Públicos","Privados"],
                  "datasets":[{
                    "label":"My First Dataset",
                    "data":[publicos, privados],
                    "backgroundColor":["rgb(245, 105, 84)","rgb(0, 166, 90)"]
                  }]
                }
              });              
          },
          error: function(xhr, status, error) {
              alert(xhr.responseText, error);
          }
      });
    }

    fn_show_dist_cli();
    fn_show_tot_clientes();
    fn_show_tot_servicios_hosting();
    fn_show_tot_servicios_housing();
  });
</script>
</body>
</html>