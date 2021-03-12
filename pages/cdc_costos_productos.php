<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Categorias";
$user=$_SESSION['usuario'];

if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
    $delete = mysqli_query($con, "UPDATE cdc_costos SET borrado=1 WHERE id='$nik'");
    if(!$delete){
        $_SESSION['formSubmitted'] = 9;
    }
}
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
    /* Seleccion de row en datatable */
    .rowselected {
        background-color: #acbad4 !important;
    }

    table#tbCategorias.dataTable tbody tr:hover {
        background-color: #acbad4;
        cursor: pointer;
    }

    table#tbCategorias.dataTable tbody tr:hover > .sorting_1 {
        background-color: #acbad4;
        cursor: pointer;
    }    
    table#tbsubCategorias.dataTable tbody tr:hover {
        background-color: #acbad4;
        cursor: pointer;
    }

    table#tbsubCategorias.dataTable tbody tr:hover > .sorting_1 {
        background-color: #acbad4;
        cursor: pointer;
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

.sub-categorias {
    background: #f0f0f0;
}
.sub-categorias:hover, .sub-categorias:focus {
    color: #555 !important;
    text-decoration: none !important;
    background-color: #e5e5e5 !important;
}

.producto-servicio {
    font-size: .8em;
}
.categorias {
    background: #e0e0e0;
    font-weight: bold;
}
.categorias:hover, .categorias:focus {
    color: #555 !important;
    text-decoration: none !important;
    background-color: #d5d5d5 !important;
}
.just-padding {
  padding: 15px;
}

.list-group.list-group-root {
  padding: 0;
  overflow: hidden;
}

.list-group.list-group-root .list-group {
  margin-bottom: 0;
}

.list-group.list-group-root .list-group-item {
  border-radius: 0;
  border-width: 1px 0 0 0;
}

.list-group.list-group-root > .list-group-item:first-child {
  border-top-width: 0;
}
.list-group-item a {
    cursor: pointer;
}
/* .list-group.list-group-root > .list-group > .list-group-item {
  padding-left: 30px;
}

.list-group.list-group-root > .list-group > .list-group > .list-group-item {
  padding-left: 45px;
} */

.list-group-item .glyphicon {
  margin-right: 5px;
}
tr.dtrg-group.dtrg-level-0 td {
    font-weight: bold;
    font-size: 1.2em !important;
}
tr.dtrg-group.dtrg-level-1 td, table.dataTable tr.dtrg-group.dtrg-level-2 td {
    background-color: #f0f0f0;
    padding-top: 0.25em;
    padding-bottom: 0.25em;
    padding-left: 2em;
    font-size: 1.1em !important;
    font-weight: bold !important;
}

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
	else if ($_SESSION['formSubmitted']=='9'){
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al ejecutar el vuelco a la base de datos.</div>';
		$_SESSION['formSubmitted'] = 0;
	}?>	
	<section class="content-header">
      <h1>
        Categorias de Productos y Servicios - CND
        <small>General</small>
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
                    <div class="row">
                        <!-- CATEGORIAS -->
                        <div class="col-md-3">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Categorias</h3>
                                    <a class="btn text-right" id='modal-abm-categoria-btn-alta'><i class="glyphicon glyphicon-plus-sign" title="Agregar Categoría"style="color:green; font-size: 20px;"></i></a>
                                </div>
                                <div class="box-body no-padding">
                                    <table class="table table-hover display" id="tbCategorias">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Nivel</th>
                                            <th>Nombre</th>
                                            <th style="width: 40px; text-align: center"><i class="glyphicon glyphicon-flash"></i></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Sub Categorias</h3>
                                    <a class="btn text-right" id="modal-abm-subcategoria-btn-alta"><i class="glyphicon glyphicon-plus-sign" title="Agregar Subcategoría"style="color:green; font-size: 20px;"></i></a>
                                </div>
                                <div class="box-body no-padding">
                                    <table class="table table-hover display" id="tbsubCategorias">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Nivel</th>
                                            <th>Nombre</th>
                                            <th style="width: 40px; text-align: center"><i class="glyphicon glyphicon-flash"></i></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>                                            
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Productos/Servicios</h3>
                                    <a class="btn text-right" id="modal-abm-productos-btn-alta"><i class="glyphicon glyphicon-plus-sign" title="Agregar Producto/Servicio"style="color:green; font-size: 20px;"></i></a>
                                </div>
                                <div class="box-body no-padding">
                                    <table class="table table-hover display" id="tbProductos">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Nivel</th>
                                            <th>Nombre</th>
                                            <th class="text-center">Unidad</th>
                                            <th class="text-right">Costo unidad</th>
                                            <th style="width: 40px; text-align: center"><i class="glyphicon glyphicon-flash"></i></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>                                            
                        </div>
                        <!-- FIN CATEGORIAS -->
                    </div>
                    <?php 
                        include_once('./modals/cdc_abmcostositem_cat.php'); 
                        include_once('./modals/cdc_abmcostositem_subcat.php'); 
                        include_once('./modals/cdc_abmcostositem_producto.php'); 
                    ?>
                </div>
            <!-- /.box-body -->
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
<script src="./modals/cdc_abmcostositem_categorias.js"></script>  

</body>
</html>
