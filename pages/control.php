<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

$user=$_SESSION['usuario'];

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

  
$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));

$query = "SELECT estados.estado as status, count(*) as number FROM referencias INNER JOIN estados ON referencias.status = estados.id_estado where id_control = '$nik' GROUP BY status";
$result = mysqli_query($con, $query); 
$mesActual = date('m');
$queryHastaHoy = "SELECT estados.estado as status, count(*) as number FROM referencias INNER JOIN estados ON referencias.status = estados.id_estado where id_control = '$nik' AND mes <= '$mesActual' GROUP BY status";
$resultHastaHoy = mysqli_query($con, $queryHastaHoy); 


//Titulo
$id = mysqli_query($con, "SELECT titulo FROM controles WHERE id_control = '$nik'");
$fila = mysqli_fetch_assoc($id);

if(isset($_GET['aksi']) == 'delete'){
    // escaping, additionally removing everything that could be (html/javascript-) code
    $cek = mysqli_query($con, "SELECT * FROM referencias WHERE id_control='$nik'");
    if(mysqli_num_rows($cek) == 0){
        echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
    }else{
        $delete = mysqli_query($con, "DELETE FROM referencias WHERE id_control='$nik'");
        if($delete){
            echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
        }
    }
}
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
 	<script type="text/javascript">  
 		google.charts.load('current', {
 			packages: ['corechart']
 		}).then(function () {
 			var data = google.visualization.arrayToDataTable([
 				['status', 'number'],
 				<?php  
 				while($row = mysqli_fetch_array($resultHastaHoy))  
 				{  
 					echo "['".$row["status"]."', ".$row["number"]."],";  
 				}  
 				?>  
 				]);

 			var colors = [];
 			var colorMap = {
 				'Controlado': '#5cb85c',
 				'Pendiente': '#d9534f',
 				'Controlado con obs alta': '#f39c12',
 				'Controlado con obs baja': '#f37c00'
 			}
 			for (var i = 0; i < data.getNumberOfRows(); i++) {
 				colors.push(colorMap[data.getValue(i, 0)]);
 			}

 			var options = {
 				title: 'Cumplimiento al Mes <?php echo date("m");?>',
 				is3D: true,
 				colors: colors,
 				backgroundColor: '#fafafa'
 			};
 			var chart = new google.visualization.PieChart(document.getElementById('piechartHastaMes'));
 			chart.draw(data, options);
 		});
</script>  
 	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
 	<script type="text/javascript">  
 		google.charts.load('current', {
 			packages: ['corechart']
 		}).then(function () {
 			var data = google.visualization.arrayToDataTable([
 				['status', 'number'],
 				<?php  
 				while($row = mysqli_fetch_array($result))  
 				{  
 					echo "['".$row["status"]."', ".$row["number"]."],";  
 				}  
 				?>  
 				]);

 			var colors = [];
 			var colorMap = {
 				'Controlado': '#5cb85c',
 				'Pendiente': '#d9534f',
 				'Controlado con obs alta': '#f39c12',
 				'Controlado con obs baja': '#f37c00'
 			}
 			for (var i = 0; i < data.getNumberOfRows(); i++) {
 				colors.push(colorMap[data.getValue(i, 0)]);
 			}

 			var options = {
 				title: 'Cumplimiento <?php echo date("Y");?>',
 				is3D: true,
 				colors: colors,
 				backgroundColor: '#fafafa'
 			};
 			var chart = new google.visualization.PieChart(document.getElementById('piechart'));
 			chart.draw(data, options);
 		});
