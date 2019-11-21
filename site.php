<!DOCTYPE html>
<?php
include("conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
//Alert icons data on top bar
$page_title="Home";
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = '0'");

if(mysqli_num_rows($persona) == 0){
    session_destroy();
    header('Location: index.html');
}

$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

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
// ACTIVOS
$sqlTmpActivos = "SELECT 1 as total 
                  FROM controls.activo 
                  INNER JOIN persona as p ON activo.responsable = p.id_persona
                  WHERE activo.tipo=':tipoActivo' AND activo.borrado='0'
                  AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";
//$sqlTemp = strtr($sqlTmpActivos, $sqlTmpActivosTipo1);
$qa_1 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '1', ':per_id_gerencia' => $per_id_gerencia)));
$a_1 = mysqli_num_rows($qa_1);
$qa_2 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '2', ':per_id_gerencia' => $per_id_gerencia)));
$a_2 = mysqli_num_rows($qa_2);
$qa_3 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '3', ':per_id_gerencia' => $per_id_gerencia)));
$a_3 = mysqli_num_rows($qa_3);

$qa_4 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '4', ':per_id_gerencia' => $per_id_gerencia)));
$a_4 = mysqli_num_rows($qa_4);
$qa_5 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '5', ':per_id_gerencia' => $per_id_gerencia)));
$a_5 = mysqli_num_rows($qa_5);
$qa_6 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '6', ':per_id_gerencia' => $per_id_gerencia)));
$a_6 = mysqli_num_rows($qa_6);
$qa_7 = mysqli_query($con, strtr($sqlTmpActivos, array(':tipoActivo' => '7', ':per_id_gerencia' => $per_id_gerencia)));
$a_7 = mysqli_num_rows($qa_7);


// ISO 270001
$sqlTmpISO27k = "SELECT 1 as total 
                FROM controls.item_iso27k 
                INNER JOIN persona as p ON item_iso27k.responsable = p.id_persona
                WHERE item_iso27k.madurez=:madurez 
                AND item_iso27k.version = (SELECT id FROM iso27k_version WHERE borrado = 0 ORDER BY modificacion desc LIMIT 1)
                AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";
$qiso_def = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '1', ':per_id_gerencia' => $per_id_gerencia)));
$iso_def = mysqli_num_rows($qiso_def);
$qiso_exc = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '2', ':per_id_gerencia' => $per_id_gerencia)));
$iso_exc = mysqli_num_rows($qiso_exc);
$qiso_perf = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '3', ':per_id_gerencia' => $per_id_gerencia)));
$iso_perf = mysqli_num_rows($qiso_perf);



// RIESGO
$sqlTmpRiesgos = "SELECT 1 as qv 
                  FROM riesgo 
                  INNER JOIN persona as p ON riesgo.responsable = p.id_persona
                  WHERE riesgo.n_resid:comparacion AND riesgo.borrado=0 AND riesgo.estado='0'
                  AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";
$qv = mysqli_query($con, strtr($sqlTmpRiesgos, array(':comparacion' => '<=3', ':per_id_gerencia' => $per_id_gerencia)));
$rqv = mysqli_num_rows($qv);
$qa = mysqli_query($con,strtr($sqlTmpRiesgos, array(':comparacion' => '  IN (4,5,6,7,8,9,10,11)', ':per_id_gerencia' => $per_id_gerencia)));
$rqa = mysqli_num_rows($qa);
$qr = mysqli_query($con,strtr($sqlTmpRiesgos, array(':comparacion' => '>11', ':per_id_gerencia' => $per_id_gerencia)));
$rqr = mysqli_num_rows($qr);


// CONTROLES
$qcc = mysqli_query($con,"SELECT 1 as total 
FROM controles 
INNER JOIN persona as p ON controles.responsable = p.id_persona
INNER JOIN referencias ON controles.id_control = referencias.id_control
WHERE referencias.mes <= MONTH(CURRENT_DATE()) AND referencias.ano =  YEAR(CURRENT_DATE()) 
and controles.borrado = 0
and referencias.borrado = 0
AND referencias.status='1'
AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )");
$cc = mysqli_num_rows($qcc);

$qcp = mysqli_query($con,"SELECT 1 as total 
FROM controles 
INNER JOIN persona as p ON controles.responsable = p.id_persona
INNER JOIN referencias ON controles.id_control = referencias.id_control
WHERE referencias.mes <= MONTH(CURRENT_DATE()) AND referencias.ano =  YEAR(CURRENT_DATE()) 
and controles.borrado = 0
and referencias.borrado = 0
AND referencias.status='2'
AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )");
$cp = mysqli_num_rows($qcp);

$qoa = mysqli_query($con,"SELECT 1 as total 
FROM controles 
INNER JOIN persona as p ON controles.responsable = p.id_persona
INNER JOIN referencias ON controles.id_control = referencias.id_control
WHERE referencias.mes <= MONTH(CURRENT_DATE()) AND referencias.ano =  YEAR(CURRENT_DATE()) 
and controles.borrado = 0
and referencias.borrado = 0
AND referencias.status='3'
AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )");
$oa = mysqli_num_rows($qoa);

