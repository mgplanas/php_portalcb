<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Métricas"; 
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = '0'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

// INDICADORES
// $sqlTmpISO27k = "SELECT 1 as total 
//                 FROM controls.item_iso27k 
//                 INNER JOIN persona as p ON item_iso27k.responsable = p.id_persona
//                 WHERE item_iso27k.madurez=:madurez 
//                 AND item_iso27k.version = (SELECT id FROM iso27k_version WHERE borrado = 0 ORDER BY modificacion desc LIMIT 1) 
//                 AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";
// $qiso_def = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '1', ':per_id_gerencia' => $per_id_gerencia)));
// $iso_def = mysqli_num_rows($qiso_def);
// $qiso_exc = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '2', ':per_id_gerencia' => $per_id_gerencia)));
// $iso_exc = mysqli_num_rows($qiso_exc);
// $qiso_perf = mysqli_query($con, strtr($sqlTmpISO27k, array(':madurez' => '3', ':per_id_gerencia' => $per_id_gerencia)));
// $iso_perf = mysqli_num_rows($qiso_perf);



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
  <link rel="stylesheet" href="../bower_components/datatables.net/css/jquery.dataTables.min.css">

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
    <section class="content-header">
            <h1>Gestión de Compras</h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
    <!--------------------------
    | Your Page Content Here |
    -------------------------->
	    <section class="content">
            <!-- INDICADORES -->
            <div class="row">
            <!-- /.box-header -->
                <div class="col-xs-12 col-md-8 text-center"></div>
                <div class="col-xs-6 col-md-1 text-center">
                  <input type="text" class="knob" value="30" data-width="90" data-height="90" data-fgColor="#3c8dbc">

                  <div class="knob-label">New Visitors</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-6 col-md-1 text-center">
                  <input type="text" class="knob" value="70" data-width="90" data-height="90" data-fgColor="#f56954">

                  <div class="knob-label">Bounce Rate</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-6 col-md-1 text-center">
                  <input type="text" class="knob" value="-80" data-min="-150" data-max="150" data-width="90" data-height="90" data-fgColor="#00a65a">

                  <div class="knob-label">Server Load</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-6 col-md-1 text-center">
                  <input type="text" class="knob" value="40" data-width="90" data-height="90" data-fgColor="#00c0ef">

                  <div class="knob-label">Disk Space</div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
          </div>         
            <!-- CONTENIDO -->
            <div class="row">
                <div class="col-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">En proceso</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Adjudicadas</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- En proceso -->
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <!-- COMPRAS -->
                                    <div class="col-md-9">
                                        <div class="box">
                                            <div class="box-body">
                                                <table id="tbEnProceso" class="display w-auto" witdh="70%">
                                                    <thead>
                                                    <tr>
                                                        <th width="1">#</i> </th>
                                                        <th>Subgerencia</th>
                                                        <th>Fecha</th>
                                                        <th>Nro</th>
                                                        <th>Concepto</th>
                                                        <th>PE</th>
                                                        <th>Paso actual</th>
                                                        <th>Siguiente</th>
                                                        <th width="1"><i class="fa fa-bolt"></i> </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $query = "SELECT C.*, sub.nombre as subgerencia, mon.sigla as moneda, cur_step.descripcion as cur_step_desc, next_step.descripcion as next_step_desc
                                                            FROM adm_compras as C
                                                            LEFT JOIN subgerencia as sub ON C.id_subgerencia = sub.id_subgerencia
                                                            LEFT JOIN adm_monedas as mon ON C.pre_id_moneda = mon.id
                                                            LEFT JOIN adm_com_pasos as cur_step ON C.id_paso_actual = cur_step.id
                                                            LEFT JOIN adm_com_pasos as next_step ON C.id_siguiente_paso = next_step.id
                                                            WHERE C.borrado = 0
                                                            AND C.id_estado = 1 ";
                                                            // AGREGO EL FILTRO DE GERENCIA DEL USUARIO=CIBERSEGURIDAD O LA GERENCIA DEL REFERENTE
                                                            // if ( $per_id_gerencia != 1) {
                                                            if ($rq_sec['admin']=='0') {
                                                                $query = $query . " AND C.id_gerencia = $per_id_gerencia ";
                                                            }                                         

                                                            $sql = mysqli_query($con, $query . "ORDER BY C.id DESC");

                                                            while($row = mysqli_fetch_assoc($sql)){

                                                                echo '<tr>';
                                                                echo '<td>'. $row['id'] .'</td>';
                                                                echo '<td>'. $row['subgerencia'] .'</td>';
                                                                echo '<td>'. $row['fecha_solicitud'] .'</td>';
                                                                echo '<td>'. $row['nro_solicitud'] .'</td>';
                                                                echo '<td>'. $row['concepto'] .'</td>';
                                                                echo '<td>'. $row['moneda'] .' ' . $row['pre_monto'] . '</td>';
                                                                echo '<td>'. $row['cur_step_desc'] .'</td>';
                                                                echo '<td>'. $row['next_step_desc'] .'</td>';
                                                                echo '<td><a data-id="'.$row['id'].'" title="ver más info" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a></td>';
                                                                echo '</tr>';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>  
                                    </div>
                                    <!-- COMMENTS -->
                                    <div class="col-md-3">
                                        <div class="box box-success">
                                            <div class="box-header">
                                                <i class="fa fa-comments-o"></i>
                                                <h3 class="box-title">Comentarios</h3>
                                            </div>
                                            <div class="box-body chat" id="chat-box">
                                            <div class="box-footer">
                                            <div class="input-group">
                                                <input class="form-control" placeholder="Type message...">

                                                <div class="input-group-btn">
                                                <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>                                                
                                    </div>                                        
                                </div>
                              
                            </div>
                            <!-- Adjudicadas -->
                            <div class="tab-pane" id="tab_2">
                            </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </div>
            </div>    
        </section>
    <!-- /.content -->
  </div>
  <!-- Main Footer -->
  <?php include_once('./site_footer.php'); ?>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- ChartJS
<script src="../bower_components/chart.js/Chart.js"></script> -->
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- jQuery Knob Chart -->
<script src="../bower_components/jquery-knob/dist/jquery.knob.min.js"></script>

<script>
  $(function () {

    // Populo gerencias
    function fn_popular_comentarios(id_compra){
      // Busco el servicio
      $.ajax({
          type: 'POST',
          url: './helpers/getAsyncDataFromDB.php',
          data: { query: "select c.*, p.nombre, p.apellido FROM adm_compras_comments as c INNER JOIN persona as p ON c.id_persona = p.id_persona WHERE c.id_compra = " + id_compra + " AND c.borrado = 0 ORDER BY c.id DESC" },
          dataType: 'json',
          success: function(json) {
                $('#chat-box').empty();
                if ("data" in json == true) {
                    $.each(json.data, function(i, d) {
                        $('#chat-box').append('<div class="item">')
                        $('#chat-box').append('<i class="fa fa-info-circle" style="font-size:16px;"></i>');
                        $('#chat-box').append('<a href="#" class="name"><small class="text-muted pull-right"><i class="fa fa-clock-o"></i> ' + d.fecha + '</small>&nbsp;' + d.apellido + ', ' + d.nombre + '</a>');
                        $('#chat-box').append('<p>' + d.comentario + '</p>'); 
                        $('#chat-box').append('</div>'); 
                    });
                }
          },
          error: function(xhr, status, error) {
            $('#chat-box').empty();
            alert(xhr.responseText, error);
          }
      });
    }


    $('#tbEnProceso').DataTable({
      'language': { 'emptyTable': 'No hay proyectos' },
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true,
    });

    $('#tbEnProceso').on('click', 'tr', function(event){
        let tb = $('#tbEnProceso').dataTable();
        let datarow = tb.fnGetData(this);
        let id = datarow[0];
        fn_popular_comentarios(id);
    });

    /* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
});
</script>
</body>
</html>