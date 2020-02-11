<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Compensatorios";
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				

// Funciones auxiliares
function getRegistroCompensatorio($id_persona, $id_periodo, $arr) {
    $result = null;

    foreach ($arr as $k => $v) {
        if ($id_persona == intval($v['id_persona'])) {

            if ($id_periodo > 0) {
                if ($id_periodo == intval($v['id_periodo'])) {
                    $result = $v;
                break;
                }
            } else {
                $result = $v;
            break;
            }
        }
    }

    return $result;
}

//Compensatorios
//-------------------------------------------------------------------------------------------------
function getTotalFromPersona($id_persona, $arr) {
    $total = 0;
    foreach ($arr as $key => $value) {
        if($value['id_persona'] == $id_persona) {
            return $value['Total'];
        }
    }
    return $total;
}

$arrCompensatorios = [];
$arrPeriodos = [];

$query = "SELECT sub.nombre as subgerencia, area.nombre as area, per.apellido, per.nombre, sumatoria.* , p.fecha_desde, p.fecha_hasta
FROM persona as per
LEFT JOIN subgerencia as sub ON per.subgerencia = sub.id_subgerencia
LEFT JOIN area ON per.area = area.id_area
INNER JOIN 
( 
SELECT bal.id_persona, bal.id_periodo, 
  IFNULL(CASE WHEN bal.tipo = 'C' THEN SUM(dias) END,0) as compensatorios,
  IFNULL(CASE WHEN bal.tipo = 'R' THEN SUM(dias) END,0) as recuperos
FROM adm_cmp_balance as bal
GROUP BY id_periodo, id_persona, tipo
) AS sumatoria ON per.id_persona = sumatoria.id_persona
INNER JOIN adm_cmp_periodos as p ON sumatoria.id_periodo = p.id AND fecha_desde >= DATE_ADD(NOW(), INTERVAL -6 MONTH) 
ORDER BY sub.nombre, area.nombre, per.apellido, per.nombre,sumatoria.id_periodo;";

$sql = mysqli_query($con, $query);

$aux_per_fecha = '';
$periodo_max=0;
$periodo_min=999999;
while($row = mysqli_fetch_assoc($sql)){    
    $periodo_min = (intval($row['id_periodo']) < $periodo_min ?  intval($row['id_periodo']) : $periodo_min);
    $periodo_max = (intval($row['id_periodo']) > $periodo_max ?  intval($row['id_periodo']) : $periodo_max);
    array_push($arrCompensatorios, $row);
}
$periodo_min = ($periodo_min > ($periodo_max)-4 ? $periodo_min : ($periodo_max)-4 );

unset($sql);
$query = "SELECT id, fecha_desde, fecha_hasta FROM adm_cmp_periodos WHERE id >= '$periodo_min' and id <= '$periodo_max' ORDER BY fecha_desde";
$sql = mysqli_query($con, $query);
while($row = mysqli_fetch_assoc($sql)){    
    array_push($arrPeriodos, $row);
}

//TOTALES
$arrCompensatoriosTotales=[];
$query = "SELECT sub.nombre as subgerencia, area.nombre as area, bal.id_persona, SUM(compensatorios-recuperos) as Total FROM 
(
  SELECT bal.id_persona, bal.id_periodo, 
    IFNULL(CASE WHEN bal.tipo = 'C' THEN SUM(dias) END,0) as compensatorios,
    IFNULL(CASE WHEN bal.tipo = 'R' THEN SUM(dias) END,0) as recuperos
  FROM adm_cmp_balance as bal
  GROUP BY id_periodo, id_persona, tipo
) as bal
LEFT JOIN persona as per ON bal.id_persona = per.id_persona
LEFT JOIN subgerencia as sub ON per.subgerencia = sub.id_subgerencia
LEFT JOIN area ON per.area = area.id_area
GROUP BY sub.id_subgerencia, area.id_area, bal.id_persona;";

$sql = mysqli_query($con, $query);

