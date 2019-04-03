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

if(isset($_POST['action']) or isset($_GET['view'])) //show all events
{
    if(isset($_GET['view']))
    {
        header('Content-Type: application/json');
        $start = mysqli_real_escape_string($con,$_GET["start"]);
        $end = mysqli_real_escape_string($con,$_GET["end"]);
        
        $result = mysqli_query($con,"SELECT id_calendario, start ,end ,title FROM calendario");
        while($row = mysqli_fetch_assoc($result))
        {
            $events[] = $row; 
        }
        echo json_encode($events); 
        exit;
    }
    elseif($_POST['action'] == "add") // add new event
    {   
        mysqli_query($con,"INSERT INTO item_calendario (titulo , start , end )
                    VALUES (
                    '".mysqli_real_escape_string($con,$_POST["title"])."',
                    '".mysqli_real_escape_string($con,date('Y-m-d H:i:s',strtotime($_POST["start"])))."',
                    '".mysqli_real_escape_string($con,date('Y-m-d H:i:s',strtotime($_POST["end"])))."'
                    )");
        header('Content-Type: application/json');
        echo '{"id":"'.mysqli_insert_id($con).'"}';
        exit;
    }
    
    elseif($_POST['action'] == "update")  // update event
    {
        if ($rq_sec['admin']=='1'){
            $startDay = date('Y-m-d',strtotime($_POST["start"]));
            $startTime = date('H:i:s',strtotime($_POST["start"]));
            $endDay = date('Y-m-d',strtotime($_POST["end"]));
            $endTime = date('H:i:s',strtotime($_POST["end"]));
            $id_item_calendario = $_POST["id"];
            $allDay = $_POST["allDay"];
            $tipo = $_POST["tipo"];

            if($tipo == '4'){
                $update_item_calendario = mysqli_query($con, "UPDATE item_calendario SET 
                startDay = '$startDay', startTime = '$startTime', endDay = '$endDay', endTime ='$endTime'
                WHERE id_item_calendario = '$id_item_calendario'") or die(mysqli_error());
            }
            else{
                $update_item_calendario = mysqli_query($con, "UPDATE item_calendario SET 
                startDay = '$startDay', endDay = '$endDay'
                WHERE id_item_calendario = '$id_item_calendario'") or die(mysqli_error());
            }

            $lastInsert = mysqli_insert_id($con);
            $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                VALUES ('2', '14', '$lastInsert', now(), '$user')") or die(mysqli_error());
             exit;
    }
    }
    
    elseif($_POST['action'] == "delete")  // remove event
    {
        mysqli_query($con,"DELETE from item_calendario where id_item_calendario = '".mysqli_real_escape_string($con,$_POST["id"])."'");
        if (mysqli_affected_rows($con) > 0) {
            echo "1";
        }
        exit;
    }
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
<!-- fullCalendar -->
  <link rel="stylesheet" href="../bower_components/fullcalendar/dist/fullcalendar.css">
  <link rel="stylesheet" href="../bower_components/fullcalendar/dist/fullcalendar.print.css" media="print">
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
  <?php include_once('./site_sidebar.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Calendario USI
       </h1>
    </section>
    <!-- Main content -->
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h4 class="box-title">Referencia Eventos</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                <div class="external-event bg-blue">Turno Día SOC de 8 a 20 hs</div>
                <div class="external-event bg-red">Turno Noche SOC de 20 a 8 hs</div>
                <div class="external-event bg-orange">Guardia pasiva L2 - Fuera de horario</div>
                <div class="external-event bg-green">Guardia pasiva L3 - Fuera de horario</div>
                <div class="external-event bg-gray">Jefe de Guardia - Fuera de horario</div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
                <div class="box-header with-border">
                  <h4 class="box-title">Guardias del día <?php echo $hoy; ?></h4>
                </div>
                <div class="box-body">
                  <!-- the events -->
                  <?php
                    $turnosd_hoy = mysqli_query($con, "SELECT CONCAT (p.apellido, ', ', p.nombre, ' - Tel. ', p.contacto) as title 
                                                        FROM item_calendario as c
                                                        INNER JOIN persona as p ON c.persona=p.id_persona
                                                        WHERE startDay = curdate() AND tipo='1'");
                    $rowsd = mysqli_fetch_assoc($turnosd_hoy);
                    $turnosn_hoy = mysqli_query($con, "SELECT CONCAT (p.apellido, ', ', p.nombre, ' - Tel. ', p.contacto) as title 
                                                        FROM item_calendario as c
                                                        INNER JOIN persona as p ON c.persona=p.id_persona
                                                        WHERE startDay = curdate() AND tipo='2'");
                    $rowsn = mysqli_fetch_assoc($turnosn_hoy);
                    $turnosl2_hoy = mysqli_query($con, "SELECT CONCAT (p.apellido, ', ', p.nombre, ' - Tel. ', p.contacto) as title 
                                                        FROM item_calendario as c
                                                        INNER JOIN persona as p ON c.persona=p.id_persona
                                                        WHERE startDay = curdate() AND tipo='0'");
                    $rowsl2 = mysqli_fetch_assoc($turnosl2_hoy);
                    $turnosl3_hoy = mysqli_query($con, "SELECT CONCAT (p.apellido, ', ', p.nombre, ' - Tel. ', p.contacto) as title 
                                                        FROM item_calendario as c
                                                        INNER JOIN persona as p ON c.persona=p.id_persona
                                                        WHERE startDay = curdate() AND tipo='3'");
                    $rowsl3 = mysqli_fetch_assoc($turnosl3_hoy);
                    $turnosl4_hoy = mysqli_query($con, "SELECT CONCAT (p.apellido, ', ', p.nombre, ' - Tel. ', p.contacto) as title 
                                                        FROM item_calendario as c
                                                        INNER JOIN persona as p ON c.persona=p.id_persona
                                                        WHERE startDay = curdate() AND tipo='5'");
                    $rowsl4 = mysqli_fetch_assoc($turnosl4_hoy);
                  ?>
                  <div id="external-events">
                    <!--<div class="external-event bg-blue"><?php echo ''.$rowsd['title']. '';?></div>
                    <div class="external-event bg-red"><?php echo ''.$rowsn['title']. '';?></div>-->
                    <div class="external-event bg-orange"><?php echo ''.$rowsl2['title']. '';?></div>
                    <div class="external-event bg-green"><?php echo ''.$rowsl3['title']. '';?></div>
                    <div class="external-event bg-gray"><?php echo ''.$rowsl4['title']. '';?></div>
                  </div>
                </div>
                <!-- /.box-body -->
          </div>
         <?php
        if ($rq_sec['guardias']=='1'){
        echo '<div class="form-group">
                <button type="button" class="btn-block btn-primary" data-toggle="modal" data-target="#modal-guardia"> Activación de guardia pasiva</button>
         </div>';
        }
        ?> 
        <div class="form-group">
                <input type="button" class="btn-block btn-primary" onclick="location.href='./calendario_guardias.php'" value="Ver activaciones de guardia" />
         </div>
            
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>  
    <!-- Modal  to Add Event -->
<div id="createEventModal" class="modal fade" role="dialog">
 <div class="modal-dialog">

 <!-- Modal content-->
 <div class="modal-content">
 <div class="modal-header">
 <button type="button" class="close" data-dismiss="modal">×</button>
 <h4 class="modal-title">Agregar Evento</h4>
 </div>
 <div class="modal-body">
 <div class="control-group">
 <label class="control-label" for="inputPatient">Evento:</label>
 <div class="field desc">
 <input class="form-control" id="title" name="title" placeholder="Event" type="text" value="">
 </div>
 </div>
 
 <input type="hidden" id="startTime"/>
 <input type="hidden" id="endTime"/>
 
 
 
 <div class="control-group">
 <label class="control-label" for="when">Cuando:</label>
 <div class="controls controls-row" id="when" style="margin-top:5px;">
 </div>
 </div>
 
 </div>
 <div class="modal-footer">
 <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
 <button type="submit" class="btn btn-primary" id="submitButton">Guardar</button>
 </div>
 </div>

 </div>
</div>
    <div class="modal fade" id="modal-guardia">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Activación de guardia pasiva<br>
                    <small><?php echo $_SESSION['usuario']?></small></h2>
                <?php
                    if(isset($_POST['Addag'])){
                        $title = mysqli_real_escape_string($con,(strip_tags($_POST["title"],ENT_QUOTES)));//Escanpando caracteres
                        $liquida = mysqli_real_escape_string($con,(strip_tags($_POST["liquida"],ENT_QUOTES)));//Escanpando caracteres
                        $startDay = mysqli_real_escape_string($con,(strip_tags($_POST["startDay"],ENT_QUOTES)));//Escanpando caracteres
                        $startTime = mysqli_real_escape_string($con,(strip_tags($_POST["startTime"],ENT_QUOTES)));//Escanpando caracteres
                        $endDay = mysqli_real_escape_string($con,(strip_tags($_POST["endDay"],ENT_QUOTES)));//Escanpando caracteres
                        $endTime = mysqli_real_escape_string($con,(strip_tags($_POST["endTime"],ENT_QUOTES)));//Escanpando caracteres

                        $insert_activacion_guardia = mysqli_query($con, "INSERT INTO activacion_guardia (title, liquida, startDay, startTime, endDay, endTime, persona) 
                        VALUES ('$title', '$liquida', '$startDay', '$startTime', '$endDay', '$endTime','$id_rowp')") or die(mysqli_error());

                       $lastInsert = mysqli_insert_id($con);
                        $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                   VALUES ('1', '13', '$lastInsert', now(), '$user')") or die(mysqli_error());
                        if($insert_activacion_guardia){
                            echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';}
                    }				
                ?>
          </div>
    <div class="modal-body">
            <!-- form start -->
        <form method="post" role="form" action="">
          <div class="box-body">
            <div class="form-group">
              <label for="title">Motivo de la activación</label>
              <textarea class="form-control" rows="5" name="title" id="title" value=""></textarea>
            </div>
              <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                        <label>Fecha de inicio</label>
                        <div class="input-group date" data-provide="datepicker1">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" name="startDay" id="datepicker1">
                        </div>
                      </div>
                    </div>
                        <div class="col-sm-6">
                            <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Hora de inicio</label>
                              <div class="input-group">
                                <input type="text" class="form-control timepicker" name="startTime">
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
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                        <label>Fecha de finalización</label>
                        <div class="input-group date" data-provide="datepicker2">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" name="endDay" id="datepicker2">
                        </div>
                      </div>
                    </div>
                        <div class="col-sm-6">
                            <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Hora de finalización</label>
                              <div class="input-group">
                                <input type="text" class="form-control timepicker" name="endTime">
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
                <label>Liquida o Recupera ?</label>
                <select name="liquida" class="form-control">
                    <option value='1'>Liquidación</option>
                    <option value='0'>Recupera</option>
                </select>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <input type="submit" name="Addag" class="btn  btn-raised btn-success" value="Guardar datos">
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
<div id="calendarModal" class="modal fade">
<div class="modal-dialog">
 <div class="modal-content">
 <div class="modal-header">
 <button type="button" class="close" data-dismiss="modal">×</button>
 <h4 class="modal-title">Detalle del Evento</h4>
 </div>
 <div id="modalBody" class="modal-body">
 <h4 id="modalTitle" class="modal-title"></h4>
 <div id="modalWhen" style="margin-top:5px;"></div>
 </div>
 <input type="hidden" id="eventID"/>
 <div class="modal-footer">
 <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
    <?php
        if ($rq_sec['admin']=='1'){
        echo '<button type="submit" class="btn btn-danger" id="deleteButton">Borrar</button>';
        }
    ?>
     
 </div>
 </div>
</div>
</div>
<!--Modal-->
    <!-- /.End content -->
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
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- fullCalendar -->
<script src="../bower_components/moment/moment.js"></script>
<script src="../bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="../bower_components/fullcalendar/dist/locale-all.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- custom scripts --> 
<script type="text/javascript" src="../js/script.js"></script> 

      
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