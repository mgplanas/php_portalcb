<!DOCTYPE html>
<?php
include("conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
//Alert icons data on top bar
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");

if(mysqli_num_rows($persona) == 0){
    session_destroy();
    header('Location: index.html');
}

$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

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

//Querys para charts
$qa_info = mysqli_query($con, "SELECT 1 as total FROM controls.activo WHERE activo.tipo='1' AND activo.borrado='0'");
$a_info = mysqli_num_rows($qa_info);
$qa_infra = mysqli_query($con, "SELECT 1 as total FROM controls.activo WHERE activo.tipo='2' AND activo.borrado='0'");
$a_infra = mysqli_num_rows($qa_infra);
$qa_serv = mysqli_query($con, "SELECT 1 as total FROM controls.activo WHERE activo.tipo='3' AND activo.borrado='0'");
$a_serv = mysqli_num_rows($qa_serv);

$qiso_def = mysqli_query($con, "SELECT 1 as total FROM controls.item_iso27k WHERE madurez='1'");
$iso_def = mysqli_num_rows($qiso_def);
$qiso_exc = mysqli_query($con, "SELECT 1 as total FROM controls.item_iso27k WHERE madurez='2'");
$iso_exc = mysqli_num_rows($qiso_exc);
$qiso_perf = mysqli_query($con, "SELECT 1 as total FROM controls.item_iso27k WHERE madurez='3'");
$iso_perf = mysqli_num_rows($qiso_perf);

$qv = mysqli_query($con, "SELECT 1 as qv FROM riesgo WHERE n_resid<=3 AND borrado=0 AND estado='0'");
$rqv = mysqli_num_rows($qv);

$qa = mysqli_query($con,"SELECT 1 as qa FROM riesgo WHERE (n_resid=4 OR n_resid=6) AND borrado=0 AND estado='0'");
$rqa = mysqli_num_rows($qa);

$qr = mysqli_query($con,"SELECT 1 as qr FROM riesgo WHERE n_resid>6 AND borrado=0 AND estado='0'");
$rqr = mysqli_num_rows($qr);

$qcc = mysqli_query($con,"SELECT 1 as total 
FROM controles INNER JOIN referencias
ON controles.id_control = referencias.id_control
WHERE referencias.mes <= MONTH(CURRENT_DATE()) AND referencias.ano =  YEAR(CURRENT_DATE()) 
and controles.borrado = 0
and referencias.borrado = 0
AND referencias.status='1'");
$cc = mysqli_num_rows($qcc);

$qcp = mysqli_query($con,"SELECT 1 as total 
FROM controles INNER JOIN referencias
ON controles.id_control = referencias.id_control
WHERE referencias.mes <= MONTH(CURRENT_DATE()) AND referencias.ano =  YEAR(CURRENT_DATE()) 
and controles.borrado = 0
and referencias.borrado = 0
AND referencias.status='2'");
$cp = mysqli_num_rows($qcp);

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
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
    <a href="site.php" class="logo">
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
              <li class="footer"><a href="./pages/riesgos.php">Gestionar los riesgos</a></li>
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
              
              <li class="footer"><a href="./pages/activos.php">Ver Activos</a></li>
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
                <a href="./pages/controles.php">Gestionar controles</a>
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
                <a href="./pages/proyectos.php">Gestionar proyectos</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="dist/img/icon_user.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $_SESSION['usuario']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="dist/img/icon_user.png" class="img-circle" alt="User Image">
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
                    echo '<a href="./pages/admin.php" class="btn btn-default btn-flat "><i class="fa fa-gears"></i> Admin</a>';
                    }
                    ?>
                </div>
                  <div class="pull-right">
                      <a href="out.php" class="btn btn-default btn-flat">Salir</a>
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
          <img src="dist/img/icon_user.png" class="img-circle" alt="User Image">
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
        <li class="active"><a href="#"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
        <li><a href="./pages/activos.php"><i class="fa fa-archive"></i> <span>Activos</span></a></li>
		<li><a href="./pages/controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
		<li><a href="./pages/iso27k.php"><i class="fa fa-crosshairs"></i> <span>Ítems ISO 27001</span></a></li>
        <li><a href="./pages/mejoras.php"><i class="fa fa-refresh"></i> <span>Mejora Continua</span></a></li>
		<li><a href="./pages/riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
		<?php if ($rq_sec['admin']=='1' OR $rq_sec['soc']=='1'){
        echo '<li><a href="./pages/calendario.php"><i class="fa fa-calendar"></i> <span>Calendario</span></a></li>';
        echo '<li><a href="./pages/novedades.php"><i class="fa fa-envelope"></i> <span>Novedades</span></a></li>';
        echo '<li><a href="./pages/proyectos.php"><i class="fa fa-list"></i> <span>Proyectos</span></a></li>';
        echo '<li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i><span>Inventario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="./pages/inventario.php"><i class="fa fa-list"></i>Listado</a></li>
            <li><a href="./pages/topologia.php"><i class="fa fa-map-o"></i> <span>Topología</span></a></li>
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
        Tablero de comando
        <small>Indicadores Generales</small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	
		<!--------------------------
        | Your Page Content Here |
        -------------------------->
		 <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php
						$query_count_activos = "SELECT 1 as total FROM activo WHERE borrado='0';";
						$count_activos = mysqli_query($con, $query_count_activos);
						echo '
						<td> ' . mysqli_num_rows($count_activos) . ' </td>
						<td>';
						?></h3>

              <p>Total de Activos</p>
            </div>
            <div class="icon">
              <i class="fa fa-archive"></i>
            </div>
            <a href="./pages/activos.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php
						$query_count_iso = "SELECT 1 as total FROM item_iso27k WHERE madurez='1'";
						$count_iso = mysqli_query($con, $query_count_iso);
						$query_count_iso_total = "SELECT 1 as total FROM item_iso27k";
						$count_iso_total = mysqli_query($con, $query_count_iso_total);
						
						$mad = round(((mysqli_num_rows($count_iso)) * 100) / (mysqli_num_rows($count_iso_total)), PHP_ROUND_HALF_UP);
						
						echo $mad . " %";
						?></h3>

              <p>Madurez ISO 27001</p>
            </div>
            <div class="icon">
              <i class="ion ion-speedometer"></i>
            </div>
            <a href="./pages/iso27k.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php
						$query_count_riesgos = "SELECT 1 as total FROM riesgo WHERE borrado='0' and estado='0';";
						$count_riesgos = mysqli_query($con, $query_count_riesgos);
						echo '
						<td> ' . mysqli_num_rows($count_riesgos) . ' </td>
						<td>';
						?></h3>
            <p>Total de Riesgos abiertos</p>
            </div>
            <div class="icon">
              <i class="ion ion-flash"></i>
            </div>
            <a href="./pages/riesgos.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php
						$query_count_controles = "SELECT 1 as total FROM controles where borrado=0;";
						$count_controles = mysqli_query($con, $query_count_controles);
						echo '
						<td> ' . mysqli_num_rows($count_controles) . ' </td>
						<td>';
						?></h3>

              <p>Controles programados</p>
            </div>
            <div class="icon">
              <i class="fa fa-retweet"></i>
            </div>
            <a href="./pages/controles.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
	<div class="row">
    <div class="col-lg-3 col-xs-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Activos por tipo</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChartATipo" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
    <div class="col-lg-3 col-xs-6">
		  <!-- /.content ISO 27001-->
			<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Madurez ítems ISO 27001</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChartISOM" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
      </div>
		</div>
		<div class="col-lg-3 col-xs-6">
      <!-- /.content Riesgos-->
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Nivel de riesgo residual</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <canvas id="pieChartRR" style="height:250px"></canvas>
        </div>
        <!-- /.box-body -->
      </div>
		</div>
		<div class="col-lg-3 col-xs-6">
      <!-- /.content Controles-->
      <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Estado de controles</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChartC" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
		</div>
  </div>

  <div class="row">
    <div class="col-lg-3 col-xs-6"></div>
    <div class="col-lg-3 col-xs-6"></div>
    <div class="col-lg-6 col-xs-6">
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
    <div class="box box-warning collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title">Cantidad según Matriz de riesgo inherente</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <table border=1 cellspacing=0 cellpadding=0
              style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
              <tr>
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
                          <div class="box_a" style="color:black">
                              <?php echo $row14['q14']; ?></div>
                  </td>
                  <td width=99 style='text-align:center; width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                          <div class="box_a" style="color:black">
                              <?php echo $row24['q24']; ?></div>
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
        <!-- /.box-body -->
      </div>      
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3 col-xs-6"></div>
    <div class="col-lg-3 col-xs-6"></div>
    <div class="col-lg-6 col-xs-6">
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
      <div class="box box-warning collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title">Cantidad según Matriz de riesgo residual</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
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
        <!-- /.box-body -->
      </div>      
    </div>
  </div>
  </section>
    <!-- /.content -->
  </div>


  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Portal de Gestión
    </div>
    <!-- Default to the left -->
    <strong>Seguridad Informática  - <a href="site.php">ARSAT S.A.</a></strong>
  </footer>

 
<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- ChartJS -->
<script src="bower_components/chart.js/Chart.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas1 = $('#pieChartATipo').get(0).getContext('2d')
    var pieChartATipo       = new Chart(pieChartCanvas1)
    var PieData1        = [
      {
        value    :  <?php echo $a_info; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Información'
      },
      {
        value    : <?php echo $a_infra; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Infraestructura'
      },
      {
        value    : <?php echo $a_serv; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Servicio'
      }
	]
	var pieChartCanvas2 = $('#pieChartISOM').get(0).getContext('2d')
    var pieChartISOM       = new Chart(pieChartCanvas2)
    var PieData2        = [
      {
        value    :  <?php echo $iso_exc; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Excluido'
      },
      {
        value    : <?php echo $iso_def; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Definido'
      },
      {
        value    : <?php echo $iso_perf; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'A Perfeccionar'
      }
	]
	var pieChartCanvas3 = $('#pieChartRR').get(0).getContext('2d')
    var pieChartRR       = new Chart(pieChartCanvas3)
    var PieData3        = [
      {
        value    :  <?php echo $rqr; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Alto'
      },
      {
        value    : <?php echo $rqv; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Bajo'
      },
      {
        value    : <?php echo $rqa; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Medio'
      }
	]
	var pieChartCanvas4 = $('#pieChartC').get(0).getContext('2d')
    var pieChartC       = new Chart(pieChartCanvas4)
    var PieData4        = [
      {
        value    :  <?php echo $cp; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Pendiente'
      },
      {
        value    : <?php echo $cc; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Controlado'
      }
	]
    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
	
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChartATipo.Doughnut(PieData1, pieOptions)
	pieChartISOM.Doughnut(PieData2, pieOptions)
	pieChartRR.Doughnut(PieData3, pieOptions)
	pieChartC.Doughnut(PieData4, pieOptions)
  })
</script>
<script>
$(document).ready(function() {
  $('.rotate').css('height', $('.rotate').width());
  $('.rotate').css('width', '40px');
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>