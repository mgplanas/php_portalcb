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

.list-group.list-group-root > .list-group > .list-group-item {
  padding-left: 30px;
}

.list-group.list-group-root > .list-group > .list-group > .list-group-item {
  padding-left: 45px;
}

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
}

table.dataTable tbody td {
    padding: 8px 10px;
    font-size: 0.9em !important;
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
        <form method="post" role="form" action="">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">     
                        <!-- /.box-header -->		
                        <div class="box-body">

                            <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-costos-id' value="<?= $id_planilla ?>">
                            
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
                        
                        </div>
                    
                    </div>
                <!-- /.box -->
                </div>
                
                <!-- /.col -->
                <?php
                    // include_once('./modals/cdc_abmcostos.php');
                    ?>        
            </div>
            <?php if ($id_planilla) {?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">     
                        <!-- /.box-header -->		
                        <div class="box-header">
                            <h3>Costeo de productos y servicios</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <!-- CATEGORIAS -->
                                <div class="col-md-3">
                                    <div id="modal-abm-costos-categorias" class="just-padding">
                                        <div id="modal-abm-costos-categorias-card" class="list-group list-group-root card">
                                        </div>
                                    </div>                                
                                </div>    
                                <!-- FIN CATEGORIAS -->
                                <!-- COSTEO -->
                                <div class="col-md-9">
                                    <table id="costeo" class="table table-striped" width="100%">
                                        <thead>
                                        <tr>
                                            <th>cat</th>
                                            <th>subcat</th>
                                            <th>Producto/Servicio</th>
                                            <th>Unidad</th>
                                            <th>Costo (USD)</th>
                                            <th>Cantidad</th>
                                            <th>Costo uv</th>
                                            <th>Costo recurrente</th>
                                            <!-- <th width="110px"></th> -->
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- FIN COSTEO -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <?php }?>
            <?php include_once('./modals/cdc_abmcostosdet.php'); ?>
        </form>
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
<script src="./helpers/cdc_abmcostos.js"></script>  
      
<script>
  $(function () {
    
    let strquery = 'SELECT cd.id, cd.id_costo, cd.id_costo_item, cd.costo_usd, cd.cantidad, cd.costo_unica_vez, cd.costo_recurrente,';
    strquery += 'ci.descripcion as descripcion, ci.unidad as unidad, ';
    strquery += 'cat.descripcion as categoria, cat.id as cat_id,';
    strquery += 'subcat.descripcion as subcategoria, subcat.id as subcat_id ';
    strquery += 'FROM cdc_costos_detalle as cd ';
    strquery += 'INNER JOIN cdc_costos_items as ci ON cd.id_costo_item = ci.id ';
    strquery += 'INNER JOIN cdc_costos_items as subcat ON ci.parent = subcat.id ';
    strquery += 'INNER JOIN cdc_costos_items as cat ON subcat.parent = cat.id ';
    strquery += 'WHERE cd.borrado = 0;';

    $('#costeo').DataTable({
        "scrollY": 400,
        "scrollX": true,
        "paging": true,
        "deferRender": true,
        "ajax": {
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: strquery },
            error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
        },
        "dataSrc": function(json) {
            console.log(json);
        },
        "columns": [
            { "data": "categoria" },
            { "data": "subcategoria" },
            { "data": "descripcion" },
            { "data": "unidad" },
            { "data": "costo_usd" },
            { "data": "cantidad" },
            { "data": "costo_unica_vez" },
            { "data": "costo_recurrente" }
        ],
        'order': [[0, 'asc'], [1, 'asc']],
        'rowGroup': {
            'dataSrc': [ 'categoria', 'subcategoria' ]
        },
        'columnDefs': [ {
            'targets': [ 0, 1 ],
            'visible': false
        } ],        
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