while($row = mysqli_fetch_assoc($sql)){    
    array_push($arrCompensatoriosTotales, $row);
}
unset($sql);
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

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="../bower_components/datatables.net/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../bower_components/datatables.net/css/rowGroup.dataTables.min.css">
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
      <h1>Compensatorios</h1>
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
                <h2 class="box-title">Balance de Compensaciones/Recuperos</h2>
              </div>
              <div class="col-sm-6" style="text-align:right;">
              <?php if ($rq_sec['admin']=='1' OR $rq_sec['compensaciones']=='1'){ ?>
                <button type="button" id="modal-import-hosting-btn-import" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-upload"></i> Importar Planilla de Guardias</button>
              <?php } ?>
              <?php if ($rq_sec['admin']=='1' OR $rq_sec['compensaciones']=='1'){ ?>
                <button type="button" id="modal-cmp-recupero-btn-alta" class="btn-sm btn-primary" data-toggle="modal"><i class="fa fa-calendar-plus-o"></i> Agregar Recupero</button>
              <?php } ?>
              </div>
            </div>

            <!-- /.box-header -->

			<div class="box-body">
              <table id="guardias" class="display" width="100%">
                <thead>
                <tr>
                    <th rowspan="2">Subgerencia</th>
                    <th rowspan="2">Persona</th>
                    <th rowspan="2" style="text-align:center;">Total a la fecha</th>
                    <?php 
                        foreach ($arrPeriodos as $key => $value) {
                            $tdesde = strtotime($value['fecha_desde']);
                            $thasta = strtotime($value['fecha_hasta']);
                            if (time() >= $tdesde and time() <= $thasta) {
                                echo '<th colspan="2" style="text-align:center;"><span class="badge bg-blue"> al '. date_format(date_create_from_format('Y-m-d H:i:s', $value['fecha_hasta']), 'd/m/Y') .'</span></th>';
                            } else {
                                echo '<th colspan="2" style="text-align:center;"> al '. date_format(date_create_from_format('Y-m-d H:i:s', $value['fecha_hasta']), 'd/m/Y') .'</th>';
                            }
                        }
                    ?>
                </tr>
                <tr>
                    <?php 
                        foreach ($arrPeriodos as $key => $value) {
                            echo '<th style="text-align:center;">Suma</th>';
                            echo '<th style="text-align:center;">Resta</th>';
                        }
                    ?>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($arrCompensatoriosTotales as $ktot => $vtot) {
                            
                            $curr_id_persona = intval($vtot['id_persona']);
                            $aux_total = $vtot['Total'];
                            $regCompensatorio = getRegistroCompensatorio($curr_id_persona, 0, $arrCompensatorios);
                            echo '<tr>';
                            echo '<td>'. $regCompensatorio['subgerencia'].' - '. $regCompensatorio['area'] .'</td>';
                            echo '<td>'. $regCompensatorio['apellido'].', '. $regCompensatorio['nombre'] .'</td>';
                            // Total
                            if ($aux_total < 0) {
                                echo '<td style="text-align:center;font-size:16px;"><strong><span class="badge bg-red">' . $aux_total . '</span></strong></td>';
                            }
                            else {
                                echo '<td style="text-align:center;font-size:16px;"><strong>'. $aux_total .'</strong></td>';
                            }
                            
                            // Voy poniendo los registros de datos
                            foreach ($arrPeriodos as $kper => $vper) {
                                $regCompensatorio = getRegistroCompensatorio($curr_id_persona, intval($vper['id']), $arrCompensatorios);
                                if ($regCompensatorio) {
                                    //----------------------------
                                    //En esta compensatorios o recuperos
                                    //----------------------------
                                    echo '<td style="text-align:center;background-color:rgb(153,255,153);">'.($regCompensatorio['compensatorios'] ? $regCompensatorio['compensatorios'] :"") .'</td>';
                                    echo '<td style="text-align:center;background-color:rgb(248,203,173);">'.($regCompensatorio['recuperos'] ? $regCompensatorio['recuperos'] :"").'</td>';
                                    //----------------------------                                    
                                } else {
                                    echo '<td style="text-align:center;background-color:rgb(153,255,153);"></td><td style="text-align:center;background-color:rgb(248,203,173);"></td>';
                                }
                            }
                                
                            echo '</tr>';       
                        }
                    ?>
                </tbody>

              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        <!-- /.col -->
        <?php
            include_once('./modals/cmp_importguardias.php');
            include_once('./modals/cmp_recupero.php');
        ?>        
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
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- export -->
<script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
<script src="../bower_components/datatables.net/js/dataTables.rowGroup.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
<script src="../bower_components/datatables.net/js/jszip.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
<script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
<script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>
<script src="./modals/cmp_importguardias.js"></script>
<script src="./modals/cmp_recupero.js"></script>
      
<script>
  $(function () {
    $('#guardias').DataTable({
      'language': { 'emptyTable': 'No hay datos' },
      'paging'      : false,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,
      'order': [[0, 'asc'], [1, 'asc']],
        'rowGroup': {
            'dataSrc': [ 0 ]
        },
        'columnDefs': [ {
            'targets': [ 0],
            'visible': false
        } ],
    });
  });
</script>
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
</body>
</html>