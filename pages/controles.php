<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

$page_title="Controles";
$user=$_SESSION['usuario'];

if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
	$cek = mysqli_query($con, "SELECT * FROM controles WHERE id_control='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $titulo = $cekd['titulo'];
    
    if(mysqli_num_rows($cek) == 0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
	}else{
		//Elimino Control
		
        $delete_control = mysqli_query($con, "UPDATE controles SET borrado='1' WHERE id_control='$nik'");
        $delete_referencia = mysqli_query($con, "UPDATE referencias SET borrado='1' WHERE id_control='$nik'");
        
        $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('3', '5', '$nik', now(), '$user', '$titulo')") or die(mysqli_error());
		if(!$delete_control){
			$_SESSION['formSubmitted'] = 9;
       }
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
                    Gestión de Controles
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
                                        <h2 class="box-title">Listado de Controles</h2>
                                    </div>
                                    <div class="col-sm-6" style="text-align:right;">
                                        <a href="cal_controles.php" class="btn btn-primary"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Calendario</a>
                                        <button type="button" class="btn-sm btn-primary" data-toggle="modal"
                                            data-target="#modal-control"><i class="fa fa-retweet"></i> Nuevo
                                            Control</button>
                                    </div>
                                </div>
                                <!-- MODAL CONTROL -->
                                <div class="modal fade" id="modal-control">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Controles >> Nuevo Control</h2>
                                            </div>
                                            <div class="modal-body">
                                                <div class="box box-primary">
                                                    <!-- /.box-header -->
                                                    <?php
                                                    if(isset($_POST['add'])){
                                                      $titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));//Escanpando caracteres
                                                      $contenido = mysqli_real_escape_string($con,(strip_tags($_POST["contenido"],ENT_QUOTES)));//Escanpando caracteres
                                                      $responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $periodo = mysqli_real_escape_string($con,(strip_tags($_POST["periodo"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $mesInicio = mysqli_real_escape_string($con,(strip_tags($_POST["mesinicio"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $criticidad = mysqli_real_escape_string($con,(strip_tags($_POST["criticidad"],ENT_QUOTES)));//Escanpando caracteres 
                                                              
                                                      $ano = date("Y");

                                                      //Inserto Control
                                                      $insert_control = mysqli_query($con, "INSERT INTO controles (titulo, contenido, creado, ano, responsable, usuario, periodo, status, tipo, mesinicio, criticidad) VALUES('$titulo','$contenido', NOW(), '$ano','$responsable', '$user','$periodo', '3', '$tipo', '$mesInicio', '$criticidad')") or die(mysqli_error());	

                                                          //Ultimo Insert
                                                        $last = $con->insert_id;
                                                        //For Inserto Referencias
                                                        $nro_referencia = 1;
                                                        // Mes Actual
                                                        // $mes = date("m");
                                                        $mes = (int)$mesInicio;
                                                        $ano = date("Y");
                                                        while ($mes <= 12) {
                                                          $insert_ref = mysqli_query($con, "INSERT INTO referencias (id_control, mes, ano, nro_referencia)
                                                            VALUES('$last', '$mes', '$ano','$nro_referencia')") or die (mysqli_error());	
                                                          $nro_referencia++;
                                                          $mes = $mes + $periodo;
                                                                  }
                                                                  $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                                                                    VALUES ('1', '5', '$last', now(), '$user', '$titulo')") or die(mysqli_error());
                                                                  unset($_POST);
                                                                  if($insert_control){
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
                                                                <label for="titulo">Título</label>
                                                                <input type="text" class="form-control" name="titulo"
                                                                    placeholder="Título del control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="descripcion">Contenido</label>
                                                                <textarea class="form-control" rows="3" name="contenido"
                                                                    placeholder="Contenido del control ..."
                                                                    required></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Criticidad</label>
                                                                <select name="criticidad" class="form-control">
                                                                    <option value='2'>No Crítico</option>										
                                                                    <option value='1'>Semi Crítico</option>										
                                                                    <option value='0'>Crítico</option>										
                                                                </select>
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
                                                                    <option value='1'>Preventivo y/o disuasivo</option>
                                                                    <option value='2'>Detectivo y/o correctivo</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>Mes de Inicio</label>
                                                                        <select name="mesinicio" class="form-control">
                                                                            <option value='1'>Enero</option>
                                                                            <option value='2'>Febrero</option>
                                                                            <option value='3'>Marzo</option>
                                                                            <option value='4'>Abril</option>
                                                                            <option value='5'>Mayo</option>
                                                                            <option value='6'>Junio</option>
                                                                            <option value='7'>Julio</option>
                                                                            <option value='8'>Agosto</option>
                                                                            <option value='9'>Septiembre</option>
                                                                            <option value='10'>Octubre</option>
                                                                            <option value='11'>Noviembre</option>
                                                                            <option value='12'>Diciembre</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>Periodo</label>
                                                                        <select name="periodo" class="form-control">
                                                                            <option value='1'>Mensual</option>
                                                                            <option value='3'>Trimestral</option>
                                                                            <option value='6'>Semestral</option>
                                                                            <option value='12'>Anual</option>
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="col-sm-3">
                                                                    <input type="submit" name="add"
                                                                        class="btn  btn-raised btn-success"
                                                                        value="Guardar datos">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <button type="button"
                                                                        class="btn btn-default pull-left"
                                                                        data-dismiss="modal">Cancelar</button>
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
                                <!-- FIN MODAL CONTROL -->
                                <!-- MODAL ACTIVO -->
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
                                <!-- FIN MODAL ACTIVO -->
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="controles" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="1">Ver</th>
                                                <th width="2">Nro</th>
                                                <th>Período</th>
                                                <th>Título</th>
                                                <th>Contenido</th>
                                                <th>Pendiente</th>
                                                <th>Criticidad</th>
                                                <th>Responsable</th>
                                                <th>Gerencia</th>
                                                <th width="110px">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query = "SELECT i.*, p.nombre, p.apellido, g.nombre as gerencia ,
                                                        (
                                                            SELECT count(*) as total
                                                            FROM controles cp
                                                            INNER JOIN referencias ON cp.id_control = referencias.id_control
                                                            WHERE cp.id_control = i.id_control 
                                                            and STR_TO_DATE(CONCAT('01,',referencias.mes , ',', referencias.ano) , '%d,%m,%Y') <= CURRENT_DATE()
                                                            and cp.borrado = 0
                                                            and referencias.borrado = 0
                                                            AND referencias.status='2'
                                                            ) as qpendientes
                                                        FROM controles as i 
                                                        LEFT JOIN persona as p on responsable = p.id_persona
                                                        LEFT JOIN gerencia as g on p.gerencia = g.id_gerencia
                                                    WHERE i.borrado='0'";
                                                // AGREGO EL FILTRO DE GERENCIA DEL USUARIO=CIBERSEGURIDAD O LA GERENCIA DEL REFERENTE
                                                if ( $per_id_gerencia != 1) {
                                                    $query = $query . " AND p.gerencia = $per_id_gerencia ";
                                                }
                                                $sql = mysqli_query($con, $query.' ORDER BY id_control ASC');

                                                if(mysqli_num_rows($sql) == 0){
                                                    echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                                }else{
                                                    $no = 1;
                                                while($row = mysqli_fetch_assoc($sql)){
                                                  
                                                  echo '<tr><td><a href="control.php?nik='.$row['id_control'].'"><span class="fa fa-retweet" aria-hidden="true"></span></a></td>';
                                                  echo '<td align="center">'.$row['id_control'].'</td>';

                                                  if($row['periodo'] == '1'){
                                                    echo '<td>Mensual</td>';
                                                  }
                                                  else if ($row['periodo'] == '3' ){
                                                    echo '<td>Trimestral</td>';
                                                  }
                                                  else if ($row['periodo'] == '6' ){
                                                    echo '<td>Semestral</td>';
                                                  }
                                                  else if ($row['periodo'] == '12' ){
                                                    echo '<td>Anual</td>';
                                                  }
                                                  echo '<td>'.$row['titulo'].'</td>';
                                                  echo '<td>'.$row['contenido'].'</td>'; 
                                                  
                                                  if ($row['qpendientes'] > 0) {
                                                      echo '<td><span class="label label-danger">Pendiente</span></td>'; 
                                                  } else {
                                                      echo '<td></td>'; 
                                                  }

                                                  if ($row['criticidad'] == '0') {
                                                      echo '<td><span class="label label-danger">Crítico</span></td>'; 
                                                  } else if ($row['criticidad'] == '1') {
                                                      echo '<td><span class="label label-warning">Semi Crítico</span></td>'; 
                                                  } else {
                                                      echo '<td>No Crítico</td>'; 
                                                  }

                                                  echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                                                  echo '<td>'.$row['gerencia'].'</td>'; 
                                                  echo '<td align="center">
                                                          <a href="edit_control.php?nik='.$row['id_control'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                                          <a href="controles.php?aksi=delete&nik='.$row['id_control'].'" title="Borrar datos" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['titulo'].'?\')" class="btn btn-danger btn-sm ';
                                                                if ($rq_sec['edicion']=='0'){
                                                                        echo 'disabled';
                                                                }
                                                  echo '"><i class="glyphicon glyphicon-trash"></i></a></td>
                                                  </tr>';
                                                  $no++;
                                                }
                                              }
                                              ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th width="1">Ver</th>
                                                <th width="2">Nro</th>
                                                <th>Período</th>
                                                <th>Título</th>
                                                <th>Contenido</th>
                                                <th>Criticidad</th>
                                                <th>Pendiente</th>
                                                <th>Responsable</th>
                                                <th>Gerencia</th>
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
        $(function() {
            $('#controles').DataTable({
                'paging': true,
                'pageLength': 20,
                'lengthChange': false,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true,
                'dom': 'frtipB',
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
            })
        })
        </script>
        <script>
        window.onload = function() {
            history.replaceState("", "", "controles.php");
        }
        </script>

        <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>