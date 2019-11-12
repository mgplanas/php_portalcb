<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Compras"; 
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = '0'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

// INDICADORES
$sqlindicadores = "SELECT COUNT(IF( c.id_paso_actual='1',1,null)) as adjudicacion ";
$sqlindicadores = $sqlindicadores . ",COUNT(IF( c.id_paso_actual='2',1,null)) as Ofertas ";
$sqlindicadores = $sqlindicadores . ",COUNT(IF( c.id_paso_actual='3',1,null)) as Dictamen ";
$sqlindicadores = $sqlindicadores . ",COUNT(IF( c.id_paso_actual='4',1,null)) as EnvioSC ";
$sqlindicadores = $sqlindicadores . ",COUNT(IF( c.id_paso_actual='5',1,null)) as PET ";
$sqlindicadores = $sqlindicadores . ",COUNT(1) as total ";
$sqlindicadores = $sqlindicadores . "FROM adm_compras as c  ";
$sqlindicadores = $sqlindicadores . "WHERE c.borrado='0' AND c.id_estado = 1  ";
$sqlindicadores = $sqlindicadores . "AND ( 0 = " . $per_id_gerencia . " OR c.id_gerencia = " . $per_id_gerencia . " ); ";
$q_indicadores = mysqli_query($con, $sqlindicadores);
$rq_indicadores = mysqli_fetch_assoc($q_indicadores);	


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
  <link rel="stylesheet" href="../bower_components/datatables.net/css/rowGroup.dataTables.min.css">

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
<script>
  var flagShowComments = true;
  function showComments() {
      $('#modal-abm-compra-comments').modal('show');
  }    
  // function toggleComments() {
  //   if (flagShowComments) {
  //     $('#div_comentarios').hide();
  //     $('#div_compras_enproceso').removeClass('col-md-10');
  //     $('#div_compras_enproceso').addClass('col-md-12');
  //     $('#tbEnProceso').css("width","100%");
  //     flagShowComments = false;
  //   } else {
  //     $('#div_compras_enproceso').removeClass('col-md-12');
  //     $('#div_compras_enproceso').addClass('col-md-10');
  //     $('#div_comentarios').show();
  //     $('#tbEnProceso').css("width","100%");
  //     flagShowComments = true;
  //   }
  // }    

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


    function addcomment() { 
      // console.log($('#compra-selected-id').val());
        // Ejecuto
        let id_compra = $('#compra-selected-id').val();
        let comentario = $('#popover-comment').val();
        let usuario = $('#compra-id-persona').val();
        console.log(id_compra, '', comentario, '', usuario);
        $.ajax({
            type: 'POST',
            url: './helpers/abmcompracommentdb.php',
            data: {
                operacion: 'A',
                id_compra: id_compra,
                comentario: comentario,
                id_persona: usuario
            },
            dataType: 'json',
            success: function(json) {
                // $('#popover-add-comment').popover('hide'); 
                fn_popular_comentarios(id_compra);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });      
    };
</script>
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
      <!-- INDICADORES -->
      <div class="row">
        <!-- /.box-header -->
          <div class="col-xs-12 col-md-7"><h1>Gestión de Compras &nbsp;&nbsp;<small>Total de compras en proceso:&nbsp;<?=$rq_indicadores['total'] ?></small></h1></div>
          <div class="col-xs-6 col-md-1 text-center">
            <input id="knob_pet" type="text" class="knob" value="<?= (int)($rq_indicadores['PET']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="#3c8dbc">
            <div class="knob-label">PET</div>
          </div>
          <div class="col-xs-6 col-md-1 text-center">
            <input id="knob_ofertas" type="text" class="knob" value="<?= (int)($rq_indicadores['Ofertas']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="#3c8dbc">
            <div class="knob-label">Ofertas</div>
          </div>
          <div class="col-xs-6 col-md-1 text-center">
            <input id="knob_enviosc" type="text" class="knob" value="<?= (int)($rq_indicadores['EnvioSC']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="#3c8dbc">
            <div class="knob-label">Envío SC</div>
          </div>
          <div class="col-xs-6 col-md-1 text-center">
            <input id="knob_dictamen" type="text" class="knob" value="<?= (int)($rq_indicadores['Dictamen']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="#3c8dbc">
            <div class="knob-label">Dictamen</div>
          </div>
          <div class="col-xs-6 col-md-1 text-center">
            <input id="knob_adjudicacion" type="text" class="knob" value="<?= (int)($rq_indicadores['adjudicacion']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="#3c8dbc">
            <div class="knob-label">Adjudicación</div>
          </div>
        <!-- /.box-body -->
      </div>             
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
    <!--------------------------
    | Your Page Content Here |
    -------------------------->
	    <section class="content">
        
        <input type="hidden" class="form-control" name="id_persona" id='compra-id-persona' value="<?=$id_rowp ?>">
        <input type="hidden" class="form-control" name="id_gerencia" id='compra-id-gerencia' value="<?=$per_id_gerencia ?>">
            <!-- CONTENIDO -->
            <div class="row">         
                <div class="col-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <!-- BOTON -->
                        <div class="pull-right" style="margin: 10px;">
                            <button id="btn-showhide-comments" type="button" class="btn" onclick="showComments()"><i class="fa fa-comments"></i>&nbsp;&nbsp;Comentarios</button>
                            <!-- <button id="btn-group-table" type="button" class="btn"><i class="fa fa-fa-outdent"></i>&nbsp;&nbsp;Agrupar</button> -->
                            <button id="modal-abm-compra-btn-alta" type="button" class="btn btbn-block btn-primary btn-sm">Nueva Compra</button>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">En proceso</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Adjudicadas</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- En proceso -->
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <input type="hidden" class="form-control" name="id" id='compra-selected-id' >
                                    <!-- COMPRAS -->
                                    <div id="div_compras_enproceso" class="col-md-12">
                                        <div class="box">
                                            <div class="box-body">
                                                <table id="tbEnProceso" class="display w-auto" witdh="100%">
                                                    <thead>
                                                    <tr>
                                                        <th width="1">#</i> </th>
                                                        <th width="1">comentarios</i> </th>
                                                        <th>Subgerencia</th>
                                                        <th>Fecha</th>
                                                        <th>Nro</th>
                                                        <th>Concepto</th>
                                                        <th>PE</th>
                                                        <th>Paso actual</th>
                                                        <th>Siguiente</th>
                                                        <th width="30px" style="text-align: center;"><i class="fa fa-bolt"></i> </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $query = "SELECT C.*, sub.nombre as subgerencia, mon.sigla as moneda, cur_step.descripcion as cur_step_desc, next_step.descripcion as next_step_desc
                                                            , (SELECT COUNT(1) FROM adm_compras_comments as com WHERE C.id = com.id_compra) as comentarios
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
                                                                echo '<td>'. $row['comentarios'] .'</td>';
                                                                echo '<td>'. $row['subgerencia'] .'</td>';
                                                                echo '<td>'. $row['fecha_solicitud'] .'</td>';
                                                                echo '<td>'. $row['nro_solicitud'] .'</td>';
                                                                echo '<td>'. $row['concepto'] .'</td>';
                                                                echo '<td align="right">'. $row['moneda'] .' ' . $row['pre_monto'] . '</td>';
                                                                echo '<td align="center">'. $row['cur_step_desc'] .'</td>';
                                                                echo '<td align="center">'. $row['next_step_desc'] .'</td>';
                                                                echo '<td align="center">';
                                                                if ($row['comentarios']>0) {
                                                                  echo '<a data-id="'.$row['id'].'" class="btn" title="'.$row['comentarios'].' comentarios" style="padding: 2px;" onclick="showComments();"><i class="fa fa-comments"></i></a>';
                                                                }
                                                                echo '<a data-id="'.$row['id'].'" title="Ver detalles" class="btn"style="padding: 2px;"><i class="fa fa-eye"></i></a>';
                                                                echo '<a data-id="'.$row['id'].'" title="editar" class="modal-abm-compra-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit" style="color: red;"></i></a></td>';
                                                                echo '</tr>';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <!-- MODAL ADD COMPRA -->
                                                <?php
                                                    include_once('./modals/abmcompra.php');
                                                    include_once('./modals/compracomments.php');
                                                ?>                                                
                                            </div>
                                            <!-- /.box-body -->
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
<!-- Popper -->
<script src="../bower_components/popper/popper.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../bower_components/datatables.net/js/dataTables.rowGroup.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- ChartJS
<script src="../bower_components/chart.js/Chart.js"></script> -->
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- jQuery Knob Chart -->
<script src="../bower_components/jquery-knob/dist/jquery.knob.min.js"></script>

<!-- MODALES -->
<script src="./modals/abmcompra.js"></script>

<script>
  $(function () {



    $('#tbEnProceso').DataTable({
      'language': { 'emptyTable': 'No hay compras' },
      'paging'      : true,
      'pageLength': 10,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      'columnDefs'  : [
        {'targets': [ 0 , 1 ], 'visible': false},
        {'targets': [2,3,4,6,7,8] , 'width': '10%' },
        {'targets': [9], 'width': '7%' }
      ],
      'drawCallback': function(settings) {
        $('#btn-showhide-comments').prop('disabled', 'true');
      },
      // 'rowGroup': {
      //       'dataSrc': [ 2 ]
      //   },
    });

    $('#tbEnProceso tbody').on('click', 'tr', function(event){
     
      let tb = $('#tbEnProceso').dataTable();
      let datarow = tb.fnGetData(this);
      let id = datarow[0];
      let comments = parseInt(datarow[1]);

      tb.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
      // seteo el id de la fila seleccionada para que lo use el commentario
      $('#compra-selected-id').val(id);
      fn_popular_comentarios(id);
      $('#btn-showhide-comments').removeAttr('disabled');
      // $("#popover-add-comment").popover('enable');
    });
    // $('#tbEnProceso').on( 'page', function () {
    //   $('#btn-showhide-comments').prop('disabled', 'true');
    // } );        
    // $("#popover-add-comment").popover('disable');
    
    // $("#popover-add-comment-icon").css('color: gray;');

    /* jQueryKnob */
    $(".knob").knob({       
      //  release : function (value) {
      //  console.log("release : " + value);
      //  },
      //  cancel : function () {
      //  },
      "readOnly": true,
      "cursor": false,
      "bgColor": "#FFFFFF",
      "skin": "tron",
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

    // SET TEXT
    $('#knob_pet').val(<?=$rq_indicadores['PET'] ?>);
    $("#knob_pet").attr('disabled','disabled');
    $('#knob_ofertas').val(<?=$rq_indicadores['Ofertas'] ?>);
    $("#knob_ofertas").attr('disabled','disabled');
    $('#knob_enviosc').val(<?=$rq_indicadores['EnvioSC'] ?>);
    $("#knob_enviosc").attr('disabled','disabled');
    $('#knob_dictamen').val(<?=$rq_indicadores['Dictamen'] ?>);
    $("#knob_dictamen").attr('disabled','disabled');
    $('#knob_adjudicacion').val(<?=$rq_indicadores['adjudicacion'] ?>);
    $("#knob_adjudicacion").attr('disabled','disabled');

    // Popper
    // $('[data-toggle="popover"]').popover();
    // $('#popover-add-comment').on('shown.bs.popover', function () {
    //   $('#popover-comment').val('');
    //   $('#popover-comment').focus();
    // });

    $('#btn-showhide-comments').prop('disabled', 'true');

    // toggleComments();
});
</script>
</body>
</html>