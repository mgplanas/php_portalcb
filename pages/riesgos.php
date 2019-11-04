<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Riesgos";
$user=$_SESSION['usuario'];

if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
	$cek = mysqli_query($con, "SELECT * FROM riesgo WHERE id_riesgo='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $titulo = $cekd['amenaza'];
 
    //Elimino Riesgo
    $delete_riesgo = mysqli_query($con, "UPDATE riesgo SET `borrado`='1' WHERE id_riesgo='$nik'");
    $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                           VALUES ('3', '4', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
    $delete_relacion = mysqli_query($con, "UPDATE riesgo_activo SET `borrado`='1' WHERE id_riesgo='$nik'");

    if(!$delete_riesgo){
        $_SESSION['formSubmitted'] = 9;
        header('Location: riesgos.php');
    }else{
         $_SESSION['formSubmitted'] = 1;
         header('Location: riesgos.php');
    }
}

//Alert icons data on top bar
//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

if(isset($_GET['aksi']) == 'filter'){
  $p=$_GET["p"];     //probabilidad
  $i=$_GET["i"];      //impacto
  $t=$_GET["t"];      //0=inherente;1=residual
  
  if($t == 0){
  $query = "SELECT i.*, p.nombre, p.apellido, ref.nombre as ref_nombre, ref.apellido as ref_apellido, c.tipo, g.nombre as gerencia 
            FROM riesgo as i 
            LEFT JOIN categoria as c on i.categoria = c.id_categoria 
            LEFT JOIN persona as p on i.responsable = p.id_persona
            LEFT JOIN persona as ref on i.referente = ref.id_persona
            LEFT JOIN gerencia as g on p.gerencia = g.id_gerencia 
            WHERE i.borrado='0' AND probabilidad=$p AND i_result=$i ";
	}else if($t == 1){
		$query = "SELECT i.*, p.nombre, p.apellido, ref.nombre as ref_nombre, ref.apellido as ref_apellido,c.tipo, g.nombre as gerencia
              FROM riesgo as i 
		          LEFT JOIN categoria as c on i.categoria = c.id_categoria 
              LEFT JOIN persona as p on i.responsable = p.id_persona
              LEFT JOIN persona as ref on i.referente = ref.id_persona
              LEFT JOIN gerencia as g on p.gerencia = g.id_gerencia 
              WHERE i.borrado='0' AND p_resid=$p AND i_resid=$i ";
		}
	
}else{
    $query = "SELECT i.*, p.nombre, p.apellido, ref.nombre as ref_nombre, ref.apellido as ref_apellido,c.tipo, g.nombre as gerencia 
              FROM riesgo as i 
              LEFT JOIN categoria as c on i.categoria = c.id_categoria 
				      LEFT JOIN persona as p on i.responsable = p.id_persona
                      LEFT JOIN persona as ref on i.referente = ref.id_persona 
              LEFT JOIN gerencia as g on p.gerencia = g.id_gerencia
              WHERE i.borrado='0' ";
}

// AGREGO EL FILTRO DE GERENCIA DEL USUARIO=CIBERSEGURIDAD O LA GERENCIA DEL REFERENTE
if ( $per_id_gerencia != 1) {
  $query = $query . " AND p.gerencia = $per_id_gerencia ";
}

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");

$q_activos = mysqli_query($con, "SELECT * FROM activo WHERE activo.borrado='0'");				

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp' AND permisos.borrado='0'");
$rq_sec = mysqli_fetch_assoc($q_sec);

//Querys para charts
$query_count_rven = "SELECT vencimiento FROM riesgo WHERE borrado='0' AND estado='0'";
$count_rven = mysqli_query($con, $query_count_rven);

$day=date("d");
$month=date("m");
$year=date("Y");
$countrv = 0;
$countrp = 0;

while($rowrv = mysqli_fetch_array($count_rven)){
    $due = explode("/", $rowrv['vencimiento']);
    $due_d = $due[0];
    $due_m = $due[1];
    $due_y = $due[2];

    $dayofy = (($month * 30)+($day));
    $dayofdue = (($due_m * 30)+($due_d));

     if ($due_y <= $year){
        if ($dayofy > $dayofdue){
            $countrv++; }
        }else $countrp++;
}
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
    <!-- Select2 -->
    <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
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
            //Alerts -> 0=no modification, 1=Edicion, 2=Nuevo activo, 3=Nueva persona, 9=Error
              if ($_SESSION['formSubmitted']=='1'){
              echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos editados correctamente.</div>';
              $_SESSION['formSubmitted'] = 0;
            }
            else if ($_SESSION['formSubmitted']=='2'){
              echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nuevo Riesgo guardado correctamente.</div>';
              $_SESSION['formSubmitted'] = 0;
            }	
            else if ($_SESSION['formSubmitted']=='3'){
              echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nueva persona guardada correctamente.</div>';
              $_SESSION['formSubmitted'] = 0;
            }
            else if ($_SESSION['formSubmitted']=='9'){
              echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al ejecutar el vuelco a la base de datos.</div>';
              $_SESSION['formSubmitted'] = 0;
            }
            $categorias = mysqli_query($con, "SELECT * FROM categoria ORDER BY tipo ASC");
            ?>
            <section class="content-header">
                <h1>
                    Gestión de Riesgos
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
                                        <h2 class="box-title">Listado de Riesgos</h2>
                                    </div>

                                    <div class="col-sm-6" style="text-align:right;">
                                    <a href="cal_riesgos.php" class="btn btn-primary"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Calendario</a>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#modal-ciclo"><i class="glyphicon glyphicon-repeat"></i>
                                            Ciclo</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#modal-inherente"><i class="glyphicon glyphicon-th"></i>
                                            Inherente</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#modal-residual"><i class="glyphicon glyphicon-th"></i>
                                            Residual</button>
                                        <button type="button" class="btn-sm btn-primary" data-toggle="modal"
                                            data-target="#modal-riesgo"><i class="glyphicon glyphicon-flash"></i> Nuevo
                                            Riesgo</button>

                                    </div>
                                </div>
                                <!-- MODAL RIESGO -->
                                <div class="modal fade" id="modal-riesgo">
                                    <div class="modal-dialog" style="width:900px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Riesgos >> Nuevo Riesgo</h2>
                                                <?php
                                                  $next_id_q = mysqli_query($con, "SELECT id_riesgo FROM riesgo ORDER BY id_riesgo DESC");
                                                  $next_id_r = mysqli_fetch_array($next_id_q);
                                                  $next_id = ($next_id_r['id_riesgo']) + 1;
                  
                                                  if(isset($_POST['Add'])){
                                                    $amenaza = mysqli_real_escape_string($con,(strip_tags($_POST["amenaza"],ENT_QUOTES)));
                                                    $vulnerabilidad = mysqli_real_escape_string($con,(strip_tags($_POST["vulnerabilidad"],ENT_QUOTES)));
                                                    $responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
                                                    $categoria = mysqli_real_escape_string($con,(strip_tags($_POST["categoria"],ENT_QUOTES)));
                                                    $probabilidad = mysqli_real_escape_string($con,(strip_tags($_POST["probabilidad"],ENT_QUOTES)));//Escapando caracteres
                                                    $i_conf = mysqli_real_escape_string($con,(strip_tags($_POST["i_conf"],ENT_QUOTES)));//Escapando caracteres
                                                    $i_int = mysqli_real_escape_string($con,(strip_tags($_POST["i_int"],ENT_QUOTES)));//Escapando caracteres
                                                    $i_disp = mysqli_real_escape_string($con,(strip_tags($_POST["i_disp"],ENT_QUOTES)));//Escapando caracteres
                                                    $control = mysqli_real_escape_string($con,(strip_tags($_POST["control"],ENT_QUOTES)));//Escapando caracteres
                                                    $estrategia = mysqli_real_escape_string($con,(strip_tags($_POST["estrategia"],ENT_QUOTES)));//Escapando caracteres
                                                    $plan = mysqli_real_escape_string($con,(strip_tags($_POST["plan"],ENT_QUOTES)));//Escapando caracteres
                                                    $p_resid = mysqli_real_escape_string($con,(strip_tags($_POST["p_resid"],ENT_QUOTES)));//Escapando caracteres
                                                    $i_resid = mysqli_real_escape_string($con,(strip_tags($_POST["i_resid"],ENT_QUOTES)));//Escapando caracteres
                                                    $observacion = mysqli_real_escape_string($con,(strip_tags($_POST["observacion"],ENT_QUOTES)));//Escapando caracteres
                                                    $alta = mysqli_real_escape_string($con,(strip_tags($_POST["alta"],ENT_QUOTES)));//Escapando caracteres
                                                              $identificado = mysqli_real_escape_string($con,(strip_tags($_POST["identificado"],ENT_QUOTES)));//Escapando caracteres
                                                              $vencimiento = mysqli_real_escape_string($con,(strip_tags($_POST["vencimiento"],ENT_QUOTES)));//Escapando caracteres
                                                              $estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escapando caracteres
                                                              $incidente = mysqli_real_escape_string($con,(strip_tags($_POST["incidente"],ENT_QUOTES)));//Escapando caracteres
                                                              $avance = mysqli_real_escape_string($con,(strip_tags($_POST["avance"],ENT_QUOTES)));//Escapando caracteres
                                                              $referente = mysqli_real_escape_string($con,(strip_tags($_POST["referente"],ENT_QUOTES)));//Escapando caracteres
                                                              
                                                              $preventivo = '0';
                                                    $detectivo = '0';
                                                                        
                                                    if ($_POST["t_control"] == 1){
                                                      $preventivo = '1';
                                                    }else {
                                                      $detectivo = '1';}
                                                          
                                                    $insert_riesgo = mysqli_query($con, "INSERT INTO riesgo SET amenaza='$amenaza', vulnerabilidad='$vulnerabilidad', creado = NOW(),
                                                    responsable='$responsable', categoria='$categoria', probabilidad='$probabilidad', i_conf='$i_conf', i_int='$i_int',
                                                    i_disp='$i_disp', control='$control', estrategia='$estrategia', plan='$plan', p_resid='$p_resid', i_resid='$i_resid',
                                                    observacion='$observacion', c_prev='$preventivo', c_detec='$detectivo', usuario='$user', alta='$alta', identificado='$identificado', vencimiento='$vencimiento', estado='$estado', incidente='$incidente', avance='$avance', referente='$referente'") or die(mysqli_error());
                                                    
                                                    $lastInsert = mysqli_insert_id($con);
                                                    
                                                    //Insert activos/riesgo en tabla de relación
                                                    foreach ($_POST['activos'] as $selectedOption){
                                                      $insert_relacion = mysqli_query($con, "INSERT INTO riesgo_activo (id_riesgo, id_activo, creado) 
                                                                  VALUES ('$lastInsert', '$selectedOption',now())") or die(mysqli_error());
                                                    }
                                              
                                                    //auditoría
                                                    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                                                  VALUES ('1', '4', '$lastInsert', now(), '$user', '$amenaza')") or die(mysqli_error());
                                                    unset($_POST);
                                                    if($insert_riesgo){
                                                      $_SESSION['formSubmitted'] = 2;
                                                      echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                    }else{
                                                      $_SESSION['formSubmitted'] = 9;
                                                      echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                    }
                                                  }
                                                ?>
                                            </div>
                                            <div class="modal-body">
                                                <!-- /.box-header -->
                                                <form method="post" role="form" action="">
                                                    <!-- form start -->
                                                    <div class="row">
                                                        <div class="box box-primary">

                                                            <div class="box-body">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="label-custom label-custom-info">Identificador</label>
                                                                        <input type="text" name="id_riesgo"
                                                                            value="<?php echo $next_id; ?>" readonly
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Fecha de alta</label>
                                                                        <div class="input-group date"
                                                                            data-provide="datepicker1">
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type="text"
                                                                                class="form-control pull-right"
                                                                                name="alta" id="datepicker1"
                                                                                placeholder="dd/mm/yyyy">
                                                                        </div>
                                                                    </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="vulnerabilidad"> Vulnerabilidad</label>
                                                                    <textarea class="form-control" rows="2"
                                                                        name="vulnerabilidad"
                                                                        placeholder="Vulnerabilidad ..."
                                                                        required></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="amenaza"> Amenaza</label>
                                                                    <textarea class="form-control" rows="2"
                                                                        name="amenaza" placeholder="Amenaza ..."
                                                                        required></textarea>
                                                                </div>                                                                
                                                                <div class="form-group">
                                                                    <label>Activos afectados</label>
                                                                    <select class="form-control select2"
                                                                        name="activos[]" multiple="multiple"
                                                                        data-placeholder="Activos" style="width: 100%;">
                                                                        <?php
                                                                        while($rowac = mysqli_fetch_array($q_activos)){
                                                                          echo "<option value=". $rowac['id_activo']. ">". $rowac['titulo']. "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Responsable</label>
                                                                    <select name="responsable" class="form-control" id="ddlresponsable">
                                                                        <?php
                                                                            $personasn = mysqli_query($con, "SELECT p.*, g.nombre as gerencia 
                                                                                                               FROM persona as p 
                                                                                                               LEFT JOIN gerencia as g ON p.gerencia = g.id_gerencia 
                                                                                                             WHERE p.borrado=0 ");
                                                                            while($rowps = mysqli_fetch_array($personasn)){
                                                                              echo "<option gerencia='" . $rowps['gerencia'] . "' value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                                                              }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="label-custom label-custom-info">Gerencia</label>
                                                                        <input id="txtgerenciaresponsable" type="text" name="gerencia_responsable" value="" readonly class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Identificado por</label>
                                                                    <select name="identificado" class="form-control" id="ddlidentificado">
                                                                        <?php
                                                                            $personasn = mysqli_query($con, "SELECT p.*, g.nombre as gerencia 
                                                                            FROM persona as p 
                                                                            LEFT JOIN gerencia as g ON p.gerencia = g.id_gerencia 
                                                                            WHERE p.borrado=0 ");
                                                                            while($rowps = mysqli_fetch_array($personasn)){
                                                                              echo "<option gerencia='" . $rowps['gerencia'] . "' value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                                                              }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="label-custom label-custom-info">Gerencia</label>
                                                                        <input id="txtgerenciaidentificado" type="text" name="gerencia_responsable" value="" readonly class="form-control">
                                                                    </div>
                                                                </div>          
                                                                <div class="col-md-6">                                                      
                                                                <div class="form-group">
                                                                    <label>Referente</label>
                                                                    <select name="referente" class="form-control" id="ddlreferente">
                                                                        <?php
                                                                            $personasn = mysqli_query($con, "SELECT p.*, g.nombre as gerencia 
                                                                            FROM persona as p 
                                                                            LEFT JOIN gerencia as g ON p.gerencia = g.id_gerencia 
                                                                            WHERE p.borrado=0 ");
                                                                            while($rowps = mysqli_fetch_array($personasn)){
                                                                              echo "<option gerencia='" . $rowps['gerencia'] . "' value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                                                              }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="label-custom label-custom-info">Gerencia</label>
                                                                        <input id="txtgerenciareferente" type="text" name="gerencia_responsable" value="" readonly class="form-control">
                                                                    </div>
                                                                </div>                                                                 
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="box box-default">

                                                                <div class="box-body">

                                                                    <div class="form-group">
                                                                        <label
                                                                            class="label-custom label-custom-info">Categoria
                                                                            de Riesgo</label>
                                                                        <select name="categoria" class="form-control">
                                                                            <?php
                                                                            while($rowc = mysqli_fetch_array($categorias)){
                                                                              echo "<option value=". $rowc['id_categoria'] . ">" .$rowc['tipo'] . "</option>";			
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="label-custom label-custom-info">Probabilidad
                                                                            de ocurrencia</label>
                                                                        <select name="probabilidad"
                                                                            class="form-control">
                                                                            <option value='1'
                                                                                <?php if($row['probabilidad'] == '1'){ echo 'selected'; } ?>>
                                                                                1 - Improbable</option>
                                                                            <option value='2'
                                                                                <?php if($row['probabilidad'] == '2'){ echo 'selected'; } ?>>
                                                                                2 - Moderada</option>
                                                                            <option value='3'
                                                                                <?php if($row['probabilidad'] == '3'){ echo 'selected'; } ?>>
                                                                                3 - Muy probable</option>
                                                                            <option value='4'
                                                                                <?php if($row['probabilidad'] == '4'){ echo 'selected'; } ?>>
                                                                                4 - Casi cierta</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-sm-4">
                                                                            <label
                                                                                class="label-custom label-custom-info"
                                                                                style="text-align:center;">Impacto en
                                                                                confidencialidad</label>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label
                                                                                class="label-custom label-custom-info"
                                                                                style="text-align:center;">Impacto en
                                                                                integridad</label>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label
                                                                                class="label-custom label-custom-info"
                                                                                style="text-align:center;">Impacto en
                                                                                disponibilidad</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-sm-4">
                                                                            <select name="i_conf" class="form-control">
                                                                                <option value='1'
                                                                                    <?php if($row['i_conf'] == '1'){ echo 'selected'; } ?>>
                                                                                    1 - Menor</option>
                                                                                <option value='2'
                                                                                    <?php if($row['i_conf'] == '2'){ echo 'selected'; } ?>>
                                                                                    2 - Moderado</option>
                                                                                <option value='3'
                                                                                    <?php if($row['i_conf'] == '3'){ echo 'selected'; } ?>>
                                                                                    3 - Mayor</option>
                                                                                <option value='4'
                                                                                    <?php if($row['i_conf'] == '4'){ echo 'selected'; } ?>>
                                                                                    4 - Catastrofico</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <select name="i_int" class="form-control">
                                                                                <option value='1'
                                                                                    <?php if($row['i_int'] == '1'){ echo 'selected'; } ?>>
                                                                                    1 - Menor</option>
                                                                                <option value='2'
                                                                                    <?php if($row['i_int'] == '2'){ echo 'selected'; } ?>>
                                                                                    2 - Moderado</option>
                                                                                <option value='3'
                                                                                    <?php if($row['i_int'] == '3'){ echo 'selected'; } ?>>
                                                                                    3 - Mayor</option>
                                                                                <option value='4'
                                                                                    <?php if($row['i_int'] == '4'){ echo 'selected'; } ?>>
                                                                                    4 - Catastrofico</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <select name="i_disp" class="form-control">
                                                                                <option value='1'
                                                                                    <?php if($row['i_disp'] == '1'){ echo 'selected'; } ?>>
                                                                                    1 - Menor</option>
                                                                                <option value='2'
                                                                                    <?php if($row['i_disp'] == '2'){ echo 'selected'; } ?>>
                                                                                    2 - Moderado</option>
                                                                                <option value='3'
                                                                                    <?php if($row['i_disp'] == '3'){ echo 'selected'; } ?>>
                                                                                    3 - Mayor</option>
                                                                                <option value='4'
                                                                                    <?php if($row['i_disp'] == '4'){ echo 'selected'; } ?>>
                                                                                    4 - Catastrofico</option>
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <br clear="all" /><br />
                                                                    <div class="form-group">

                                                                        <label
                                                                            class="label-custom label-custom-info">Control
                                                                            existente / propuesto</label>
                                                                        <input type="text" name="control"
                                                                            value="<?php echo $row ['control']; ?>"
                                                                            class="form-control"
                                                                            placeholder="Control existente ...">
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-lg-6"
                                                                            style="text-align:center;">
                                                                            <input type="radio" name="t_control"
                                                                                value="1"
                                                                                <?php if($row['c_prev'] == '1'){ echo 'checked'; } ?>>
                                                                            Preventivo
                                                                            <!-- /input-group -->
                                                                        </div>
                                                                        <div class="col-lg-6"
                                                                            style="text-align:center;">
                                                                            <input type="radio" name="t_control"
                                                                                value="2"
                                                                                <?php if($row['c_detec'] == '1'){ echo 'checked'; } ?>>
                                                                            Detectivo
                                                                            <!-- /input-group -->
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <br clear="all" /><br />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="box box-default">

                                                                <div class="box-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="label-custom label-custom-info">Estrategia
                                                                                    propuesta</label>
                                                                                <select name="estrategia"
                                                                                    class="form-control">
                                                                                    <option value='ACEPTAR'
                                                                                        <?php if($row['estrategia'] == 'ACEPTAR'){ echo 'selected'; } ?>>
                                                                                        ACEPTAR</option>
                                                                                    <option value='REDUCIR'
                                                                                        <?php if($row['estrategia'] == 'REDUCIR'){ echo 'selected'; } ?>>
                                                                                        REDUCIR</option>
                                                                                    <option value='TRANSFERIR'
                                                                                        <?php if($row['estrategia'] == 'TRANSFERIR'){ echo 'selected'; } ?>>
                                                                                        TRANSFERIR</option>
                                                                                    <option value='EVITAR'
                                                                                        <?php if($row['estrategia'] == 'EVITAR'){ echo 'selected'; } ?>>
                                                                                        EVITAR</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label>Fecha de resolución</label>
                                                                                <div class="input-group date"
                                                                                    data-provide="datepicker2">
                                                                                    <div class="input-group-addon">
                                                                                        <i class="fa fa-calendar"></i>
                                                                                    </div>
                                                                                    <input type="text"
                                                                                        class="form-control pull-right"
                                                                                        name="vencimiento"
                                                                                        id="datepicker2"
                                                                                        placeholder="dd/mm/yyyy"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="label-custom label-custom-info">Plan
                                                                            de tratamiento</label>
                                                                        <input type="text" name="plan"
                                                                            value="<?php echo $row ['plan']; ?>"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <label
                                                                                class="label-custom label-custom-info"
                                                                                style="text-align:center;">Probabilidad
                                                                                residual</label>
                                                                            <select name="p_resid" class="form-control">
                                                                                <option value='1'
                                                                                    <?php if($row['p_resid'] == '1'){ echo 'selected'; } ?>>
                                                                                    1 - Improbable</option>
                                                                                <option value='2'
                                                                                    <?php if($row['p_resid'] == '2'){ echo 'selected'; } ?>>
                                                                                    2 - Moderada</option>
                                                                                <option value='3'
                                                                                    <?php if($row['p_resid'] == '3'){ echo 'selected'; } ?>>
                                                                                    3 - Muy probable</option>
                                                                                <option value='4'
                                                                                    <?php if($row['p_resid'] == '4'){ echo 'selected'; } ?>>
                                                                                    4 - Casi cierta</option>
                                                                            </select>
                                                                            <!-- /input-group -->
                                                                        </div>
                                                                        <!-- /.col-lg-6 -->
                                                                        <div class="col-lg-6">
                                                                            <div class="input-group">
                                                                                <label
                                                                                    class="label-custom label-custom-info"
                                                                                    style="text-align:center;">Impacto
                                                                                    residual</label>
                                                                                <select name="i_resid"
                                                                                    class="form-control">
                                                                                    <option value='1'
                                                                                        <?php if($row['i_resid'] == '1'){ echo 'selected'; } ?>>
                                                                                        1 - Menor</option>
                                                                                    <option value='2'
                                                                                        <?php if($row['i_resid'] == '2'){ echo 'selected'; } ?>>
                                                                                        2 - Moderado</option>
                                                                                    <option value='3'
                                                                                        <?php if($row['i_resid'] == '3'){ echo 'selected'; } ?>>
                                                                                        3 - Mayor</option>
                                                                                    <option value='4'
                                                                                        <?php if($row['i_resid'] == '4'){ echo 'selected'; } ?>>
                                                                                        4 - Catastrofico</option>
                                                                                </select>
                                                                            </div>
                                                                            <!-- /input-group -->
                                                                        </div>
                                                                        <!-- /.col-lg-6 -->
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <label
                                                                                class="label-custom label-custom-info"
                                                                                style="text-align:center;">Estado</label>
                                                                            <select name="estado" class="form-control">
                                                                                <option value='0'
                                                                                    <?php if($row['estado'] == '0'){ echo 'selected'; } ?>>
                                                                                    Abierto</option>
                                                                                <option value='1'
                                                                                    <?php if($row['estado'] == '1'){ echo 'selected'; } ?>>
                                                                                    Cerrado</option>

                                                                            </select>
                                                                            <!-- /input-group -->
                                                                        </div>
                                                                        <!-- /.col-lg-6 -->
                                                                        <div class="col-lg-6">
                                                                            <div class="input-group">
                                                                                <label
                                                                                    class="label-custom label-custom-info"
                                                                                    style="text-align:center;">Incidente</label>
                                                                                <select name="incidente"
                                                                                    class="form-control">
                                                                                    <option value='0'
                                                                                        <?php if($row['incidente'] == '0'){ echo 'selected'; } ?>>
                                                                                        No</option>
                                                                                    <option value='1'
                                                                                        <?php if($row['incidente'] == '1'){ echo 'selected'; } ?>>
                                                                                        Si</option>
                                                                                </select>
                                                                            </div>
                                                                            <!-- /input-group -->
                                                                        </div>
                                                                        <!-- /.col-lg-6 -->
                                                                    </div>
                                                                    <br>
                                                                    <div class="form-group">
                                                                        <label class="label-custom label-custom-info">%
                                                                            Avance</label>
                                                                        <input type="text" name="avance"
                                                                            value="<?php echo $row ['avance']; ?>"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            class="label-custom label-custom-info">Observaciones</label>
                                                        <div class="col-sm-12">
                                                            <textarea class="form-control" rows="2" name="observacion"
                                                                placeholder="Observaciones ..."></textarea><br>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="col-sm-6">
                                                            <input type="submit" name="Add"
                                                                class="btn btn-raised btn-success"
                                                                value="Guardar datos">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <button type="button" class="btn btn-default pull-left"
                                                                data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN MODAL RIESGO -->
                                <!-- MODAL PERSONA -->
                                <div class="modal fade" id="modal-persona">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Nueva Persona</h2>
                                                <?php
                                                $gerencias = mysqli_query($con, "SELECT * FROM gerencia ORDER BY nombre ASC");
                                                if(isset($_POST['Addp'])){
                                                  $legajo = mysqli_real_escape_string($con,(strip_tags($_POST["legajo"],ENT_QUOTES)));//Escanpando caracteres
                                                  $nombre = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres
                                                  $apellido = mysqli_real_escape_string($con,(strip_tags($_POST["apellido"],ENT_QUOTES)));//Escanpando caracteres 
                                                  $cargo = mysqli_real_escape_string($con,(strip_tags($_POST["cargo"],ENT_QUOTES)));//Escanpando caracteres 
                                                  $gerencia = mysqli_real_escape_string($con,(strip_tags($_POST["gerencia"],ENT_QUOTES)));//Escanpando caracteres 
                                                  $email = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres 
                                                  //Inserto Control
                                                  $insert_persona = mysqli_query($con, "INSERT INTO persona(legajo, nombre, apellido, cargo, gerencia, email) VALUES ('$legajo','$nombre','$apellido', '$cargo', '$gerencia', '$email')") or die(mysqli_error());	
                                                  $lastInsert = mysqli_insert_id($con);
                                                  $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                                VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
                                                  unset($_POST);
                                                  if($insert_persona){
                                                    $_SESSION['formSubmitted'] = 3;
                                                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                  }else{
                                                    $_SESSION['formSubmitted'] = 9;
                                                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                  }				
                                                }				
                                                ?>
                                            </div>
                                            <div class="modal-body">
                                                <!-- form start -->
                                                <form method="post" role="form" action="">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label for="legajo">Legajo</label>
                                                            <input type="text" class="form-control" name="legajo"
                                                                placeholder="Legajo">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre"
                                                                placeholder="Nombre">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="apellido">Apellido</label>
                                                            <input type="text" class="form-control" name="apellido"
                                                                placeholder="Apellido">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Dirección E-mail</label>
                                                            <input type="text" class="form-control" name="email"
                                                                placeholder="E-mail corporativo">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="cargo">Cargo</label>
                                                            <input type="text" class="form-control" name="cargo"
                                                                placeholder="Cargo">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Gerencia</label>
                                                            <select name="gerencia" class="form-control">
                                                                <?php
                                                                  while($rowg = mysqli_fetch_array($gerencias)){
                                                                      echo "<option value=". $rowg['id_gerencia'] . ">" .$rowg['nombre'] . "</option>";
                                                                        }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-3">
                                                                <input type="submit" name="Addp"
                                                                    class="btn  btn-raised btn-success"
                                                                    value="Guardar datos">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <button type="button" class="btn btn-default pull-left"
                                                                    data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>

                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN MODAL PERSONA -->
                                <!-- MODAL MATRIZ INHERENTE -->
                                <div class="modal fade" id="modal-inherente">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Cantidad según Matriz de riesgo inherente</h2>
                                                <?php
                                                  //querys de datos matriz inherente
                                                  //Riesgos Inherentes
                                                  $q14 = "SELECT count(*) as q14 FROM riesgo WHERE probabilidad=1 && i_result=4 && borrado=0 && estado=0";
                                                  $result14 = mysqli_query($con, $q14);
                                                  $row14 = mysqli_fetch_assoc($result14);
                                                  $q13 = "SELECT count(*) as q13 FROM riesgo WHERE probabilidad=1 && i_result=3 && borrado=0 && estado=0";
                                                  $result13 = mysqli_query($con, $q13);
                                                  $row13 = mysqli_fetch_assoc($result13);
                                                  $q12 = "SELECT count(*) as q12 FROM riesgo WHERE probabilidad=1 && i_result=2 && borrado=0 && estado=0";
                                                  $result12 = mysqli_query($con, $q12);
                                                  $row12 = mysqli_fetch_assoc($result12);
                                                  $q11 = "SELECT count(*) as q11 FROM riesgo WHERE probabilidad=1 && i_result=1 && borrado=0 && estado=0";
                                                  $result11 = mysqli_query($con, $q11);
                                                  $row11 = mysqli_fetch_assoc($result11);
                                                  $q24 = "SELECT count(*) as q24 FROM riesgo WHERE probabilidad=2 && i_result=4 && borrado=0 && estado=0";
                                                  $result24 = mysqli_query($con, $q24);
                                                  $row24 = mysqli_fetch_assoc($result24);
                                                  $q23 = "SELECT count(*) as q23 FROM riesgo WHERE probabilidad=2 && i_result=3 && borrado=0 && estado=0";
                                                  $result23 = mysqli_query($con, $q23);
                                                  $row23 = mysqli_fetch_assoc($result23);
                                                  $q22 = "SELECT count(*) as q22 FROM riesgo WHERE probabilidad=2 && i_result=2 && borrado=0 && estado=0";
                                                  $result22 = mysqli_query($con, $q22);
                                                  $row22 = mysqli_fetch_assoc($result22);
                                                  $q21 = "SELECT count(*) as q21 FROM riesgo WHERE probabilidad=2 && i_result=1 && borrado=0 && estado=0";
                                                  $result21 = mysqli_query($con, $q21);
                                                  $row21 = mysqli_fetch_assoc($result21);
                                                  $q34 = "SELECT count(*) as q34 FROM riesgo WHERE probabilidad=3 && i_result=4 && borrado=0 && estado=0";
                                                  $result34 = mysqli_query($con, $q34);
                                                  $row34 = mysqli_fetch_assoc($result34);
                                                  $q33 = "SELECT count(*) as q33 FROM riesgo WHERE probabilidad=3 && i_result=3 && borrado=0 && estado=0";
                                                  $result33 = mysqli_query($con, $q33);
                                                  $row33 = mysqli_fetch_assoc($result33);
                                                  $q32 = "SELECT count(*) as q32 FROM riesgo WHERE probabilidad=3 && i_result=2 && borrado=0 && estado=0";
                                                  $result32 = mysqli_query($con, $q32);
                                                  $row32 = mysqli_fetch_assoc($result32);
                                                  $q31 = "SELECT count(*) as q31 FROM riesgo WHERE probabilidad=3 && i_result=1 && borrado=0 && estado=0";
                                                  $result31 = mysqli_query($con, $q31);
                                                  $row31 = mysqli_fetch_assoc($result31);
                                                  $q44 = "SELECT count(*) as q44 FROM riesgo WHERE probabilidad=4 && i_result=4 && borrado=0 && estado=0";
                                                  $result44 = mysqli_query($con, $q44);
                                                  $row44 = mysqli_fetch_assoc($result44);
                                                  $q43 = "SELECT count(*) as q43 FROM riesgo WHERE probabilidad=4 && i_result=3 && borrado=0 && estado=0";
                                                  $result43 = mysqli_query($con, $q43);
                                                  $row43 = mysqli_fetch_assoc($result43);
                                                  $q42 = "SELECT count(*) as q42 FROM riesgo WHERE probabilidad=4 && i_result=2 && borrado=0 && estado=0";
                                                  $result42 = mysqli_query($con, $q42);
                                                  $row42 = mysqli_fetch_assoc($result42);
                                                  $q41 = "SELECT count(*) as q41 FROM riesgo WHERE probabilidad=4 && i_result=1 && borrado=0 && estado=0";
                                                  $result41 = mysqli_query($con, $q41);
                                                  $row41 = mysqli_fetch_assoc($result41);	
                                                ?>
                                            </div>
                                            <div class="modal-body">
                                                <div class=WordSection1>
                                                    <div align=center>
                                                        <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
                                                            style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
                                                            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
                                                                <td width=30 valign=top
                                                                    style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=114
                                                                    style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=387 colspan=4
                                                                    style='width:289.95pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>PROBABILIDAD DE OCURRENCIA<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:1'>
                                                                <td width=30 valign=top
                                                                    style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=114
                                                                    style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>IMPROBABLE</p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADA</p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>PROBABLE</p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CERTEZA</p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:2;height:63.3pt'>
                                                                <td width=30 rowspan=4 style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>I<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>M<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>P<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>A<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>C<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>T<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>O</span></p>
                                                                </td>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CATASTRÓFICO</p>
                                                                </td>
                                                                <td width=98 style='text-align:center; width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <a href="riesgos.php?aksi=filter&t=0&p=1&i=4"
                                                                        style="display:contents">
                                                                        <div class="box_a" style="color:black">
                                                                            <?php echo $row14['q14']; ?></div>
                                                                    </a>
                                                                </td>
                                                                <td width=99 style='text-align:center; width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <a href="riesgos.php?aksi=filter&t=0&p=2&i=4"
                                                                        style="display:contents">
                                                                        <div class="box_a" style="color:black">
                                                                            <?php echo $row24['q24']; ?></div>
                                                                    </a>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row34['q34']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row44['q44']; ?></p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:3;height:63.4pt'>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MAYOR</p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row13['q13']; ?></p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row23['q23']; ?></p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row33['q33']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row43['q43']; ?></p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:4;height:70.8pt'>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADO</p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row12['q12']; ?></p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row22['q22']; ?></p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row32['q32']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row42['q42']; ?></p>
                                                                </td>
                                                            </tr>
                                                            <tr
                                                                style='mso-yfti-irow:5;mso-yfti-lastrow:yes;height:62.4pt'>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MENOR</p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row11['q11']; ?></p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row21['q21']; ?></p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row31['q31']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row41['q41']; ?></p>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>

                                                    <p class=MsoNormal align=center style='text-align:center'>
                                                        <o:p>&nbsp;</o:p>
                                                    </p>

                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN MODAL MATRIZ INHERENTE -->
                                <!-- MODAL CICLO -->
                                <div class="modal fade" id="modal-ciclo">
                                    <div class="modal-dialog" style="width: 900px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Ciclo de gestión de riesgo</h2>
                                            </div>
                                            <div class="modal-body">
                                                <img src="../img/custom/ciclo_riesgo.png" class="img-responsive" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN MODAL CICLO -->
                                <!-- MODAL RESIDUAL -->
                                <div class="modal fade" id="modal-residual">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Cantidad según Matriz de riesgo residual</h2>
                                                <?php
                                                  //querys de datos matriz RESIDUAL
                                                  $q14r = "SELECT count(*) as q14r FROM riesgo WHERE p_resid=1 && i_resid=4 && borrado=0 && estado=0";
                                                  $result14r = mysqli_query($con, $q14r);
                                                  $row14r = mysqli_fetch_assoc($result14r);
                                                  $q13r = "SELECT count(*) as q13r FROM riesgo WHERE p_resid=1 && i_resid=3 && borrado=0 && estado=0";
                                                  $result13r = mysqli_query($con, $q13r);
                                                  $row13r = mysqli_fetch_assoc($result13r);
                                                  $q12r = "SELECT count(*) as q12r FROM riesgo WHERE p_resid=1 && i_resid=2 && borrado=0 && estado=0";
                                                  $result12r = mysqli_query($con, $q12r);
                                                  $row12r = mysqli_fetch_assoc($result12r);
                                                  $q11r = "SELECT count(*) as q11r FROM riesgo WHERE p_resid=1 && i_resid=1 && borrado=0 && estado=0";
                                                  $result11r = mysqli_query($con, $q11r);
                                                  $row11r = mysqli_fetch_assoc($result11r);
                                                  $q24r = "SELECT count(*) as q24r FROM riesgo WHERE p_resid=2 && i_resid=4 && borrado=0 && estado=0";
                                                  $result24r = mysqli_query($con, $q24r);
                                                  $row24r = mysqli_fetch_assoc($result24r);
                                                  $q23r = "SELECT count(*) as q23r FROM riesgo WHERE p_resid=2 && i_resid=3 && borrado=0 && estado=0";
                                                  $result23r = mysqli_query($con, $q23r);
                                                  $row23r = mysqli_fetch_assoc($result23r);
                                                  $q22r = "SELECT count(*) as q22r FROM riesgo WHERE p_resid=2 && i_resid=2 && borrado=0 && estado=0";
                                                  $result22r = mysqli_query($con, $q22r);
                                                  $row22r = mysqli_fetch_assoc($result22r);
                                                  $q21r = "SELECT count(*) as q21r FROM riesgo WHERE p_resid=2 && i_resid=1 && borrado=0 && estado=0";
                                                  $result21r = mysqli_query($con, $q21r);
                                                  $row21r = mysqli_fetch_assoc($result21r);
                                                  $q34r = "SELECT count(*) as q34r FROM riesgo WHERE p_resid=3 && i_resid=4 && borrado=0 && estado=0";
                                                  $result34r = mysqli_query($con, $q34r);
                                                  $row34r = mysqli_fetch_assoc($result34r);
                                                  $q33r = "SELECT count(*) as q33r FROM riesgo WHERE p_resid=3 && i_resid=3 && borrado=0 && estado=0";
                                                  $result33r = mysqli_query($con, $q33r);
                                                  $row33r = mysqli_fetch_assoc($result33r);
                                                  $q32r = "SELECT count(*) as q32r FROM riesgo WHERE p_resid=3 && i_resid=2 && borrado=0 && estado=0";
                                                  $result32r = mysqli_query($con, $q32r);
                                                  $row32r = mysqli_fetch_assoc($result32r);
                                                  $q31r = "SELECT count(*) as q31r FROM riesgo WHERE p_resid=3 && i_resid=1 && borrado=0 && estado=0";
                                                  $result31r = mysqli_query($con, $q31r);
                                                  $row31r = mysqli_fetch_assoc($result31r);
                                                  $q44r = "SELECT count(*) as q44r FROM riesgo WHERE p_resid=4 && i_resid=4 && borrado=0 && estado=0";
                                                  $result44r = mysqli_query($con, $q44r);
                                                  $row44r = mysqli_fetch_assoc($result44r);
                                                  $q43r = "SELECT count(*) as q43r FROM riesgo WHERE p_resid=4 && i_resid=3 && borrado=0 && estado=0";
                                                  $result43r = mysqli_query($con, $q43r);
                                                  $row43r = mysqli_fetch_assoc($result43r);
                                                  $q42r = "SELECT count(*) as q42r FROM riesgo WHERE p_resid=4 && i_resid=2 && borrado=0 && estado=0";
                                                  $result42r = mysqli_query($con, $q42r);
                                                  $row42r = mysqli_fetch_assoc($result42r);
                                                  $q41r = "SELECT count(*) as q41r FROM riesgo WHERE p_resid=4 && i_resid=1 && borrado=0 && estado=0";
                                                  $result41r = mysqli_query($con, $q41r);
                                                  $row41r = mysqli_fetch_assoc($result41r);	
                                                ?>
                                            </div>
                                            <div class="modal-body">
                                                <div class=WordSection1>
                                                    <div align=center>
                                                        <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
                                                            style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
                                                            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
                                                                <td width=30 valign=top
                                                                    style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=114
                                                                    style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=387 colspan=4
                                                                    style='width:289.95pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>PROBABILIDAD DE OCURRENCIA<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:1'>
                                                                <td width=30 valign=top
                                                                    style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=114
                                                                    style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                                        <o:p>&nbsp;</o:p>
                                                                    </p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>IMPROBABLE</p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADA</p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>PROBABLE</p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CERTEZA</p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:2;height:63.3pt'>
                                                                <td width=30 rowspan=4 style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>I<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>M<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>P<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>A<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>C<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>T<o:p></o:p></span></p>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>O</span></p>
                                                                </td>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CATASTRÓFICO</p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row14r['q14r']; ?></p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row24r['q24r']; ?></p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row34r['q34r']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row44r['q44r']; ?></p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:3;height:63.4pt'>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MAYOR</p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row13r['q13r']; ?></p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row23r['q23r']; ?></p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row33r['q33r']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row43r['q43r']; ?></p>
                                                                </td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:4;height:70.8pt'>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADO</p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row12r['q12r']; ?></p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row22r['q22r']; ?></p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row32r['q32r']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row42r['q42r']; ?></p>
                                                                </td>
                                                            </tr>
                                                            <tr
                                                                style='mso-yfti-irow:5;mso-yfti-lastrow:yes;height:62.4pt'>
                                                                <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MENOR</p>
                                                                </td>
                                                                <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row11r['q11r']; ?></p>
                                                                </td>
                                                                <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row21r['q21r']; ?></p>
                                                                </td>
                                                                <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row31r['q31r']; ?></p>
                                                                </td>
                                                                <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                                                    <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row41r['q41r']; ?></p>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>

                                                    <p class=MsoNormal align=center style='text-align:center'>
                                                        <o:p>&nbsp;</o:p>
                                                    </p>

                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN MODAL RESIDUAL -->
                                <!-- MODAL VER ITEM -->
                                <div id="ver-itemDialog" class="modal fade">
                                    <div class="modal-dialog" style="width:900px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Riesgos >> Ver Riesgo</h2>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="box box-primary">

                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="amenaza"> Amenaza</label>
                                                                <textarea class="form-control" rows="2" id="amenaza"
                                                                    value="" readonly></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="vulnerabilidad"> Vulnerabilidad</label>
                                                                <textarea class="form-control" rows="2"
                                                                    id="vulnerabilidad" value="" readonly></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Activos afectados</label>
                                                                <input type="text" class="form-control" name="activos"
                                                                    id="activos" value="" readonly>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="box box-default">

                                                            <div class="box-body">
                                                                <div class="form-group">
                                                                    <label>Responsable</label>
                                                                    <input type="text" class="form-control"
                                                                        name="responsable" id="responsable" value=""
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="label-custom label-custom-info">Categoria
                                                                        de Riesgo</label>
                                                                    <input type="text" class="form-control"
                                                                        name="categoria" id="categoria" value=""
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="label-custom label-custom-info">Probabilidad
                                                                        de ocurrencia</label>
                                                                    <input type="text" class="form-control" name="prob"
                                                                        id="prob" value="" readonly>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-sm-4">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Impacto en
                                                                            confidencialidad</label>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Impacto en
                                                                            integridad</label>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Impacto en
                                                                            disponibilidad</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            name="i_conf" id="i_conf" value="" readonly>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            name="i_int" id="i_int" value="" readonly>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            name="i_disp" id="i_disp" value="" readonly>
                                                                    </div>
                                                                </div><br>
                                                                <div class="form-row">
                                                                    <div class="col-sm-4">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Impacto
                                                                            resultante</label>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Riesgo
                                                                            Inherente</label>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Valoración
                                                                            Riesgo</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            name="i_result" id="i_result" value=""
                                                                            readonly>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            name="n_riesgo" id="n_riesgo" value=""
                                                                            readonly>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            name="valoracion" id="valoracion" value=""
                                                                            readonly>
                                                                    </div>
                                                                </div><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="box box-default">

                                                            <div class="box-body">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="label-custom label-custom-info">Control
                                                                        existente / propuesto</label>
                                                                    <input type="text" class="form-control"
                                                                        name="control" id="control" value="" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="label-custom label-custom-info">Tipo
                                                                        de control</label>
                                                                    <input type="text" class="form-control"
                                                                        name="t_control" id="t_control" value=""
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="label-custom label-custom-info">Estrategia
                                                                        propuesta</label>
                                                                    <input type="text" class="form-control"
                                                                        name="estrategia" id="estrategia" value=""
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="label-custom label-custom-info">Plan
                                                                        de tratamiento</label>
                                                                    <input type="text" class="form-control" name="plan"
                                                                        id="plan" value="" readonly>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Probabilidad
                                                                            residual</label>
                                                                        <input type="text" class="form-control"
                                                                            name="p_resid" id="p_resid" value=""
                                                                            readonly>
                                                                        <!-- /input-group -->
                                                                    </div>
                                                                    <!-- /.col-lg-6 -->
                                                                    <div class="col-lg-6">
                                                                        <div class="input-group">
                                                                            <label
                                                                                class="label-custom label-custom-info"
                                                                                style="text-align:center;">Impacto
                                                                                residual</label>
                                                                            <input type="text" class="form-control"
                                                                                name="i_resid" id="i_resid" value=""
                                                                                readonly>
                                                                        </div>
                                                                        <!-- /input-group -->
                                                                    </div>
                                                                    <!-- /.col-lg-6 -->
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <label class="label-custom label-custom-info"
                                                                            style="text-align:center;">Riesgo
                                                                            residual</label>
                                                                        <input type="text" class="form-control"
                                                                            name="n_resid" id="n_resid" value=""
                                                                            readonly>
                                                                        <!-- /input-group -->
                                                                    </div>
                                                                    <!-- /.col-lg-6 -->
                                                                    <div class="col-lg-6">
                                                                        <div class="input-group">
                                                                            <label
                                                                                class="label-custom label-custom-info"
                                                                                style="text-align:center;">Valoración
                                                                                residual</label>
                                                                            <input type="text" class="form-control"
                                                                                name="val_resid" id="val_resid" value=""
                                                                                readonly>
                                                                        </div>
                                                                        <!-- /input-group -->
                                                                    </div>
                                                                    <!-- /.col-lg-6 -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="label-custom label-custom-info">Observaciones</label>
                                                    <textarea class="form-control" rows="2" name="observacion"
                                                        id="observacion" value="" readonly></textarea><br>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-right"
                                                    data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div>
                                <!-- FIN MODAL VER -->

                                <div class="box-body">
                                    <table id="riesgos" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <!--<th width="1">Ver</th> -->
                                                <th width="2">ID</th>
                                                <th>Vulnerabilidad</th>
                                                <th>Amenaza</th>
                                                <th>Responsable</th>
                                                <th>Referente</th>
                                                <th>Gerencia</th>
                                                <th>F.Alta</th>
                                                <!--<th>Activos</th> -->
                                                <!-- <th>Categoría</th> -->
                                                <!-- <th>Riesgo</th> -->
                                                <!-- <th>Acción</th> -->
                                                <th>Valoración</th>
                                                <th>Estado</th>
                                                <th>Incidente</th>
                                                <th>Avance</th>
                                                <th>Vencimiento</th>
                                                <th>Plazo</th>
                                                <th width="110px">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $sql = mysqli_query($con, $query.' ORDER BY id_riesgo ASC');
                                              
                                              if(mysqli_num_rows($sql) == 0){
                                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                              }else{
                                              
                                                while($row = mysqli_fetch_assoc($sql)){
                                                  $idr = $row['id_riesgo'];
                                                  $q_riesgo_activo = "SELECT ac.titulo FROM controls.riesgo_activo as i 
                                                        LEFT JOIN activo as ac on i.id_activo = ac.id_activo
                                                        WHERE id_riesgo='$idr'";
                                                  $sqlq = mysqli_query($con, $q_riesgo_activo);
                                                                
                                                  $q_concat = "SELECT GROUP_CONCAT(titulo SEPARATOR ' / ') as string FROM controls.riesgo_activo as i 
                                                        LEFT JOIN activo as ac on i.id_activo = ac.id_activo
                                                        WHERE id_riesgo='$idr'";
                                                  $sqlconcat = mysqli_query($con, $q_concat);
                                                                
                                                  $rowconcat = mysqli_fetch_assoc($sqlconcat);
                                                  $character = implode($rowconcat);
                                                  
                                                  echo '<td align="center">'.$row['id_riesgo'].'</td>';
                                                  echo '<td>'.$row['vulnerabilidad'].'</td>';
                                                  echo '<td>'.$row['amenaza'].'</td>';
                                                  echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                                                  echo '<td>'.$row['ref_apellido'].' '.$row['ref_nombre']. '</td>'; 
                                                  echo '<td>'.$row['gerencia'].'</td>'; 
                                                  echo '<td>'.$row['alta'].'</td>'; 
                                                //   echo '<td>'.$row['tipo'].'</td>';
                                                //   echo '<td>'.$row['n_riesgo'].'</td>';
                                                  echo '<td>'.$row['valoracion'].'</td>';
                                                  echo '<td>'.$row['v_inicial'].'</td>';
                                                  
                                                  if($row['estado'] == '0'){
                                                    echo '<td><span class="label label-warning">Abierto</span></td>'; 
                                                  }
                                                  else if ($row['estado'] == '1' ){
                                                    echo '<td><span class="label label-info">Cerrado</span></td>';
                                                  }
                                                  if($row['incidente'] == '0'){
                                                    echo '<td><span class="label label-info">No</span></td>'; 
                                                  }
                                                  else if ($row['incidente'] == '1' ){
                                                    echo '<td><span class="label label-danger">Si</span></td>';
                                                  }

                                                  echo '<td>'.$row['avance'].' %</td>'; 
                                                  
                                                  $day=date("d");
                                                  $month=date("m");
                                                  $year=date("Y");

                                                  $due = explode("/", $row['vencimiento']);
                                                  $due_d = $due[0];
                                                  $due_m = $due[1];
                                                  $due_y = $due[2];
                                                  $ok=0;

                                                  $dayofy = (($month * 30)+($day));
                                                  $dayofdue = (($due_m * 30)+($due_d));

                                                  if ($due_y >= $year){
                                                      if ($dayofy < $dayofdue){
                                                          $ok=1;
                                                      }
                                                      else if ($dayofy == $dayofdue){
                                                          $ok=2;
                                                      }
                                                  }
                                                  echo '<td><span class="badge bg-';

                                                  if ($row['estado'] == '0' ){
                                                      if ($ok == '0'){
                                                          echo 'red';
                                                      }

                                                      else if ($ok == '1'){
                                                          echo 'green';
                                                      }
                                                      else if ($ok == '2'){
                                                          echo 'yellow';
                                                      }
                                                  } else {echo 'gray';}

                                                  echo '">'.$row['vencimiento'].'</span></td>';
                                                  
                                                  $days2ven = $dayofdue - $dayofy;
                                                  
                                                  if ($row['estado'] == '0' ){
                                                      //if ($due_y >= $year){
                                                          echo '<td>'.$days2ven.' días</td>';
                                                      //}else echo '<td><span class="label label-danger">Vencido</span></td>';
                                                  }else echo '<td><span class="label label-info">No Aplica</span></td>';
                                                                
                                                  echo '<td align="center">
                                                          <a href="edit_riesgo.php?nik='.$row['id_riesgo'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                                          <a href="riesgos.php?aksi=delete&nik='.$row['id_riesgo'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['amenaza'].'?\')" class="btn btn-danger btn-sm ';
                                                                if ($rq_sec['edicion']=='0'){
                                                                        echo 'disabled';
                                                                }
                                                                echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                                        </td>
                                                        </tr>';
                                                }
                                              }
                                              ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <!--<th width="1">Ver</th>-->
                                                <th width="2">ID</th>
                                                <th>Vulnerabilidad</th>
                                                <th>Amenaza</th>
                                                <th>Responsable</th>
                                                <th>Referente</th>
                                                <th>Gerencia</th>
                                                <th>F.Alta</th>
                                                <!--<th>Activos</th>-->
                                                <!-- <th>Categoría</th> -->
                                                <!-- <th>Riesgo</th> -->
                                                <!-- <th>Acción</th> -->
                                                <th>Valoración</th>
                                                <th>Estado</th>
                                                <th>Incidente</th>
                                                <th>Avance</th>
                                                <th>Vencimiento</th>
                                                <th>Plazo</th>
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
        <?php include_once('./site_footer.php'); ?>

        <!-- REQUIRED JS SCRIPTS -->
        <!-- jQuery 3 -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script src="../bower_components/jquery/dist/jquery.js"></script>

        <!-- Bootstrap 3.3.7 -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <!-- <script src="../bower_components/datatables.net/js/dataTables.fixedHeader.min.js"></script> -->
        <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.min.js"></script>
        <!-- ChartJS -->
        <script src="../bower_components/chart.js/Chart.js"></script>
        <script src="../bower_components/chart.js/Chart.min.js"></script>
        <!-- InputMask -->
        <script src="../plugins/input-mask/jquery.inputmask.js"></script>
        <script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
        <!-- date-range-picker -->
        <script src="../bower_components/moment/min/moment.min.js"></script>
        <script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap datepicker -->
        <script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- Select2 -->
        <script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
        <!-- export -->
        <script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
        <script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
        <script src="../bower_components/datatables.net/js/jszip.min.js"></script>
        <script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
        <script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
        <script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
        <script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>

        <script>
        $(function() {
            $('#riesgos').DataTable({
                'ordering': true,
                'paging': true,
                'pageLength': 20,
                'lengthChange': false,
                'searching': true,
                
                'info': true,
                'autoWidth': false,
                'dom': 'Bfrtip',
                'buttons': [{
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A4',

                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                    }
                ]
            });
            var table = $('#riesgos').DataTable();
            $('#riesgos thead tr').clone(true).appendTo( '#riesgos thead' );
            $('#riesgos thead tr:eq(1) th').each( function (colIdx) {
                $(this).removeClass('sorting');
                var table = $('#riesgos').DataTable();

                // Si son las columnas de filtro creo el ddl
                if (colIdx == 4 || colIdx == 6 || colIdx == 9 || colIdx == 10) {
                    var select = $('<select style="width: 100%;"><option value=""></option></select>')
                    .on( 'change', function () {
                        table
                            .column( colIdx )
                            .search( $(this).val() )
                            .draw();
                    } )
                    .on( 'click' , function(){return false;} )
                    // .wrap( "<div></div>" );             // VER
                    // Get the search data for the first column and add to the select list
                    table
                        .column( colIdx )
                        .cache( 'search' )
                        .sort()
                        .unique()
                        .each( function ( d ) {
                            select.append( $('<option value="'+d+'">'+d+'</option>') );
                        });
                    
                    var filterhtml = select.parent().prop('outerHTML');
                    $(this).html(select);
                    // $(this).html(filterhtml);

                }
                else {
                    $(this).html("");
                }

            } );            
        })
        </script>
        <script>
        window.onload = function() {
            history.replaceState("", "", "riesgos.php");
        }
        </script>
        <script>
        $(function() {
            $(".ver-itemDialog").click(function() {
                $('#itemId').val($(this).data('id'));
                $('#amenaza').val($(this).data('amenaza'));
                $('#vulnerabilidad').val($(this).data('vulnerabilidad'));
                $('#responsable').val($(this).data('responsable'));
                $('#categoria').val($(this).data('categoria'));
                $('#activos').val($(this).data('activos'));
                $('#nivel').val($(this).data('nivel'));
                $('#valoracion').val($(this).data('valoracion'));
                $('#plan').val($(this).data('plan'));
                $('#prob').val($(this).data('prob'));
                $('#i_conf').val($(this).data('i_conf'));
                $('#i_int').val($(this).data('i_int'));
                $('#i_disp').val($(this).data('i_disp'));
                $('#control').val($(this).data('control'));
                $('#estrategia').val($(this).data('estrategia'));
                $('#p_resid').val($(this).data('p_resid'));
                $('#i_resid').val($(this).data('i_resid'));
                $('#i_result').val($(this).data('i_result'));
                $('#observacion').val($(this).data('observacion'));
                $('#n_riesgo').val($(this).data('n_riesgo'));
                $('#n_resid').val($(this).data('n_resid'));
                $('#val_resid').val($(this).data('val_resid'));

                if ($(this).data('c_prev') == '1') {
                    $('#t_control').val('PREVENTIVO')
                } else {
                    $('#t_control').val('DETECTIVO')
                };


                $("#ver-itemDialog").modal("show");

            });
        });
        </script>


        <script type="text/javascript">
        $(function() {

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY h:mm A'
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'))
                }
            )

            //Date picker
            $('#datepicker1').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                todayHighlight: true,
                daysOfWeekDisabled: [0, 6]
            })
            $('#datepicker2').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                todayHighlight: true,
                daysOfWeekDisabled: [0, 6]
            })

        })
        </script>
<script>
$(function(){
    $('#txtgerenciaresponsable').val($('#ddlresponsable option:selected', this).attr('gerencia'));    
    $('#txtgerenciaidentificado').val($('#ddlidentificado option:selected', this).attr('gerencia'));    
    $('#txtgerenciareferente').val($('#ddlreferente option:selected', this).attr('gerencia'));    
    $('#ddlresponsable').on('change', function() {
        $('#txtgerenciaresponsable').val($('option:selected', this).attr('gerencia'));    
    });    
    $('#ddlidentificado').on('change', function() {
        $('#txtgerenciaidentificado').val($('option:selected', this).attr('gerencia'));    
    });
    $('#ddlreferente').on('change', function() {
        $('#txtgerenciareferente').val($('option:selected', this).attr('gerencia'));    
    });
});
</script>
</body>

</html>