<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Riesgo";
$user=$_SESSION['usuario'];


// BORRADO AVANCE
if(isset($_GET['akav']) == 'delete'){
	$niav = mysqli_real_escape_string($con,(strip_tags($_GET["niav"],ENT_QUOTES)));
	$cek = mysqli_query($con, "SELECT * FROM avance_riesgo WHERE id_avance_riesgo='$niav'");
	$cekd = mysqli_fetch_assoc($cek);
  $titulo = $cekd['detalle'];
 
    //Elimino el avance
    $delete_riesgo = mysqli_query($con, "UPDATE avance_riesgo SET borrado=1 WHERE id_avance_riesgo='$niav'");
    $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                           VALUES ('3', '4', '$niav', now(), '$user', '$titulo')") or die(mysqli_error());

    if(!$delete_riesgo){
        $_SESSION['formSubmitted'] = 19;
        header('Location: edit_riesgo.php?nik=' . $_GET["nik"]  );
    }else{
         $_SESSION['formSubmitted'] = 11;
         header('Location: edit_riesgo.php?nik=' . $_GET["nik"] );
    }
}


$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT i.*, pr.id as proceso_id, pr.nombre as proceso_nombre, p.nombre, p.apellido, c.tipo FROM riesgo as i 
								LEFT JOIN categoria as c on i.categoria = c.id_categoria 
								LEFT JOIN persona as p on i.responsable = p.id_persona 
                                LEFT JOIN procesos as pr on i.proceso = pr.id 
								WHERE i.borrado='0' AND i.id_riesgo='$nik'");

							  
