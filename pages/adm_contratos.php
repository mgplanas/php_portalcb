<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Vto. Contratos"; 
$user=$_SESSION['usuario'];

/// BORRADO DE ENTES
if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
    //Elimino ENTE
    $delete_control = mysqli_query($con, "UPDATE adm_contratos_vto SET borrado='1' WHERE id='$nik'");
  
    if(!$delete_control){
        $_SESSION['formSubmitted'] = 9;
    }
}

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

//Get Access
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
    <link rel="stylesheet" href="../bower_components/datatables.net/css/rowGroup.dataTables.min.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

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
      <h1>Seguimiento de Vto. de Contratos</h1>
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
					<h2 class="box-title">Listado de Contratos</h2>
				</div>
                <div class="col-sm-6" style="text-align:right;">
                    <?php if ($rq_sec['admin']=='1' OR $rq_sec['admin_contrato']=='1'){ ?>
                        <button type="button" id="modal-abm-contrato-btn-alta" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-calendar-plus-o"></i> Nuevo Vto. Contrato</button>
                    <?php } ?>
				</div>
            </div>

            <!-- /.box-header -->
			<div class="box-body">
              <table id="vtos" class="table table-hover" width="100%">
                <thead>
                <tr>
				  <th>SubGerencia</th>
                  <th>Proveedor</th>
                  <th>Mantenimiento/Soporte</th>
                  <th>OC</th>
                  <th>Estado</th>
                  <th style="text-align: center;"><i class="fa fa-clock-o" title="Días de Atrazo/Restante"></i></th>
                  <th>Vencimiento</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $query = "SELECT c.id, c.id_proveedor, c.id_subgerencia, c.oc, c.tipo_mantenimiento, c.vencimiento,
                                    s.nombre as subgerencia, 
                                    p.razon_social as proveedor, 
                                    datediff(c.vencimiento, now()) as dias 
                                FROM adm_contratos_vto as c 
                                INNER JOIN subgerencia as s ON c.id_subgerencia = s.id_subgerencia 
                                INNER JOIN adm_com_proveedores as p ON c.id_proveedor = p.id 
                                WHERE c.borrado = 0;";
					
					$sql = mysqli_query($con, $query);

					if(mysqli_num_rows($sql) == 0){
						echo '<tr><td colspan="8">No hay datos.</td></tr>';
					}else{
						$no = 1;
						while($row = mysqli_fetch_assoc($sql)){
							
							echo '<tr>';
							echo '<td>'. $row['subgerencia'].'</td>';
							echo '<td>'. $row['proveedor'].'</td>';
							echo '<td>'. $row['tipo_mantenimiento'].'</td>';
                            echo '<td>'. $row['oc'].'</td>';
                            // estado
                            echo '<td>';
                            if ($row['dias'] < 0) {
                                echo '<span class="badge bg-red">Vencido</span>';
                            } else if ($row['dias'] < 150) {
                                echo '<span class="badge bg-yellow">Renovar</span>';
                            } else {
                                echo '<span class="badge bg-green">Vigente</span>';
                            }                             
                            echo '</td>';
                            // FIN ESTADO
                            // DIAS DE VENCIDO/FALTANTE
                            echo '<td>'; 
                            $abs = abs($row['dias']);
                            if ($row['dias'] < 0) {
                                echo '<span title="' . $abs . ' día(s) de vencido">' . $abs . '</span>';
                            } else {
                                echo '<span title="faltan ' . $abs . ' día(s)">' . $abs . '</span>';
                            }                            
                            echo'</td>';
                            // DIAS DE VENCIDO/FALTANTE
                            // VENCIMIENTO
                            $timestamp = strtotime(str_replace('/', '.', $row['vencimiento']));
                            $mysql_date = date('Y-m-d', $timestamp);                                                   
                            echo '<td data-sort="'. $mysql_date .'">'.date("d/m/Y", strtotime($row['vencimiento'])).'</td>'; 
                            
                            echo '<td>'; 
                            if ($rq_sec['admin']=='1' OR $rq_sec['admin_contrato']=='1'){
                                echo '<a data-id="' . $row["id"] . '" ';
                                echo 'data-subgerencia="' . $row["id_subgerencia"] . '" ';
                                echo 'data-proveedor="' . $row["id_proveedor"] . '" ';
                                echo 'data-vencimiento="' . $row["vencimiento"] . '" ';
                                echo 'data-oc="' . $row["oc"] . '" ';
                                echo 'data-tipo="' . $row["tipo_mantenimiento"] . '" ';
                                echo 'title="editar" class="modal-abm-contrato-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit"></i></a>';
                                echo '<a data-id="' . $row["id"] . '" title="eliminar" class="modal-abm-contrato-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash" style="color: red;"></i></a>';
                            }
                            echo '</td></tr>';
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
            include_once('./modals/adm_contratos.php');
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
<script src="../bower_components/moment/min/moment.min.js"></script>
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
<script src="./modals/adm_contratos.js"></script>         

<script>
    window.onload = function() {
        history.replaceState("", "", "adm_contratos.php");
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