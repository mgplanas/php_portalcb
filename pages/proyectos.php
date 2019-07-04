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
	$cek = mysqli_query($con, "SELECT * FROM proyecto WHERE id_proyecto='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $titulo = $cekd['titulo'];
    
    if(mysqli_num_rows($cek) == 0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
	}else{
		//Elimino Activo
		
        $delete_proyecto = mysqli_query($con, "UPDATE proyecto SET `borrado`='1' WHERE id_proyecto='$nik'");
      
        $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('3', '3', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
		if(!$delete_proyecto){
			$_SESSION['formSubmitted'] = 9;
		}
	}
}

//Alert icons data on top bar


//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' and borrado=0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$id_rowpg = $rowp['grupo'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['soc']=='0'){
	header('Location: ../site.php');
}

//Count Total de Proyectos
$query_total_proyectos = "SELECT 1 as total FROM proyecto WHERE proyecto.borrado='0'";// AND proyecto.estado!='4'";
$count_total_proyectos = mysqli_query($con, $query_total_proyectos); 
$rowtp = mysqli_num_rows($count_total_proyectos);

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
  <link rel="stylesheet" href="../bower_components/datatables.net/css/jquery.dataTables.min.css">
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
<!-- ChartJS -->
<script src="../js/chart.js"></script>
<script src="../js/chart.min.js"></script>
<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/jquery/dist/jquery.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nuevo proyecto guardado correctamente.</div>';
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
        Gestión de Proyectos
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
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab_1" data-toggle="tab">Mis Proyectos</a></li>
              <li><a href="#tab_2" data-toggle="tab">Mi Grupo</a></li>
              <?php
                    if ($rq_sec['admin']=='1'){
                    echo ' <li><a href="#tab_3" data-toggle="tab">Proyectos</a></li>';
                    echo ' <li><a href="#tab_4" data-toggle="tab">Completados</a></li>';
                    echo ' <li><a href="#tab_5" data-toggle="tab">Indicadores</a></li>';    
                    }
              ?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="box-header">
                    <div class="col-sm-12" style="text-align:right;">
                        <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-proyecto"><i class="fa fa-list"></i> Nuevo Proyecto</button>
                    </div>
                </div>
                <div class="box">
                    <div class="box-body">
                      <table id="mis_proyectos" class="display" width="100%">
                        <thead>
                            <th width="1">Ver</th>
                            <th width="2">Nro</th>
                            <th>Titulo</th>
                            <th>Tipo</th>
                            <th>Categoría</th>
                            <th>Responsable</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Avance</th>
                            <th>Porcentaje</th>
                            <th width="2">Vencimiento</th>
                            <th width="110px">Acciones</th>
                        </thead>
                        <tbody>
                            <?php
                              $query = "SELECT i.*, p.nombre, p.apellido, t.nombre as tipo_nombre FROM proyecto as i 
                                        LEFT JOIN persona as p on i.responsable = p.id_persona
                                        LEFT JOIN tipo_proyecto as t on i.tipo = t.id
                                        WHERE i.responsable = $id_rowp AND i.borrado='0' AND i.estado!='4'";

                              $sql = mysqli_query($con, $query.' ORDER BY id_proyecto ASC');

                              while($row = mysqli_fetch_assoc($sql)){

                                  echo '<tr>
                                  <td>
                                    <a data-id="'.$row['id_proyecto'].'" 
                                        data-titulo="'.$row['titulo'].'"
                                        data-categoria="'.$row['categoria'].'"
                                        data-descripcion="'.$row['descripcion'].'"
                                        data-responsable="'.$row['apellido'].' '.$row['nombre'].'"
                                        data-prioridad="'.$row['prioridad'].'"
                                        data-inicio="'.$row['inicio'].'"
                                        data-due_date="'.$row['due_date'].'"
                                        data-estado="'.$row['estado'].'"
                                        data-porcentaje="'.$row['porcentaje'].'"
                                        data-avance="'.$row['avance'].'"
                                        title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                                  </td>';
                                  echo '<td align="center">'.$row['id_proyecto'].'</td>';
                                  echo '<td>'.$row['titulo'].'</td>';
                                  echo '<td>'.$row['tipo_nombre'].'</td>';
                                  echo '<td>';
                                    if($row['categoria'] == '1'){
                                        echo 'Proyecto nuevo';
                                    }
                                    else if ($row['categoria'] == '2' ){
                                        echo 'Proyecto de mejora';
                                    }
                                    else if ($row['categoria'] == '3' ){
                                        echo 'Tarea';
                                    }
                                  echo '</td>';
                                  echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 

                                  if($row['prioridad'] == '1'){
                                      echo '<td>Alta</td>';
                                  }
                                  else if ($row['prioridad'] == '2' ){
                                      echo '<td>Media</td>';
                                  }
                                  else if ($row['prioridad'] == '3' ){
                                      echo '<td>Baja</td>';
                                  }

                                  if($row['estado'] == '1'){
                                      echo '<td><span class="label label-warning">No iniciada</span></td>'; 
                                  }
                                  else if ($row['estado'] == '2' ){
                                      echo '<td><span class="label label-info">En curso</span></td>';
                                  }
                                  else if ($row['estado'] == '3' ){
                                      echo '<td><span class="label label-danger">Aplazada</span></td>';
                                  }
                                  else if ($row['estado'] == '4' ){
                                      echo '<td><span class="label label-success">Completada</span></td>';
                                  } 

                                  echo '<td>
                                      <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-';
                                          if ($row['porcentaje']<='33'){
                                              echo 'danger';
                                          }
                                          else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                              echo 'yellow';
                                          }
                                          else if ($row['porcentaje']>='66'){
                                              echo 'green';
                                          }
                                          echo '" style="width: '.$row['porcentaje'].'%">
                                        </div>
                                      </div>
                                  </td>
                                  <td><span class="badge bg-';

                                    if ($row['porcentaje']<='33'){
                                        echo 'red';
                                    }
                                    else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                        echo 'yellow';
                                    }
                                    else if ($row['porcentaje']>='66'){
                                        echo 'green';
                                    }


                                  echo '">'.$row['porcentaje'].' %</span></td>';

                                  $day=date("d");
                                  $month=date("m");
                                  $year=date("Y");

                                  $due = explode("/", $row['due_date']);
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

                                  if ($row['estado'] !== '4' ){
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

                                  echo '">'.$row['due_date'].'</span></td>';

                                  echo '
                                  <td align="center">
                                    <a href="edit_proyecto.php?nik='.$row['id_proyecto'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="proyectos.php?aksi=delete&nik='.$row['id_proyecto'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['titulo'].'?\')" class="btn btn-danger btn-sm ';
                                    if ($rq_sec['edicion']=='0'){
                                            echo 'disabled';
                                    }
                                    echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                  </td>
                                  </tr>
                                  ';
                              }
                            ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.box-body -->
                </div>
              </div>
              <div class="tab-pane" id="tab_2">
                <div class="box-header">
                    <div class="col-sm-12" style="text-align:right;">
                        <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-proyecto"><i class="fa fa-list"></i> Nuevo Proyecto</button>
                    </div>
                </div>
                <div class="box">
                    <div class="box-body">
                      <table id="mi_grupo" class="display" width="100%">
                        <thead>
                        <tr>
                          <th width="1">Ver</th>
                          <th width="2">Nro</th>
                          <th>Titulo</th>
                          <th>Tipo</th>
                          <th>Categoría</th>
                          <th>Responsable</th>
                          <th>Prioridad</th>
                          <th>Estado</th>
                          <th>Avance</th>
                          <th>Porcentaje</th>
                          <th width="2">Vencimiento</th>
                          <th width="110px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT i.*, p.nombre, p.apellido, t.nombre as tipo_nombre FROM proyecto as i 
                                      LEFT JOIN persona as p on i.responsable = p.id_persona
                                      LEFT JOIN tipo_proyecto as t on i.tipo = t.id
                                      WHERE i.grupo = $id_rowpg AND i.borrado='0' AND i.estado!='4'";

                            $sql = mysqli_query($con, $query.' ORDER BY id_proyecto ASC');

                            if(mysqli_num_rows($sql) == 0){
                                echo '<tr><td colspan="11">No hay datos.</td></tr>';
                            }else{
                                while($row = mysqli_fetch_assoc($sql)){

                                    echo '
                                    <tr>
                                    <td>
                                    <a data-id="'.$row['id_proyecto'].'" 
                                        data-titulo="'.$row['titulo'].'"
                                        data-categoria="'.$row['categoria'].'"
                                        data-descripcion="'.$row['descripcion'].'"
                                        data-responsable="'.$row['apellido'].' '.$row['nombre'].'"
                                        data-prioridad="'.$row['prioridad'].'"
                                        data-inicio="'.$row['inicio'].'"
                                        data-due_date="'.$row['due_date'].'"
                                        data-estado="'.$row['estado'].'"
                                        data-porcentaje="'.$row['porcentaje'].'"
                                        data-avance="'.$row['avance'].'"
                                        title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                                    </td>';
                                    echo '<td align="center">'.$row['id_proyecto'].'</td>';
                                    echo '<td>'.$row['titulo'].'</td>';
                                    echo '<td>'.$row['tipo_nombre'].'</td>';
                                    echo '<td>';
                                    if($row['categoria'] == '1'){
                                        echo 'Proyecto nuevo';
                                    }
                                    else if ($row['categoria'] == '2' ){
                                        echo 'Proyecto de mejora';
                                    }
                                    else if ($row['categoria'] == '3' ){
                                        echo 'Tarea';
                                    }
                                    echo '</td>';
                                    echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 

                                    if($row['prioridad'] == '1'){
                                        echo '<td>Alta</td>';
                                    }
                                    else if ($row['prioridad'] == '2' ){
                                        echo '<td>Media</td>';
                                    }
                                    else if ($row['prioridad'] == '3' ){
                                        echo '<td>Baja</td>';
                                    }

                                    if($row['estado'] == '1'){
                                        echo '<td><span class="label label-warning">No iniciada</span></td>'; 
                                    }
                                    else if ($row['estado'] == '2' ){
                                        echo '<td><span class="label label-info">En curso</span></td>';
                                    }
                                    else if ($row['estado'] == '3' ){
                                        echo '<td><span class="label label-danger">Aplazada</span></td>';
                                    }
                                    else if ($row['estado'] == '4' ){
                                        echo '<td><span class="label label-success">Completada</span></td>';
                                    } 

                                    echo '<td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-';
                                        if ($row['porcentaje']<='33'){
                                            echo 'danger';
                                        }
                                        else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                            echo 'yellow';
                                        }
                                        else if ($row['porcentaje']>='66'){
                                            echo 'green';
                                        }

                                    echo '" style="width: '.$row['porcentaje'].'%"></div>
                                        </div>
                                        </td>';

                                    echo '<td><span class="badge bg-';

                                    if ($row['porcentaje']<='33'){
                                            echo 'red';
                                        }
                                        else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                            echo 'yellow';
                                        }
                                        else if ($row['porcentaje']>='66'){
                                            echo 'green';
                                        }


                                    echo '">'.$row['porcentaje'].' %</span></td>';

                                    $day=date("d");
                                    $month=date("m");
                                    $year=date("Y");

                                    $due = explode("/", $row['due_date']);
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

                                    if ($row['estado'] !== '4' ){
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

                                    echo '">'.$row['due_date'].'</span></td>';

                                    echo '<td align="center">
                                    <a href="edit_proyecto.php?nik='.$row['id_proyecto'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="proyectos.php?aksi=delete&nik='.$row['id_proyecto'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['titulo'].'?\')" class="btn btn-danger btn-sm ';
                                    if ($rq_sec['edicion']=='0'){
                                            echo 'disabled';
                                    }
                                    echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                    </tr>
                                    ';
                                }
                            }
                            ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
              </div>
              <div class="tab-pane" id="tab_3">
                  <div class="box">
                    <div class="box-header">
                        <div class="col-sm-12" style="text-align:right;">
                            <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-proyecto"><i class="fa fa-list"></i> Nuevo Proyecto</button>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                      <table id="proyectos" class="display" width="100%">
                        <thead>
                        <tr>
                          <th width="1">Ver</th>
                          <th width="2">Nro</th>
                          <th>Titulo</th>
                          <th>Tipo</th>
                          <th>Categoría</th>
                          <th>Responsable</th>
                          <th>Prioridad</th>
                          <th>Estado</th>
                          <th>Avance</th>
                          <th>Porcentaje</th>
                          <th width="2">Vencimiento</th>
                          <th width="110px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT i.*, p.nombre, p.apellido, t.nombre as tipo_nombre FROM proyecto as i 
                                      LEFT JOIN persona as p on i.responsable = p.id_persona
                                      LEFT JOIN tipo_proyecto as t on i.tipo = t.id
                                      WHERE i.borrado='0' AND i.estado!='4'
                                      ORDER BY id_proyecto ASC";

                            $sql = mysqli_query($con, $query);

                            if(mysqli_num_rows($sql) == 0){
                                echo '<tr><td colspan="11">No hay datos.</td></tr>';
                            }else{
                                while($row = mysqli_fetch_assoc($sql)){

                                    echo '
                                    <tr>
                                    <td>
                                    <a data-id="'.$row['id_proyecto'].'" 
                                        data-titulo="'.$row['titulo'].'"
                                        data-categoria="'.$row['categoria'].'"
                                        data-descripcion="'.$row['descripcion'].'"
                                        data-responsable="'.$row['apellido'].' '.$row['nombre'].'"
                                        data-prioridad="'.$row['prioridad'].'"
                                        data-inicio="'.$row['inicio'].'"
                                        data-due_date="'.$row['due_date'].'"
                                        data-estado="'.$row['estado'].'"
                                        data-porcentaje="'.$row['porcentaje'].'"
                                        data-avance="'.$row['avance'].'"
                                        title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                                    </td>';
                                    echo '


                                    <td align="center">'.$row['id_proyecto'].'</td>';


                                    echo '<td>'.$row['titulo'].'</td>';
                                    echo '<td>'.$row['tipo_nombre'].'</td>';
                                    echo '<td>';
                                    if($row['categoria'] == '1'){
                                        echo 'Proyecto nuevo';
                                    }
                                    else if ($row['categoria'] == '2' ){
                                        echo 'Proyecto de mejora';
                                    }
                                    else if ($row['categoria'] == '3' ){
                                        echo 'Tarea';
                                    }
                                    echo '
                                    </td>
                                    <td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 


                                    if($row['prioridad'] == '1'){
                                        echo '<td>Alta</td>';
                                    }
                                    else if ($row['prioridad'] == '2' ){
                                        echo '<td>Media</td>';
                                    }
                                    else if ($row['prioridad'] == '3' ){
                                        echo '<td>Baja</td>';
                                    }

                                    if($row['estado'] == '1'){
                                        echo '<td><span class="label label-warning">No iniciada</span></td>'; 
                                    }
                                    else if ($row['estado'] == '2' ){
                                        echo '<td><span class="label label-info">En curso</span></td>';
                                    }
                                    else if ($row['estado'] == '3' ){
                                        echo '<td><span class="label label-danger">Aplazada</span></td>';
                                    }
                                    else if ($row['estado'] == '4' ){
                                        echo '<td><span class="label label-success">Completada</span></td>';
                                    } 

                                    echo '<td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-';
                                        if ($row['porcentaje']<='33'){
                                            echo 'danger';
                                        }
                                        else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                            echo 'yellow';
                                        }
                                        else if ($row['porcentaje']>='66'){
                                            echo 'green';
                                        }

                                    echo '" style="width: '.$row['porcentaje'].'%"></div>
                                        </div>
                                        </td>
                                        <td><span class="badge bg-';

                                    if ($row['porcentaje']<='33'){
                                            echo 'red';
                                        }
                                        else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                            echo 'yellow';
                                        }
                                        else if ($row['porcentaje']>='66'){
                                            echo 'green';
                                        }


                                    echo '">'.$row['porcentaje'].' %</span></td>';

                                    $day=date("d");
                                    $month=date("m");
                                    $year=date("Y");

                                    $due = explode("/", $row['due_date']);
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

                                    if ($row['estado'] !== '4' ){
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

                                    echo '">'.$row['due_date'].'</span></td>';

                                    echo '
                                    <td align="center">
                                    <a href="edit_proyecto.php?nik='.$row['id_proyecto'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="proyectos.php?aksi=delete&nik='.$row['id_proyecto'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['titulo'].'?\')" class="btn btn-danger btn-sm ';
                                    if ($rq_sec['edicion']=='0'){
                                            echo 'disabled';
                                    }
                                    echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                    </tr>
                                    ';
                                }
                            }
                            ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
              </div>
              <div class="tab-pane" id="tab_4">
                <div class="box">
                  <div class="box-body">
                    <table id="completados" class="display" width="100%">
                      <thead>
                      <tr>
                        <th width="1">Ver</th>
                        <th width="2">Nro</th>
                        <th>Titulo</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Responsable</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Avance</th>
                        <th>Porcentaje</th>
                        <th width="2">Vencimiento</th>
                        <th width="110px">Acciones</th>
                      </tr>
                      </thead>
                      <tbody>
                          <?php
                          $query = "SELECT i.*, p.nombre, p.apellido, t.nombre as tipo_nombre FROM proyecto as i 
                                    LEFT JOIN persona as p on i.responsable = p.id_persona
                                    LEFT JOIN tipo_proyecto as t on i.tipo = t.id
                                    WHERE i.borrado='0' AND i.estado='4'
                                    ORDER BY id_proyecto ASC";

                          $sql = mysqli_query($con, $query);

                          if(mysqli_num_rows($sql) == 0){
                              echo '<tr><td colspan="8">No hay datos.</td></tr>';
                          }else{
                              while($row = mysqli_fetch_assoc($sql)){

                                  echo '
                                  <tr>
                                  <td>
                                  <a data-id="'.$row['id_proyecto'].'" 
                                      data-titulo="'.$row['titulo'].'"
                                      data-categoria="'.$row['categoria'].'"
                                      data-tipo="'.$row['tipo'].'"
                                      data-descripcion="'.$row['descripcion'].'"
                                      data-responsable="'.$row['apellido'].' '.$row['nombre'].'"
                                      data-prioridad="'.$row['prioridad'].'"
                                      data-inicio="'.$row['inicio'].'"
                                      data-due_date="'.$row['due_date'].'"
                                      data-estado="'.$row['estado'].'"
                                      data-porcentaje="'.$row['porcentaje'].'"
                                      data-avance="'.$row['avance'].'"
                                      title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                                  </td>';
                                  echo '


                                  <td align="center">'.$row['id_proyecto'].'</td>';


                                  echo '<td>'.$row['titulo'].'</td>';
                                  echo '<td>'.$row['tipo_nombre'].'</td>';
                                  echo '<td>';
                                  if($row['categoria'] == '1'){
                                      echo 'Proyecto nuevo';
                                  }
                                  else if ($row['categoria'] == '2' ){
                                      echo 'Proyecto de mejora';
                                  }
                                  else if ($row['categoria'] == '3' ){
                                      echo 'Tarea';
                                  }
                                  echo '
                                  </td>
                                  <td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 


                                  if($row['prioridad'] == '1'){
                                      echo '<td>Alta</td>';
                                  }
                                  else if ($row['prioridad'] == '2' ){
                                      echo '<td>Media</td>';
                                  }
                                  else if ($row['prioridad'] == '3' ){
                                      echo '<td>Baja</td>';
                                  }

                                  if($row['estado'] == '1'){
                                      echo '<td><span class="label label-warning">No iniciada</span></td>'; 
                                  }
                                  else if ($row['estado'] == '2' ){
                                      echo '<td><span class="label label-info">En curso</span></td>';
                                  }
                                  else if ($row['estado'] == '3' ){
                                      echo '<td><span class="label label-danger">Aplazada</span></td>';
                                  }
                                  else if ($row['estado'] == '4' ){
                                      echo '<td><span class="label label-success">Completada</span></td>';
                                  } 

                                  echo '<td>
                                      <div class="progress progress-xs">
                                          <div class="progress-bar progress-bar-';
                                      if ($row['porcentaje']<='33'){
                                          echo 'danger';
                                      }
                                      else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                          echo 'yellow';
                                      }
                                      else if ($row['porcentaje']>='66'){
                                          echo 'green';
                                      }

                                  echo '" style="width: '.$row['porcentaje'].'%"></div>
                                      </div>
                                      </td>
                                      <td><span class="badge bg-';

                                  if ($row['porcentaje']<='33'){
                                          echo 'red';
                                      }
                                      else if ($row['porcentaje']<='66' && $row['porcentaje']>'33'){
                                          echo 'yellow';
                                      }
                                      else if ($row['porcentaje']>='66'){
                                          echo 'green';
                                      }


                                  echo '">'.$row['porcentaje'].' %</span></td>';
                                  
                                  if ($row['due_date']) {

                                    $day=date("d");
                                    $month=date("m");
                                    $year=date("Y");
  
                                    $due = explode("/", $row['due_date']);
                                    $due_d = $due[0];
                                    $due_m = $due[1];
                                    $due_y = $due[2];
                                    $ok=0;
  
                                    $dayofy = (($month * 30)+($day));
                                    $dayofdue = (($due_m * 30)+($due_d));
  
                                    if ($due_y == $year){
                                        if ($dayofy < $dayofdue){
                                            $ok=1;
                                        }
                                        else if ($dayofy == $dayofdue){
                                            $ok=2;
                                        }
                                    }else if ($due_y > $year){ $ok=1;}
  
                                    echo '<td><span class="badge bg-';
  
                                    if ($row['estado'] !== '4' ){
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
  
                                    echo '">'.$row['due_date'].'</span></td>';

                                  } else {
                                    echo '<td></td>';
                                  }
                                  echo '
                                  <td align="center">
                                  <a href="edit_proyecto.php?nik='.$row['id_proyecto'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                  <a href="proyectos.php?aksi=delete&nik='.$row['id_proyecto'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['titulo'].'?\')" class="btn btn-danger btn-sm ';
                                  if ($rq_sec['edicion']=='0'){
                                          echo 'disabled';
                                  }
                                  echo '"><i class="glyphicon glyphicon-trash"></i></a>
                                  </td>
                                  </tr>
                                  ';
                              }
                          }
                          ?>
                      </tbody>
                      <tfoot>
                      <tr>
                        <th width="1">Ver</th>
                        <th width="2">Nro</th>
                        <th>Titulo</th>
                        <th>Categoría</th>
                        <th>Responsable</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Avance</th>
                        <th>Porcentaje</th>
                        <th>Vencimiento</th>
                        <th width="110px">Acciones</th>
                      </tr>
                      </tfoot>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
              <div class="tab-pane" id="tab_5">
                  <div class="row">
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                          <div class="inner">
                            <h3><?php
                                      $query_count_vencidas = "SELECT due_date FROM proyecto
                                                              WHERE borrado='0' AND (estado='1' OR estado='2')";
                                      $count_vencidas = mysqli_query($con, $query_count_vencidas);
                                
                                      $day=date("d");
                                      $month=date("m");
                                      $year=date("Y");
                                      $countv = 0;
                                
                                      while($rowcv = mysqli_fetch_array($count_vencidas)){
                                          $due = explode("/", $rowcv['due_date']);
                                          $due_d = $due[0];
                                          $due_m = $due[1];
                                          $due_y = $due[2];
                                          

                                          $dayofy = (($month * 30)+($day));
                                          $dayofdue = (($due_m * 30)+($due_d));
                                          
                                            if ($due_y <= $year){
                                              if ($dayofy > $dayofdue){
                                                  $countv++; }
                                              }
                                          
                                      }
                                      echo '
                                      <td> ' . $countv . ' </td>
                                      <td>';
                                      ?></h3>

                            <p>Proyectos vencidos</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-thumbs-down"></i>
                          </div>
                          <a class="small-box-footer"><?php $pv=round((($countv) * 100) / ($rowtp), PHP_ROUND_HALF_UP); echo $pv . " % del total de los proyectos"; ?></a>
                        </div>
                      </div>
                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-orange">
                        <div class="inner">
                          <h3><?php
                                    $query_count_no = "SELECT 1 as total FROM proyecto WHERE borrado='0' AND estado='1'";
                                    $count_no = mysqli_query($con, $query_count_no);

                                    echo '
                                    <td> ' . mysqli_num_rows($count_no) . ' </td>
                                    <td>';
                                    ?></h3>

                          <p>Proyectos NO iniciados</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-thumbs-down"></i>
                        </div>
                      <a class="small-box-footer"><?php $pno=round((((mysqli_num_rows($count_no)) * 100) / $rowtp), PHP_ROUND_HALF_UP); echo $pno . " % del total de los proyectos"; ?></a>
                      </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-blue">
                        <div class="inner">
                          <h3><?php
                                    $query_count_si = "SELECT 1 as total FROM proyecto WHERE borrado='0' AND estado='2'";
                                    $count_si = mysqli_query($con, $query_count_si);

                                    echo '
                                    <td> ' . mysqli_num_rows($count_si) . ' </td>
                                    <td>';
                                    ?></h3>

                          <p>Proyectos en curso</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-gears"></i>
                        </div>
                      <a class="small-box-footer"><?php $psi=round(((mysqli_num_rows($count_si)) * 100) / ($rowtp), PHP_ROUND_HALF_UP); echo $psi . " % del total de los proyectos"; ?></a>
                      </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3><?php
                                    $query_count_comp = "SELECT 1 as total FROM proyecto WHERE borrado='0' AND estado='4'";
                                    $count_comp = mysqli_query($con, $query_count_comp);

                                    echo '
                                    <td> ' . mysqli_num_rows($count_comp) . ' </td>
                                    <td>';
                                    ?></h3>
                        <p>Proyectos Completados</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-thumbs-up"></i>
                        </div>
                      <a class="small-box-footer"><?php $psi=round(((mysqli_num_rows($count_comp)) * 100) / ($rowtp), PHP_ROUND_HALF_UP); echo $psi . " % del total de los proyectos"; ?></a>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6 col-xs-6">
                          <div class="box box-primary">
                              <div class="box-header with-border">
                                <h3 class="box-title">Asignación de proyectos</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                  </button>
                                  
                                </div>
                              </div>
                              <div class="box-body">
                                <div class="chart">
                                  <canvas id="graphCanvas1" style="height:300px"></canvas>
                                  <script>
                                      $(document).ready(function () {
                                          showGraph1();
                                      });


                                      function showGraph1()
                                      {
                                          {
                                              $.post("getProyResp.php",
                                              function (data1)
                                              {
                                                  var name1 = [];
                                                  var marks = [];
                                                  
                                                  parsedData1 = JSON.parse(data1);
                                                  
                                                  for (var i in parsedData1) {
                                                      name1.push(parsedData1[i].persona);
                                                      marks.push(parsedData1[i].total);
                                                  }
                                                  console.log(name1);
                                                  
                                                  var chartdata1 = {
                                                      labels: name1,
                                                      datasets: [
                                                          {
                                                              label: 'Proyectos',
                                                              backgroundColor: '#003366',
                                                              borderColor: '#003366',
                                                              hoverBackgroundColor: '#CCCCCC',
                                                              hoverBorderColor: '#666666',
                                                              data: marks
                                                          }
                                                      ]
                                                  };
                                                  
                                                  var options1 = {
                                                      responsive: true,
                                                      title: {
                                                          display: false,
                                                          position: "top",
                                                          text: "Bar Graph",
                                                          fontSize: 18,
                                                          fontColor: "#111"
                                                      },
                                                      legend: {
                                                          display: false,
                                                          position: "bottom",
                                                          labels: {
                                                              fontColor: "#333",
                                                              fontSize: 16
                                                          }
                                                      },
                                                      scales: {
                                                          yAxes: [{
                                                              ticks: {
                                                                  min: 0
                                                              }
                                                          }]
                                                      }
                                                  };

                                                  var graphTarget1 = $("#graphCanvas1");

                                                  var barGraph1 = new Chart(graphTarget1, {
                                                      type: 'bar',
                                                      data: chartdata1,
                                                      options: options1
                                                  });
                                              });
                                          }
                                      } 
                                  </script>  
                              </div>
                              </div>
                      
                          </div> 
                      </div>
                      <div class="col-md-6 col-xs-6">
                          <div class="box box-primary">
                              <div class="box-header with-border">
                                <h3 class="box-title">Estado de proyectos</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                  </button>
                                  
                                </div>
                              </div>
                              <div class="box-body">
                                <div class="chart">
                                  <canvas id="graphCanvas2" style="height:300px"></canvas>
                                  <script>
                                      $(document).ready(function () {
                                          showGraph();
                                      });


                                      function showGraph()
                                      {
                                          {
                                              $.post("getProyRespStat.php",
                                              function (data)
                                              {
                                                  var name = [];
                                                  var data1 = [];
                                                  var data2 = [];
                                                  var data3 = [];
                                                  var data4 = [];
                                                  
                                                  parsedData = JSON.parse(data);
                                                  
                                                  for (var i in parsedData) {
                                                      name.push(parsedData[i].persona);
                                                      data1.push(parsedData[i].completado);
                                                      data2.push(parsedData[i].aplazado);
                                                      data3.push(parsedData[i].en_curso);
                                                      data4.push(parsedData[i].no_iniciado);
                                                  }
                                                  var chartdata = {
                                                      labels: name,
                                                      datasets: [
                                                        {
                                                              label: 'Completado',
                                                              data: data1,
                                                              backgroundColor: '#009933'
                                                            },
                                                          {
                                                              label: 'En Curso',
                                                              data: data3,
                                                              backgroundColor: '#3366ff'
                                                          },
                                                          {
                                                              label: 'Aplazado',
                                                              data: data2,
                                                              backgroundColor: '#cc0000'
                                                          },
                                                          {
                                                              label: 'No Iniciado',
                                                              data: data4,
                                                              backgroundColor: '#ff9900'
                                                          }
                                                      ]
                                                  };
                                                  var options = {
                                                      responsive: true,
                                                      title: {
                                                          display: false,
                                                          position: "top",
                                                          text: "Bar Graph",
                                                          fontSize: 18,
                                                          fontColor: "#111"
                                                      },
                                                      legend: {
                                                          display: true,
                                                          position: "top",
                                                          labels: {
                                                              fontColor: "#333",
                                                              fontSize: 16
                                                          }
                                                      },
                                                      scales: {
                                                              xAxes: [{ stacked: true }],
                                                              yAxes: [{ stacked: true }]
                                                            }
                                                  };

                                                  var graphTarget = $("#graphCanvas2");

                                                  var barGraph = new Chart(graphTarget, {
                                                      type: 'bar',
                                                      data: chartdata,
                                                      options: options
                                                  });
                                              });
                                          }
                                      } 
                                  </script>  
                              </div>
                              </div>
                      
                          </div> 
                      </div>
                  </div>
              </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <div class="modal fade" id="modal-proyecto">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title">Proyectos >> Nuevo Proyecto</h2>
                      </div>
                      <div class="modal-body">
                        <div class="box box-primary">
                    <!-- /.box-header -->
                    <?php

                        if(isset($_POST['Add'])){
                            $titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));
                            $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
                            $responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));
                            $categoria = mysqli_real_escape_string($con,(strip_tags($_POST["categoria"],ENT_QUOTES)));
                            $prioridad = mysqli_real_escape_string($con,(strip_tags($_POST["prioridad"],ENT_QUOTES)));
                            $inicio = mysqli_real_escape_string($con,(strip_tags($_POST["inicio"],ENT_QUOTES)));
                            $due_date = mysqli_real_escape_string($con,(strip_tags($_POST["due_date"],ENT_QUOTES)));
                            $estado = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));
                            $porcentaje = mysqli_real_escape_string($con,(strip_tags($_POST["porcentaje"],ENT_QUOTES)));
                            $avance = mysqli_real_escape_string($con,(strip_tags($_POST["avance"],ENT_QUOTES)));
                            $grupo = mysqli_real_escape_string($con,(strip_tags($_POST["grupo"],ENT_QUOTES)));
                            $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));

                            $insert_proyecto = mysqli_query($con, "INSERT INTO proyecto (titulo, descripcion, responsable, categoria, prioridad, inicio, due_date, grupo, creado, usuario, tipo) VALUES ('$titulo', '$descripcion', '$responsable', '$categoria', '$prioridad', '$inicio', '$due_date', '$grupo', NOW(), '$user', '$tipo')") or die(mysqli_error());	

                            $lastInsert = mysqli_insert_id($con);
                            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                                       VALUES ('1', '3', '$lastInsert', now(), '$user', '$titulo')") or die(mysqli_error());
                            unset($_POST);
                            if($insert_proyecto){
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
                          <label for="titulo">Titulo</label>
                          <input type="text" class="form-control" name="titulo" placeholder="Titulo" required>
                        </div>
                        <div class="form-group">
                          <label for="descripcion">Descripción</label>
                          <textarea class="form-control" rows="3" name="descripcion" placeholder="Descripción ..." required></textarea>
                        </div>
                        <div class="form-group">
                          <label>Responsable</label>
                          <select name="responsable" class="form-control">
                                <?php
                                        $personasn = mysqli_query($con, "SELECT * FROM persona where gerencia='1'");
                                        while($rowps = mysqli_fetch_array($personasn)){
                                            echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                            }
                                ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Grupo</label>
                          <select name="grupo" class="form-control">
                                <?php
                                        $grupos = mysqli_query($con, "SELECT * FROM grupo");
                                        while($rowps = mysqli_fetch_array($grupos)){
                                            echo "<option value='". $rowps['id_grupo'] . "'>" .$rowps['nombre'] . "</option>";										
                                            }
                                ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Tipo</label>
                          <select name="tipo" class="form-control">
                                <?php
                                        $tipos = mysqli_query($con, "SELECT * FROM tipo_proyecto");
                                        while($rowt = mysqli_fetch_array($tipos)){
                                            echo "<option value='". $rowt['id'] . "'>" .$rowt['nombre'] . "</option>";										
                                            }
                                ?>
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Categoría</label>
                          <select name="categoria" class="form-control">
                            <option value='1'>Proyecto nuevo</option>
                            <option value='2'>Proyecto de mejora</option>
                            <option value='3'>Tarea</option>
                           </select>
                        </div>
                        <div class="form-group">
                                <label>Prioridad</label>
                                <select name="prioridad" class="form-control">
                                    <option value='1'>Alta</option>
                                    <option value='2'>Media</option>
                                    <option value='3'>Baja</option>
                                </select>
                        </div>
                        <div class="form-group">
                        <label>Inicio</label>
                        <div class="input-group date" data-provide="datepicker1">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" name="inicio" id="datepicker1">
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Fecha de vencimiento</label>
                        <div class="input-group date" data-provide="datepicker2">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" name="due_date" id="datepicker2">
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
<div id="ver-itemDialog" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Proyectos >> Ver Proyecto</h2>
            </div>
            <div class="box box-primary">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titulo">Titulo</label>
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
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-3">
                            <label for="categoria">Categoría</label>
                            <input type="text" class="form-control" name="categoria" id="categoria" value="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <label for="prioridad">Prioridad</label>
                            <input type="text" class="form-control" name="prioridad" id="prioridad" value="" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-3">
                            <label for="inicio">Inicio</label>
                            <input type="text" class="form-control" name="inicio" id="inicio" value="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <label for="due_date">Fecha de vencimiento</label>
                            <input type="text" class="form-control" name="due_date" id="due_date" value="" readonly> 
                        </div>
                      </div>
                    </div>
                    <div class="container" >
                      <div class="row">
                        <div class="col-sm-3">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-control" name="estado" id="estado" value="" readonly>
                         </div>
                        <div class="col-sm-3">
                            <label for="porcentaje">Porcentaje de avance</label>
                            <input type="text" class="form-control" name="porcentaje" id="porcentaje" value="" readonly>
                        </div>
                      </div>
                    </div><br>
                   <div class="form-group">
                        <label for="avance">Avance</label>
                        <textarea class="form-control" rows="3" name="avance" id="avance" value="" readonly></textarea>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
            </div>	
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	
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
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

      
<script>
  $(function () {
    $('#proyectos').DataTable({
      'language': { 'emptyTable': 'No hay proyectos' },
      'paging'      : false,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,

    })
  })
</script>
<script>
  $(function () {
    $('#mis_proyectos').DataTable({
      'language': { 'emptyTable': 'No hay proyectos' },
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,
    })
  })
</script>
<script>
  $(function () {
    $('#mi_grupo').DataTable({
      'language': { 'emptyTable': 'No hay proyectos' },
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,
    })
  })
</script>  
<script>
  $(function () {
    $('#completados').DataTable({
      'language': { 'emptyTable': 'No hay proyectos' },
      'paging'      : false,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,

    })
  })
</script>
<script>
    window.onload = function() {
        history.replaceState("", "", "proyectos.php");
    }
</script>
<script>
$(function(){
  $(".ver-itemDialog").click(function(){
    $('#itemId').val($(this).data('id'));
	$('#titulo').val($(this).data('titulo'));
	$('#descripcion').val($(this).data('descripcion'));
	$('#responsable').val($(this).data('responsable'));
    $('#inicio').val($(this).data('inicio'));
    $('#due_date').val($(this).data('due_date'));
    $('#porcentaje').val($(this).data('porcentaje')+" %");
    $('#avance').val($(this).data('avance'));
   

	if($(this).data('categoria') == '1') {
		$('#categoria').val('Proyecto Nuevo')}
	else if($(this).data('categoria') == '2'){
		$('#categoria').val('Proyecto de Mejora')}
	else if($(this).data('categoria') == '3'){
		$('#categoria').val('Tarea')};
    
    if($(this).data('prioridad') == '1') {
		$('#prioridad').val('Alta')}
	else if($(this).data('prioridad') == '2'){
		$('#prioridad').val('Media')}
	else if($(this).data('prioridad') == '3'){
		$('#prioridad').val('Baja')};
      
    if($(this).data('estado') == '1') {
		$('#estado').val('No Iniciada')}
	else if($(this).data('estado') == '2'){
		$('#estado').val('En Curso')}
	else if($(this).data('estado') == '3'){
		$('#estado').val('Aplazada')}
    else if($(this).data('estado') == '4'){
		$('#estado').val('Completada')};
	console.log("llega");
	$("#ver-itemDialog").modal("show");
	
  });
});
</script>
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

  })
</script>
<script>
function myFunction() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("proyectos");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
</script>
<script>
$('a[data-toggle="tab"]').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    var id = $(e.target).attr("href");
    localStorage.setItem('selectedTab', id)
});

var selectedTab = localStorage.getItem('selectedTab');
if (selectedTab != null) {
    $('a[data-toggle="tab"][href="' + selectedTab + '"]').tab('show');
}
</script>

</body>
</html>