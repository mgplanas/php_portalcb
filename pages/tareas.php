<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$user=$_SESSION['usuario'];
//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['soc']=='0'){
	header('Location: ../site.php');
}

//Alert icons data on top bar

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

$hoy=date("d.m.y");			
		
?>
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
<!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
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
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bolt"></i>
              <span class="label label-success"><?php echo $rowr; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo $rowr; ?> riesgos asignados</li>
              <li>
                <!-- inner menu: contains the messages -->
                
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="riesgos.php">Gestionar los riesgos</a></li>
            </ul>
          </li>
          <!-- /.messages-menu -->

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-archive"></i>
              <span class="label label-warning"><?php echo $rowa; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Eres responsable de <?php echo $rowa; ?> activos</li>
              
              <li class="footer"><a href="activos.php">Ver Activos</a></li>
            </ul>
          </li>
          <!-- Tasks Menu -->
          <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-retweet"></i>
              <span class="label label-danger"><?php echo $rowc; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo $rowc; ?> controles asignados</li>
              
              <li class="footer">
                <a href="controles.php">Gestionar controles</a>
              </li>
            </ul>
          </li>
		  <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-list"></i>
              <span class="label label-info"><?php echo $rowcp; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo $rowcp; ?> proyectos asignados</li>
              
              <li class="footer">
                <a href="controles.php">Gestionar proyectos</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="../dist/img/icon_user.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $_SESSION['usuario']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="../dist/img/icon_user.png" class="img-circle" alt="User Image">
				<p>
                   <?php echo ''.$rowp['nombre']. ' '.$rowp['apellido']. '';?>
                  <small><?php echo ''.$rowp['cargo']. '';?></small>
                </p>
              </li>
           <!-- Menu Footer-->
              <li class="user-footer">
               <div class="pull-right">
                  <a href="../out.php" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button 
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../dist/img/icon_user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p> 
			<?php echo ''.$rowp['nombre']. '';?><br>
			<?php echo ''.$rowp['apellido']. '';?>
		  </p>
          <!-- Status
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <!-- Optionally, you can add icons to the links -->
        <li><a href="../site.php"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
        <li><a href="activos.php"><i class="fa fa-archive"></i> <span>Activos</span></a></li>
		<li><a href="controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
		<li><a href="iso27k.php"><i class="fa fa-crosshairs"></i> <span>Ítems ISO 27001</span></a></li>
        <li><a href="mejoras.php"><i class="fa fa-refresh"></i> <span>Mejora Continua</span></a></li>
		<li><a href="riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
        <?php if ($rq_sec['admin']=='1' OR $rq_sec['soc']=='1'){
        echo '<li><a href="calendario.php"><i class="fa fa-calendar"></i> <span>Calendario</span></a></li>';
        echo '<li><a href="novedades.php"><i class="fa fa-envelope"></i> <span>Novedades</span></a></li>';
        echo '<li class="active"><a href="tareas.php"><i class="fa fa-calendar-o"></i> <span>Tareas Programadas</span></a></li>';
        echo '<li><a href="proyectos.php"><i class="fa fa-list"></i> <span>Proyectos</span></a></li>';
        echo '<li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i><span>Inventario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="inventario.php"><i class="fa fa-list"></i>Listado</a></li>
            <li><a href="topologia.php"><i class="fa fa-map-o"></i> <span>Topología</span></a></li>
          </ul>
        </li>';
        }?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tareas Programadas SOC
       </h1>
        <div class="col-md-12" style="text-align:right;">
            <?php
            if ($rq_sec['admin']=='1'){
                    echo '<button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-tarea"><i class="fa fa-plus"></i> Nueva Tarea</button>';
            }
            ?>
        </div>
    </section>
    <!-- Main content -->
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
    <section class="content">
    <div class="row">
        <div class="col-md-6">
                <div class="callout callout-warning" style="text-align:center">
                    <h4>Tareas del día : <?php echo date("Y-m-d"); ?></h4>
                </div>         
                <?php
                $today= date("Y-m-d");
                $query_tarea_hoy = "SELECT t.*, (select count(*) from comentario where t.id_tarea = novedad and tipo='2') as comentarios, p.nombre,                         p.apellido, g.nombre as nombreg
                                        FROM tarea as t 
                                        LEFT JOIN persona as p on t.persona = p.id_persona
                                        LEFT JOIN grupo as g on t.grupo = g.id_grupo
                                        WHERE t.borrado='0' AND t.dia='$today'";

                $sql_tarea_hoy = mysqli_query($con, $query_tarea_hoy.' ORDER BY hora ASC');
                					if(mysqli_num_rows($sql_tarea_hoy) == 0){
						echo '<tr><td colspan="8">No hay tareas que mostrar.</td></tr>';
                }else{
                    while($rown = mysqli_fetch_assoc($sql_tarea_hoy)){
                             echo '
                                <div class="box box-widget collapsed-box">
                                    <div class="box-header with-border">
                                          <div class="user-block">';
                                            
                                            if($rown['estado'] == '0'){
                                                echo'<img class="img-circle" src="../dist/img/task-pending-flat.png" alt="User Image">';
                                            }else{
                                                echo'<img class="img-circle" src="../dist/img/task-done-flat.png" alt="User Image">';
                                            };
                                            
                                            echo' <span class="username"><a href="#">'.$rown['apellido'].' '.$rown['nombre']. ' - Grupo: '.$rown['nombreg'].'</a></span>
                                            <span class="description">'.$rown['titulo'].' - '.$rown['hora'].'</span>
                                          </div>
                                          <!-- /.user-block -->
                                          <div class="box-tools">
                                            <span >'.$rown['comentarios'].' comentarios</span>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                          </div>
                                        <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                    <!-- post text -->
                                        <p>'.$rown['descripcion'].'</p>';
                                
                                    if($rown['estado'] == '0'){
						                  echo '<button type="button" class="btn btn-default btn-xs" onclick="updateCompletado('.$rown['id_tarea'].', \''.$user.'\');"><i class="fa fa-thumbs-o-up"></i> Completada</button>';
                                    }else{
                                        echo'
                                        <button type="button" class="btn btn-success btn-xs disabled"><i class="fa fa-thumbs-o-up"></i> Completada</button>
                                        <span class="text-muted pull-right">' .$rown['completada'].'</span>
                                        ';
                                        
                                        };
                                    echo'
                                    </div>
                                    <!-- /.box-body -->
                                
                                    <div class="box-footer box-comments">';
                                        $id_tarea = $rown['id_tarea'];
                                        $query_comentario = "SELECT c.*, p.nombre, p.apellido FROM comentario as c 
                                                            LEFT JOIN persona as p on c.persona = p.id_persona
                                                            WHERE c.borrado='0' AND c.novedad='$id_tarea' and c.tipo='2'";

                                        $sql_comentario = mysqli_query($con, $query_comentario.' ORDER BY id_comentario DESC');        
                                        while($rowcom = mysqli_fetch_assoc($sql_comentario)){
                              
                                            echo'
                                              <!-- /.box-comment -->
                                              <div class="box-comment">
                                                <!-- User image -->
                                                <img class="img-circle img-sm" src="../dist/img/icon_user.png" alt="User Image">
                                                <div class="comment-text">
                                                      <span class="username">
                                                        '.$rowcom['apellido'].' '.$rowcom['nombre']. '
                                                        <span class="text-muted pull-right">'.$rowcom['creado'].'</span>
                                                      </span><!-- /.username -->
                                                    '.$rowcom['texto'].'
                                                </div>
                                                <!-- /.comment-text -->
                                              </div>
                                              <!-- /.box-comment -->
                                             </div>
                                            ';
                                        };
                                         echo'
                                       <!-- /.box-footer -->
                                        <div class="box-footer">
                                          <form method="post">
                                            <img class="img-responsive img-circle img-sm" src="../dist/img/icon_user.png" alt="Alt Text">
                                            <!-- .img-push is used to add margin to elements next to floating images -->
                                            <div class="img-push">
                                              <input type="text" name="comment" id="comment" class="form-control input-sm" onkeydown="submitComment('.$rown['id_tarea'].', '.$rowp['id_persona'].', this.form.comment.value);" placeholder="Press enter to post comment">
                                            </div>
                                          </form>
                                        </div>
                                        <!-- /.box-footer -->
                                        </div>
                                        ';
                        }
                }
                ?>
        </div>

         <div class="col-md-6">
             <div class="callout callout-info" style="text-align:center">
                <h4>Tareas programadas</h4>
             </div>
             <?php
                    $today= date("Y-m-d");
                    $query_tarea = "SELECT t.*, (select count(*) from comentario where t.id_tarea = novedad and tipo='2') as comentarios, p.nombre,                     p.apellido, g.nombre as nombreg
                                        FROM tarea as t 
                                        LEFT JOIN persona as p on t.persona = p.id_persona
                                        LEFT JOIN grupo as g on t.grupo = g.id_grupo
                                        WHERE t.borrado='0' AND t.dia > '$today'";

                    $sql_tarea = mysqli_query($con, $query_tarea.' ORDER BY hora ASC');
                                        if(mysqli_num_rows($sql_tarea) == 0){
                            echo '<tr><td colspan="8">No hay tareas que mostrar.</td></tr>';
                    }else{
                        while($rown = mysqli_fetch_assoc($sql_tarea)){
                                 echo '
                                    <div class="box box-widget collapsed-box">
                                    <div class="box-header with-border">
                                      <div class="user-block">
                                        <img class="img-circle" src="../dist/img/task-program-flat.png" alt="User Image">
                                        <span class="username"><a href="#">'.$rown['apellido'].' '.$rown['nombre'].' - Grupo: '.$rown['nombreg'].'</a></span>
                                        <span class="description">'.$rown['titulo'].' - '.$rown['dia'].' - '.$rown['hora'].'</span>

                                      </div>
                                      <!-- /.user-block -->
                                      <div class="box-tools">

                                          <span >'.$rown['comentarios'].' comentarios</span>
                                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                      </div>
                                      <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                      <!-- post text -->
                                      <p>'.$rown['descripcion'].'</p>';
                                    echo'
                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer box-comments">';
                                    $id_tarea = $rown['id_tarea'];
                                    $query_comentario = "SELECT c.*, p.nombre, p.apellido FROM comentario as c 
                                                LEFT JOIN persona as p on c.persona = p.id_persona
                                                WHERE c.borrado='0' AND c.novedad='$id_tarea' and c.tipo='2'";

                                    $sql_comentario = mysqli_query($con, $query_comentario.' ORDER BY id_comentario DESC');        
                                    while($rowcom = mysqli_fetch_assoc($sql_comentario)){

                                        echo'
                                          <!-- /.box-comment -->
                                          <div class="box-comment">
                                            <!-- User image -->
                                            <img class="img-circle img-sm" src="../dist/img/icon_user.png" alt="User Image">

                                            <div class="comment-text">
                                                  <span class="username">
                                                    '.$rowcom['apellido'].' '.$rowcom['nombre']. '
                                                    <span class="text-muted pull-right">'.$rowcom['creado'].'</span>
                                                  </span><!-- /.username -->
                                                '.$rowcom['texto'].'
                                            </div>
                                            <!-- /.comment-text -->
                                          </div>
                                          <!-- /.box-comment -->
                                           </div>
                                        ';
                                    };

                                     echo'
                                   
                                    <!-- /.box-footer -->
                                    <div class="box-footer">
                                      <form method="post">
                                        <img class="img-responsive img-circle img-sm" src="../dist/img/icon_user.png" alt="Alt Text">
                                        <!-- .img-push is used to add margin to elements next to floating images -->
                                        <div class="img-push">
                                          <input type="text" name="comment" id="comment" class="form-control input-sm" onkeydown="submitComment('.$rown['id_tarea'].', '.$rowp['id_persona'].', this.form.comment.value);" placeholder="Press enter to post comment">
                                        </div>
                                      </form>
                                    </div>
                                    </div>
                                        </div>
                                    <!-- /.box-footer -->';

                            }
                    }
                    ?>
         </div>
        </div>

    <!-- /.End content -->
    </div>
 
    <div class="modal fade" id="modal-tarea">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Nueva tarea programada - USI<br>
                <?php
                    if(isset($_POST['AddT'])){
                        $titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));//Escanpando caracteres
                        $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));//Escanpando caracteres
                        $persona = mysqli_real_escape_string($con,(strip_tags($_POST["persona"],ENT_QUOTES)));//Escanpando caracteres
                        $grupo = mysqli_real_escape_string($con,(strip_tags($_POST["grupo"],ENT_QUOTES)));//Escanpando caracteres
                        $dia = mysqli_real_escape_string($con,(strip_tags($_POST["dia"],ENT_QUOTES)));//Escanpando caracteres
                        $hora = mysqli_real_escape_string($con,(strip_tags($_POST["hora"],ENT_QUOTES)));//Escanpando caracteres
                        
                        $insert_tarea= mysqli_query($con, "INSERT INTO tarea (titulo, descripcion, persona, grupo, dia, hora, creador, creado, usuario) 
                        VALUES ('$titulo', '$descripcion', '$persona', '$grupo', '$dia', '$hora', '$id_rowp', now(), '$user')") or die(mysqli_error());

                        if($insert_tarea){
                            echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';}
                    }				
                ?>
        </div>
        <div class="modal-body">
                <!-- form start -->
            <form method="post" role="form" action="">
              <div class="box-body">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" value="">
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción de la tarea</label>
                  <textarea class="form-control" rows="5" name="descripcion" id="descripcion" value=""></textarea>
                </div>
                <div class="form-group">
                      <label>Responsable</label>
                      <select name="persona" class="form-control">
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
                  <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                        <label>Fecha de tarea</label>
                        <div class="input-group date" data-provide="datepicker1">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" name="dia" id="datepicker1">
                        </div>
                      </div>
                    </div>
                        <div class="col-sm-6">
                            <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Hora de la tarea</label>
                              <div class="input-group">
                                <input type="text" class="form-control timepicker" name="hora">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                              </div>
                              <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
                        </div>
                </div>
                 <div class="form-group">
                    <div class="col-sm-3">
                        <input type="submit" name="AddT" class="btn  btn-raised btn-success" value="Guardar datos">
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
<!-- Modal to Event Details -->

<!--Modal-->

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
<!-- jQuery UI 1.11.4 -->
<script src="../bower_components/jquery-ui/jquery-ui.min.js"></script>
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
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        
<script>
    window.onload = function() {
        history.replaceState("", "", "tareas.php");
    }
</script>
  
<script>
  function updateCompletado(id_tarea, id_persona)
  {   

  var  datap=id_tarea;
  var  rmvfile=id_persona;

  $.ajax({
   type:'post',
      url:'setCompletado.php',
          data:{rmvfile: rmvfile, datap: datap},
 
 });
      location.reload(true);
 
}
</script>
<script>
  function submitComment(id_tarea, id_persona, comment)
  {   
      if(event.key === 'Enter') {
        var  dataid=id_tarea;
        var  datap=id_persona;
        var  datac=comment;
        var  datat='2';
 
          $.ajax({
           type:'post',
              url:'submitComment.php',
                  data:{dataid: dataid, datap: datap, datac: datac, datat:datat},

         });
              //location.href = './novedades.php';
            location.reload(true);
              }
 
}
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
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY h:mm A' })
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
      format: 'yyyy/mm/dd',
      todayHighlight: true
    })
      
    $('#datepicker2').datepicker({
      autoclose: true,
      format: 'yyyy/mm/dd',
      todayHighlight: true
    })
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false 
    })
    
  })
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>