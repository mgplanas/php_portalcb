<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Personas";
$user=$_SESSION['usuario'];

$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT * FROM persona WHERE borrado='0' AND id_persona='$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: admin.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){

    $legajo = mysqli_real_escape_string($con,(strip_tags($_POST["legajo"],ENT_QUOTES)));//Escanpando caracteres
    $nombre = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres
    $apellido = mysqli_real_escape_string($con,(strip_tags($_POST["apellido"],ENT_QUOTES)));//Escanpando caracteres 
    $cargo = mysqli_real_escape_string($con,(strip_tags($_POST["cargo"],ENT_QUOTES)));//Escanpando caracteres 
    $gerencia = mysqli_real_escape_string($con,(strip_tags($_POST["gerencia"],ENT_QUOTES)));//Escanpando caracteres 
    $email = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres 
    $grupo = mysqli_real_escape_string($con,(strip_tags($_POST["grupo"],ENT_QUOTES)));//Escanpando caracteres 
    $contacto = mysqli_real_escape_string($con,(strip_tags($_POST["contacto"],ENT_QUOTES)));//Escanpando caracteres 
    
	  $update_persona = mysqli_query($con, "UPDATE persona 
                                            SET legajo='$legajo', 
                                            nombre='$nombre', 
                                            apellido='$apellido', 
                                            cargo='$cargo', 
                                            gerencia='$gerencia', 
                                            email='$email' ,
                                            contacto='$contacto',
                                            grupo= '$grupo'
                                        WHERE id_persona='$nik'") or die(mysqli_error());	
    $nombre_completo = $apellido . ' ' . $nombre;
    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('2', '2','$nik', now(), '$user', '$nombre_completo')") or die(mysqli_error());
	if($update_persona){
		$_SESSION['formSubmitted'] = 1;
		header("Location: admin.php");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: admin.php");					
	}
}
//Alert icons data on top bar
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

//Count riesgos
$riesgos = "SELECT 1 as total FROM riesgo WHERE riesgo.responsable='$id_rowp' AND riesgo.borrado='0'";
$count_riesgos = mysqli_query($con, $riesgos );
$rowr = mysqli_num_rows($count_riesgos);

//Count activos
$query_count_activos = "SELECT 1 as total FROM activo WHERE activo.responsable='$id_rowp' AND activo.borrado='0'";
$count_activos = mysqli_query($con, $query_count_activos);
$rowa = mysqli_num_rows($count_activos);

//Count Controles
$query_controles = "SELECT 1 as total FROM controles WHERE controles.responsable='$id_rowp'";
$count_controles = mysqli_query($con, $query_controles); 
$rowc = mysqli_num_rows($count_controles);

//Count Proyectos
$query_proyectos = "SELECT 1 as total FROM proyecto WHERE proyecto.responsable='$id_rowp'";
$count_proyectos = mysqli_query($con, $query_proyectos); 
$rowcp = mysqli_num_rows($count_proyectos);

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);
	
?>
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
    <!-- Header Navbar -->
    <?php include_once('./site_header.php'); ?>

  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <?php include_once('./site_sidebar.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Gestión de Personas
        <small>Editar >> <?php echo $row['apellido']. ' ' . $row['nombre']; ?></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	<div class="box box-primary">
            <!-- /.box-header -->
			
            <!-- form start -->
            <form method="post" role="form" action="">
              <div class="box-body">
                <div class="form-group">
                    <label for="legajo">Legajo</label>
                    <input type="text" class="form-control" name="legajo" value="<?php echo $row['legajo']; ?>">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="<?php echo $row['nombre']; ?>">
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" class="form-control" name="apellido" value="<?php echo $row['apellido']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Dirección E-mail</label>
                    <input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="contacto">Contacto</label>
                    <input type="text" class="form-control" name="contacto" value="<?php echo $row['contacto']; ?>">
                </div>                
                <div class="form-group">
                    <label for="cargo">Cargo</label>
                    <input type="text" class="form-control" name="cargo" value="<?php echo $row['cargo']; ?>">
                </div>
                <div class="form-group">
                    <label>Gerencia</label>
                    <select name="gerencia" class="form-control" id="gerenciaselector">
                        <?php
                          $gerencias = mysqli_query($con, "SELECT * FROM gerencia ORDER BY nombre ASC");
                          while($rowps = mysqli_fetch_array($gerencias)){
                            if($rowps['id_gerencia']==$row['gerencia']) {
                              echo "<option value='". $rowps['id_gerencia'] . "' selected='selected'>" .$rowps['nombre'] . "</option>";
                            }
                            else {
                              echo "<option value='". $rowps['id_gerencia'] . "'>" .$rowps['nombre'] . "</option>";
                            }
                          }
                      ?>                    
                    </select>
                </div>

                <div class="form-group" id="grupodiv">
                    <label>Grupo</label>
                    <select name="grupo" class="form-control" id="gruposelector">
                        <?php
                          $grupos = mysqli_query($con, "SELECT * FROM grupo ORDER BY nombre ASC");
                          while($rowps = mysqli_fetch_array($grupos)){
                            if($rowps['id_grupo']==$row['grupo']) {
                              echo "<option value='". $rowps['id_grupo'] . "' selected='selected'>" .$rowps['nombre'] . "</option>";
                            }
                            else {
                              echo "<option value='". $rowps['id_grupo'] . "'>" .$rowps['nombre'] . "</option>";
                            }
                          }
                      ?>                    
                    </select>
                </div>

                <div class="form-group">
					<div class="col-sm-2">
						<input type="submit" name="save" class="btn  btn-raised btn-success" value="Guardar datos">
					</div>
					<div class="col-sm-2">
						<a href="admin.php" class="btn btn-warning btn-raised">Cancelar</a>
					</div>
				</div>                
			  </div>
            </form>
          </div>
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
<script>
    $(function() {
      function populateGroups(id_gerencia) {
          //Limpio los grupos
          $("#gruposelector").empty().append('<option selected="selected" value="0">Ninguno</option>');
          //Populo los grupos
          $.ajax({
              type: 'POST',
              url: './helpers/getAsyncDataFromDB.php',
              data: { query: 'SELECT * FROM grupo WHERE id_gerencia =' + id_gerencia + ' ORDER BY nombre ASC;' },
              dataType: 'json',
              success: function(json) {

                console.log(json)
                console.log("data" in json)
                if ("data" in json == true) {
                    // Use jQuery's each to iterate over the opts value
                    $.each(json.data, function(i, d) {
                        // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
                        $('#gruposelector').append('<option value="' + d.id_grupo + '">' + d.nombre + '</option>');
                    });
                }
              },
              error: function(xhr, status, error) {
                  alert(xhr.responseText, error);
              }
          });
      }

      //Seto el trigger si la gerencia cambia 
      $('#gerenciaselector').on('change', function() {
        populateGroups($("#gerenciaselector").val());
      });      

      // disparo el cambio en el load;
      populateGroups($("#gerenciaselector").val());
    });
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>