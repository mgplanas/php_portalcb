<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="ISO27K1";
$user=$_SESSION['usuario'];
$current_version=mysqli_real_escape_string($con,(strip_tags($_GET["version"],ENT_QUOTES)));
$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT i.*, m.nivel, p.nombre, p.apellido,v.modificacion as v_mod, v.numero as v_numero, v.descripcion as v_desc FROM item_iso27k as i 
						      LEFT JOIN madurez as m on i.madurez = m.id_madurez 
						      LEFT JOIN persona as p on i.responsable = p.id_persona 
                  LEFT JOIN iso27k_version as v on i.version = v.id
							  WHERE i.borrado='0' AND i.id_item_iso27k='$nik'");
$sqlrefs = mysqli_query($con, "SELECT * FROM iso27k_refs WHERE id_item_iso27k = '$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: iso27k.php?version='$current_version'");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){

	$responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
  $referentes = (isset($_POST["referentes"]) ? $_POST["referentes"] : []);
	$madurez = mysqli_real_escape_string($con,(strip_tags($_POST["madurez"],ENT_QUOTES)));
	$implementacion = mysqli_real_escape_string($con,(strip_tags($_POST["implementacion"],ENT_QUOTES)));
	$evidencia = mysqli_real_escape_string($con,(strip_tags($_POST["evidencia"],ENT_QUOTES)));
  $codigo = mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));
  
  mysqli_autocommit($con, false);
  $resultado = true;
  $resultado = mysqli_query($con, "UPDATE item_iso27k SET responsable='$responsable', madurez='$madurez', implementacion='$implementacion',                                          evidencia='$evidencia', modificado=NOW(), usuario='$user' 
                     WHERE id_item_iso27k='$nik'");
  if ($resultado) {
    
    $resultado = mysqli_query($con, "DELETE FROM iso27k_refs WHERE id_item_iso27k ='$nik'");
    if ($resultado) {

      if (count($referentes,COUNT_NORMAL)>0) {
        $sqlInsRef = "INSERT INTO iso27k_refs (id_item_iso27k, id_persona,  borrado) VALUES ";
        $refCounter = 0;
        foreach ($referentes as $ref) {
          if ($refCounter > 0) $sqlInsRef .= ", ";
          $sqlInsRef .= "('$nik', '$ref', 0)";
          $refCounter++;
        }  
          
        $resultado = mysqli_query($con, $sqlInsRef);
      }
      if ($resultado) {
        $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                        VALUES ('2', '6','$nik', now(), '$user', '$codigo')");
      }  
    }
  }
                    
  if ($resultado) {
    mysqli_commit($con);
  } else {
    mysqli_rollback($con);
  }
  mysqli_autocommit($con, true);
	if($resultado){
		$_SESSION['formSubmitted'] = 1;
		header("Location: iso27k.php?version='$current_version'");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: iso27k.php?version='$current_version'");					
	}
}
//Alert icons data on top bar

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
        Gestión de ítems de cumplimiento ISO 27001
        <small>Editar >> <?php echo $row ['codigo']; ?></small>
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
                  <label for="evidencia">Versión de Matriz</label>
                  <input type="text" class="form-control" name="version" id="version" value="<?php echo $row['v_numero'] . ' [' . $row ['v_mod'] . ']'; ?>" readonly>                  
                </div> 
                <div class="form-group">
                  <label for="codigo">Código</label>
                  <input type="text" class="form-control" name="codigo" value="<?php echo $row ['codigo']; ?>"readonly>
                </div>
                <div class="form-group">
                  <label for="titulo">Titulo</label>
                  <?php echo "<textarea class=form-control readonly name=titulo>{$row['titulo']}</textarea>"; ?>
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <?php echo "<textarea class=form-control readonly name=descripcion>{$row['descripcion']}</textarea>"; ?>
                </div>
				        <div class="form-group">
                  <label>Responsable</label>
                  <select name="responsable" class="form-control">
                    <?php
                        $personasn = mysqli_query($con, "SELECT * FROM persona ORDER BY apellido, nombre");
                        while($rowps = mysqli_fetch_array($personasn)){
                          if($rowps['id_persona']==$row['responsable']) {
                            echo "<option value='". $rowps['id_persona'] . "' selected='selected'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";
                          }
                          else {
                            echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                          }
                        }
                        ?>
                  </select>
                </div>
				        <div class="form-group">
                  <label>Referentes &nbsp;<code>Realizar la selección/deselección con la tecla CTRL presionada. De lo contrario se perderán los referentes seleccionados.</code></label>
                  <select name="referentes[]" class="form-control custom-select" multiple>
                    <?php
                          mysqli_data_seek($personasn,0);
                          while($rowps = mysqli_fetch_array($personasn)){
                            // lo busco en los seleccionados
                            mysqli_data_seek($sqlrefs,0);
                            $existe = false;
                            while($rowrefs = mysqli_fetch_array($sqlrefs)){
                              if($rowps['id_persona']==$rowrefs['id_persona']){
                                $existe = true;
                                break;
                              }
                            }
                            if ($existe) {
                              echo "<option value='". $rowps['id_persona'] . "' selected='selected'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";
                            } else {
                              echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                            }
                          }
                      ?>
                    </select>
                </div>
				        <div class="form-group">
                  <label>Madurez</label>
                  <select name="madurez" class="form-control">
                        <?php
                            $q_madurez = mysqli_query($con, "SELECT * FROM madurez");
                            while($rowmd = mysqli_fetch_array($q_madurez)){
                                if($rowmd['id_madurez']==$row['madurez']) {
                                    echo "<option value='". $rowmd['id_madurez'] . "' selected='selected'>" .$rowmd['nivel'] . "</option>";
                                }
                                else {
                                    echo "<option value='". $rowmd['id_madurez'] . "'>" .$rowmd['nivel'] . "</option>";										
                                }
                            }
						            ?>
                   </select>
                </div>
                <div class="form-group">
                  <label for="implementacion">Implementación</label>
                  <?php echo "<textarea class=form-control name=implementacion>{$row['implementacion']}</textarea>"; ?>
               </div>
				      <div class="form-group">
                  <label for="evidencia">Evidencia</label>
                  <?php echo "<textarea class=form-control name=evidencia>{$row['evidencia']}</textarea>"; ?>
               </div>
              <div class="form-group">
                <div class="col-sm-2">
                  <input type="submit" name="save" class="btn  btn-raised btn-success" value="Guardar datos">
                </div>
                <div class="col-sm-2">
                  <a href=<?php echo '"iso27k.php?version=' .$current_version. '"'; ?> class="btn btn-warning btn-raised">Cancelar</a>
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

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>
