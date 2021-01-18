<!DOCTYPE html>
<?php
include("../conexion.php");

$_TIPO_RANGOS_ASIGNADOS = 0;
$_TIPO_RANGOS_OCUPADOS = 1;

function setSemaphoreBadge($value, $type, $formated) {

    global $_TIPO_RANGOS_ASIGNADOS, $_TIPO_RANGOS_OCUPADOS;

    $fmt_value = ($formated ? number_format($value,2,",",".") : $value);
    $res = "";
    if ($type == $_TIPO_RANGOS_OCUPADOS) {
        if ($value <= 60) {$res = '<span class="badge bg-green">'.$fmt_value.'</span>';}
        elseif ($value <= 70) {$res = '<span class="badge bg-yellow">'.$fmt_value.'</span>';}
        elseif ($value <= 80) {$res = '<span class="badge bg-orange">'.$fmt_value.'</span>';}
        else {$res = '<span class="badge bg-red">'.$fmt_value.'</span>';}
    } elseif ($type == $_TIPO_RANGOS_ASIGNADOS) {
        if ($value <= 80) {$res = '<span class="badge bg-green">'.$fmt_value.'</span>';}
        elseif ($value <= 100) {$res = '<span class="badge bg-yellow">'.$fmt_value.'</span>';}
        elseif ($value <= 120) {$res = '<span class="badge bg-orange">'.$fmt_value.'</span>';}
        else {$res = '<span class="badge bg-red">'.$fmt_value.'</span>';}
    }

    return $res;
}

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="STORAGE"; 
$user=$_SESSION['usuario'];


