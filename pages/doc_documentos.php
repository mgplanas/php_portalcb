<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}
$page_title="Documentos"; 
$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = '0'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
$_SESSION['id_usuario'] = $id_rowp;
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO
/// BORRADO DE ENTES
if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
    //Elimino ENTE
    $delete_control = mysqli_query($con, "UPDATE doc_documentos SET borrado='1' WHERE id='$nik'");
  
    if(!$delete_control){
        $_SESSION['formSubmitted'] = 9;
    }
}

// INDICADORES
$sqlindicadores = "SELECT 
  COUNT(IF (DATEDIFF(doc.proxima_actualizacion, NOW()) > 30  ,1,null) ) as vigentes
  , COUNT(IF (DATEDIFF(doc.proxima_actualizacion, NOW()) <= 30 AND DATEDIFF(doc.proxima_actualizacion, NOW()) >= 0, 1, null) ) as proximos
, COUNT(IF (DATEDIFF(doc.proxima_actualizacion, NOW()) < 0  ,1,null) ) as vencidos
,COUNT(1) as total 
FROM doc_documentos as doc  
WHERE doc.borrado='0';";
$q_indicadores = mysqli_query($con, $sqlindicadores);
$rq_indicadores = mysqli_fetch_assoc($q_indicadores);	

$__LOW = 3;
$__HIGH_2 = 10;
$__HIGH = 5;
$__DIFF_DAYS = 2;

if ($rq_indicadores['vigentes'] <= $__LOW) {$i_vigentes_color = '#00a65a';}
else if ($rq_indicadores['vigentes'] >= $__HIGH) {$i_vigentes_color = '#00a65a';}
else {$i_vigentes_color = '#00a65a';}

if ($rq_indicadores['proximos|'] <= $__LOW) {$i_proximos_color = '#f39c12';}
else if ($rq_indicadores['proximos|'] >= $__HIGH) {$i_proximos_color = '#f39c12';}
else {$i_proximos_color = '#f39c12';}

if ($rq_indicadores['vencidos'] <= $__LOW) {$i_vencidos_color = '#f56954';}
else if ($rq_indicadores['vencidos'] >= $__HIGH_2) {$i_vencidos_color = '#f56954';}
else {$i_vencidos_color = '#f56954';}

// $sql = "SELECT id_paso, COUNT(1) as cuenta, FLOOR(AVG(DATEDIFF(now(), fecha))) as promedio
// FROM adm_compras_pasos_hist 
// WHERE id_paso > 0
// GROUP BY id_paso";

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				

function formatDate($value) {
    $gmtTimezone = new DateTimeZone('GMT');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha ='';
    if ($value && $value != '0000-00-00 00:00:00') {
         $fecha_dt = new DateTime($value);
         $fecha = $fecha_dt->format('d/m/Y');
    }

    return $fecha;
}

