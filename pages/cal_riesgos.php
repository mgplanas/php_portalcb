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
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				
		
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
    .mesactual {
        background-color: lightgrey;
    }
    .mesactualHeader {
        background-color: grey;
        color: white;
    }
    .rotateheader {
        /* Safari */
        -webkit-transform: rotate(-90deg);
        /* Firefox */
        -moz-transform: rotate(-90deg);
        /* IE */
        -ms-transform: rotate(-90deg);
        /* Opera */
        -o-transform: rotate(-90deg);
        float: left;
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
            <?php include_once('./site_header.php'); ?>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <?php include_once('./site_sidebar.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    Gestión de Controles
                    <small>General</small>
                </h1>
            </section>
            <!-- Main content -->
            <section class="content container-fluid">
                <!--------------------------
                | Your Page Content Here |
                -------------------------->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <div class="col-sm-6" style="text-align:left">
                                        <h2 class="box-title">Calendario de Riesgos</h2>
                                    </div>
                                    <div class="col-sm-6" style="text-align:right;">
                                    </div>
                                </div>
                                <!-- MODAL CONTROL -->
                                <div id="ver-itemDialog" class="modal fade">
                                    <div class="modal-dialog" style="width:900px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                    <button type="button" id="criticidad" class="btn"></button>
                                                
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="box box-primary">
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                <div class="col-md-9">
                                                                    <label for="titulo"> Título</label>
                                                                    <input type="text" class="form-control" name="titulo" id="titulo" value="" readonly>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="periodicidad"> Periodicidad</label>
                                                                    <input type="text" class="form-control" name="periodicidad" id="periodicidad" value="" readonly>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="contenido"> Contenido</label>
                                                                <textarea class="form-control" rows="2" id="contenido" value="" readonly></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="responsable"><i class="glyphicon glyphicon-user"></i> Responsable</label>
                                                                <input type="text" class="form-control" name="responsable" id="responsable" value="" readonly>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="box box-primary">
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="estatus"> Estado</label>
                                                                    <input type="text" class="form-control" name="estatus" id="estatus" value="" readonly>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="controlador"><i class="glyphicon glyphicon-user"></i>Controlador</label>
                                                                    <input type="text" class="form-control" name="controlador" id="controlador" value="" readonly>
                                                                </div>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group" id="divaccion">
                                                                <label for="accion"><i class="glyphicon glyphicon-flash"></i> Acción</label>
                                                                <textarea class="form-control" rows="2" id="accion" value="" readonly></textarea>
                                                            </div>
                                                            <div class="form-group" id="divobservacion">
                                                                <label for="observacion"><i class="glyphicon glyphicon-eye-open"></i> Observación</label>
                                                                <textarea class="form-control" rows="2" id="observacion" value="" readonly></textarea>
                                                            </div>
                                                            <div class="form-group" id="divevidencia">
                                                                <label for="evidencia"><i class="glyphicon glyphicon-list-alt"></i> Evidencia</label>
                                                                <textarea class="form-control" rows="2" id="evidencia" value="" readonly></textarea>
                                                            </div>                                                                                                                  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div>
                                <!-- FIN MODAL CONTROL -->
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="riesgos" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                            <?php
                                            $mActual = date('m');
                                                echo '<th>Gerencia</th>';
                                                echo '<th ' . (1==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Enero</th>';
                                                echo '<th ' . (2==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Febrero</th>';
                                                echo '<th ' . (3==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Marzo</th>';
                                                echo '<th ' . (4==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Abril</th>';
                                                echo '<th ' . (5==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Mayo</th>';
                                                echo '<th ' . (6==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Junio</th>';
                                                echo '<th ' . (7==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Julio</th>';
                                                echo '<th ' . (8==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Agosto</th>';
                                                echo '<th ' . (9==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Septiembre</th>';
                                                echo '<th ' . (10==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Octubre</th>';
                                                echo '<th ' . (11==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Novimebre</th>';
                                                echo '<th ' . (12==$mActual ? 'class="mesactualHeader"' : ''  ) . '>Diciembre</th>';
                                            ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query = "SELECT g.nombre,
                                                                date_format(str_to_date(r.vencimiento, '%d/%m/%Y'), '%m') as mes, 
                                                                COUNT(1) as cuenta
                                                            FROM riesgo as r
                                                            INNER JOIN persona as p ON r.responsable = p.id_persona
                                                            LEFT JOIN gerencia as g ON p.gerencia= g.id_gerencia
                                                            WHERE r.borrado=0 AND r.estado='0'
                                                            AND date_format(str_to_date(r.vencimiento, '%d/%m/%Y'), '%Y') = YEAR(NOW())
                                                            AND ( 1 = 1 OR  p.gerencia = 2 )
                                                            GROUP BY g.nombre, 
                                                            date_format(str_to_date(r.vencimiento, '%d/%m/%Y'), '%m')";
                                                $sql = mysqli_query($con, $query);
                                                $allRows = mysqli_num_rows($sql);
                                                if($allRows == 0) {
                                                    echo '<tr><td colspan="13">No hay datos.</td></tr>';
                                                } else {
                                                    $nRow = 1;
                                                    $mesActual = date('m');

                                                    $gerencia_actual = '';
                                                    $row = mysqli_fetch_assoc($sql);
                                                    while($nRow <= $allRows) {

                                                        $gerencia_actual = $row['nombre'];
                                                        echo '<tr>';
                                                        // Celda de ver control
                                                        echo '<td>' . $row['nombre'] . '</td>';
                                                        
                                                        $mesControl = 1;
                                                        while ($nRow <= $allRows && $row['nombre'] == $gerencia_actual) {
                                                            
                                                            // Formo el calendario mes a mes creado las celdas vacias hasat el mes del control
                                                            // de 1-12 y marcando las que vienen por DB
                                                            for ($i = $mesControl; $i < $row['mes']; $i++) {
                                                                echo '<td ' . ($i==$mesActual ? 'class="mesactual"' : ''  ) . '></td>';
                                                            }
                                                            
                                                            //----------------------------
                                                            //En esta celda hay riesgos
                                                            //----------------------------
                                                            echo '<td class="text-center ' . ($row['mes']==$mesActual ? 'mesactual"' : '"'  ) . '>';
                                                            // Cambio el ícono si está pendiente o no
                                                                // Si está pendiente me fijo si está atrazado con respecto al mes en curso
                                                            if ($mesActual > $row['mes']) {
                                                                echo '<span class="badge bg-red">' . $row['cuenta'] . '</span>';
                                                            } else {
                                                                echo '<span class="badge bg-green">' . $row['cuenta'] . '</span>';
                                                            }
                                                            echo '</td>';
                                                            //----------------------------

                                                            //Incremento el mes para generar celdas hasta el próximo mes 
                                                            $mesControl = $row['mes'] + 1;
                                                            
                                                            $row = mysqli_fetch_assoc($sql);
                                                            $nRow++;
                                                        }
                                                        
                                                        // relleno los meses que faltan
                                                        for ($i = $mesControl; $i <= 12; $i++) {
                                                            echo '<td ' . ($i==$mesActual ? 'class="mesactual"' : ''  ) . '></td>';
                                                        }
                                                        echo '</tr>';
                                                    }
                                                  }
                                              ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->

                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
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
        <!-- export -->
        <script src="../bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
        <script src="../bower_components/datatables.net/js/buttons.flash.min.js"></script>
        <script src="../bower_components/datatables.net/js/jszip.min.js"></script>
        <script src="../bower_components/datatables.net/js/buttons.html5.min.js"></script>
        <script src="../bower_components/datatables.net/js/buttons.print.min.js"></script>
        <script src="../bower_components/datatables.net/js/pdfmake.min.js"></script>
        <script src="../bower_components/datatables.net/js/vfs_fonts.js"></script>


        <script>
        $(function() {
            $('#controles').DataTable({
                'paging': false,
                // 'pageLength': 20,
                'lengthChange': false,
                'searching': false,
                'ordering': true,
                'info': true,
                'autoWidth': true,
                'dom': 'frtip',
                // 'buttons': [{
                //         extend: 'pdfHtml5',
                //         orientation: 'landscape',
                //         pageSize: 'A4',

                //     },
                //     {
                //         extend: 'excel',
                //         text: 'Excel',
                //     }
                // ]
            });
        });
        </script>

        <script>
        $(function() {
            $(".ver-itemDialog").click(function() {
                let crit = $(this).data('criticidad');
                $('#criticidad').removeClass();
                $('#criticidad').addClass('btn');
                if (crit == 0) {
                    $('#criticidad').html('Crítico');
                    $('#criticidad').addClass('btn-danger'); 
                } else if (crit == 1) {
                    $('#criticidad').html('Semicrítico');
                    $('#criticidad').addClass('btn-warning'); 
                } else {
                    $('#criticidad').html('No Crítico');
                    $('#criticidad').addClass('btn-success'); 
                }
                $('#criticidad').addClass('pull-left'); 

                $('#titulo').val($(this).data('titulo'));
                $('#contenido').val($(this).data('contenido'));
                $('#responsable').val($(this).data('responsable') + ' - ' + $(this).data('gerencia'));
                // $('#gerencia').val($(this).data('gerencia'));
                
                switch ($(this).data('periodicidad')) {
                    case 1:
                        $('#periodicidad').val('Mensual');
                        break;
                    case 3:
                        $('#periodicidad').val('Trimestral');
                        break;
                    case 1:
                        $('#periodicidad').val('Semestral');
                        break;
                    case 1:
                        $('#periodicidad').val('Anual');
                        break;
                
                    default:
                        break;
                }
                let d = new Date();
                let mesActual = d.getMonth();
                $('#divaccion').hide();
                $('#divobservacion').hide();
                $('#divevidencia').hide();
                if ($(this).data('estatus') == 1) {
                    $('#estatus').val('Completado');
                    $('#controlador').val($(this).data('controlador'));
                    $('#divaccion').show();
                    $('#divobservacion').show();
                    $('#divevidencia').show();                    
                    $('#accion').val($(this).data('accion'));
                    $('#observacion').val($(this).data('observacion'));
                    $('#evidencia').val($(this).data('evidencia'));
                } else {
                    if (mesActual > $(this).data('mes')) {
                        $('#estatus').val('Vencido');
                    } else {
                        $('#estatus').val('Pendiente');
                    }
                }


                $("#ver-itemDialog").modal("show");

            });
        });
        </script>

        <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>