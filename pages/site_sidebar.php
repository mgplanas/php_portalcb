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
    <?php if ($rq_sec['admin']=='1' OR $rq_sec['compliance']=='1'){ ?>
      <li><a href="controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
      <li><a href="iso27k.php"><i class="fa fa-crosshairs"></i> <span>ISO 27001</span></a></li>
      <li><a href="iso9k.php"><i class="fa fa-crosshairs"></i> <span>ISO 9001</span></a></li>
      <li><a href="mejoras.php"><i class="fa fa-refresh"></i> <span>Mejora Continua</span></a></li>
      <li><a href="riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
    <?php }?>
    <?php if ($rq_sec['admin']=='1' OR $rq_sec['proy']=='1' OR $rq_sec['admin_proy']=='1'){
          echo '<li><a href="proyectos.php"><i class="fa fa-list"></i> <span>Proyectos</span></a></li>';
    }?>
    
    <?php if ($rq_sec['admin']=='1' OR $rq_sec['soc']=='1'){
    echo '<li><a href="calendario.php"><i class="fa fa-calendar"></i> <span>Calendario</span></a></li>';
    echo '<li><a href="novedades.php"><i class="fa fa-envelope"></i> <span>Novedades</span></a></li>';
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
    <?php if ($rq_sec['admin']=='1' OR $rq_sec['cli_dc']=='1'){ ?>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-cloud"></i> <span>Clientes DC</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <!-- <li><a href="cdc_dashboard.php"><i class="fa fa-pie-chart"></i> Dashboard</a></li> -->
        <li><a href="cdc_organismo.php"><i class="fa fa-building"></i> Organismos</a></li>
        <li><a href="cdc_cliente.php"><i class="fa fa-user"></i> Clientes</a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-gears"></i> Servicios
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="sdc_housing.php"><i class="fa fa-home"></i> Housing</a></li>
            <li><a href="sdc_hosting.php"><i class="fa fa-server"></i> Hosting</a></li>
          </ul>
        </li>
      </ul>
    </li>    
    <?php } ?>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-pie-chart"></i><span>Métricas</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="met_activos.php"><i class="fa fa-archive"></i>Activos</a></li>
        <li><a href="met_iso27k.php"><i class="fa fa-crosshairs"></i> <span>ISO 27001</span></a></li>
        <li><a href="met_riesgos.php"><i class="fa fa-flash"></i> <span>Riesgos</span></a></li>
        <li><a href="met_controles.php"><i class="fa fa-retweet"></i> <span>Controles</span></a></li>
        <li><a href="met_mejoras.php"><i class="fa fa-refresh"></i> <span>Mejoras</span></a></li>
      </ul>
    </li>

  </ul>
  <!-- /.sidebar-menu -->
</section>
<!-- /.sidebar -->
</aside>