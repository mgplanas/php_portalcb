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


// RIESGOS POR GERENCIA
$sqlCapexOpex = "SELECT c.capex_opex, m.sigla, FORMAT(SUM(c.pre_monto),2,'es_AR') as estimado, FORMAT(SUM(c.oc_monto),2,'es_AR') as adjudicado,
CAST((SUM(c.oc_monto)*100/SUM(c.pre_monto)) AS DECIMAL(5,2)) as delta,
COUNT(1) as cantidad
FROM adm_compras as c 
INNER JOIN adm_monedas as m ON c.pre_id_moneda = m.id
WHERE c.id_estado = 2
AND c.borrado = 0
GROUP BY c.capex_opex, m.sigla
ORDER BY c.capex_opex, m.sigla;";

$sqlCapexOpexVigente = "SELECT c.capex_opex, m.sigla, FORMAT(SUM(c.pre_monto),2,'es_AR') as estimado, FORMAT(SUM(c.oc_monto),2,'es_AR') as adjudicado,
CAST((SUM(c.oc_monto)*100/SUM(c.pre_monto)) AS DECIMAL(5,2)) as delta,
COUNT(1) as cantidad
FROM adm_compras as c 
INNER JOIN adm_monedas as m ON c.pre_id_moneda = m.id
WHERE c.id_estado = 2
AND c.borrado = 0
AND YEAR(c.fecha_solicitud) = YEAR(CURDATE()) AND YEAR(c.fecha_oc) = YEAR(CURDATE())
GROUP BY c.capex_opex, m.sigla
ORDER BY c.capex_opex, m.sigla;";



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
        <small>Compras</small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	    <section class="content">
            <div class="row">
                <div class="col-lg-5 col-xs-12">
                    <div class="row">
                        <!-- RIESGO POR GERENCIA ABIERTOS -->
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Estimado vs Adjudicado</h3>
                            </div>
                            <div class="box-body no-padding">
                            <table class="table table-striped">
                                <tr>
                                    <th>CAPEX/OPEX</th>
                                    <th style="text-align: center;">Moneda</th>
                                    <th style="text-align: center;">Cantidad</th>
                                    <th style="text-align: right;">Estimado</th>
                                    <th style="text-align: right;">Adjudicado</th>
                                    <th style="text-align: right;">Delta</th>
                                </tr>
                                <?php
                                    $resRA = mysqli_query($con, $sqlCapexOpex);
                                    $allRows = mysqli_num_rows($resRA);
                                    if ($allRows == 0) {
                                        echo '<tr><td colspan="4">No hay datos.</td></tr>';
                                    }else {
                                        $capexOpex_actual = '';
                                        $cambio = true;
                                        while($row = mysqli_fetch_assoc($resRA)){ 
                                            $cambio = ($capexOpex_actual != $row['capex_opex']);
                                            $capexOpex_actual = $row['capex_opex'];
                                            echo '<tr>';
                                            echo '<td>' . ($cambio ? ($row['capex_opex']=='O' ? 'OPEX' : 'CAPEX') : '') . '</td>';
                                            echo '<td align="center">' . $row['sigla'] . '</td>';
                                            echo '<td align="center">' . $row['cantidad'] . '</td>';
                                            echo '<td align="right">' . $row['estimado'] . '</td>';
                                            echo '<td align="right">' . $row['adjudicado'] . '</td>';
                                            echo '<td align="right">' . $row['delta']  . '%</td>';
                                            echo '</tr>';
                                    }
                                    }
                                ?>                                
                            </table>
                            </div>
                            <!-- /.box-body -->
                        </div>     
                    </div>
                    <div class="row">
                        <!-- RIESGO POR GERENCIA ABIERTOS -->
                        <div class="box box-success">
                            <div class="box-header">
                                <h3 class="box-title">Estimado vs Adjudicado (Año vigente)</h3>
                            </div>
                            <div class="box-body no-padding">
                            <table class="table table-striped">
                                <tr>
                                    <th>CAPEX/OPEX</th>
                                    <th style="text-align: center;">Moneda</th>
                                    <th style="text-align: center;">Cantidad</th>
                                    <th style="text-align: right;">Estimado</th>
                                    <th style="text-align: right;">Adjudicado</th>
                                    <th style="text-align: right;">Delta</th>
                                </tr>
                                <?php
                                    $resRA = mysqli_query($con, $sqlCapexOpexVigente);
                                    $allRows = mysqli_num_rows($resRA);
                                    if ($allRows == 0) {
                                        echo '<tr><td colspan="4">No hay datos.</td></tr>';
                                    }else {
                                        $capexOpex_actual = '';
                                        $cambio = true;
                                        while($row = mysqli_fetch_assoc($resRA)){ 
                                            $cambio = ($capexOpex_actual != $row['capex_opex']);
                                            $capexOpex_actual = $row['capex_opex'];
                                            echo '<tr>';
                                            echo '<td>' . ($cambio ? ($row['capex_opex']=='O' ? 'OPEX' : 'CAPEX') : '') . '</td>';
                                            echo '<td align="center">' . $row['sigla'] . '</td>';
                                            echo '<td align="center">' . $row['cantidad'] . '</td>';
                                            echo '<td align="right">' . $row['estimado'] . '</td>';
                                            echo '<td align="right">' . $row['adjudicado'] . '</td>';
                                            echo '<td align="right">' . $row['delta']  . '%</td>';
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
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>