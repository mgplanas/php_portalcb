<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Proyectos";
$user=$_SESSION['usuario'];

$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
$sql = mysqli_query($con, "SELECT i.*, p.nombre, p.apellido FROM proyecto as i 
						 	  LEFT JOIN persona as p on i.responsable = p.id_persona
							  WHERE i.borrado='0' AND i.id_proyecto='$nik'");

if(mysqli_num_rows($sql) == 0){
	header("Location: proyectos.php");
}else{
	$row = mysqli_fetch_assoc($sql);}
			
if(isset($_POST['save'])){

	$titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
    $responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
    $categoria = mysqli_real_escape_string($con,(strip_tags($_POST["categoria"],ENT_QUOTES)));
    $prioridad = mysqli_real_escape_string($con,(strip_tags($_POST["prioridad"],ENT_QUOTES)));
    $inicio = mysqli_real_escape_string($con,(strip_tags($_POST["inicio"],ENT_QUOTES)));
    $due_date = mysqli_real_escape_string($con,(strip_tags($_POST["due_date"],ENT_QUOTES)));
    //$estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));
    //$porcentaje = mysqli_real_escape_string($con,(strip_tags($_POST["porcentaje"],ENT_QUOTES)));
    $grupo = mysqli_real_escape_string($con,(strip_tags($_POST["grupo"],ENT_QUOTES)));
    $path = mysqli_real_escape_string($con,(strip_tags($_POST["path"],ENT_QUOTES)));
    $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));
	
	$update_proyecto = mysqli_query($con, "UPDATE proyecto SET titulo='$titulo', tipo='$tipo', descripcion='$descripcion', responsable='$responsable', categoria='$categoria', prioridad='$prioridad', inicio='$inicio', due_date='$due_date', grupo='$grupo', path='$path', modificado=NOW() WHERE id_proyecto='$nik'") or die(mysqli_error());
    
	$insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('2', '3','$nik', now(), '$user', '$titulo')") or die(mysqli_error());
	if($update_proyecto){
		$_SESSION['formSubmitted'] = 1;
		header("Location: proyectos.php");
	}else{
		$_SESSION['formSubmitted'] = 9;
		header("Location: proyectos.php");					
	}
}
//Alert icons data on top bar
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' and borrado=0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

