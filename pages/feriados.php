<!DOCTYPE html>
<?php
    include("../conexion.php");

    session_start();

    if (!isset($_SESSION['usuario'])){
        header('Location: ../index.html');
    }
    $page_title="Feriados";
    $user=$_SESSION['usuario'];

    //Get user query
    $persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
    $rowp = mysqli_fetch_assoc($persona);
    $id_rowp = $rowp['id_persona'];

    //Get Personas
    $personas = mysqli_query($con, "SELECT * FROM persona");
    $q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
    $rq_sec = mysqli_fetch_assoc($q_sec);				
            

    //Feriados
    //-------------------------------------------------------------------------------------------------

    $query = "SELECT id, fecha, descripcion FROM adm_dnl WHERE borrado = 0 ORDER BY fecha desc;";
    $sql = mysqli_query($con, $query);

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
      <h1>Días No Laborables</h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	 <section class="content">
      <div class="row">
        <div class="col-xs-6">
          <div class="box">
            <div class="box-header">
              <div class="col-sm-6" style="text-align:left">
                <h2 class="box-title">Listado de Días no laborables</h2>
              </div>
              <div class="col-sm-6" style="text-align:right;">
                <button type="button" id="modal-abm-dnl-btn-alta" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-calendar"></i> Agregar Dia no laborable</button>
              </div>
            </div>

            <!-- /.box-header -->

			<div class="box-body">
              <table id="guardias" class="display" width="100%">
                <thead>
                    <th>Descripcion</th>
                    <th>Fecha</th>
                    <th style="text-align: right;">Acciones</th>
                </thead>
                <tbody>
                    <?php
                        if(mysqli_num_rows($sql) == 0){
                            echo '<tr><td colspan="3">No hay datos.</td></tr>';
                        }else{
                            while($row = mysqli_fetch_assoc($sql)){
                                echo '<tr>';
                                echo '<td>'.$row['fecha'].'</td>';
                                echo '<td>'.$row['descripcion'].'</td>';
                                echo '<td align="right">';
                                echo '<a data-id="'.$row['id'].'" data-fecha="'.$row['fecha'].'" data-descripcion="'.$row['descripcion'].'" title="editar" class="modal-abm-dnl-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit"></i></a>';
                                if ($rq_sec['admin_compras'] == '1') {echo '<a data-id="'.$row['id'].'" title="eliminar" class="modal-abm-dnl-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash"  style="color: red;"></i></a>';}
                                echo '</td>';
                                echo '</tr>';
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
            include_once('./modals/adm_dnl.php');
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
<script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
<script src="../bower_components/datatables.net/js/jszip.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
<script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
<script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>
<script src="./modals/adm_dnl.js"></script>
      
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