/// BORRADO DE SERVICIO DE IAAS
if(isset($_GET['aksi'])  && $_GET['aksi'] == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
  //Elimino Control
  
  $delete_control = mysqli_query($con, "UPDATE sdc_storage SET borrado='1' WHERE id='$nik'");
  
  //$delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
  //                  VALUES ('3', '5', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
  if(!$delete_control){
    $_SESSION['formSubmitted'] = 9;
  }
}
/// SOLICITAR BAJA DE STORAGE
if(isset($_GET['aksi']) && $_GET['aksi'] == 'solbaja'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
  //Elimino Control
  
  $delete_control = mysqli_query($con, "UPDATE sdc_storage SET estado='2' WHERE id='$nik'");
  
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
        font-size: 12px !important;
        vertical-align: bottom;
    }
    table {
        border-collapse: collapse !important;
    }
    table td { font-size: 11px; }
    .bajasolicitada {
        background-color: rgba(221,75,57,0.4) !important;
    }
    .solicitud_pendiente {
        font-size: 16px !important;
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
          <?php if ($rq_sec['admin']=='1' OR $rq_sec['storage_admin']=='1')
          { 
            //Solicitudes de Baja pendientes
            $solicitudes = mysqli_query($con, "SELECT COUNT(*) as cuenta FROM sdc_storage WHERE estado = 2  AND borrado = 0;");
            $row_sol = mysqli_fetch_assoc($solicitudes);
            $cuenta = $row_sol['cuenta'];
            if ($cuenta > 0) {
                echo '<span class="badge bg-red solicitud_pendiente"> Solicitudes de baja pendientes: '.$cuenta.'</span>';
            }
              ?>
          <button title="Métricas" type="button" id="modal-abm-storage-btn-stat" class="btn-sm btn-primary" data-role="ADMIN" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-pie-chart"></i></button>
          <button type="button" id="modal-abm-storage-btn-alta" class="btn-sm btn-primary" data-role="ADMIN" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-database"></i> Nuevo Equipo de Storage</button>
          <?php } ?>
				</div>
            </div>

            <!-- /.box-header -->
	
			<div class="box-body">
              <table id="tbstorage" class="display" width="100%">
                <thead>
                <tr>
                  <th>estado</th>
                  <th width="10%">Storage</th>
                  <th width="10%" align="center">Categoría</th>
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
                  <th class="text-center" width="1"><i class="fa fa-flash"></i></th>
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
							echo '<td>'. $row['estado'].'</td>';
							echo '<td>'. $row['nombre'].'</td>';
							echo '<td align="center">'. $row['cat_nombre'].'</td>';
							echo '<td class="text-right">'. number_format($row['capacidad_fisica_tb'],2,",",".").'</td>';
							echo '<td class="text-right">'. number_format($row['per_asignacion_recomendado'],2,",",".").'</td>';
							echo '<td class="text-right">'. number_format($cap_asignable_tb,2,",",".") .'</td>';
							echo '<td class="text-right">'. number_format($row['asignado_tb'],2,",",".").'</td>';
							echo '<td class="text-right">'. setSemaphoreBadge($per_asignado_actual, $_TIPO_RANGOS_ASIGNADOS, true) .'</td>';
							echo '<td class="text-right">'. number_format($asignacion_disponible,2,",",".") .'</td>';
                            echo '<td class="text-right">'. setSemaphoreBadge($row['per_fisico_ocupado'], $_TIPO_RANGOS_OCUPADOS, true). '</td>';
							echo '<td class="text-right">'. number_format($fisico_utilizado_tb,2,",",".") .'</td>';
							echo '<td class="text-right">'. setSemaphoreBadge($row['per_estimado_asignacion_max'], $_TIPO_RANGOS_ASIGNADOS, true).'</td>';
							echo '<td class="text-right">'. number_format($capacidad_asig_max,2,",",".") .'</td>';
							echo '<td class="text-right">'. number_format($asignacion_disponible_est,2,",",".") .'</td>';

                            echo '<td align="center">';
                            if ($rq_sec['storage_admin']=='1'){ 
                                echo '<a 
                                data-id="' . $row['id'] . '" 
                                data-nombre="' . $row['nombre'] . '" 
                                data-categoria="' . $row['categoria'] . '" 
                                data-capacidad-fisica="' . $row['capacidad_fisica_tb'] . '" 
                                data-asignacion-recomendada="' . $row['per_asignacion_recomendado'] . '" 
                                data-asignacion-max="' . $row['per_estimado_asignacion_max'] . '" 
                                data-fisico-ocupado="' . $row['per_fisico_ocupado'] . '" 
                                data-asignado="' . $row['asignado_tb'] . '" 
                                data-role="ADMIN" 
                                title="Editar Equipo de Storage" class="modal-abm-storage-btn-edit btn btn-sm"><i class="fa fa-sliders"></i></a>
                                <a href="sdc_storage.php?aksi=delete&nik='.$row['id'].'" title="Borrar Equipo" onclick="return confirm(\'Esta seguro de borrar el equipo de storage?\')" class="btn btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
                            }
                            // OPERADORES
                            if ($row['estado'] == 1 AND (  $rq_sec['storage_op']=='1')){ 
                                echo '<a 
                                data-id="' . $row['id'] . '" 
                                data-nombre="' . $row['nombre'] . '" 
                                data-categoria="' . $row['categoria'] . '" 
                                data-capacidad-fisica="' . $row['capacidad_fisica_tb'] . '" 
                                data-asignacion-recomendada="' . $row['per_asignacion_recomendado'] . '" 
                                data-asignacion-max="' . $row['per_estimado_asignacion_max'] . '" 
                                data-fisico-ocupado="' . $row['per_fisico_ocupado'] . '" 
                                data-asignado="' . $row['asignado_tb'] . '" 
                                data-role="OP" 

                                title="Editar Asignación" class="modal-abm-storage-btn-edit btn btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="sdc_storage.php?aksi=solbaja&nik='.$row['id'].'" title="Dar de baja Equipo" onclick="return confirm(\'Esta seguro de dar de baja el equipo de storage?\')" class="btn btn-sm"><i class="fa fa-arrow-circle-down"></i></a>';
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
            include_once('./modals/sdc_storage_stat.php');
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
<!-- <script src="./modals/sdc_storage_vms_view.js"></script> -->
      
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
<script>
  $(function () {
    $('#tbstorage').DataTable({
        'paging'      : false,
        'scrollX'     : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : false,
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
            }],
        'createdRow': function( row, data, dataIndex){
            // Si el esado es baja (solicitada por los operadores) => la pinto
            if( data[0] ==  2){
                $(row).addClass('bajasolicitada');
            }
        },
        'columnDefs': [{
                'targets': [ 0 ],
                'visible': false
            },
            {
                'targets': [ 3,4,5,6,7,8,9,10,11,12,13,14 ],
                'orderable': false
            }
        ]     
    });  
    $('#tbstat').DataTable({
        'paging'      : false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : true,
        'dom'         : 'B',
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