// if ($rq_sec['soc']=='0'){
// 	header('Location: ../site.php');
// }
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
<!-- daterange picker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- IChecks -->
  <link rel="stylesheet" href="../plugins/iCheck/all.css">

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
        Gestión de Proyectos
        <small>Editar >> <?php echo $row ['titulo']; ?></small>
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
              <div class="box-body">
                <div class="form-group">
                  <label for="titulo">Titulo</label>
                  <input type="text" class="form-control" name="titulo" value="<?php echo $row ['titulo']; ?>">
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <?php 
                    echo "<textarea class=form-control name=descripcion>{$row['descripcion']} </textarea>"; ?>
                </div>
				        <div class="form-group">
                  <label>Responsable</label>
                  <select name="responsable" class="form-control">
                    <?php

                        $personasn = mysqli_query($con, "SELECT * FROM persona WHERE borrado = 0 and gerencia='$per_id_gerencia' ORDER BY apellido");
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
                  <label>Tipo</label>
                  <select name="tipo" class="form-control">
                    <?php

                        $tipo = mysqli_query($con, "SELECT * FROM tipo_proyecto");
                        while($rowt = mysqli_fetch_array($tipo)){
                          if($rowt['id']==$row['tipo']) {
                            echo "<option value='". $rowt['id'] . "' selected='selected'>" .$rowt['nombre'] ."</option>";
                          }
                          else {
                            echo "<option value='". $rowt['id'] . "'>" .$rowt['nombre'] ."</option>";
                          }
                        }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                    <label>Prioridad</label>
                    <select name="prioridad" class="form-control">
                        <option value='1'<?php if($row['prioridad'] == '1'){ echo 'selected'; } ?>>Alta</option>
                        <option value='2'<?php if($row['prioridad'] == '2'){ echo 'selected'; } ?>>Media</option>
                        <option value='3'<?php if($row['prioridad'] == '3'){ echo 'selected'; } ?>>Baja</option>
                    </select>
				</div>
                <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                              <label>Grupo</label>
                              <select name="grupo" class="form-control">
                                    <?php
                                            $grupos = mysqli_query($con, "SELECT * FROM grupo WHERE id_gerencia='$per_id_gerencia'");
                                            while($rowpg = mysqli_fetch_array($grupos)){
                                                if($rowpg['id_grupo']==$row['grupo']) {
                                                    echo "<option value='". $rowpg['id_grupo'] . "' selected='selected'>" .$rowpg['nombre'] . "</option>";
                                                }
                                                else {
                                                    echo "<option value='". $rowpg['id_grupo'] . "'>" .$rowpg['nombre'] . "</option>";										
                                                }
                                            }
                                    ?>
                              </select>
                            </div>
                        </div>
				     <div class="col-sm-6">
                        <div class="form-group">
                          <label>Categoría</label>
                          <select name="categoria" class="form-control">
                            <option value='1'<?php if($row['categoria'] == '1'){ echo 'selected'; } ?>>Proyecto Nuevo</option>
                            <option value='2'<?php if($row['categoria'] == '2'){ echo 'selected'; } ?>>Proyecto de mejora</option>
                            <option value='3'<?php if($row['categoria'] == '3'){ echo 'selected'; } ?>>Tarea</option>
                         </select>
                        </div>
                    </div>
                </div>
				<div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Inicio</label>
                                <div class="input-group date" data-provide="datepicker1">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                    <input type="text" class="form-control pull-right" name="inicio" id="datepicker1" value="<?php echo $row ['inicio']; ?>">
                                </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label>Fecha de vencimiento</label>
                            <div class="input-group date" data-provide="datepicker2">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" 

                                     class="form-control pull-right" name="due_date" id="datepicker2" value="<?php echo $row ['due_date']; ?>">
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for="repro">Fecha Reprogramación</label>
                            <input type="text" class="form-control" name="repro" value="<?php echo $row ['repro_date']; ?>" readonly>
                          </div>
                        </div>
                </div>
                <div class="row">
                        <div class="col-sm-6">
                             <div class="form-group">
                              <label for="estado">Estado</label>
                                <?php
                                    if($row['estado'] == '1'){
                                        echo '<input type="text" readonly class="form-control" name="porcentaje" value="No Iniciada">'; 
                                    }
                                    else if ($row['estado'] == '2' ){
                                        echo '<input type="text" readonly class="form-control" name="porcentaje" value="En Curso">';
                                    }
                                    else if ($row['estado'] == '3' ){
                                        echo '<input type="text" readonly class="form-control" name="porcentaje" value="Aplazada">';
                                    }
                                    else if ($row['estado'] == '4' ){
                                        echo '<input type="text" readonly class="form-control" name="porcentaje" value="Completada">';
                                    } 
                                    else if ($row['estado'] == '5' ){
                                        echo '<input type="text" readonly class="form-control" name="porcentaje" value="Cancelada">';
                                    } 
                                ?>
                            </div>
                         </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                              <label for="porcentaje">Porcentaje de avance (Real)</label>
                              <input type="text" readonly class="form-control" name="porcentaje" value="<?php echo $row ['porcentaje']; ?> %">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                              <label for="porcentaje_estimado">Porcentaje de avance (Estimado)</label>
                              <input type="text" readonly class="form-control" name="porcentaje_estimado" value="<?php echo $row ['porcentaje_estimado']; ?> %">
                            </div>
                        </div>
                </div>
                <div class="form-group">
                  <label for="path">Path de documentación</label>
                  <input type="text" class="form-control" name="path" value="<?php echo $row ['path']; ?>">
                </div>
                <div class="form-group">
                            <button type="button" class="btn-block btn-flat btn-primary" data-toggle="modal" data-target="#modal-avance"><i class="fa fa-plus"></i> Agregar Avance</button>
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
                  <th style="text-align: center;width: 10px;">T</th>
                  <th>Detalle</th>
                  <th style="width: 150px">Fecha</th>
                </tr>
                <?php
                    $query = "SELECT a.*, p.repro_date FROM avance as a 
                              INNER JOIN proyecto as p ON a.id_proyecto = p.id_proyecto
                              WHERE a.borrado='0' AND a.id_proyecto='$nik'
                              ORDER BY a.id_avance ASC";

                    $sql = mysqli_query($con, $query);

                    if(mysqli_num_rows($sql) == 0){
                        echo '<tr><td colspan="8">No hay datos.</td></tr>';
                    }else{
                        while($row = mysqli_fetch_assoc($sql)){

                            echo '
                            <tr>
                            <td>
                            <a data-id="'.$row['id_avance'].'" 
                                data-detail="'.$row['detalle'].'"
                                data-fecha="'.$row['fecha'].'"
                                data-usuario="'.$row['user'].'"
                                title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                            </td>';
                            echo '<td align="center">'.$row['id_avance'].'</td>';
                            if ($row['reunion']==1) {
                              echo '<td style="text-align: center;"><i title="Reunion de ' .$row['tiempo'].' minutos" class="fa fa-users" style="font-size: 20px;"></i></td>';
                            } elseif ($row['reunion']==0) {
                              echo '<td style="text-align: center;"><i title="Avance" class="fa fa-arrow-circle-right" style="font-size: 20px;"></i></td>';
                            } elseif ($row['reunion']==2) {
                              echo '<td style="text-align: center;"><i title="Riesgo" class="fa fa-flash" style="font-size: 20px;"></i></td>';
                            } elseif ($row['reunion']==3) {
                              echo '<td style="text-align: center;"><i title="Problema" class="fa fa-exclamation-triangle" style="font-size: 20px;"></i></td>';
                            } elseif ($row['reunion']==4) {
                              echo '<td style="text-align: center;"><i title="Reprogramación (' . $row['repro_date'] . ')" class="fa fa-calendar" style="font-size: 20px;"></i></td>';
                            } else {
                              echo '<td></td>';
                            }
                            echo '<td>'.$row['detalle'].'</td>';
                            echo '
                            <td>'.$row['fecha'].'</td>';
                        }
                    }
                    ?>  
              </table>
            </div>
            <!-- /.box-body -->
          </div>
				 <div class="form-group">
					<div class="col-sm-6">
						<input type="submit" name="save" class="btn  btn-raised btn-success" value="Guardar datos">
					</div>
					<div style="text-align: right;" class="col-sm-6">
						<a href="proyectos.php" class="btn btn-warning btn-raised">Cancelar</a>
					</div>
				</div>
			  </div>
            </form>
          </div>
    <!-- /.content -->
    <div class="modal fade" id="modal-avance">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Nuevo Avance</h2>
            <?php
               $meta_proyecto = mysqli_query($con, "SELECT * FROM proyecto WHERE id_proyecto='$nik'");
               $rowmp = mysqli_fetch_array($meta_proyecto);
              
                if(isset($_POST['Adda'])){
                    $detalle = mysqli_real_escape_string($con,(strip_tags($_POST["detalle"],ENT_QUOTES)));//Escanpando caracteres
                    $estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres
                    $porcentaje = mysqli_real_escape_string($con,(strip_tags($_POST["porcentaje"],ENT_QUOTES)));//Escanpando caracteres
                    $porcentaje_estimado = mysqli_real_escape_string($con,(strip_tags($_POST["porcentaje_estimado"],ENT_QUOTES)));//Escanpando caracteres
                    $categoria = mysqli_real_escape_string($con,(strip_tags($_POST["categoria"],ENT_QUOTES)));//Escanpando caracteres
                    $tiempo = mysqli_real_escape_string($con,(strip_tags($_POST["tiempo"],ENT_QUOTES)));//Escanpando caracteres
                    $repro = mysqli_real_escape_string($con,(strip_tags($_POST["reprog_date"],ENT_QUOTES)));//Escanpando caracteres
                    
                    $insert_avance = mysqli_query($con, "INSERT INTO avance (id_proyecto, detalle, fecha, user, reunion, tiempo) 
                                                         VALUES ('$nik', '$detalle', now(), '$user', '$categoria', '$tiempo')") or die(mysqli_error());
                    
                    if ($categoria=='4') {
                      $update_proyecto = mysqli_query($con, "UPDATE proyecto SET repro_date='$repro', estado='$estado', porcentaje='$porcentaje', porcentaje_estimado='$porcentaje_estimado', modificado=NOW() WHERE id_proyecto='$nik'") or die(mysqli_error());	
                    } else {
                      $update_proyecto = mysqli_query($con, "UPDATE proyecto SET estado='$estado', porcentaje='$porcentaje', porcentaje_estimado='$porcentaje_estimado', modificado=NOW() WHERE id_proyecto='$nik'") or die(mysqli_error());	
                    }
										 
                    
                    
                    $lastInsert = mysqli_insert_id($con);
                    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                               VALUES ('1', '12', '$lastInsert', now(), '$user')") or die(mysqli_error());
	                if($insert_avance){
						echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';}
                }				
            ?>
          </div>
          <div class="modal-body">
            <!-- form start -->
        <form method="post" role="form" action="">
          <div class="box-body">
            <div class="form-group">
              <label for="legajo">Detalle del avance</label>
              <textarea class="form-control" rows="5" name="detalle" id="detalle" value=""></textarea>
            </div>
              <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value='1'<?php if($rowmp['estado'] == '1'){ echo 'selected'; } ?>>No Iniciada</option>
                            <option value='2'<?php if($rowmp['estado'] == '2'){ echo 'selected'; } ?>>En Curso</option>
                            <option value='3'<?php if($rowmp['estado'] == '3'){ echo 'selected'; } ?>>Aplazada</option>
                            <option value='4'<?php if($rowmp['estado'] == '4'){ echo 'selected'; } ?>>Completada</option>
                            <option value='5'<?php if($rowmp['estado'] == '5'){ echo 'selected'; } ?>>Cancelada</option>
                        </select>
                    </div>
                  </div>
                <div class="col-sm-4">
                      <div class="form-group">
                      <label for="porcentaje">% Avance real</label>
                      <input type="text" class="form-control" name="porcentaje" value="<?php echo $rowmp['porcentaje']; ?>">
                    </div>
                </div>
                <div class="col-sm-4">
                      <div class="form-group">
                      <label for="porcentaje_estimado">% Avance estimado</label>
                      <input type="text" class="form-control" name="porcentaje_estimado" value="<?php echo $rowmp['porcentaje_estimado']; ?>">
                    </div>
                </div>
              </div>
              <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="categoria">Categoría</label>
                                <select name="categoria" class="form-control" id="ddlcategoria">
                                    <option value='0' selected>Avance</option>
                                    <option value='1' >Reunión</option>
                                    <option value='2' >Riesgo</option>
                                    <option value='3' >Problema</option>
                                    <option value='4' >Reprogramación</option>
                                </select>                            
                            </div>
                         </div>
                        <div class="col-sm-6">
                             <div id="tiempo" class="form-group">
                              <label>Minutos</label>
                              <input type="number" min="0" class="form-control" name="tiempo" value="15">
                            </div>
                             <div id="fecha-reprog" class="form-group">
                              <label>Fecha de Reprogramación</label>
                              <div class="input-group date" data-provide="datepicker3">
                                <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="reprog_date" id="datepicker3">
                              </div>
                            </div>
                        </div>
                </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <input id="avance-submit" type="submit" name="Adda" class="btn  btn-raised btn-success" value="Guardar datos" >
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

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
            </div>	
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
  </div>
  <!-- Main Footer -->
  <?php include_once('./site_footer.php'); ?>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- ICHECKS -->
<!-- <script src="../plugins/iCheck/icheck.min.js"></script> -->
      
<script type="text/javascript">
  $(function () {

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })
      
    $('#datepicker2').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })
    $('#datepicker3').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      daysOfWeekDisabled: [0,6]
    })

  })
</script>
<script>
$(function(){
  $(".ver-itemDialog").click(function(){
    $('#itemId').val($(this).data('id'));
	$('#detail').val($(this).data('detail'));
	$('#fecha').val($(this).data('fecha'));
	$('#usuario').val($(this).data('usuario'));

    $("#ver-itemDialog").modal("show");
	
  });
});
</script>
<script>
    $(function() {

      $('#avance-submit').click(function() {
        let cat = $('#ddlcategoria').val();
        let repro = $('#datepicker3').val();

        if (cat == '4' && !repro) {
          alert('La fecha de reprogramación no debe estar vacía.');
          return false;
        }
        return true;
      });


      //Seto el trigger si la el anio cambia 
      $('#ddlcategoria').on('change', function() {
        $('#tiempo').hide();
        $('#fecha-reprog').hide();
        // Si elige la reunión muestro el div de timer
        if ($(this).val() == "1") {
          $('#tiempo').show();
        }
        if ($(this).val() == "4") {
          $('#fecha-reprog').show();
        }
      });

      $('#tiempo').hide();
      $('#fecha-reprog').hide();
    });
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>