<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Clientes DC";
$user=$_SESSION['usuario'];

/// BORRADO DE CLIENTE (SOLO SI NO TIEN SERVICIOS ASOCIADOS)
if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
  //Elimino Control
  
  $delete_control = mysqli_query($con, "UPDATE cdc_cliente SET borrado='1' WHERE id='$nik'");
  
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

      .dataTables_scrollHeadInner {
  width: 100% !important;
  }
  .dataTables_scrollHeadInner table {
  width: 100% !important;
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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
				<div class="col-sm-6" style="text-align:left">
					<h2 class="box-title">Listado de Clientes</h2>
				</div>
         <div class="col-sm-6" style="text-align:right;">
          <?php if ($rq_sec['admin']=='1' OR $rq_sec['admin_cli_dc']=='1'){ ?>
            <button type="button" id="modal-abm-cliente-btn-alta" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-user"></i> Nuevo Cliente</button>
          <?php } ?>
				</div>
            </div>

            <!-- /.box-header -->
	
			<div class="box-body">
              <table id="clientes" class="display">
                <thead>
                <tr>
                    <th>Organismo</th>
                    <th>Cliente</th>
                    <th>Alias/Sigla</th>
                    <th>CUIT</th>
                    <th>Sector</th>
                    <th><i class="fa fa-briefcase" title="Convenio" style="font-size: 20px;"></i></th>
                    <th><i class="fa fa-home" title="Housing" style="font-size: 20px;"></i></th>
                    <th><i class="fa fa-server" title="Hosting" style="font-size: 20px;"></i></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
					<?php
					$query = "SELECT C.id_organismo, C.id, C.razon_social, O.razon_social as organismo, C.cuit, C.nombre_corto, C.sector, C.con_convenio,
                    (SELECT COUNT(1) FROM sdc_hosting as HO where HO.id_cliente = C.id AND HO.borrado=0) as hosting,
                    (SELECT COUNT(1) FROM sdc_housing as HU where HU.id_cliente = C.id AND HU.borrado=0) as housing
                  FROM cdc_cliente as C 
                  LEFT JOIN cdc_organismo as O ON C.id_organismo = O.id
                  WHERE C.borrado = 0";
					
					$sql = mysqli_query($con, $query);

					if(mysqli_num_rows($sql) == 0){
						echo '<tr><td colspan="8">No hay datos.</td></tr>';
					}else{
						$no = 1;
						while($row = mysqli_fetch_assoc($sql)){
							
							echo '<tr>';
							echo '<td>'. $row['organismo'].'</td>';
							echo '<td>'. $row['razon_social'].'</td>';
							echo '<td align="center">'. $row['nombre_corto'].'</td>';
							echo '<td align="center">'. $row['cuit'].'</td>';
              echo '<td align="center">'. $row['sector'].'</td>';
              echo '<td align="center">'. ($row['con_convenio'] ? '<i class="fa fa-check"></i>' : '') .'</td>';
              echo '<td align="center">';
              if ($row['housing'] > 0) {
                echo '<a data-tipo="'. ($row['cuit']=='30709670413' ? 'I' : 'C') .'" data-sector="'. $row['sector'] .'" data-organismo="'.$row['organismo'].'" data-cliente="'.$row['razon_social'].'" data-id="'.$row['id'].'" title="ver servicio de Housing" class="modal-abm-housing-view btn">' . $row['housing'] . '</a>';
              }
              echo '</td>';
              echo '<td align="center">';
              if ($row['hosting'] > 0) {
                echo '<a data-tipo="'. ($row['cuit']=='30709670413' ? 'I' : 'C') .'" data-sector="'. $row['sector'] .'" data-organismo="'.$row['organismo'].'" data-cliente="'.$row['razon_social'].'" data-id="'.$row['id'].'" title="ver servicios de Hosting" class="modal-abm-hosting-view btn">' . $row['hosting'] . '</a>';
              }
              echo '</td>';
							echo '<td align="center">';
              if ($rq_sec['admin']=='1' OR $rq_sec['admin_cli_dc']=='1'){
                echo '<a 
                  data-id="' . $row['id'] . '" 
                  data-nombre="' . $row['razon_social'] . '" 
                  data-sigla="' . $row['nombre_corto'] . '" 
                  data-cuit="' . $row['cuit'] . '" 
                  data-organismo="' . $row['id_organismo'] . '" 
                  data-sector="' . $row['sector'] . '" 
                  data-convenio="' . $row['con_convenio'] . '" 
                  title="Editar Cliente" class="modal-abm-cliente-btn-edit btn btn-sm"><i class="glyphicon glyphicon-edit"></i></a>';
                  if ($row['housing'] == 0 AND $row['hosting'] == 0) {
                    echo '<a href="cdc_cliente.php?aksi=delete&nik='.$row['id'].'" title="Borrar Cliente" onclick="return confirm(\'Esta seguro de borrar el cliente '. $row['razon_social'] .' ?\')" class="btn btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
                  }
              }
              echo '</td>
							</tr>
							';
						}
					}
					?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>

          <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- MODAL Housing -->
        <?php
            include_once('./modals/sdc_housing_view.php');
            include_once('./modals/sdc_hosting_view.php');
            include_once('./modals/cdc_abmcliente.php');
        ?>
        <!-- FIN Housing -->        
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
<script src="./modals/sdc_housing_view.js"></script>      
<script src="./modals/sdc_hosting_view.js"></script>      
<script src="./modals/cdc_abmcliente.js"></script>      
<script>
  $(function () {
    $('#clientes').DataTable({
      'paging'      : true,
			'pageLength': 20,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'order'       : [[ 0, 'desc' ], [1, 'asc']],
      'info'        : true,
      'autoWidth'   : false,
      'dom'         : 'frtipB',
      'buttons'     : [{
                  extend: 'pdfHtml5',
                  orientation: 'landscape',
                  pageSize: 'A4',
                         
                     },
                      {
            extend: 'excel',
            text: 'Excel',
            }]

    })
  })
</script>
<script>
    window.onload = function() {
        history.replaceState("", "", "cdc_cliente.php");
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