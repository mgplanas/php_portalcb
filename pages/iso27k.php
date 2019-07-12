<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

$user=$_SESSION['usuario'];
$current_version = 0;
$last_version = 0;
$sqllastver = mysqli_query($con, "SELECT id FROM iso27k_version WHERE borrado=0 ORDER BY modificacion desc LIMIT 1");
$rowlv = mysqli_fetch_assoc($sqllastver); 
$last_version = $rowlv['id'];
//VERSION DE LA MATRIZ
if ($_GET["version"]) {
  $current_version = $_GET["version"];
} else {
  $current_version = $last_version;
}

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
	$cek = mysqli_query($con, "SELECT * FROM item_iso27k WHERE id_item_iso27k='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $titulo = $cekd['codigo'];
    
    if(mysqli_num_rows($cek) == 0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
	}else{
		//Elimino Activo
		
        $delete_iso = mysqli_query($con, "UPDATE item_iso27k SET `borrado`='1' WHERE id_item_iso27k='$nik'");
      
        $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('3', '6', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
		if(!$delete_iso){
			$_SESSION['formSubmitted'] = 9;
		}
	}
}

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
  <title>SI-ARSAT</title>
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

    <!-- Logo -->
    <a href="../site.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">SI</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SI</b>-ARSAT</span>
    </a>

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
        Gestión de ítems de cumplimiento ISO 27001
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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
				<div class="col-sm-4 align-middle" style="text-align:left;">
					<h2 class="box-title">Versión de la matriz de cumplimiento</h2>
          <select id="versionselector" name="responsable" class="form-control">
            <?php
              $versiones = mysqli_query($con, "SELECT * FROM iso27k_version WHERE borrado = 0 ORDER BY modificacion desc ");
              while($rowps = mysqli_fetch_array($versiones)){
                if($rowps['id']==$current_version) {
                  echo "<option value='". $rowps['id'] . "' selected='selected'>" .$rowps['numero'] . " [ " . $rowps['modificacion']. '] - ' . $rowps['descripcion']. "</option>";
                }
                else {
                  echo "<option value='". $rowps['id'] . "'>" .$rowps['numero'] . " [ " . $rowps['modificacion']. '] - ' . $rowps['descripcion']. "</option>";
                }
              }
            ?>
          </select>
              
				</div>
            </div>
        <!-- /.modal Activo-->
            <!-- /.box-header -->		
			<div class="box-body">
              <table id="iso27k" class="display" width="100%">
                <thead>
                <tr>
                  <th width="1"></th>
                  <th width="1"></th>
				          <th width="2">Codigo</th>
                  <th>Titulo</th>
                  <th>Descripcion</th>
                  <th>Responsable</th>
                  <th>Referentes</th>
				          <th>Madurez</th>
                  <th>Implementación</th>
                  <?php if ($current_version == $last_version) { echo '<th width="110px">Acciones</th>';}?>
                </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "SELECT i.*, m.nivel, p.nombre, p.apellido, 
                  (
                    SELECT GROUP_CONCAT(CONCAT(refp.apellido, ',', refp.nombre)  SEPARATOR '<br/>') as referentes
                    FROM iso27k_refs as r
                    INNER JOIN persona as refp ON r.id_persona = refp.id_persona
                    WHERE r.id_item_iso27k = i.id_item_iso27k
                    GROUP BY r.id_item_iso27k
                  ) as referentes,
                  stit.codigo as s_codigo, stit.titulo as s_titulo, stit.descripcion as s_descripcion,
                  tit.codigo as t_codigo, tit.titulo as t_titulo, tit.descripcion as t_descripcion
                  FROM item_iso27k as i 
                  LEFT JOIN madurez as m on i.madurez = m.id_madurez 
                  LEFT JOIN persona as p on i.responsable = p.id_persona
                  LEFT JOIN item_iso27k as stit on  i.parent = stit.id_item_iso27k
                  LEFT JOIN item_iso27k as tit on stit.parent = tit.id_item_iso27k
                  WHERE i.borrado='0'
                    AND i.nivel = 3 
                    AND i.version = " . $current_version;
                  
                  $sql = mysqli_query($con, $query.' ORDER BY id_item_iso27k ASC');

                  $no = 1;
                  while($row = mysqli_fetch_assoc($sql)){
                    
                    echo '<tr>';
                    echo '<td>'.$row['t_codigo']. ' - ' .$row['t_titulo']. '</td>'; 
                    echo '<td>'.$row['s_codigo']. ' - ' .$row['s_titulo']. ' <br/><small>' .$row['s_descripcion']. '</small></td>'; 
                    echo '<td align="center">'.$row['codigo'].'</td>';
                    echo '<td>'.$row['titulo'].'</td>';
                    echo '<td>'.$row['descripcion'].'</td>';
                    echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                    echo '<td>'.$row['referentes'].'</td>'; 
                    echo '<td>'.$row['nivel'].'</td>'; 
                    echo '<td>'.$row['implementacion'].'</td>'; 
                    if ($current_version == $last_version) {
                      echo '
                      <td align="center">
                        <a href="edit_iso27k.php?nik='.$row['id_item_iso27k'].'&version='. $current_version .'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="iso27k.php?aksi=delete&nik='.$row['id_item_iso27k'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['titulo'].'?\')" class="btn btn-danger btn-sm ';
                        if ($rq_sec['edicion']=='0'){
                          echo 'disabled';
                        }
                        echo '"><i class="glyphicon glyphicon-trash" ></i></a>
                      </td>';
                    }
                    echo '</tr>';
                    $no++;
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
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Portal de Gestión
    </div>
    <!-- Default to the left -->
    <strong>Seguridad Informática  - <a href="../site.php">ARSAT S.A.</a></strong>
  </footer>

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
      
<script>
  $(function () {
    $('#iso27k').DataTable({
      'language': { 'emptyTable': 'No hay datos' },
      'paging'      : true,
      'pageLength': 20,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,
      'order': [[0, 'asc'], [1, 'asc']],
        'rowGroup': {
            'dataSrc': [ 0, 1 ]
        },
        'columnDefs': [ {
            'targets': [ 0, 1 ],
            'visible': false
        } ],
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

    });

    $('#versionselector').on('change', function() {
      window.location.href = "iso27k.php?version=".concat(this.value);
    });    
  });
</script>

</body>
</html>
