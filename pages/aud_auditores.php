<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

if (!isset($_GET["id_ente"])){
	header('Location: ./aud_ente.php');
}
$id_ente = mysqli_real_escape_string($con,(strip_tags($_GET["id_ente"],ENT_QUOTES)));

$page_title="Auditores"; 
$user=$_SESSION['usuario'];

/// BORRADO DE Auditores
if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
    //Elimino ENTE
    $delete_control = mysqli_query($con, "UPDATE aud_auditores SET borrado='1' WHERE id='$nik'");
  
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
        
// Get ENTE
//Get user query
$ente = mysqli_query($con, "SELECT * FROM aud_entes WHERE id = '$id_ente' AND borrado = 0");
$rowente = mysqli_fetch_assoc($ente);
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
      <h1>Gestión de Auditores del Ente Auditor <?= $rowente['razon_social'] ?></h1>
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
					<h2 class="box-title">Listado de Auditores</h2>
				</div>
                <div class="col-sm-6" style="text-align:right;">
                    <button type="button" id="modal-abm-auditor-btn-alta" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo" data-idente="<?= $id_ente ?>"><i class="fa fa-user"></i> Nuevo Auditor</button>
				</div>
            </div>

            <!-- /.box-header -->
			<div class="box-body">
              <table id="auditores" class="table table-bordered table-hover">
                <thead>
                <tr>
				  <th>Apellido</th>
                  <th>Nombre</th>
                  <th>DNI</th>
                  <th width="110px">Acciones</th>
                </tr>
                </thead>
                <tbody>
					<?php
					$query = "SELECT O.id, O.apellido, O.nombre, O.dni FROM aud_auditores AS O 
                    WHERE O.borrado = 0 and O.id_ente = '$id_ente'
                    ORDER BY O.apellido;";
					
					$sql = mysqli_query($con, $query);

					if(mysqli_num_rows($sql) == 0){
						echo '<tr><td colspan="8">No hay datos.</td></tr>';
					}else{
						$no = 1;
						while($row = mysqli_fetch_assoc($sql)){
							
							echo '<tr>';
							echo '<td>'. $row['apellido'].'</td>';
							echo '<td>'. $row['nombre'].'</td>';
							echo '<td>'. $row['dni'].'</td>';
                            echo '<td align="center">';
                            if ($rq_sec['admin']=='1' OR $rq_sec['edicion']=='1'){
                                echo '<a 
                                    data-id="' . $row['id'] . '" 
                                    data-idente="' . $id_ente . '" 
                                    data-nombre="' . $row['nombre'] . '" 
                                    data-apellido="' . $row['apellido'] . '" 
                                    data-dni="' . $row['dni'] . '" 
                                    title="Editar Auditor" class="modal-abm-auditor-btn-edit btn btn-sm"><i class="glyphicon glyphicon-edit"></i></a>';
                                echo '<a href="aud_auditores.php?id_ente='. $id_ente .'&aksi=delete&nik='.$row['id'].'" title="Borrar Auditor" onclick="return confirm(\'Esta seguro de borrar el auditor '. $row['apellido'] .', ' . $row['nombre'] . ' ?\')" class="btn btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
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
            include_once('./modals/aud_abmauditores.php');
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
<script src="./modals/aud_abmauditores.js"></script>         
<script>
  $(function () {
    $('#auditores').DataTable({
      'paging'      : true,
		'pageLength': 20,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
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
        history.replaceState("", "", "aud_auditores.php?id_ente=<?= $id_ente ?>");
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