if(mysqli_num_rows($sql) == 0){
	header("Location: riesgos.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){
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
    $proceso = mysqli_real_escape_string($con,(strip_tags($_POST["proceso"],ENT_QUOTES)));//Escapando caracteres
    
	$preventivo = '0';
	$detectivo = '0';
	
	if ($_POST["t_control"] == 1){
		$preventivo = '1';
	}else {
		$detectivo = '1';}
				
	$update_riesgo = mysqli_query($con, "UPDATE riesgo SET amenaza='$amenaza', vulnerabilidad='$vulnerabilidad', creado = NOW(),
	responsable='$responsable', categoria='$categoria', probabilidad='$probabilidad', i_conf='$i_conf', i_int='$i_int',
	i_disp='$i_disp', control='$control', estrategia='$estrategia', plan='$plan', p_resid='$p_resid', i_resid='$i_resid',
	observacion='$observacion', c_prev='$preventivo', c_detec='$detectivo', usuario='$user', alta='$alta', identificado='$identificado', vencimiento='$vencimiento', estado='$estado', incidente='$incidente', avance='$avance', referente='$referente', proceso='$proceso'
    WHERE id_riesgo='$nik'") or die(mysqli_error());
	
	//Update activos/riesgo en tabla de relación
	$delete_relacion = mysqli_query($con, "DELETE FROM riesgo_activo WHERE id_riesgo='$nik'");
    
    foreach ($_POST['activos'] as $selectedOption){
		$update_relacion = mysqli_query($con, "INSERT INTO riesgo_activo (id_riesgo, id_activo, creado) 
							   VALUES ('$nik', '$selectedOption',now())") or die(mysqli_error());
	}

	//auditoría
	$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
							   VALUES ('2', '4', '$nik', now(), '$user', '$amenaza')") or die(mysqli_error());
	unset($_POST);
	if($update_riesgo){
		$_SESSION['formSubmitted'] = 1;
		header("Location: riesgos.php");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: riesgos.php");
	}
}

//Alert icons data on top bar
//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' and borrado=0");
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
$query_controles = "SELECT 1 as total FROM controles WHERE controles.responsable='$id_rowp' AND controles.borrado='0'";
$count_controles = mysqli_query($con, $query_controles); 
$rowc = mysqli_num_rows($count_controles);

//Count Proyectos
$query_proyectos = "SELECT 1 as total FROM proyecto WHERE proyecto.responsable='$id_rowp' AND proyecto.borrado='0'";
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
  <!-- Select2 -->
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">

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
    <?php
      //Alerts -> 0=no modification, 1=Edicion, 2=Nuevo activo, 3=Nueva persona, 9=Error
      if ($_SESSION['formSubmitted']=='11'){
        echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos editados correctamente.</div>';
        $_SESSION['formSubmitted'] = 0;
      }
      else if ($_SESSION['formSubmitted']=='19'){
        echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al ejecutar el vuelco a la base de datos.</div>';
        $_SESSION['formSubmitted'] = 0;
      }
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Gestión de Riesgos
        <small>Editar >> Riesgo >> #<?php echo ''.$row['id_riesgo']. '';?></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
		<div class="box box-primary">
            <!-- /.box-header -->
			<form method="post" role="form" action="">
            <!-- form start -->
			<div class="row">
				<div class="box box-primary">
					
					<div class="box-body">
						<div class="form-group">
                            <label>Fecha de alta</label>
                            <div class="input-group date" data-provide="datepicker1">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" class="form-control pull-right" name="alta" value="<?php echo $row ['alta']; ?>" id="datepicker1" placeholder="dd/mm/yyyy">
                            </div>
                      </div>
                      <div class="form-group">
						  <label for="vulnerabilidad"> Vulnerabilidad</label>
						  <?php echo "<textarea class=form-control name=vulnerabilidad>{$row['vulnerabilidad']}</textarea>"; ?>
						</div>

                        <div class="form-group">
							<label for="amenaza"> Amenaza</label>
							<?php echo "<textarea class=form-control name=amenaza>{$row['amenaza']}</textarea>"; ?>
							</div>
						<div class="form-group">
							<label>Activos afectados</label>
							<select class="form-control select2" name="activos[]" multiple="multiple" data-placeholder="Activos" style="width: 100%;">
							  <?php
								$q_activos = mysqli_query($con, "SELECT a.*, r.id_activo as ida FROM activo as a 
								left join (select * from riesgo_activo where id_riesgo = '$nik' AND borrado='0') as r on a.id_activo = r.id_activo WHERE a.borrado='0'") ;

								while($rowqa = mysqli_fetch_array($q_activos)){

									if($rowqa['ida']) {
										echo "<option value='". $rowqa['id_activo'] . "' selected='selected'>" .$rowqa['titulo'] . "</option>";
										}
									else {
										echo "<option value=". $rowqa['id_activo']. ">". $rowqa['titulo'].  "</option>";
										}
									
								}
								?>
							</select>
						</div>
            <div class="col-md-6">
              <div class="form-group">
						  <label>Responsable</label>
						  <select name="responsable" class="form-control" id="ddlresponsable">
								<?php
                                    $personasn = mysqli_query($con, "SELECT p.*, g.nombre as gerencia, s.nombre as subgerencia 
                                    FROM persona as p 
                                    LEFT JOIN gerencia as g ON p.gerencia = g.id_gerencia 
                                    LEFT JOIN subgerencia as s on p.subgerencia = s.id_subgerencia
                                    WHERE p.borrado=0 ORDER BY p.apellido, p.nombre");
									while($rowps = mysqli_fetch_array($personasn)){
										if($rowps['id_persona']==$row['responsable']) {
											echo "<option gerencia='" . $rowps['gerencia'] . "' subgerencia='" . $rowps['subgerencia'] . "' value='". $rowps['id_persona'] . "' selected='selected'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";
										}
										else {
											echo "<option gerencia='" . $rowps['gerencia'] . "' subgerencia='" . $rowps['subgerencia'] . "' value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
										}
									}
								?>
						  </select>
						</div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="label-custom label-custom-info">SubGerencia</label>
                    <input id="txtgerenciaresponsable" type="text" name="gerencia_responsable" value="" readonly class="form-control">
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
						  <label>Identificado por</label>
						  <select name="identificado" class="form-control" id="ddlidentificado">
								<?php
									$personasn = mysqli_query($con, "SELECT p.*, g.nombre as gerencia, s.nombre as subgerencia 
                                    FROM persona as p 
                                    LEFT JOIN gerencia as g ON p.gerencia = g.id_gerencia
                                    LEFT JOIN subgerencia as s on p.subgerencia = s.id_subgerencia 
                                    WHERE p.borrado=0 ORDER BY p.apellido, p.nombre");
									while($rowps = mysqli_fetch_array($personasn)){
										if($rowps['id_persona']==$row['identificado']) {
											echo "<option gerencia='" . $rowps['gerencia'] . "' subgerencia='" . $rowps['subgerencia'] . "' value='". $rowps['id_persona'] . "' selected='selected'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";
										}
										else {
											echo "<option gerencia='" . $rowps['gerencia'] . "' subgerencia='" . $rowps['subgerencia'] . "' value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
										}
									}
								?>
						  </select>
						</div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="label-custom label-custom-info">SubGerencia</label>
                    <input id="txtgerenciaidentificado" type="text" name="gerencia_identificado" value="" readonly class="form-control">
                </div>
            </div>  
            <div class="col-md-6">
            <div class="form-group">
						  <label>Referente</label>
						  <select name="referente" class="form-control" id="ddlreferente">
								<?php
									$personasn = mysqli_query($con, "SELECT p.*, g.nombre as gerencia, s.nombre as subgerencia 
                                    FROM persona as p 
                                    LEFT JOIN gerencia as g ON p.gerencia = g.id_gerencia 
                                    LEFT JOIN subgerencia as s on p.subgerencia = s.id_subgerencia
                                    WHERE p.borrado=0 ORDER BY p.apellido, p.nombre");
									while($rowps = mysqli_fetch_array($personasn)){
										if($rowps['id_persona']==$row['referente']) {
											echo "<option gerencia='" . $rowps['gerencia'] . "' subgerencia='" . $rowps['subgerencia'] . "' value='". $rowps['id_persona'] . "' selected='selected'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";
										}
										else {
											echo "<option gerencia='" . $rowps['gerencia'] . "' subgerencia='" . $rowps['subgerencia'] . "' value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
										}
									}
								?>
						  </select>
						</div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="label-custom label-custom-info">SubGerencia</label>
                    <input id="txtgerenciareferente" type="text" name="gerencia_responsable" value="" readonly class="form-control">
                </div>
            </div>      
					</div>
				</div>
			<div class="col-md-6">
				<div class="box box-default">
					  <div class="box-body">
					   <div class="form-row">
							<div class="col-lg-6">
                                  <div class="form-group">
                                        <label class="label-custom label-custom-info">Categoria de Riesgo</label>
                                        <select name="categoria" class="form-control">
                                            <?php
                                            $q_categoria = mysqli_query($con, "SELECT * FROM categoria ORDER BY tipo ASC");
                                            while($rowqc = mysqli_fetch_array($q_categoria)){
                                                if($rowqc['id_categoria']==$row['categoria']) {
                                                    echo "<option value='". $rowqc['id_categoria'] . "' selected='selected'>" .$rowqc['tipo'] . "</option>";
                                                }
                                                else {
                                                    echo "<option value='". $rowqc['id_categoria'] . "'>" .$rowqc['tipo'] . "</option>";										
                                                } 
                                            }
                                            ?>
                                        </select>
                                    </div>
                           </div>
						  <div class="col-lg-6">
                                  <div class="form-group">
                                    <label class="label-custom label-custom-info">Probabilidad de ocurrencia</label>
                                        <select name="probabilidad" class="form-control">	
                                            <option value='1'<?php if($row['probabilidad'] == '1'){ echo 'selected'; } ?>>1 - Improbable</option>
                                            <option value='2'<?php if($row['probabilidad'] == '2'){ echo 'selected'; } ?>>2 - Moderada</option>
                                            <option value='3'<?php if($row['probabilidad'] == '3'){ echo 'selected'; } ?>>3 - Muy probable</option>
                                            <option value='4'<?php if($row['probabilidad'] == '4'){ echo 'selected'; } ?>>4 - Casi cierta</option>
                                        </select>
                                </div>
                           </div>
                          </div>
						<div class="form-row">
							<div class="col-sm-4">
								<label class="label-custom label-custom-info" style="text-align:center;">Impacto en confidencialidad</label>
							</div>
							<div class="col-sm-4">
								<label class="label-custom label-custom-info" style="text-align:center;">Impacto en integridad</label>
							</div>
							<div class="col-sm-4">
								<label class="label-custom label-custom-info" style="text-align:center;">Impacto en disponibilidad</label>
							</div>
						</div>
						<div class="form-row">
								<div class="col-sm-4">
										<select name="i_conf" class="form-control">	
									<option value='1'<?php if($row['i_conf'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
									<option value='2'<?php if($row['i_conf'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
									<option value='3'<?php if($row['i_conf'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
									<option value='4'<?php if($row['i_conf'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
									</select>
								</div>
								<div class="col-sm-4">
									<select name="i_int" class="form-control">	
									<option value='1'<?php if($row['i_int'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
									<option value='2'<?php if($row['i_int'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
									<option value='3'<?php if($row['i_int'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
									<option value='4'<?php if($row['i_int'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
									</select>
								</div>
								<div class="col-sm-4">
									<select name="i_disp" class="form-control">	
									<option value='1'<?php if($row['i_disp'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
									<option value='2'<?php if($row['i_disp'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
									<option value='3'<?php if($row['i_disp'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
									<option value='4'<?php if($row['i_disp'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
									</select>
								</div>
						</div>
                        <br clear="all" /><br />
                        <div class="form-row">
								<div class="col-sm-4">
									<div class="form-group">
								        <label class="label-custom label-custom-info">Impacto Resultante</label>
									   <input type="text" name="i_result" value="<?php echo $row ['i_result']; ?>" class="form-control" readonly>
                                    </div>	
								</div>
								<div class="col-sm-4">
									<div class="form-group">
								        <label class="label-custom label-custom-info">Nivel de riesgo inherente</label>
									   <input type="text" name="n_riesgo" value="<?php echo $row ['n_riesgo']; ?>" class="form-control" readonly>
                                    </div>	
								</div>
								<div class="col-sm-4">
									<div class="form-group">
								        <label class="label-custom label-custom-info">Valoración Inicial</label>
									   <input type="text" name="v_inicial" value="<?php echo $row ['v_inicial']; ?>" class="form-control" readonly>
                                    </div>	
								</div>
						</div>
                        <br/>
                         <div class="form-group">
								<label class="label-custom label-custom-info">Control existente / propuesto</label>
									<input type="text" name="control" value="<?php echo $row ['control']; ?>" class="form-control" placeholder="Control existente ..."required>
							</div>
							<div class="row">
								<div class="col-lg-6" style="text-align:center;">
									<input type="radio" name="t_control" value="1" <?php if($row['c_prev'] == '1'){ echo 'checked'; } ?>> Preventivo
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
								<div class="col-lg-6" style="text-align:center;">
								  <input type="radio" name="t_control" value="2" <?php if($row['c_detec'] == '1'){ echo 'checked'; } ?>> Detectivo
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
							</div> 
                          <div class="form-group">
					           <label class="label-custom label-custom-info">Observaciones</label>
                               <div class="col-sm-12">
                                  <?php echo "<textarea class=form-control name=observacion>{$row['observacion']}</textarea>"; ?>
                               </div>
			             </div>
					</div>
			</div>
            </div>
			<div class="col-md-6">
				<div class="box box-default">
						<div class="box-body">
                            <div class="row">
								<div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="label-custom label-custom-info">Estrategia propuesta</label>
                                        <select name="estrategia" class="form-control">	
                                            <option value='ACEPTAR'<?php if($row['estrategia'] == 'ACEPTAR'){ echo 'selected'; } ?>>ACEPTAR</option>
                                            <option value='REDUCIR'<?php if($row['estrategia'] == 'REDUCIR'){ echo 'selected'; } ?>>REDUCIR</option>
                                            <option value='TRANSFERIR'<?php if($row['estrategia'] == 'TRANSFERIR'){ echo 'selected'; } ?>>TRANSFERIR</option>
                                            <option value='EVITAR'<?php if($row['estrategia'] == 'EVITAR'){ echo 'selected'; } ?>>EVITAR</option>
                                        </select>
                                    </div>
                                </div>
                       <div class="col-lg-6">         
                            <div class="form-group">
                                    <label>Fecha de resolución</label>
                                    <div class="input-group date" data-provide="datepicker2">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="text" class="form-control pull-right" name="vencimiento" value="<?php echo $row ['vencimiento']; ?>" id="datepicker2" placeholder="dd/mm/yyyy">
                                    </div>
                              </div>
                                </div>
                            </div>

							<div class="form-group">
								<label class="label-custom label-custom-info">Plan de tratamiento</label>
									<input type="text" name="plan" value="<?php echo $row ['plan']; ?>" class="form-control" required>
							</div>
							<div class="row">
								<div class="col-lg-4">
									<label class="label-custom label-custom-info" style="text-align:center;">Probabilidad residual</label>
									<select name="p_resid" class="form-control">	
										<option value='1'<?php if($row['p_resid'] == '1'){ echo 'selected'; } ?>>1 - Improbable</option>
										<option value='2'<?php if($row['p_resid'] == '2'){ echo 'selected'; } ?>>2 - Moderada</option>
										<option value='3'<?php if($row['p_resid'] == '3'){ echo 'selected'; } ?>>3 - Muy probable</option>
										<option value='4'<?php if($row['p_resid'] == '4'){ echo 'selected'; } ?>>4 - Casi cierta</option>
									</select>
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
								<div class="col-lg-4">
								  <div class="input-group">
									<label class="label-custom label-custom-info" style="text-align:center;">Impacto residual</label>
									<select name="i_resid" class="form-control">	
										<option value='1'<?php if($row['i_resid'] == '1'){ echo 'selected'; } ?>>1 - Menor</option>
										<option value='2'<?php if($row['i_resid'] == '2'){ echo 'selected'; } ?>>2 - Moderado</option>
										<option value='3'<?php if($row['i_resid'] == '3'){ echo 'selected'; } ?>>3 - Mayor</option>
										<option value='4'<?php if($row['i_resid'] == '4'){ echo 'selected'; } ?>>4 - Catastrofico</option>
										</select>
								  </div>
								  <!-- /input-group -->
								</div>
                                <div class="col-lg-4">
                                <div class="form-group">
								    <label class="label-custom label-custom-info">Nivel residual</label>
									<input type="text" name="n_resid" value="<?php echo $row ['n_resid']; ?>" class="form-control" readonly>
							     </div>
                                </div>
                <!-- /.col-lg-6 -->
							</div><br/>
                          <div class="row">
								<div class="col-lg-4">
									<label class="label-custom label-custom-info" style="text-align:center;">Estado</label>
									<select id="txtriesgo_estado" name="estado" class="form-control" readonly disabled>	
										<option value='0'<?php if($row['estado'] == '0'){ echo 'selected'; } ?>>Abierto</option>
										<option value='1'<?php if($row['estado'] == '1'){ echo 'selected'; } ?>>Cerrado</option>
										
									</select>
								  <!-- /input-group -->
								</div>
                <!-- /.col-lg-6 -->
								<div class="col-lg-4">
								  <div class="input-group">
									<label class="label-custom label-custom-info" style="text-align:center;">Incidente</label>
									<select name="incidente" class="form-control">	
										<option value='0'<?php if($row['incidente'] == '0'){ echo 'selected'; } ?>>No</option>
										<option value='1'<?php if($row['incidente'] == '1'){ echo 'selected'; } ?>>Si</option>
										</select>
								  </div>
								  <!-- /input-group -->
								</div>
                                <div class="col-lg-4">
                                    <div class="form-group">
								<label class="label-custom label-custom-info">Valoración actual</label>
									<input type="text" name="v_actual" value="<?php echo $row ['v_actual']; ?>" class="form-control" readonly>
							</div>
                                </div>
                <!-- /.col-lg-6 -->
							</div>
                            <br>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="label-custom label-custom-info">% Avance</label>
                                            <input id="txt_riesgo_avance" type="text" name="avance" value="<?php echo $row ['avance']; ?>" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <?php $day=date("d");
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
                                    if ($row['estado'] == '0' ){
                                        if ($due_y >= $year){
                                            $days2ven = $dayofdue - $dayofy;
                                        }else $days2ven = "Vencido";
                                    }else $days2ven = "No Aplica";;
                                    ?>
                                    <div class="form-group">
                                        <label class="label-custom label-custom-info">Plazo</label>
                                            <input type="text" name="avance" value="<?php echo $days2ven; ?>" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="label-custom label-custom-info">Proceso afectado</label>
                                        <select name="proceso" class="form-control">
                                            <?php
                                            $proceso = mysqli_query($con, "SELECT * FROM procesos where borrado = 0;");
                                            echo "<option value=''></option>";  
                                            while($rowpr = mysqli_fetch_array($proceso)){
                                                if($rowpr['id']==$row['proceso_id']) {
                                                    echo "<option value='". $rowpr['id'] . "' selected='selected'>" .$rowpr['nombre'] . "</option>";
                                                }
                                                else {
                                                    echo "<option value='". $rowpr['id'] . "'>" .$rowpr['nombre'] . "</option>";										
                                                } 
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
 					</div>
				</div>
			</div>	
            </div>
			 <div class="form-group">
                <?php 
                if ($rq_sec['admin_riesgos']==1) {
                    echo '<button type="button" class="modal-avance btn-block btn-flat btn-primary " ><i class="fa fa-plus"></i> Agregar Avance</button>';
                }
                ?>
            </div>
            <div class="box">
                <div class="box-header">
                  <h2 class="box-title">Avances</h2>
               </div>
            <!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-striped">
                    <tr>
                      <th width="1">Ver</th>
                      <th style="width: 10px">#</th>
                      <th>Detalle</th>
                      <th style="width: 150px">Fecha</th>
                      <th style="width: 150px">Avance</th>
                      <th style="width: 150px">usuario</th>
                      <th style="text-align:center;width: 10%;">Acción</th>
                    </tr>
                    <?php
                        $query = "SELECT * FROM avance_riesgo
                                  WHERE borrado='0' AND id_riesgo='$nik'
                                  ORDER BY id_avance_riesgo ASC";

                        $sql = mysqli_query($con, $query);

                        if(mysqli_num_rows($sql) == 0){
                            echo '<tr><td colspan="8">No hay datos.</td></tr>';
                        }else{
                            while($rowavance = mysqli_fetch_assoc($sql)){

                                echo '<tr>
                                  <td><a data-id="'.$rowavance['id_avance_riesgo'].'" 
                                      data-detail="'.$rowavance['detalle'].'"
                                      data-justif="'.$rowavance['justificacion'].'"
                                      data-fecha="'.$rowavance['fecha'].'"
                                      data-usuario="'.$rowavance['user'].'"
                                      title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                                  </td>';
                                echo '<td align="center">'.$rowavance['id_avance_riesgo'].'</td>';
                                $detalleAvance = $rowavance['detalle'];
                                $porcentajeAvance = $rowavance['avance'];
                                if ($porcentajeAvance == 100) {
                                  if ($rowavance['justificacion'] != '') {
                                    $detalleAvance = $detalleAvance . ' <b>Justificación del cierre: </b> ' . $rowavance['justificacion'];
                                  } else {
                                    $detalleAvance = $detalleAvance . ' <b>Justificación del cierre: </b> ' . $row['justificacion_cierre'];
                                  }
                                }
                                echo '<td>'.$detalleAvance.'</td>';
                                echo '<td>'.$rowavance['fecha'].'</td>';
                                echo '<td>'.$rowavance['avance'].'</td>';
                                echo '<td>'.$rowavance['user'].'</td>';
                                if ($rq_sec['admin_riesgos']==1) {
                                    echo '<td align="center">
                                          <a data-id="'.$rowavance['id_avance_riesgo'].'" 
                                            data-detail="'.$rowavance['detalle'].'"
                                            data-justif="'.$rowavance['justificacion'].'"
                                            data-fecha="'.$rowavance['fecha'].'"
                                            data-usuario="'.$rowavance['user'].'"
                                            data-porcentaje= "'.$rowavance['avance'].'"
                                            data-riesgo="'. $nik .'"
                                            data-estado="'.$row['estado'].'"
                                            data-avance="'.$row['avance'].'"
                                            data-justificacion="'.$row['justificacion_cierre'].'"
                                            title="Editar datos" class="editar-itemDialog btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>';    
                                        
                                    // Si el riesgo esta cerrado no dejo eliminar avance.
                                    if ($row['estado']==0) {
                                      echo '<a href="edit_riesgo.php?akav=delete&niav='.$rowavance['id_avance_riesgo'].'&nik=' . $nik .'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de ['.$rowavance['detalle'].']?\')" class="btn btn-danger btn-sm ';
                                          
                                      if ($rq_sec['admin_riesgos']=='0'){
                                              echo 'disabled';
                                      }
                                      echo '"><i class="glyphicon glyphicon-trash"></i></a>';
                                    }
                                    echo '</td>';
                                }
                                echo '</tr>';
                            }
                        }
                        ?>  
                  </table>
                </div>
            <!-- /.box-body -->
          </div>
		    <div class="modal-footer">	
            <div class="col-sm-6">
                <?php 
                if ($rq_sec['admin_riesgos']==1) {
                    echo '<input type="submit" name="save" class="btn btn-raised btn-success" value="Guardar datos">';
                }
                ?>
            </div>
            <div class="col-sm-6">
                <a href="riesgos.php" class="btn btn-default pull-left">Cancelar</a>
            </div>
		    </div>


    <!-- MODAL ADD AVANCE -->
    <div class="modal fade" id="modal-avance">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Nuevo Avance de gestión de riesgo</h2>
            <?php
               $meta_riesgo = mysqli_query($con, "SELECT * FROM riesgo WHERE id_riesgo='$nik'");
               $rowmp = mysqli_fetch_array($meta_riesgo);
              
                if(isset($_POST['Adda'])){
                    $detalle = mysqli_real_escape_string($con,(strip_tags($_POST["detalle"],ENT_QUOTES)));//Escanpando caracteres
                    $estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres
                    $avance = mysqli_real_escape_string($con,(strip_tags($_POST["avance"],ENT_QUOTES)));//Escanpando caracteres
                    $justificacion = mysqli_real_escape_string($con,(strip_tags($_POST["justificacion"],ENT_QUOTES)));//Escanpando caracteres
                    
                    $sqlInsert_avance = "INSERT INTO avance_riesgo (id_riesgo, detalle, fecha, user, avance, justificacion) VALUES ('$nik', '$detalle', now(), '$user', $avance, '$justificacion')";
                    $insert_avance = mysqli_query($con, $sqlInsert_avance) or die(mysqli_error());
                    
                    // Si el estado es abierto limpio la justificacion
                    if ($estado=="0") {
                      $justificacion = "";
                    }
                    $query_upd_riesgo = "UPDATE riesgo SET estado='$estado', avance='$avance', modificado=NOW() , justificacion_cierre = '$justificacion'";
                    if ($estado == "1") {
                      $query_upd_riesgo = $query_upd_riesgo . ", vencimiento = DATE_FORMAT(NOW(), '%d/%m/%Y') ";
                    } 
                    $query_upd_riesgo = $query_upd_riesgo . "WHERE id_riesgo='$nik'";

                    $update_riesgo = mysqli_query($con, $query_upd_riesgo) or die(mysqli_error());	
                    
                    
                    $lastInsert = mysqli_insert_id($con);
                    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                               VALUES ('1', '4', '$lastInsert', now(), '$user')") or die(mysqli_error());
	                if($insert_avance){
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';}
                }				
            ?>
          </div>
          <div class="modal-body">
            <!-- form start -->
        <form method="post" role="form" action="" onsubmit="return validateAddAvance()">
          <div class="box-body">
            <div class="form-group">
              <label for="detalle">Detalle del avance</label>
              <textarea class="form-control" rows="5" name="detalle" id="detalle" value=""></textarea>
            </div>
            <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="estado" class="form-control" id="estadoaddcierre">
                                    <option value='0'<?php if($rowmp['estado'] == '0'){ echo 'selected'; } ?>>Abierto</option>
                                    <option value='1'<?php if($rowmp['estado'] == '1'){ echo 'selected'; } ?>>Cerrado</option>
                                </select>
                            </div>
                         </div>
                        <div class="col-sm-6">
                             <div class="form-group">
                              <label for="porcentaje">Porcentaje de avance</label>
                              <input id="txtavanceadd" type="number" class="form-control" name="avance" min="0" max="100" value="<?php echo $rowmp['avance']; ?>">
                            </div>
                        </div>
                </div>

            <div class="form-group" id="justificacioncierre">
              <label for="detalle">Justificación</label>
              <textarea class="form-control" rows="5" name="justificacion" id="txtjustificacionadd" value=""></textarea>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <input type="submit" name="Adda" class="btn  btn-raised btn-success" value="Guardar datos">
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
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
    <!-- END MODAL ADD AVANCE -->
    
    <!-- MODAL EDIT AVANCE -->
    <div class="modal fade" id="modal-avance-edit">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Editar Avance de gestión de riesgo</h2>
            <?php
               $meta_riesgo = mysqli_query($con, "SELECT * FROM riesgo WHERE id_riesgo='$nik'");
               $rowmp = mysqli_fetch_array($meta_riesgo);
              
                if(isset($_POST['EditAvance'])){
                    $detalle = mysqli_real_escape_string($con,(strip_tags($_POST["detalle"],ENT_QUOTES)));//Escanpando caracteres
                    $estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres
                    $avance = mysqli_real_escape_string($con,(strip_tags($_POST["avance"],ENT_QUOTES)));//Escanpando caracteres
                    $id_avance = mysqli_real_escape_string($con,(strip_tags($_POST["id_avance"],ENT_QUOTES)));//Escanpando caracteres
                    $justificacion = mysqli_real_escape_string($con,(strip_tags($_POST["justificacion"],ENT_QUOTES)));//Escanpando caracteres

                    $insert_avance = mysqli_query($con, "UPDATE avance_riesgo SET detalle='$detalle', fecha=now(), user='$user', avance=$avance, justificacion = '$justificacion' WHERE id_avance_riesgo=$id_avance") or die(mysqli_error());
                    
                    // Si el estado es abierto limpio la justificacion
                    if ($estado=="0") {
                      $justificacion = "";
                    }
                    $upsSQL = "UPDATE riesgo SET estado=$estado, avance=$avance, modificado=NOW(), justificacion_cierre = '$justificacion'";
                    if ($estado=="1"){
                      $upsSQL = $upsSQL . ", vencimiento = DATE_FORMAT(NOW(), '%d/%m/%Y') ";
                    }
                    $upsSQL = $upsSQL . "WHERE id_riesgo=$nik";
                    $update_riesgo = mysqli_query($con, $upsSQL) or die(mysqli_error());	
                    
                    
                    $lastInsert = mysqli_insert_id($con);
                    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                               VALUES ('1', '4', '$lastInsert', now(), '$user')") or die(mysqli_error());
	                if($insert_avance){
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
            <label class="label-custom label-custom-info">Avance #</label>
            <input type="text" name="id_avance" id="id_avance" value="" class="form-control" readonly>
          </div>          
            <div class="form-group">
              <label for="detalle">Detalle del avance</label>
              <textarea class="form-control" rows="5" name="detalle" id="edit-detalle" value=""></textarea>
            </div>
            <div class="row">
              <div class="col-sm-6">
                  <div class="form-group">
                      <label>Estado</label>
                      <select name="estado" class="form-control" id="estadoeditcierre">
                          <option value='0'<?php if($rowmp['estado'] == '0'){ echo 'selected'; } ?>>Abierto</option>
                          <option value='1'<?php if($rowmp['estado'] == '1'){ echo 'selected'; } ?>>Cerrado</option>
                      </select>
                  </div>
                </div>
              <div class="col-sm-6">
                    <div class="form-group">
                    <label for="porcentaje">Porcentaje de avance</label>
                    <input id="txtavanceedit" type="number" class="form-control" name="avance" min="0" max="100" value="<?php echo $rowmp['avance']; ?>">
                  </div>
              </div>
            </div>
            <div class="form-group" id="justificacioneditcierre">
              <label for="detalle">Justificación</label>
              <textarea class="form-control" rows="5" name="justificacion" id="txtjustificacionedit" value=""></textarea>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <input type="submit" name="EditAvance" class="btn  btn-raised btn-success validar-edicion" value="Guardar datos">
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
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
    <!-- FIN MODAL EDIT AVANCE -->

    <!-- MODAL VER AVANCE -->
    <div id="ver-itemDialog" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h2 class="modal-title">Avances >> Ver Avance</h2>
                </div>
                <div class="box box-primary">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="detail">Detalle</label>
                            <textarea class="form-control" rows="5" name="detail" id="detail" value="" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="text" class="form-control" name="fecha" id="fecha" value="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="usuario">Usuario</label>
                            <input type="text" class="form-control" name="usuario" id="usuario" value="" readonly>
                        </div>
                        <!-- <div class="form-group">
                            <label for="per_avance">Porcentaje de avance</label>
                            <input type="text" class="form-control" name="per_avance" id="per_avance" value="" readonly>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
                </div>	
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- FIN MODAL AVANCE -->
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
<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
    //Colorpicker
    // $('.my-colorpicker1').colorpicker()
    // //color picker with addon
    // $('.my-colorpicker2').colorpicker()
  })
</script>
<script>
  $(function(){
    $(".ver-itemDialog").click(function(){
      $('#itemId').val($(this).data('id'));
      $('#detail').val($(this).data('detail'));
      $('#fecha').val($(this).data('fecha'));
      $('#usuario').val($(this).data('usuario'));
      // $('#per_avance').val($(this).data('peravance'));
      $("#ver-itemDialog").modal("show");
    });
  });
</script>
<script>
  $(function(){

  });
</script>
<script>
  $(function(){
    
    var changedFlag = false;

    // -------------------------------------------------------
    // ADD
    // Defino el behavior el combo de estado ADD
    function cambiarEstadoAdd(cerrado) {
      console.log('pasa');
      if (changedFlag == true) {
        changedFlag = false;
        return;
      }
      changedFlag = true;
      if (cerrado) {
        $('#estadoaddcierre').val(1);
        $('#txtavanceadd').val(100);
        $('#justificacioncierre').show();
        $('#txtjustificacionadd').attr('required', true);
        $('#txtavanceadd').attr('readonly', true);
      } else {
        $('#estadoaddcierre').val(0);
        if ($('#txtavanceadd').val()==100) {
          $('#txtavanceadd').val(0);
        }
        $('#justificacioncierre').hide();
        $('#txtjustificacionadd').attr('required', false);
        $('#txtavanceadd').attr('readonly', false);
      }
      changedFlag = false;
    }
    
    // Defino el behavior el combo de estado EDIT
    $('#estadoaddcierre').on('change', function() {
      if (this.value == 0) {
        cambiarEstadoAdd(false);
      }
      else {
        cambiarEstadoAdd(true);
      }
    }); 

    //% AVANCE
    $('#txtavanceadd').on('input', function() {
      if (this.value < 100) {
        cambiarEstadoAdd(false);
      }
      else {
        cambiarEstadoAdd(true);
      }
    }); 

    // BEHABIOUR ADD AVANCE
    //Por default está oculto
    $('#justificacioncierre').hide();
    $('#txtjustificacionadd').attr('required', false);
    
    changedFlag = true;
    if ($('#estadoaddcierre').val()==0) {
      cambiarEstadoAdd(false);
    } else {
      cambiarEstadoAdd(true);
    }
    changedFlag = false;
    // -------------------------------------------------------
    
    // -------------------------------------------------------
    // EDIT
    // -------------------------------------------------------
    let justificacionCierre = "";
    function cambiarEstado(cerrado) {
      if (changedFlag == true) {
        changedFlag = false;
        return;
      }
      changedFlag = true;
      if (cerrado) {
        $('#estadoeditcierre').val(1);
        $('#txtavanceedit').val(100);
        $('#justificacioneditcierre').show();
        $('#txtjustificacionedit').attr('required', true);
        $('#txtjustificacionedit').val(justificacionCierre);
        $('#txtavanceedit').attr('readonly', true);
      } else {
        $('#estadoeditcierre').val(0);
        if ($('#txtavanceedit').val()==100) {
          $('#txtavanceedit').val(0);
        }
        $('#justificacioneditcierre').hide();
        $('#txtjustificacionedit').attr('required', false);
        $('#txtavanceedit').attr('readonly', false);
      }
      changedFlag = false;
    }
    
    // Defino el behavior el combo de estado EDIT
    $('#estadoeditcierre').on('change', function() {
      if (this.value == 0) {
        cambiarEstado(false);
      }
      else {
        cambiarEstado(true);
      }
    }); 

    //% AVANCE
    $('#txtavanceedit').on('input', function() {
      if (this.value < 100) {
        cambiarEstado(false);
      }
      else {
        cambiarEstado(true);
      }
    }); 

    // BEHABIOUR EDIT AVANCE
    //Por default está oculto
    $('#justificacioneditcierre').hide();
    $('#txtjustificacionedit').attr('required', false);
    
    changedFlag = true;
    if ($('#estadoeditcierre').val()==0) {
      cambiarEstado(false);
    } else {
      cambiarEstado(true);
    }
    changedFlag = false;
    // -------------------------------------------------------

    $(".editar-itemDialog").click(function(){
      $('#id_avance').val($(this).data('id'));
      $('#edit-detalle').val($(this).data('detail'));
      // $('#txtjustificacionedit').val($(this).data('justif'));
      justificacionCierre = $(this).data('justif') != '' ? $(this).data('justif') : $(this).data('justificacion');
      console.log('data-justif: click' ,$(this).data('justif'));
      if ($(this).data('estado')=="0") {
        cambiarEstado(false);
      } else {
        cambiarEstado(true);
      }    
      $('#txtavanceedit').val($(this).data('avance'));
      $("#modal-avance-edit").modal("show");
    });

    $(".modal-avance").click(function(){
      console.log('estado ', $('#txtriesgo_estado').val());
      console.log('anavce ', $('#txt_riesgo_avance').val());
      if ($('#txtriesgo_estado').val()==0) {
        cambiarEstadoAdd(false);
      } else {
        cambiarEstadoAdd(true);
      }    
      $('#txtavanceadd').val($('#txt_riesgo_avance').val() );
      $("#modal-avance").modal("show");
    });

    // $('#modal-avance').on('shown.bs.modal', function (e) {
    //   if ($(this).data('estado')=="0") {
    //     cambiarEstadoAdd(false);
    //   } else {
    //     cambiarEstadoAdd(true);
    //   }  
    //   $('txtavanceadd').val($(this).data('avance'));
    // });

  });
</script>
<script>
$(function(){
    let gersubResponsable = ($('#ddlresponsable option:selected').attr('subgerencia') ? $('#ddlresponsable option:selected').attr('subgerencia') : $('#ddlresponsable option:selected').attr('gerencia'))
    let gersubIdentificado = ($('#ddlidentificado option:selected').attr('subgerencia') ? $('#ddlidentificado option:selected').attr('subgerencia') : $('#ddlidentificado option:selected').attr('gerencia'))
    let gersubReferente = ($('#ddlreferente option:selected').attr('subgerencia') ? $('#ddlreferente option:selected').attr('subgerencia') : $('#ddlreferente option:selected').attr('gerencia'))
    $('#txtgerenciaresponsable').val(gersubResponsable);    
    $('#txtgerenciaidentificado').val(gersubIdentificado);    
    $('#txtgerenciareferente').val(gersubReferente);    
    $('#ddlresponsable').on('change', function() {
        let gersubResponsable = ($('#ddlresponsable option:selected').attr('subgerencia') ? $('#ddlresponsable option:selected').attr('subgerencia') : $('#ddlresponsable option:selected').attr('gerencia'))
        $('#txtgerenciaresponsable').val(gersubResponsable);    
    });    
    $('#ddlidentificado').on('change', function() {
        let gersubIdentificado = ($('#ddlidentificado option:selected').attr('subgerencia') ? $('#ddlidentificado option:selected').attr('subgerencia') : $('#ddlidentificado option:selected').attr('gerencia'))
        $('#txtgerenciaidentificado').val(gersubIdentificado);    
    });
    $('#ddlreferente').on('change', function() {
        let gersubReferente = ($('#ddlreferente option:selected').attr('subgerencia') ? $('#ddlreferente option:selected').attr('subgerencia') : $('#ddlreferente option:selected').attr('gerencia'))
        $('#txtgerenciareferente').val(gersubReferente);    
    });
});
</script>
<script>
function validateEditAvance() {
  if ($('#estadoEditcierre').val() == "1" ) {
    if ($('#txtjustificacionedit').val() == "") {
      alert("Debe escribir una justificación al cerrar el riesgo.");
      return false;
    }
  }
}
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>