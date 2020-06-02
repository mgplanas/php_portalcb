<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}


$page_title="Costeo";
$id_planilla = 0;
if (isset($_GET["planilla"])){
    $id_planilla = mysqli_real_escape_string($con,(strip_tags($_GET["planilla"],ENT_QUOTES)));
}


$user=$_SESSION['usuario'];
//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
				
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
  <link rel="stylesheet" href="../bower_components/datatables.net/css/rowGroup.dataTables.min.css">
  <link rel="stylesheet" href="../css/bootstrap-select.min.css">
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
    <?php
	if ($_SESSION['formSubmitted']=='1'){
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos editados correctamente.</div>';
		$_SESSION['formSubmitted'] = 0;
	}
	else if ($_SESSION['formSubmitted']=='2'){
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nuevo Control guardado correctamente.</div>';
		$_SESSION['formSubmitted'] = 0;
	}	
	else if ($_SESSION['formSubmitted']=='3'){
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nueva persona guardada correctamente.</div>';
		$_SESSION['formSubmitted'] = 0;
	}
	else if ($_SESSION['formSubmitted']=='9'){
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al ejecutar el vuelco a la base de datos.</div>';
		$_SESSION['formSubmitted'] = 0;
	}?>	
	<section class="content-header">
      <h1>
        Planillas de Costos - CND
        <small><?=($id_planilla ? 'General' : 'Nueva') ?></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	 <section class="content">
      <div class="row">
        <div class="col-md-12">
            <div class="box">     
                <!-- /.box-header -->		
                <div class="box-body">

                    <form method="post" role="form" action="">
                    
                        <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-costos-id' >
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente">Cliente</label>
                                    <input type="text" class="form-control" name="cliente" placeholder="Razón Social" id='modal-abm-costos-cliente' required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="servicio">Servicio</label>
                                    <input type="text" class="form-control" name="servicio" placeholder="Hosting / Housing.." id='modal-abm-costos-servicio'>
                                </div>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <div class="input-group date" data-provide="modal-abm-costos-fecha">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" required="required" name="fecha" id="modal-abm-costos-fecha">
                                    </div>                        
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="meses">Meses de contrato</label>
                                    <input type="number" min="1" class="form-control" name="meses"  id='modal-abm-costos-meses' required>
                                </div>     
                            </div>                   
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="dc">Duracion</label>
                                    <input type="number" min="0" class="form-control" name="dc"  id='modal-abm-costos-dc'>
                                </div>                                 
                            </div>                   
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cm">CM (%)</label>
                                    <input type="number" min="0" class="form-control" name="cm"  id='modal-abm-costos-cm'>
                                </div>                                 
                            </div>                   
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inflacion">Inflación (%)</label>
                                    <input type="number" min="0" class="form-control" name="inflacion"  id='modal-abm-costos-inflacion'>
                                </div>                                 
                            </div>                                                 
                        </div>
                        <?php if (!$id_planilla) {?>
                            <div id="modal-abm-costos-crear-div" class="form-group float-md-right">
                                <div class="col-md-10"></div>
                                <div class="col-md-1">
                                    <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-costos-submit'>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        <?php } ?>
                    
                        <!-- <table id="planillas" class="display" width="100%">
                            <thead>
                            <tr>
                            <th rowspan="2">Cliente</th>
                            <th rowspan="2" style="text-align: center;">Fecha</th>
                            <th rowspan="2" style="text-align: center;">Meses</th>
                            <th rowspan="2" style="text-align: center;">Duración</th>
                            <th colspan="2" style="text-align: center;">Costos (USD)</th>
                            <th colspan="3" style="text-align: center;">Costos (ARS)</th>
                            <th colspan="2" style="text-align: center;">Parametro (%)</th>
                            <th rowspan="2" style="text-align: right;"></th>
                            </tr>
                            <tr>
                            <th style="text-align: right;">recurrente</th>
                            <th style="text-align: right;">única vez</th>
                            <th style="text-align: right;">recurrente</th>
                            <th style="text-align: right;">CM</th>
                            <th style="text-align: right;">inflación</th>
                            <th style="text-align: right;">CM</th>
                            <th style="text-align: right;">inflación</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = "SELECT 
                                id, 
                                id_cliente, 
                                cliente, 
                                fecha, 
                                servicio, 
                                meses_contrato, 
                                duracion, 
                                costo_unica_vez, 
                                costo_recurrente, 
                                cotizacion_usd, 
                                inflacion, 
                                cm 
                                FROM cdc_costos 
                                WHERE borrado = 0 ORDER BY fecha desc;";
                            
                            $sql = mysqli_query($con, $query);

                            $no = 1;
                            while($row = mysqli_fetch_assoc($sql)){
                                $ars_recurrente = $row['costo_recurrente'] * $row['cotizacion_usd'];
                                $ars_cm = $ars_recurrente * (1 + $row['cm'] / 100);
                                $ars_inflacion = $ars_cm * (1 + $row['inflacion'] / 100);
                                echo '<tr>';
                                echo '<td>'.$row['cliente']. '</td>'; 
                                echo '<td style="text-align: center;">'.date('d/m/Y' ,strtotime($row['fecha'])). '</td>'; 
                                echo '<td style="text-align: center;">'.$row['meses_contrato']. '</td>'; 
                                echo '<td style="text-align: center;">'.$row['duracion']. '</td>'; 
                                echo '<td style="text-align: right;">'.$row['costo_recurrente']. '</td>'; 
                                echo '<td style="text-align: right;">'.$row['costo_unica_vez']. '</td>'; 
                                echo '<td style="text-align: right;">'. round($ars_recurrente,2) . '</td>'; 
                                echo '<td style="text-align: right;">'. round($ars_cm,2) . '</td>'; 
                                echo '<td style="text-align: right;">'. round($ars_inflacion,2) . '</td>'; 
                                echo '<td style="text-align: right;">'.$row['cm']. '</td>'; 
                                echo '<td style="text-align: right;">'.$row['inflacion']. '</td>'; 
                                echo '<td align="right">';
                                echo '<a data-id="'.$row['id'].'" title="editar" class="modal-abm-costos-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit" ></i></a>';
                                if ($rq_sec['admin_cli_dc'] == '1') {echo '<a data-id="'.$row['id'].'" title="eliminar" class="modal-abm-costos-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash" style="color: red;"></i></a>';}
                                echo '</td></tr>';
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table> -->
                    </form>
                </div>
            
            </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        
        <!-- /.col -->
        <?php
            // include_once('./modals/cdc_abmcostos.php');
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
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
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
<script src="../js/bootstrap-select.min.js"></script>
<!-- <script src="./modals/cdc_abmcostos.js"></script>   -->
      
<script>
  $(function () {
    
    $('#planillas').DataTable({
      'language': { 'emptyTable': 'No hay datos' },
      'paging'      : true,
      'pageLength': 20,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,
      'dom'         : 'Bfrtip',
      'buttons'     : [{
                  extend: 'pdfHtml5',
                  orientation: 'landscape',
                  pageSize: 'A4',
                         
                     },
                      {
            extend: 'excel',
            text: 'Excel',
            }]

    });
  });
</script>

</body>
</html>
