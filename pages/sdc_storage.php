<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="STORAGE"; 
$user=$_SESSION['usuario'];


/// BORRADO DE SERVICIO DE IAAS
if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
  //Elimino Control
  
  $delete_control = mysqli_query($con, "UPDATE sdc_iaas SET borrado='1' WHERE id='$nik'");
  
  //$delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
  //                  VALUES ('3', '5', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
  if(!$delete_control){
    $_SESSION['formSubmitted'] = 9;
  }
}


//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'  AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

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
  <link rel="stylesheet" href="../bower_components/datatables.net/css/jquery.dataTables.min.css">
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

    table thead th.noventa{
        transform: rotate(-90deg);
        transform-origin: center !important;
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
      <h1>Servicios de Storage</h1>
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
					<h2 class="box-title">Listado de Equipos de Storage</h2>
				</div>
         <div class="col-sm-6" style="text-align:right;">
          <?php if ($rq_sec['admin']=='1' OR $rq_sec['admin_cli_dc']=='1'){ ?>
          <button type="button" id="modal-abm-storage-btn-alta" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-database"></i> Nuevo Equipo de Storage</button>
          <?php } ?>
				</div>
            </div>

            <!-- /.box-header -->
	
			<div class="box-body">
              <table id="iaas" class="table table-hover">
                <thead>
                <tr>
                  <th>Storage</th>
                  <th class="noventa">[TB] Capacidad Física</th>
                  <th class="noventa">[%] Asig. Recomendada</th>
                  <th class="noventa">[TB] Capacidad Asignable</th>
                  <th class="noventa">[TB] Asignado</th>
                  <th class="noventa">[%] Asignado Actual</th>
                  <th class="noventa">[TB] Asignación disponible</th>
                  <th class="noventa">[%] Físco ocupado</th>
                  <th class="noventa">[TB] Físico Utilizado</th>
                  <th class="noventa">[%] Estimado Asig. Máxima</th>
                  <th class="noventa">Capacidad Asig. Máxima</th>
                  <th class="noventa">Asig. Disponible Estimada</th>
                  <th>Categoría</th>
                  <th width="1"><i class="fa fa-flash"></i></th>
                </tr>
                </thead>
                <tbody>
					<?php
					$query = "SELECT s.*, cat.nombre as cat_nombre, st.nombre as st_nombre 
                            FROM sdc_storage as s
                            INNER JOIN sto_categorias as cat ON s.categoria = cat.id
                            INNER JOIN sto_estados as st ON s.estado = st.id
                            WHERE s.borrado = 0;"; 
					
					$sql = mysqli_query($con, $query);

					if(mysqli_num_rows($sql) > 0){
						$no = 1;
						while($row = mysqli_fetch_assoc($sql)){
							$cap_asignable_tb = $row['capacidad_fisica_tb'] * ($row['per_asignacion_recomendado']/100);
                            $per_asignado_actual = $row['asignado_tb'] * 100 / $row['capacidad_fisica_tb'];
                            $asignacion_disponible = ($row['asignado_tb'] >= $cap_asignable_tb ? 0 : $cap_asignable_tb - $row['asignado_tb']);
                            $fisico_utilizado_tb = $row['capacidad_fisica_tb'] * $row['per_fisico_ocupado'] / 100;
                            $capacidad_asig_max = ($row['asignado_tb'] > ($row['capacidad_fisica_tb']*$row['per_estimado_asignacion_max']/100) ? $row['asignado_tb'] : ($row['capacidad_fisica_tb']*$row['per_estimado_asignacion_max']/100));
                            $asignacion_disponible_est = $capacidad_asig_max - $row['asignado_tb'];
							echo '<tr>';
							echo '<td>'. $row['nombre'].'</td>';
							echo '<td class="text-right">'. number_format($row['capacidad_fisica_tb'],2,",",".").'</td>';
							echo '<td class="text-right">'. number_format($row['per_asignacion_recomendado'],2,",",".").'</td>';
							echo '<td class="text-right">'. number_format($cap_asignable_tb,2,",",".") .'</td>';
							echo '<td class="text-right">'. number_format($row['asignado_tb'],2,",",".").'</td>';
							echo '<td class="text-right">'. number_format($per_asignado_actual,2,",",".") .'</td>';
							echo '<td class="text-right">'. number_format($asignacion_disponible,2,",",".") .'</td>';
							echo '<td class="text-right">'. number_format($row['per_fisico_ocupado'],2,",",".") .'</td>';
							echo '<td class="text-right">'. number_format($fisico_utilizado_tb,2,",",".") .'</td>';
							echo '<td class="text-right">'. $row['per_estimado_asignacion_max'].'</td>';
							echo '<td class="text-right">'. number_format($capacidad_asig_max,2,",",".") .'</td>';
							echo '<td class="text-right">'. number_format($asignacion_disponible_est,2,",",".") .'</td>';
							echo '<td>'. $row['cat_nombre'].'</td>';

                            echo '<td align="center">';
                            if ($rq_sec['admin']=='1' OR $rq_sec['admin_cli_dc']=='1'){ 
                                echo '<a 
                                data-id="' . $row['id'] . '" 
                                title="Editar Reserva" class="modal-abm-storage-btn-edit btn btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="sdc_iaas.php?aksi=delete&nik='.$row['id'].'" title="Borrar Reserva" onclick="return confirm(\'Esta seguro de borrar la reserva de VRA?\')" class="btn btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
                            }
                            echo '</td>
                            </tr>';
						}
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
            include_once('./modals/sdc_abmstorage.php');
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
<script src="./modals/sdc_abmstorage.js"></script>
<!-- <script src="./modals/sdc_iaas_vms_view.js"></script> -->
      
<script>

</script>
<script>
    window.onload = function() {
        history.replaceState("", "", "sdc_storage.php");
    }
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