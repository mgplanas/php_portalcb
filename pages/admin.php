<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

//Alert icons data on top bar
$user=$_SESSION['usuario'];

if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
	$cek = mysqli_query($con, "SELECT i.*, p.email FROM permisos as i 
	                           LEFT JOIN persona as p on i.id_persona = p.id_persona WHERE id_permiso='$nik'");
	$cekd = mysqli_fetch_assoc($cek);
    $email = $cekd['email'];
    
    if(mysqli_num_rows($cek) == 0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
	}else{
		//Elimino Activo
		
        $delete_permiso = mysqli_query($con, "UPDATE permisos SET `borrado`='1' WHERE id_permiso='$nik'");
      
        $delete_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
											   VALUES ('3', '8', '$nik', now(), '$user', '$email')") or die(mysqli_error());
		if(!$delete_permiso){
			$_SESSION['formSubmitted'] = 9;
            header("Location: admin.php");
		}
	}
}

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['admin']=='0'){
	header('Location: ../site.php');
}

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
    <!-- 
  <link rel="stylesheet" href="../css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="../css/jquery.dataTables.min.css"> -->
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
                    <li><a href="controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
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
                      }
                    ?>
                    <li class="active"><a href="admin.php"><i class="fa fa-gear"></i> <span>Administración</span></a>
                    </li>

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
                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Nuevo Activo guardado correctamente.</div>';
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
            ?>
            <!-- HEADER -->
            <section class="content-header">
                <h1>
                    Administración del Portal
                    <small>General</small>
                </h1>
            </section>
            <!-- FIN HEADER -->
            <!-- Main content -->
            <section class="content container-fluid">
              <!--------------------------
              | Your Page Content Here |
              -------------------------->
              <section class="content">
                  <div class="row">
                      <div class="col-xs-12">
                          <div class="nav-tabs-custom">
                              <ul class="nav nav-tabs">
                                  <li class="active"><a href="#tab_1" data-toggle="tab">Permisos Usuarios</a></li>
                                  <li><a href="#tab_0" data-toggle="tab">Personas</a></li>
                                  <li><a href="#tab_2" data-toggle="tab">Log Auditoría</a></li>

                              </ul>
                              <div class="tab-content">

                                <!-- TAB PERMISOS -->
                                <div class="tab-pane active" id="tab_1">
                                  <table id="permisos" class="table table-bordered table-hover">
                                        <div class="col-sm-12" style="text-align:right;">
                                            <button type="button" class="btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modal-acceso"><i class="glyphicon glyphicon-user"></i>
                                                Nuevo Acceso</button>
                                        </div>
                                        <thead>
                                            <tr>
                                                <th width="1">Nro</th>
                                                <th width="1">Legajo</th>
                                                <th width="100">Correo</th>
                                                <th width="100">Usuario</th>
                                                <th width="1">Admin</th>
                                                <th width="1">SOC</th>
                                                <th width="1">Compliance</th>
                                                <th width="1">Edición</th>
                                                <th width="1">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $query = "SELECT i.*, p.nombre, p.apellido, p.legajo, p.email FROM permisos as i 
                                                        LEFT JOIN persona as p on i.id_persona = p.id_persona
                                                        WHERE i.borrado='0'
                                                        ORDER BY i.id_permiso ASC";
                                              
                                              $sql = mysqli_query($con, $query);
                                              if(mysqli_num_rows($sql) == 0){
                                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                                }
                                                else
                                                {
                                                          
                                                while($row = mysqli_fetch_assoc($sql)){
                                                  echo '<td align="center">'.$row['id_permiso'].'</td>';
                                                  echo '<td>'.$row['legajo'].'</td>';
                                                  echo '<td>'.$row['email'].'</td>';
                                                  echo '<td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><a href=#><input name="admin" type="checkbox" onclick="updatePerm(1, '.$row['id_permiso'].');" value="1"'; if($row['admin'] == '1'){ echo 'checked'; } echo'></a></label>
                                                          </div>
                                                        </td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="soc" type="checkbox" onclick="updatePerm(2, '.$row['id_permiso'].');" value="1"'; if($row['soc'] == '1'){ echo 'checked'; } echo'></label>
                                                          </div>
                                                        </td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="compliance" type="checkbox" onclick="updatePerm(3, '.$row['id_permiso'].');" value="1"'; if($row['compliance'] == '1'){ echo 'checked'; } echo'></label>
                                                                </div></td>'; 
                                                  echo '<td>
                                                          <div class="checkbox">
                                                            <label><input name="edicion" type="checkbox" onclick="updatePerm(4, '.$row['id_permiso'].');" value="1"'; if($row['edicion'] == '1'){ echo 'checked'; } echo'></label>
                                                          </div></td>'; 
                                                  echo '<td align="center">
                                                          <a href="admin.php?aksi=delete&nik='.$row['id_permiso'].'" title="Deshabilitar usuario" onclick="return confirm(\'Esta seguro de borrar los permisos de '.$row['email'].'?\')" class="btn btn-danger btn-sm ';
                                                                if ($rq_sec['edicion']=='0'){
                                                                        echo 'disabled';
                                                                }
                                                                echo '"><i class="glyphicon glyphicon-ban-circle"></i></a>
                                                        </td>';
                                                  echo '</tr>';
                                                }
                                              }
                                              ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th width="1">Nro</th>
                                                <th width="1">Legajo</th>
                                                <th width="100">Correo</th>
                                                <th width="100">Usuario</th>
                                                <th width="1">Admin</th>
                                                <th width="1">SOC</th>
                                                <th width="1">Compliance</th>
                                                <th width="1">Edición</th>
                                                <th width="1">Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="modal fade" id="modal-acceso">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Nuevo Acceso</h2>
                                                <?php
                                                  if(isset($_POST['Addp'])){
                                                    $persona = mysqli_real_escape_string($con,(strip_tags($_POST["persona"],ENT_QUOTES)));//Escanpando caracteres
                                                    $lectura = mysqli_real_escape_string($con,(strip_tags($_POST["lectura"],ENT_QUOTES)));//Escanpando caracteres
                                                    $edicion = mysqli_real_escape_string($con,(strip_tags($_POST["edicion"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $compliance = mysqli_real_escape_string($con,(strip_tags($_POST["compliance"],ENT_QUOTES)));//Escanpando caracteres 
                                                    $soc = mysqli_real_escape_string($con,(strip_tags($_POST["soc"],ENT_QUOTES)));//Escanpando caracteres 
                                                    //Inserto Control
                                                    $insert_acceso = mysqli_query($con, "INSERT INTO permisos (id_persona, lectura, edicion, compliance, soc) VALUES ('$persona','$lectura','$edicion', '$compliance', '$soc')") or die(mysqli_error());	
                                                    $lastInsert = mysqli_insert_id($con);
                                                    $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                                                                  VALUES ('1', '16', '$lastInsert', now(), '$user')") or die(mysqli_error());
                                                    unset($_POST);
                                                    if($insert_acceso){
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
                                                            <label>Persona</label>
                                                            <select name="persona" class="form-control">
                                                                <?php
                                                                    $personasn = mysqli_query($con, "SELECT * FROM persona WHERE id_persona NOT IN (SELECT id_persona FROM permisos)");
                                                                    while($rowps = mysqli_fetch_array($personasn)){
                                                                        echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                                                        }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <label>Permisos</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="lectura" type="checkbox" value="1">
                                                                Solo lectura
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="edicion" type="checkbox" value="1">
                                                                Edición (Borrar ítems)
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="compliance" type="checkbox" value="1">
                                                                Compliance (Riesgos, Activos, ISO 27001, Controles)
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="soc" type="checkbox" value="1"> SOC
                                                                (Operaciones de Ciberseguridad)
                                                            </label>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-3">
                                                                <input type="submit" name="Addp"
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
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN TAB PERMISOS -->
                                <!-- TAB PERSONA -->
                                <div class="tab-pane" id="tab_0">
                                  <table id="personas" class="table table-bordered table-hover">
                                    <div class="col-sm-12" style="text-align:right;">
                                      <button type="button" class="btn-sm btn-primary" data-toggle="modal"
                                              data-target="#modal-persona"><i
                                              class="glyphicon glyphicon-user"></i> Nueva Persona</button>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th width="1">Nro1</th>
                                            <th width="1">Legajo</th>
                                            <th width="1">Nombre</th>
                                            <th width="1">email</th>
                                            <th width="1">Cargo</th>
                                            <th width="1">Gerencia</th>
                                            <th width="1">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        $query = "SELECT p.id_persona, p.nombre, p.apellido, p.legajo, p.email, p.cargo, g.nombre as gerencia 
                                        FROM persona as p 
                                        LEFT JOIN gerencia as g on p.gerencia = g.id_gerencia
                                        WHERE p.borrado = 0
                                        ORDER BY p.apellido ASC";
                                        
                                        $sql = mysqli_query($con, $query);
                                        if(mysqli_num_rows($sql) == 0){
                                            echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                        }
                                        else
                                        {
                                          while($row = mysqli_fetch_assoc($sql)){
                                            echo '<td align="center">'.$row['id_persona'].'</td>';
                                            echo '<td>'.$row['legajo'].'</td>';
                                            echo '<td>'.$row['apellido']. ' ' .$row['nombre']. '</td>';
                                            echo '<td>'.$row['email'].'</td>';
                                            echo '<td>'.$row['cargo'].'</td>'; 
                                            echo '<td>'.$row['gerencia'].'</td>'; 
                                            echo '<td align="center">
                                                    <a href="edit_persona.php?nik='.$row['id_persona'].'" title="Editar persona" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                                                    <a href="admin.php?aksi=delete&nik='.$row['id_persona'].'" title="Borrar persona" onclick="return confirm(\'Esta seguro de borrar los datos de '.$row['apellido']. ' ' .$row['nombre'].'?\')" class="btn btn-danger btn-sm ';
                                            echo '"><i class="glyphicon glyphicon-trash"></i></a></td>';
                                            echo '</tr>';
                                          }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th width="1">Nro</th>
                                            <th width="1">Legajo</th>
                                            <th width="1">Nombre</th>
                                            <th width="1">email</th>
                                            <th width="1">Cargo</th>
                                            <th width="1">Gerencia</th>
                                            <th width="1">Acciones</th>
                                        </tr>
                                    </tfoot>
                                  </table>
                                </div>

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
                                            if(isset($_POST['AddPersona'])){
                                              
                                              $legajo = mysqli_real_escape_string($con,(strip_tags($_POST["legajo"],ENT_QUOTES)));//Escanpando caracteres
                                              $nombre = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres
                                              $apellido = mysqli_real_escape_string($con,(strip_tags($_POST["apellido"],ENT_QUOTES)));//Escanpando caracteres 
                                              $cargo = mysqli_real_escape_string($con,(strip_tags($_POST["cargo"],ENT_QUOTES)));//Escanpando caracteres 
                                              $gerencia = mysqli_real_escape_string($con,(strip_tags($_POST["gerencia"],ENT_QUOTES)));//Escanpando caracteres 
                                              $email = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres 
                                              //Inserto Control
                                              $insert_persona = mysqli_query($con, "INSERT INTO persona(legajo, nombre, apellido, cargo, gerencia, email, borrado) VALUES ('$legajo','$nombre','$apellido', '$cargo', '$gerencia', '$email', 0)") or die(mysqli_error());	
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
                                                          <input type="submit" name="AddPersona"
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
                                      <!-- /.modal-content -->
                                  </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN TAB PERSONA -->

                                <!-- TAB LOG -->
                                <div class="tab-pane" id="tab_2">
                                    <!-- /contenido de tab 2 -->
                                    <table id="auditoria" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="1">Nro</th>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Usuario</th>
                                                <th>Objeto</th>
                                                <th>Ítem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $query = "SELECT i.id_auditoria, i.fecha, i.id_item, i.i_titulo, p.nombre, p.apellido, it.titulo, e.tipo FROM auditoria as i 
                                                                        LEFT JOIN persona as p on i.usuario = p.email
                                                                        LEFT JOIN item as it on i.item = it.id_item
                                                                        LEFT JOIN evento as e on i.evento = e.id_evento
                                                                        ORDER BY i.id_auditoria DESC";
                                              
                                              $sql = mysqli_query($con, $query);
                                              if(mysqli_num_rows($sql) == 0){
                                                echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                              }else{
                                                while($row = mysqli_fetch_assoc($sql)){
                                                                              
                                                                echo '
                                                  
                                                  
                                                  <td align="center">'.$row['id_auditoria'].'</td>';


                                                  echo '
                                                  
                                                  </td>								
                                                
                                                  <td>'.$row['fecha'].'</td>
                                                  
                                                  ';
                                                                echo '
                                                  
                                                  </td>								
                                                
                                                  <td>'.$row['tipo'].'</td>
                                                  ';
                                                  
                                                  echo '
                                                  </td>
                                                  <td>'.$row['apellido'].' '.$row['nombre']. '</td>'; 
                                                  
                                                  echo '
                                                  
                                                  </td>								
                                                
                                                  <td>'.$row['titulo'].'</td>
                                                  ';
                                                  
                                                                echo '
                                                  
                                                  </td>								
                                                
                                                  <td>'.$row['i_titulo'].'</td>
                                                  </td></tr>';
                                                  
                                                  
                                                  
                                                  
                                                }
                                              }
                                              ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th width="1">Nro</th>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Usuario</th>
                                                <th>Objeto</th>
                                                <th>Ítem</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- FIN TAB LOG -->
                              </div>
                              <!-- /.tab-content -->
                          </div>
                          <!-- /.box-body -->
                      </div>
                  </div>
              </section>
            </section>
            <!-- /.content -->
        </div>
    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Portal de Gestión
        </div>
        <!-- Default to the left -->
        <strong>Seguridad Informática - <a href="../site.php">ARSAT S.A.</a></strong>
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

    <!--
<script src="../bower_components/datatables.net/js/jszip.min.js"></script>
<script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>
<script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
<script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
 -->


    <script>
    window.onload = function() {
        history.replaceState("", "", "admin.php");
    }
    </script>
    <script>
    $(function() {
        $('#auditoria').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'info': true,
            'autoWidth': false,
        })
    })
    </script>
    <script>
    $(function() {
        $('#permisos').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'info': true,
            'autoWidth': false
        })
    })
    </script>
    <script>
    function updatePerm(perm, id_permiso) {

        var datap = perm;
        var rmvfile = id_permiso;

        if (confirm("Está seguro de cambiar los permisos?") == true) {
            if (id_permiso != '') {
                $.ajax({
                    type: 'post',
                    url: 'setPermiso.php',
                    data: {
                        rmvfile: rmvfile,
                        datap: datap
                    },
                    success: function(msg) {}
                });
            }
        }
    }
    </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>