?>
<style>
.dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}
.direct-search {
  cursor: pointer;
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
  <!-- <link rel="stylesheet" href="../bower_components/datatables.net/css/rowGroup.dataTables.min.css"> -->
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
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

    .direct-vigente, .direct-vencido, .direct-proximo {
        background: transparent !important;
        cursor: pointer;
    }
    .direct-vigente.selected{
        background-color: #00a65a !important;
        color: white !important;
        font-weight: bold !important;
    }
    .direct-vencido.selected{
        background-color: #f56954 !important;
        color: white !important;
        font-weight: bold !important;
    }
    .direct-proximo.selected{
        background-color: #f39c12 !important;
        color: white !important;
        font-weight: bold !important;
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
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/moment/locale/es.js" charset="UTF-8"></script>

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
          <div class="col-xs-12 col-md-9"><h1>Gestión de documentos DC &nbsp;&nbsp;<small>Total:&nbsp;<?=$rq_indicadores['total'] ?></small></h1></div>
          <div class="col-xs-3 col-md-1 text-center">
            <input id="knob_vigentes" type="text" class="knob" value="<?= (int)($rq_indicadores['vigentes']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="<?=$i_vigentes_color ?>">
            <div class="knob-label direct-vigente">Vigentes</div>
          </div>
          <div class="col-xs-6 col-md-1 text-center">
            <input id="knob_proximos" type="text" class="knob" value="<?= (int)($rq_indicadores['proximos']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="<?=$i_proximos_color ?>">
            <div class="knob-label direct-proximo">Próximos</div>
          </div>
          <div class="col-xs-6 col-md-1 text-center">
            <input id="knob_vencidos" type="text" class="knob" value="<?= (int)($rq_indicadores['vencidos']/$rq_indicadores['total']*100) ?>" data-width="60" data-height="60" data-fgColor="<?=$i_vencidos_color ?>">
            <div class="knob-label direct-vencido">Vencidos</div>
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
                <div class="box">
                    <div class="box-header">
                        <div class="col-sm-12" style="text-align:right;">
                            <button type="button" id="modal-abm-doc-btn-alta" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-activo"><i class="fa fa-file"></i> Nueva Documentación</button>
                        </div>
                    </div>                    
                    <div class="box-body">
                        <table id="tbDocumentos" class="display" width="100%">
                            <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Versión</th>
                                <th>Nombre</th>
                                <!-- <th>Área</th> -->
                                <th>Dueño</th>
                                <th>Fecha Vigencia</th>
                                <th>Fecha de Próxima Actualización</th>
                                <th>Estado</th>
                                <!-- <th>Path</th> -->
                                <!-- <th>Frecuencia de revisión en días</th> -->
                                <th>Aprobado</th>
                                <!-- <th>Versión Aprobación</th> -->
                                <!-- <th>Nombre minuta</th> -->
                                <!-- <th>Path del documento de aprobación</th> -->
                                <!-- <th>Periodicidad de la comunicación</th> -->
                                <!-- <th>Forma de comunicación</th> -->
                                <!-- <th>Fecha Comumicación</i></th> -->
                                <th width="70px" style="text-align: right;"><i class="fa fa-bolt"></i> </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = "SELECT doc.*
                                    , CONCAT(owner.apellido, ', ', owner.nombre) as owner
                                    , area.descripcion as area
                                    , forma.forma as forma_com
                                    , forma.descripcion as forma_desc
                                    , per.periodicidad as periodicidad_com
                                    , per.descripcion as periodicidad_desc
                                    , tipo.tipo as tipo_doc
                                    , tipo.descripcion as tipo_desc
                                    , (DATEDIFF(doc.proxima_actualizacion, NOW())) as dias
                                    FROM doc_documentos as doc
                                    INNER JOIN persona as owner ON doc.id_owner = owner.id_persona
                                    LEFT JOIN doc_areas as area ON doc.id_area = area.id
                                    LEFT JOIN doc_formas_com as forma ON doc.id_forma_com = forma.id
                                    LEFT JOIN doc_periodicidad as per ON doc.id_periodicidad_com = per.id
                                    LEFT JOIN doc_tipos as tipo ON doc.id_tipo = tipo.id
                                    WHERE doc.borrado = 0 ";                                

                                    $sql = mysqli_query($con, $query . "ORDER BY doc.id DESC;");

                                    while($row = mysqli_fetch_assoc($sql)){

                                        echo '<tr>';
                                        echo '<td style="text-align: center;">'. $row['tipo_doc'] .'</td>';
                                        echo '<td style="text-align: center;">'. $row['version'] .'</td>';
                                        echo '<td>'. $row['nombre'] .'</td>';
                                        // echo '<td>'. $row['area'] .'</td>';
                                        echo '<td>'. $row['owner'] .'</td>';
                                        echo '<td style="text-align: center;">'. formatDate($row['vigencia']) .'</td>';
                                        echo '<td style="text-align: center;"><span class="badge bg-'; 
                                            if ($row['dias'] > 30) {
                                                echo 'green';
                                            } else if ($row['dias'] < 0) {
                                                echo 'red';
                                            } else {
                                                echo 'yellow';
                                            }
                                            echo '">' . formatDate($row['proxima_actualizacion']) . '</span>';
                                        echo '</td>';
                                        echo '<td>'; 
                                            if ($row['dias'] > 30) {
                                                echo 'vigente';
                                            } else if ($row['dias'] < 0) {
                                                echo 'vencido';
                                            } else {
                                                echo 'proximo';
                                            }
                                        echo '</td>';
                                        // echo '<td>' . $row['path'] . '</td>';
                                        // echo '<td style="text-align: center;">'. $row['frecuencia_revision'] .'</td>';
                                        echo '<td style="text-align: center;">'. formatDate($row['aprobado']) .'</td>';
                                        // echo '<td style="text-align: center;">'. $row['aprobado_version'] .'</td>';
                                        // echo '<td>'. $row['aprobado_minuta'] .'</td>';
                                        // echo '<td>'. $row['aprobado_path'] .'</td>';
                                        // echo '<td>'. $row['periodicidad_com'] .'</td>';
                                        // echo '<td style="text-align: center;">'. $row['forma_com'] .'</td>';
                                        // echo '<td style="text-align: center;">'. formatDate($row['comunicado']) .'</td>';
                                        echo '<td align="right">';
                                        // echo '<a data-id="'.$row['id'].'" title="Ver detalles" class="modal-abm-docs-btn-view btn"style="padding: 2px;"><i class="fa fa-eye"></i></a>';
                                        echo '<a data-frecuencia="'.$row['frecuencia_revision'].'" data-nombre="'.$row['nombre'].'" data-id="'.$row['id'].'" title="revisar" class="modal-abm-docs-btn-review btn" style="padding: 2px;"><i class="fa fa-eye"></i></a>';
                                        echo '<a data-version="'.$row['version'].'" data-nombre="'.$row['nombre'].'" data-id="'.$row['id'].'" data-minuta="'.$row['aprobado_minuta'].'" title="aprobar" class="modal-abm-docs-btn-aprobar btn" style="padding: 2px;"><i class="fa fa-thumbs-o-up"></i></a>';
                                        if ($rq_sec['admin_doc'] == '1') {
                                            echo '<a data-id="'.$row['id'].'" title="editar" class="modal-abm-doc-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit"></i></a>';
                                            echo '<a href="doc_documentos.php?aksi=delete&nik='.$row['id'].'" title="Eliminar Documento" style="padding: 2px;" onclick="return confirm(\'Esta seguro de borrar el documento '. $row['nombre'] .' ?\')" class="btn btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
                                            // echo '<a data-id="'.$row['id'].'" title="eliminar" class="modal-abm-docs-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash"></i></a>';
                                        }
                                        echo '</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>                                            
                    </div>
                    <?php include_once('./modals/abmdoc.php'); ?>
                    <?php include_once('./modals/abmdoc_aprobar.php'); ?>
                    <!-- /.box-body -->
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
<!-- <script src="../bower_components/popper/popper.min.js"></script> -->
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- <script src="../bower_components/datatables.net/js/dataTables.rowGroup.min.js"></script> -->
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- InputMask -->
<!-- <script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script> -->
<!-- date-range-picker -->
<!-- <script src="../bower_components/moment/min/moment.min.js"></script> -->
<!-- <script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script> -->
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- ChartJS
<script src="../bower_components/chart.js/Chart.js"></script> -->
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- jQuery Knob Chart -->
<script src="../bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- export -->
<script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
<script src="../bower_components/datatables.net/js/jszip.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
<script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
<script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
<script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>
<script src="../bower_components/datatables.net/js/buttons.colVis.min.js"></script>
<!-- MODALES -->
<script src="./modals/abmdoc.js"></script>

<script>
    $(function() {
        $('#tbDocumentos').DataTable({
            'language': { 'emptyTable': 'No hay documentos' },
            'ordering': true,
            'paging': true,
            'pageLength': 20,
            'lengthChange': false,
            'searching': true,
            
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtp',
            'columnDefs'  : [
                {'targets': [ 6 ], 'visible': false},
            ],
            'buttons': [{
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',

                },
                {
                    extend: 'excel',
                    text: 'Excel',
                }
            ]
        });
        var table = $('#tbDocumentos').DataTable();
        $('#tbDocumentos thead tr').clone(true).appendTo( '#tbDocumentos thead' );
        $('#tbDocumentos thead tr:eq(1) th').each( function (colIdx) {
            $(this).removeClass('sorting sorting_asc sorting_desc');
            var table = $('#tbDocumentos').DataTable();

            // Si son las columnas de filtro creo el ddl
            if (colIdx == 0 || colIdx == 3 ) {
                var select = $('<select style="width: 100%;"><option value=""></option></select>')
                .on( 'change', function () {
                    table
                        .column( colIdx )
                        .search( $(this).val() )
                        .draw();
                } )
                .on( 'click' , function(){return false;} )
                // .wrap( "<div></div>" );             // VER
                // Get the search data for the first column and add to the select list
                table
                    .column( colIdx )
                    .cache( 'search' )
                    .sort()
                    .unique()
                    .each( function ( d ) {
                        select.append( $('<option value="'+d+'">'+d+'</option>') );
                    });
                
                var filterhtml = select.parent().prop('outerHTML');
                $(this).html(select);
                // $(this).html(filterhtml);

            }
            else {
                $(this).html("");
            }

        } );            
    })
</script>
<script>
  $(function () {

    $('#popover-comment').on('keypress', function(event){
      if (event.which == 13) {
        event.preventDefault();
        addcomment();
        $('#popover-comment').val('');
        $('#popover-comment').focus();
      }
    });

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
    $('#knob_vigentes').val(<?=$rq_indicadores['vigentes'] ?>);
    $("#knob_vigentes").attr('disabled','disabled');
    $('#knob_vencidos').val(<?=$rq_indicadores['vencidos'] ?>);
    $("#knob_vencidos").attr('disabled','disabled');
    $('#knob_proximos').val(<?=$rq_indicadores['proximos'] ?>);
    $("#knob_proximos").attr('disabled','disabled');

    $('#btn-showhide-comments').prop('disabled', 'true');

    function resetSearchSelection() {
        $('.direct-vencido').removeClass('selected');
        $('.direct-proximo').removeClass('selected');
        $('.direct-vigente').removeClass('selected');
    }

    $('.direct-vencido').on( 'click', function () {
        if ($(this).hasClass( "selected" )) {
            $('#tbDocumentos').DataTable().columns( 6 ).search( '' ).draw();
        } else {
            resetSearchSelection();
            $('#tbDocumentos').DataTable().columns( 6 ).search( 'vencido' ).draw();
        }
        $(this).toggleClass( "selected" );
    });
    $('.direct-proximo').on( 'click', function () {
        if ($(this).hasClass( "selected" )) {
            $('#tbDocumentos').DataTable().columns( 6 ).search( '' ).draw();
        } else {
            resetSearchSelection();
            $('#tbDocumentos').DataTable().columns( 6 ).search( 'proximo' ).draw();
        }
        $(this).toggleClass( "selected" );
    });
    $('.direct-vigente').on( 'click', function () {
        if ($(this).hasClass( "selected" )) {
            $('#tbDocumentos').DataTable().columns( 6 ).search( '' ).draw();
        } else {
            resetSearchSelection();
            $('#tbDocumentos').DataTable().columns( 6 ).search( 'vigente' ).draw();
        }
        $(this).toggleClass( "selected" );
    });


});
</script>
</body>
</html>