$qob = mysqli_query($con,"SELECT 1 as total 
FROM controles 
INNER JOIN persona as p ON controles.responsable = p.id_persona
INNER JOIN referencias ON controles.id_control = referencias.id_control
WHERE referencias.mes <= MONTH(CURRENT_DATE()) AND referencias.ano =  YEAR(CURRENT_DATE()) 
and controles.borrado = 0
and referencias.borrado = 0
AND referencias.status='4'
AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )");
$ob = mysqli_num_rows($qob);

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

  <style>
    .bg-semino {
      background-color: #6495ED !important;
      color: #fff !important; 
    }
    .bg-logo {
      background-image: url('pages/logo_arsat.png');
      background-repeat: no-repeat;
      /* background-attachment: fixed; */
      background-position: center center;
      background-size: 500px;
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
    <a href="site.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">GITyS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>GITyS</b>-ARSAT</span>
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
              <span class="label label-warning"><?php echo $rowr; ?></span>
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
              <span class="label label-info"><?php echo $rowa; ?></span>
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
              <span class="label label-success"><?php echo $rowcp; ?></span>
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
                    if ($rq_sec['admin']=='1' || $rq_sec['admin_per']=='1' ){
                    echo '<a href="./pages/admin.php" class="btn btn-default btn-flat "><i class="fa fa-gears"></i> Admin. Permisos</a>';
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
        <?php if ($rq_sec['admin']=='1' OR $rq_sec['compras']=='1'){ ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-edit"></i><span>Administración</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if ($rq_sec['admin']=='1' OR $rq_sec['compras']=='1'){ ?>
                <li><a href="./pages/compras.php"><i class="fa fa-cart-plus"></i>Compras</a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>        
        <?php if ($rq_sec['admin']=='1' OR $rq_sec['compliance']=='1'){ ?>
          <li><a href="./pages/activos.php"><i class="fa fa-archive"></i> <span>Activos</span></a></li>
          <li><a href="./pages/controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
          <li><a href="./pages/iso27k.php"><i class="fa fa-crosshairs"></i> <span>ISO 27001</span></a></li>
          <li><a href="./pages/iso9k.php"><i class="fa fa-crosshairs"></i> <span>ISO 9001</span></a></li>        
          <li><a href="./pages/mejoras.php"><i class="fa fa-refresh"></i> <span>Mejora Continua</span></a></li>
          <li><a href="./pages/riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
        <?php }?>
        <?php if ($rq_sec['admin']=='1' OR $rq_sec['proy']=='1' OR $rq_sec['admin_proy']=='1'){
          echo '<li><a href="./pages/proyectos.php"><i class="fa fa-list"></i> <span>Proyectos</span></a></li>';
        }?>
        <?php if ($rq_sec['admin']=='1' OR $rq_sec['soc']=='1'){
            echo '<li><a href="./pages/calendario.php"><i class="fa fa-calendar"></i> <span>Calendario</span></a></li>';
            echo '<li><a href="./pages/novedades.php"><i class="fa fa-envelope"></i> <span>Novedades</span></a></li>';
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
            <?php if ($rq_sec['admin']=='1' OR $rq_sec['cli_dc']=='1'){ ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-cloud"></i> <span>Clientes DC</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="./pages/cdc_dashboard.php"><i class="fa fa-pie-chart"></i> Dashboard</a></li>
                <li><a href="./pages/cdc_organismo.php"><i class="fa fa-building"></i> Organismos</a></li>
                <li><a href="./pages/cdc_cliente.php"><i class="fa fa-user"></i> Clientes</a></li>
                <li class="treeview">
                  <a href="#"><i class="fa fa-gears"></i> Servicios
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="./pages/sdc_housing.php"><i class="fa fa-home"></i> Housing</a></li>
                    <li><a href="./pages/sdc_hosting.php"><i class="fa fa-server"></i> Hosting</a></li>
                  </ul>
                </li>
              </ul>
            </li>    
            <?php } ?>
            <?php if ($rq_sec['admin']=='1' OR $rq_sec['compliance']=='1'  OR $rq_sec['compras']=='1'){ ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-pie-chart"></i><span>Métricas</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php if ($rq_sec['admin']=='1' OR $rq_sec['compliance']=='1'){ ?>
                    <li><a href="./pages/met_activos.php"><i class="fa fa-archive"></i>Activos</a></li>
                    <li><a href="./pages/met_iso27k.php"><i class="fa fa-crosshairs"></i> <span>ISO 27001</span></a></li>
                    <li><a href="./pages/met_riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
                    <li><a href="./pages/met_controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
                    <li><a href="./pages/met_mejoras.php"><i class="fa fa-refresh"></i> <span>Mejoras</span></a></li>
                  <?php } if ($rq_sec['admin']=='1' OR $rq_sec['compras']=='1'){ ?>
                    <li><a href="./pages/met_compras.php"><i class="fa fa-cart-plus"></i> <span>Compras</span></a></li>
                  <?php } ?>
                </ul>
              </li>
            <?php } ?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div id="main-content" class="content-wrapper bg-logo">
    <!-- Content Header (Page header) -->
    <?php if ($rq_sec['admin']=='1' OR $rq_sec['compliance']=='1'){ ?>
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
						$query_count_activos = "SELECT 1 as total 
                                    FROM activo 
                                    INNER JOIN persona as p ON activo.responsable = p.id_persona
                                    WHERE activo.borrado='0'
                                    AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia );";
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
            <div style="position: relative; padding: 3px; color: #fff; color: rgba(255,255,255,0.8); display: block; z-index: 10; background: rgba(0,0,0,0.1); text-decoration: none;">
              <a style="color: white;" href="./pages/activos.php" >  <i class="fa fa-list"></i></a>
              <a style="color: white;" href="./pages/met_activos.php" >  <i class="fa fa-pie-chart"></i></a>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php
						$query_count_iso = "SELECT 1 as total 
                                FROM item_iso27k 
                                INNER JOIN persona as p ON item_iso27k.responsable = p.id_persona
                                WHERE madurez='1'
                                AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )";
						$count_iso = mysqli_query($con, $query_count_iso);
						$query_count_iso_total = "SELECT 1 as total 
                                      FROM item_iso27k
                                      INNER JOIN persona as p ON item_iso27k.responsable = p.id_persona
                                      WHERE ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )";
						$count_iso_total = mysqli_query($con, $query_count_iso_total);
            
            if (mysqli_num_rows($count_iso_total) > 0) {
              $mad = round(((mysqli_num_rows($count_iso)) * 100) / (mysqli_num_rows($count_iso_total)), PHP_ROUND_HALF_UP);
              echo $mad . " %";
            } else {
              echo "Sin datos";
            }
						
						?></h3>

              <p>Madurez ISO 27001</p>
            </div>
            <div class="icon">
              <i class="ion ion-speedometer"></i>
            </div>
            <div style="position: relative; padding: 3px; color: #fff; color: rgba(255,255,255,0.8); display: block; z-index: 10; background: rgba(0,0,0,0.1); text-decoration: none;">
              <a style="color: white;" href="./pages/iso27k.php" >  <i class="fa fa-list"></i></a>
              <a style="color: white;" href="./pages/met_iso27k.php" >  <i class="fa fa-pie-chart"></i></a>
            </div>            
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php
						$query_count_riesgos = "SELECT 1 as total 
                                    FROM riesgo 
                                    INNER JOIN persona as p ON riesgo.responsable = p.id_persona
                                    WHERE riesgo.borrado='0' 
                                    and riesgo.estado='0'
                                    AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )";
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
            <div style="position: relative; padding: 3px; color: #fff; color: rgba(255,255,255,0.8); display: block; z-index: 10; background: rgba(0,0,0,0.1); text-decoration: none;">
              <a style="color: white;" href="./pages/riesgos.php" >  <i class="fa fa-list"></i></a>
              <a style="color: white;" href="./pages/met_riesgos.php" >  <i class="fa fa-pie-chart"></i></a>
            </div>               
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-semino">
            <div class="inner">
              <h3><?php
						$query_count_controles = "SELECT 1 as total 
                                      FROM controles 
                                      INNER JOIN persona as p ON controles.responsable = p.id_persona
                                      where controles.borrado=0
                                      AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )";
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
            <div style="position: relative; padding: 3px; color: #fff; color: rgba(255,255,255,0.8); display: block; z-index: 10; background: rgba(0,0,0,0.1); text-decoration: none;">
              <a style="color: white;" href="./pages/controles.php" >  <i class="fa fa-list"></i></a>
              <a style="color: white;" href="./pages/met_controles.php" >  <i class="fa fa-pie-chart"></i></a>
            </div>                    
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
    </section>
    <?php } ?>
    <!-- /.content -->
  </div>


  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include_once('./pages/site_footer.php'); ?>

 
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