</script>  


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

    .label-obs-baja {
      background-color: #f37c00
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
                <a href="proyectos.php">Gestionar proyectos</a>
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
                <div class="pull-left">
                    <?php
                    if ($rq_sec['admin']=='1'){
                    echo '<a href="admin.php" class="btn btn-default btn-flat "><i class="fa fa-gears"></i> Admin</a>';
                    }
                    ?>
                </div>
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
		<li class="active"><a href="controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
		<li><a href="iso27k.php"><i class="fa fa-crosshairs"></i> <span>Ítems ISO 27001</span></a></li>
		<li><a href="riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
        <?php if ($rq_sec['admin']=='1' OR $rq_sec['soc']=='1'){
            echo '<li><a href="calendario.php"><i class="fa fa-calendar"></i> <span>Calendario</span></a></li>';
            echo '<li><a href="novedades.php"><i class="fa fa-envelope"></i> <span>Novedades</span></a></li>';
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
        <small>Control >> <?php echo $fila ['titulo']; ?></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
<div class="btn-group">
    <button type="button"  id="ex1" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Estado
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
    <?php
  	     $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);
  	     //$nik = strip_tags($_GET["nik"],ENT_QUOTES);  
  	?>
     <li><a href="?filter=0&nik=<?php echo $nik;?>">Todos</a></li>
    <li><a href="?filter=1&nik=<?php echo $nik;?>">Controlado</a></li>
    <li><a href="?filter=2&nik=<?php echo $nik;?>">Pendiente</a></li>
    </ul>
    <?php echo "<input type='hidden' id='nik' name='nik' value='".strip_tags($_GET["nik"],ENT_QUOTES)."'/>" ?>
</div>
       
<br />
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <tr>
            <th>No.</th>
            <th>Mes</th>
            <th>Estado</th>
            <th>Controlador</th>
            <th width="110px">Acciones</th>
        </tr>
        <?php

        //AGREGO
        $nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));

        if($filter){
            $sql = mysqli_query($con, "SELECT *  FROM referencias  WHERE mes < '13' AND id_control= '$nik' AND status='$filter' ORDER BY nro_referencia ASC");
        }else{
            $sql = mysqli_query($con, "SELECT * FROM referencias WHERE mes < '13' AND id_control='$nik' ORDER BY nro_referencia ASC");
        }
        if(mysqli_num_rows($sql) == 0){
            echo '<tr><td colspan="8">No hay datos.</td></tr>';
        }else{
            $no = 1;

            while($row = mysqli_fetch_assoc($sql)){
                echo '
                <tr>
                    <td>'.$no.'</td>
                    <td>';
                        if($row['mes'] == '1'){
                            echo 'Enero';
                        }
                        else if ($row['mes'] == '2' ){
                            echo 'Febrero';
                        }
                        else if ($row['mes'] == '3' ){
                            echo 'Marzo';
                        }
                        else if ($row['mes'] == '4' ){
                            echo 'Abril';
                        }
                        else if ($row['mes'] == '5' ){
                            echo 'Mayo';
                        }
                        else if ($row['mes'] == '6' ){
                            echo 'Junio';
                        }
                        else if ($row['mes'] == '7' ){
                            echo 'Julio';
                        }
                        else if ($row['mes'] == '8' ){
                            echo 'Agosto';
                        }
                        else if ($row['mes'] == '9' ){
                            echo 'Septiembre';
                        }
                        else if ($row['mes'] == '10' ){
                            echo 'Octubre';
                        }
                        else if ($row['mes'] == '11' ){
                            echo 'Noviembre';
                        }
                        else if ($row['mes'] == '12' ){
                            echo 'Diciembre';
                        }
                    echo '
                    </td>				
                    <td>';
                    if($row['status'] == '1'){
                        echo '<span class="label label-success">Controlado</span>';
                    }
                    else if ($row['status'] == '2' ){
                        echo '<span class="label label-danger">Pendiente</span>';
                    }
                    else if ($row['status'] == '3' ){
                        echo '<span class="label label-warning">Controlado con obs alta</span>';
                    }
                    else if ($row['status'] == '4' ){
                        echo '<span class="label label-obs-baja">Controlado con obs baja</span>';
                    }
                    
                    $id_controlador = $row['controlador'];
                    $sql_controlador = mysqli_query($con, "SELECT *  FROM persona  WHERE id_persona= '$id_controlador'");
                    $row_controlador = mysqli_fetch_assoc($sql_controlador);
                    echo '
                        <td>'.$row_controlador['nombre'].' , '.$row_controlador['apellido'].'</td>
							
					';
                    
                    echo '
                    </td>
                    <td>
                        <a href="edit_referencia.php?nik='.$row['id_referencia'].'&cek='.$nik.'" title="Editar Controles" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="control.php?aksi=delete&nik='.$row['id_referencia'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['id_referencia'].'?\')" class="btn btn-danger btn-sm ';
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
    </table>
    </div>
<?php
    if($filter==0){
        echo '<center><h2>Indicadores de cumplimiento</h2></center>';
        echo '<br />';  
        echo '<table class="columns" style=" margin: 0px auto;">';
        echo '<tr>';
            echo '<td><div id="piechart" style="width: 400px; height: 400px style="border: 2px solid #ccc margin: 0px auto;"></div></td>';
            echo '<td><div id="piechartHastaMes" style="width: 400px; height: 400px style="border: 2px solid #ccc  margin: 0px auto;"></div></td>';
        echo '</tr>';
        echo '</table>';
    }
?>
        
</div>
		    <!--Table and divs that hold the pie charts-->

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>