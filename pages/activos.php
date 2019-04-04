<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

$user=$_SESSION['usuario'];

if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
	$cek = mysqli_query($con, "SELECT * FROM activo WHERE id_activo='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $titulo = $cekd['titulo'];
    
    if(mysqli_num_rows($cek) == 0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
	}else{
		//Elimino Activo
		
        $delete_activo = mysqli_query($con, "UPDATE activo SET `borrado`='1' WHERE id_activo='$nik'");
      
        $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('3', '1', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
		if(!$delete_activo){
			$_SESSION['formSubmitted'] = 9;
		}
	}
}

//Alert icons data on top bar


//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
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
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nuevo Activo guardado correctamente.</div>';
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
        Gestión de Activos
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
				<div class="col-sm-6" style="text-align:left">
					<h2 class="box-title">Listado de Activos</h2>
				</div>
 				<div class="col-sm-6" style="text-align:right;">
					<button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-archive"></i> Nuevo Activo</button>
				</div>
            </div>
		<div class="modal fade" id="modal-activo">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Activos >> Nuevo Activo</h2>
              </div>
              <div class="modal-body">
                <div class="box box-primary">
            <!-- /.box-header -->
			<?php
				
				if(isset($_POST['Add'])){
					$titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));
					$descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
					$responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
					$clasificacion = mysqli_real_escape_string($con,(strip_tags($_POST["clasificacion"],ENT_QUOTES)));
					$pdp = mysqli_real_escape_string($con,(strip_tags($_POST["pdp"],ENT_QUOTES)));
					$tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));
					$ubicacion = mysqli_real_escape_string($con,(strip_tags($_POST["ubicacion"],ENT_QUOTES)));
					$soporte = mysqli_real_escape_string($con,(strip_tags($_POST["soporte"],ENT_QUOTES)));
					$direccion = mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));
										
					$insert_activo = mysqli_query($con, "INSERT INTO activo(titulo, descripcion, responsable, clasificacion, pdp, tipo, ubicacion, soporte, creado, usuario, direccion) VALUES ('$titulo', '$descripcion', '$responsable', '$clasificacion', '$pdp', '$tipo', '$ubicacion', '$soporte', NOW(), '$user', '$direccion' )") or die(mysqli_error());	
					$lastInsert = mysqli_insert_id($con);
					$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('1', '1', '$lastInsert', now(), '$user', '$titulo')") or die(mysqli_error());
					unset($_POST);
					if($insert_activo){
						$_SESSION['formSubmitted'] = 2;
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
					}else{
						$_SESSION['formSubmitted'] = 9;
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
					}
				}
				?>
            <!-- form start -->
            <form method="post" role="form" action="">
              <div class="box-body">
                <div class="form-group">
                  <label for="titulo">Nombre</label>
                  <input type="text" class="form-control" name="titulo" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <textarea class="form-control" rows="3" name="descripcion" placeholder="Descripción ..." required></textarea>
                </div>
				<div class="form-group">
                  <label>Responsable</label>
                  <select name="responsable" class="form-control">
						<?php
								$personasn = mysqli_query($con, "SELECT * FROM persona");
								while($rowps = mysqli_fetch_array($personasn)){
									echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
									}
						?>
                  </select>
                </div>
				<div class="form-group">
                  <label>Tipo</label>
                  <select name="tipo" class="form-control">
                    <option value='1'>Datos/Información</option>
                    <option value='2'>Equipamiento</option>
                    <option value='3'>Instalaciones</option>
                    <option value='4'>Personal</option>
                    <option value='5'>Servicios</option>
                    <option value='6'>Software</option>
                    <option value='7'>Suministros</option>
                   </select>
                </div>
				<div class="form-group">
						<label>Ubicación</label>
						<select name="ubicacion" class="form-control">
							<option value='1'>Centro de Datos - Benavidez</option>
							<option value='2'>Centro de Datos - Tucumán</option>
							<option value='3'>Otro</option>
						</select>
				</div>
				<div class="form-group">
                  <label for="direccion">Dirección</label>
                  <input type="text" class="form-control" name="direccion" placeholder="Dirección IP / FQDN / Path">
                </div>
				<div class="panel box box-warning">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Completar solo para Activos de Información
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse">
                    <div class="box-body">
                      <div class="form-group">
						<label for="soporte">Sistema o Formato</label>
						<input type="text" class="form-control" name="soporte" placeholder="Sistema o Formato donde se aloja el activo">
					  </div>
					  <div class="form-group">
						<label>Clasificación Información</label>
						<select name="clasificacion" class="form-control">
							<option value='1'>Pública</option>
							<option value='2'>Interna</option>
							<option value='3'>Confidencial</option>
						</select>
					</div>
                <label for="Ley_PDP">Ley 25.326 PDP</label>
				<div class="checkbox">
                  <label>
					  <input name="pdp" type="checkbox" value="1"> Contiene datos Personales?
				 </label>
                </div>
                    </div>
                  </div>
                </div>

				 <div class="form-group">
					<div class="col-sm-3">
						<input type="submit" name="Add" class="btn  btn-raised btn-success" value="Guardar datos">
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			  </div>
            </form>
          </div>
			
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal Activo-->
            <!-- /.box-header -->
		<div id="ver-itemDialog" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						<h2 class="modal-title">Activos >> Ver Activo</h2>
					</div>
					<div class="box box-primary">
						<div class="modal-body">
							<div class="form-group">
								<label for="titulo">Nombre</label>
								<input type="text" class="form-control" name="titulo" id="titulo" value="" readonly>
							</div>
							<div class="form-group">
								<label for="descripcion">Descripción</label>
								<textarea class="form-control" rows="3" name="descripcion" id="descripcion" value="" readonly></textarea>
							</div>
							<div class="form-group">
								<label for="responsable">Responsable</label>
								<input type="text" class="form-control" name="responsable" id="responsable" value="" readonly>
							</div>
							<div class="form-group">
								<label for="tipo">Tipo</label>
								<input type="text" class="form-control" name="tipo" id="tipo" value="" readonly>
							</div>
							<div class="form-group">
								<label for="ubicacion">Ubicación</label>
								<input type="text" class="form-control" name="ubicacion" id="ubicacion" value="" readonly>
							</div>
							<div class="form-group">
								<label for="direccion">Dirección</label>
								<input type="text" class="form-control" name="direccion" id="direccion" value="" readonly>
							</div>
							<div class="form-group">
								<h3 class="modal-title">Solo aplicable para activos de información</h3>
								<label for="soporte">Sistema o Formato</label>
								<input type="text" class="form-control" name="soporte" id="soporte" value="" readonly>
								<label for="clasificacion">Clasificación</label>
								<input type="text" class="form-control" name="clasificacion" id="clasificacion" value="" readonly>
								<label for="pdp">Contiene datos personales</label>
								<input type="text" class="form-control" name="pdp" id="pdp" value="" readonly>
							</div>
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
					</div>	
 				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->	
		
			<div class="box-body">
              <table id="activos" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="1">Ver</th>
				  <th width="2">Nro</th>
                  <th>Nombre</th>
                  <th>Tipo</th>
                  <th>Responsable</th>
				  <th>Ubicación</th>
                  <th width="110px">Acciones</th>
                </tr>
                </thead>
                <tbody>
					<?php
					$query = "SELECT i.*, p.nombre, p.apellido FROM activo as i 
						 	  LEFT JOIN persona as p on i.responsable = p.id_persona
							  WHERE i.borrado='0' AND i.agrupado='0'";
					
					$sql = mysqli_query($con, $query.' ORDER BY id_activo ASC');

					if(mysqli_num_rows($sql) == 0){
						echo '<tr><td colspan="8">No hay datos.</td></tr>';
					}else{
						$no = 1;
						while($row = mysqli_fetch_assoc($sql)){
							
							echo '
							<tr>
							<td>
							<a data-id="'.$row['id_activo'].'" 
								data-titulo="'.$row['titulo'].'"
								data-tipo="'.$row['tipo'].'"
								data-descripcion="'.$row['descripcion'].'"
								data-responsable="'.$row['apellido'].' '.$row['nombre'].'"
								data-ubicacion="'.$row['ubicacion'].'"
								data-soporte="'.$row['soporte'].'"
								data-clasificacion="'.$row['clasificacion'].'"
								data-pdp="'.$row['pdp'].'"
								data-direccion="'.$row['direccion'].'"
							    title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
							</td>';
							echo '
							
							
							<td align="center">'.$no.'</td>';


							echo '
							
							</td>								
						
							<td>'.$row['titulo'].'</td>
							
							<td>';
							if($row['tipo'] == '1'){
								echo 'Datos/Información';
							}
							else if ($row['tipo'] == '2' ){
								echo 'Equipamiento';
							}
							else if ($row['tipo'] == '3' ){
								echo 'Instalaciones';
							}
                            else if ($row['tipo'] == '4' ){
								echo 'Personal';
							}
                            else if ($row['tipo'] == '5' ){
								echo 'Servicios';
							}
                            else if ($row['tipo'] == '6' ){
								echo 'Software';
							}
                            else if ($row['tipo'] == '7' ){
								echo 'Suministros';
							}
							echo '
							</td>
							<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
							
							
							if($row['ubicacion'] == '1'){
								echo '<td>CND - Benavidez</td>';
							}
							else if ($row['ubicacion'] == '2' ){
								echo '<td>CND - Tucumán</td>';
							}
							else if ($row['ubicacion'] == '3' ){
								echo '<td>Otro</td>';
							}
							 
							;
							echo '
							<td align="center">
							<a href="edit_activo.php?nik='.$row['id_activo'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
							<a href="activos.php?aksi=delete&nik='.$row['id_activo'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['titulo'].'?\')" class="btn btn-danger btn-sm ';
                            if ($rq_sec['edicion']=='0'){
                                    echo 'disabled';
                            }
                            echo '"><i class="glyphicon glyphicon-trash"></i></a>
							</td>
							</tr>
							';
							$no++;
						}
					}
					?>
                </tbody>
                <tfoot>
                <tr>
                  <th width="1">Ver</th>
				  <th width="2">Nro</th>
                  <th>Nombre</th>
                  <th>Tipo</th>
                  <th>Responsable</th>
				  <th>Ubicación</th>
                  <th width="110px">Acciones</th>
                </tr>
                </tfoot>
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
<script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
<script src="../bower_components/datatables.net/js/jszip.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
<script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
<script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>
      