<?php if ($rq_sec['admin']=='1' OR $rq_sec['compliance']=='1'){ ?>
<script>
  $(function () {
    $("#main-content").toggleClass("bg-logo");
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
        value    :  <?php echo $a_1; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Datos/Información'
      },
      {
        value    : <?php echo $a_2; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Equipamiento'
      },
      {
        value    : <?php echo $a_3; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Instalaciones'
      },
      {
        value    :  <?php echo $a_4; ?>,
        color    : '#f569ff',
        highlight: '#f569ff',
        label    : 'Personal'
      },
      {
        value    : <?php echo $a_5; ?>,
        color    : '#00ff5a',
        highlight: '#00ff5a',
        label    : 'Servicios'
      },
      {
        value    : <?php echo $a_6; ?>,
        color    : '#f3ff12',
        highlight: '#f3ff12',
        label    : 'Software'
      },
      {
        value    : <?php echo $a_7; ?>,
        color    : '#f39cff',
        highlight: '#f39cff',
        label    : 'Suministros'
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
      },
      {
        value    : <?php echo $oa; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Controlado con obs alta'
      },
      {
        value    : <?php echo $ob; ?>,
        color    : '#f37c00',
        highlight: '#f37c00',
        label    : 'Controlado con obs baja'
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
<?php } ?>
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