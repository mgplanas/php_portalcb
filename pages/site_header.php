<?php
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

?>
<!-- Logo -->
<a href="../site.php" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">GITyS</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>GITyS</b>-ARSAT</span>
</a>
<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <?php 
    if ($_SERVER['PRODUCTION_SERVER'] == 0) {
        echo '<span class="label label-success">DESARROLLO</span>';
    }
    if ($_SERVER['PRODUCTION_SERVER'] == 1) {
        echo '<span class="label label-warning">ENTORNO DE TEST</span>';
    }
    ?>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- CONTADORES -->
            <!-- RIESGOS -->
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
                    <li class="footer"><a href="riesgos.php">Gestionar los riesgos</a></li>
                </ul>
            </li>
            
            <!-- ACTIVOS -->
            <li class="dropdown notifications-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-archive"></i>
                    <span class="label label-info"><?php echo $rowa; ?></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="header">Eres responsable de <?php echo $rowa; ?> activos</li>
                    
                    <li class="footer"><a href="activos.php">Ver Activos</a></li>
                </ul>
            </li>
            
            <!-- CONTROLES -->
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

            <!-- PROYECTOS -->
            <li class="dropdown tasks-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-list"></i>
                    <span class="label label-success"><?php echo $rowcp; ?></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="header">Tienes <?php echo $rowcp; ?> proyectos asignados</li>
                    
                    <li class="footer">
                    <a href="proyectos.php">Gestionar proyectos</a>
                    </li>
                </ul>
            </li>
            


            <!-- PERFIL DE USUARIO -->
            <!-- MENU DE PERFIL -->
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
                            <small>
                            <?php 
                                $qgerencia = mysqli_query($con, "SELECT nombre FROM gerencia WHERE id_gerencia =" . $rowp['gerencia'] );
                                $rowg = mysqli_fetch_assoc($qgerencia);
                                echo  $rowg['nombre'];                            
                            ?>
                            </small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <?php
                            if ($rq_sec['admin']=='1' || $rq_sec['admin_per']=='1' ){
                            echo '<a href="admin.php" class="btn btn-default btn-flat "><i class="fa fa-gears"></i> Admin. Permisos</a>';
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