<script>
  $(function () {
    $('#activos').DataTable({
      'paging'      : false,
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
        history.replaceState("", "", "activos.php");
    }
</script>
<script>
$(function(){
  $(".ver-itemDialog").click(function(){
    $('#itemId').val($(this).data('id'));
	$('#titulo').val($(this).data('titulo'));
	$('#descripcion').val($(this).data('descripcion'));
	$('#responsable').val($(this).data('responsable'));
	$('#soporte').val($(this).data('soporte'));
	$('#direccion').val($(this).data('direccion'));
	
	if($(this).data('pdp') == '1') {
		$('#pdp').val('SI')}
		else{ $('#pdp').val('NO')};
	
	if($(this).data('tipo') == '1') {
		$('#tipo').val('Activo de información')}
	else if($(this).data('tipo') == '2'){
		$('#tipo').val('Infraestructura')}
	else if($(this).data('tipo') == '3'){
		$('#tipo').val('Servicio')};
	
	if($(this).data('ubicacion') == '1') {
		$('#ubicacion').val('Centro de Datos - Benavidez')}
	else if($(this).data('ubicacion') == '2'){
		$('#ubicacion').val('Centro de Datos - Tucuman')}
	else if($(this).data('ubicacion') == '3'){
		$('#ubicacion').val('Otro')};
		
	if($(this).data('clasificacion') == '1') {
		$('#clasificacion').val('Pública')}
	else if($(this).data('clasificacion') == '2'){
		$('#clasificacion').val('Interna')}
	else if($(this).data('clasificacion') == '3'){
		$('#clasificacion').val('Confidencial')};
	
	$("#ver-itemDialog").modal("show");
	
  });
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>