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
                                        <h2 class="box-title">Calendario de Controles</h2>
                                    </div>
                                    <div class="col-sm-6" style="text-align:right;">
                                    </div>
                                </div>
                                <!-- MODAL CONTROL -->
                                <div class="modal fade" id="modal-control">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">Controles >> Nuevo Control</h2>
                                            </div>
                                            <div class="modal-body">
                                                <div class="box box-primary">
                                                    <!-- /.box-header -->
                                                    <?php
                                                    if(isset($_POST['add'])){
                                                      $titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));//Escanpando caracteres
                                                      $contenido = mysqli_real_escape_string($con,(strip_tags($_POST["contenido"],ENT_QUOTES)));//Escanpando caracteres
                                                      $responsable = mysqli_real_escape_string($con,(strip_tags($_POST["responsable"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $periodo = mysqli_real_escape_string($con,(strip_tags($_POST["periodo"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $mesInicio = mysqli_real_escape_string($con,(strip_tags($_POST["mesinicio"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $tipo = mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));//Escanpando caracteres 
                                                      $criticidad = mysqli_real_escape_string($con,(strip_tags($_POST["criticidad"],ENT_QUOTES)));//Escanpando caracteres 
                                                              
                                                      $ano = date("Y");

                                                      //Inserto Control
                                                      $insert_control = mysqli_query($con, "INSERT INTO controles (titulo, contenido, creado, ano, responsable, usuario, periodo, status, tipo, mesinicio, criticidad) VALUES('$titulo','$contenido', NOW(), '$ano','$responsable', '$user','$periodo', '3', '$tipo', '$mesInicio', '$criticidad')") or die(mysqli_error());	

                                                          //Ultimo Insert
                                                        $last = $con->insert_id;
                                                        //For Inserto Referencias
                                                        $nro_referencia = 1;
                                                        // Mes Actual
                                                        // $mes = date("m");
                                                        $mes = (int)$mesInicio;
                                                        $ano = date("Y");
                                                        while ($mes <= 12) {
                                                          $insert_ref = mysqli_query($con, "INSERT INTO referencias (id_control, mes, ano, nro_referencia)
                                                            VALUES('$last', '$mes', '$ano','$nro_referencia')") or die (mysqli_error());	
                                                          $nro_referencia++;
                                                          $mes = $mes + $periodo;
                                                                  }
                                                                  $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario, i_titulo) 
                                                                                    VALUES ('1', '5', '$last', now(), '$user', '$titulo')") or die(mysqli_error());
                                                                  unset($_POST);
                                                                  if($insert_control){
                                                                      $_SESSION['formSubmitted'] = 2;
                                                                      echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                                  }else{
                                                                      $_SESSION['formSubmitted'] = 9;
                                                                      echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                                                                  }    
                                                              }       
                                                    ?>
                                                    <!-- form start -->
                                                    <form method="post" role="form" action="">
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="titulo">Título</label>
                                                                <input type="text" class="form-control" name="titulo"
                                                                    placeholder="Título del control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="descripcion">Contenido</label>
                                                                <textarea class="form-control" rows="3" name="contenido"
                                                                    placeholder="Contenido del control ..."
                                                                    required></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Criticidad</label>
                                                                <select name="criticidad" class="form-control">
                                                                    <option value='2'>No Crítico</option>										
                                                                    <option value='1'>Semi Crítico</option>										
                                                                    <option value='0'>Crítico</option>										
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Responsable</label>
                                                                <select name="responsable" class="form-control">
                                                                    <?php
                                                                        $personasn = mysqli_query($con, "SELECT * FROM persona");
                                                                        while($rowps = mysqli_fetch_array($personasn)){
                                                                          echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                                                          }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tipo</label>
                                                                <select name="tipo" class="form-control">
                                                                    <option value='1'>Preventivo y/o disuasivo</option>
                                                                    <option value='2'>Detectivo y/o correctivo</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>Mes de Inicio</label>
                                                                        <select name="mesinicio" class="form-control">
                                                                            <option value='1'>Enero</option>
                                                                            <option value='2'>Febrero</option>
                                                                            <option value='3'>Marzo</option>
                                                                            <option value='4'>Abril</option>
                                                                            <option value='5'>Mayo</option>
                                                                            <option value='6'>Junio</option>
                                                                            <option value='7'>Julio</option>
                                                                            <option value='8'>Agosto</option>
                                                                            <option value='9'>Septiembre</option>
                                                                            <option value='10'>Octubre</option>
                                                                            <option value='11'>Noviembre</option>
                                                                            <option value='12'>Diciembre</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>Periodo</label>
                                                                        <select name="periodo" class="form-control">
                                                                            <option value='1'>Mensual</option>
                                                                            <option value='3'>Trimestral</option>
                                                                            <option value='6'>Semestral</option>
                                                                            <option value='12'>Anual</option>
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="col-sm-3">
                                                                    <input type="submit" name="add"
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
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- FIN MODAL CONTROL -->
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="controles" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="1">Ver</th>
                                                <th>Control</th>
                                                <th>Enero</th>
                                                <th>Febrero</th>
                                                <th>Marzo</th>
                                                <th>Abril</th>
                                                <th>Mayo</th>
                                                <th>Junio</th>
                                                <th>Julio</th>
                                                <th>Agosto</th>
                                                <th>Septiembre</th>
                                                <th>Octubre</th>
                                                <th>Novimebre</th>
                                                <th>Diciembre</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query = "SELECT CON.id_control, 
                                                                CON.titulo, 
                                                                CON.contenido, 
                                                                CON.status, 
                                                                CON.ano, 
                                                                CON.responsable, 
                                                                CONCAT(RES.apellido, ', ', RES.nombre) as responsableNombre,
                                                                CON.periodo, 
                                                                CON.borrado, 
                                                                CON.tipo, 
                                                                CON.mesinicio, 
                                                                CON.criticidad, 
                                                                REF.id_referencia, 
                                                                REF.accion, 
                                                                REF.observacion, 
                                                                REF.evidencia, 
                                                                REF.mes, 
                                                                REF.ano, 
                                                                REF.nro_referencia, 
                                                                REF.status as estadoControl, 
                                                                REF.controlador,
                                                                CONCAT(CLD.apellido , ', ' , CLD.nombre) as controladorNombre
                                                        FROM referencias as REF
                                                        INNER JOIN controles as CON ON REF.id_control = CON.id_control
                                                        LEFT JOIN persona AS RES ON CON.responsable = RES.id_persona
                                                        LEFT JOIN persona AS CLD ON REF.controlador = CLD.id_persona
                                                        WHERE CON.ano = YEAR(NOW())
                                                        AND REF.borrado = 0
                                                        AND CON.borrado = 0 ";
                                                // AGREGO EL FILTRO DE GERENCIA DEL USUARIO=CIBERSEGURIDAD O LA GERENCIA DEL REFERENTE
                                                // if ( $per_id_gerencia != 1) {
                                                //     $query = $query . " AND p.gerencia = $per_id_gerencia ";
                                                // }
                                                $sql = mysqli_query($con, $query.' ORDER BY CON.criticidad, CON.id_control,  REF.mes');
                                                $allRows = mysqli_num_rows($sql);
                                                if($allRows == 0) {
                                                    echo '<tr><td colspan="8">No hay datos.</td></tr>';
                                                } else {
                                                    $nRow = 1;
                                                    $mesActual = date('m');

                                                    $idControlActual = -1;
                                                    while($row = mysqli_fetch_assoc($sql)) {

                                                        $idControlActual = $row['id_control'];
                                                        echo '<tr>';
                                                        // Celda de ver control
                                                        echo '<td><a  data-id="'.$row['id_control'].'"title="ver datos" class="ver-itemDialog btn btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a></td>';                                                        
                                                        echo '<td>' . $row['titulo'] . '</td>';
                                                        
                                                        $mesControl = 1;
                                                        $totalControles = 0;
                                                        while ($nRow <= $allRows && $row['id_control'] == $idControlActual) {
                                                            
                                                            // Formo el calendario mes a mes creado las celdas vacias hasat el mes del control
                                                            // de 1-12 y marcando las que vienen por DB
                                                            for ($i = $mesControl; $i < $row['mes']; $i++) {
                                                                echo '<td></td>';
                                                            }

                                                            //En esta celda hay un control
                                                            echo '<td>';
                                                            // Cambio el ícono si está pendiente o no
                                                            if ($row['estadoControl']==1) {
                                                                echo '<a  data-idref="'.$row['id_referencia'].'"title="Controlado - [' . $row['controladorNombre'] . ']" class="ver-itemDialog btn"><i class="glyphicon glyphicon-ok-sign" style="color:green; font-size: 20px;"></i></a>';
                                                            } else {
                                                                // Si está pendiente me fijo si está atrazado con respecto al mes en curso
                                                                if ($mesActual > $row['mes']) {
                                                                    echo '<a  data-idref="'.$row['id_referencia'].'"title="Vencido" class="ver-itemDialog btn"><i class="glyphicon glyphicon-remove-sign" style="color:red; font-size: 20px;"></i></a>';
                                                                } else {
                                                                    echo '<a  data-idref="'.$row['id_referencia'].'"title="Pendiente" class="ver-itemDialog btn"><i class="glyphicon glyphicon-record" style="font-size: 20px;"></i></a>';
                                                                }
                                                            }
                                                            echo '</td>';

                                                            //Incremento el mes para generar celdas hasta el próximo mes 
                                                            $mesControl = $row['mes'] + 1;
                                                            
                                                            $row = mysqli_fetch_assoc($sql);
                                                            $nRow++;
                                                            $totalControles++;
                                                        }
                                                        
                                                        // relleno los meses que faltan
                                                        for ($i = $mesControl; $i <= 12; $i++) {
                                                            echo '<td></td>';
                                                        }
                                                        // Celda total (cuenta de controles)
                                                        echo '<td>' . $totalControles . '</td>';
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
                'paging': true,
                'pageLength': 20,
                'lengthChange': false,
                'searching': false,
                'ordering': true,
                'info': true,
                'autoWidth': true,
                'dom': 'frtipB',
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
            })
        })
        </script>
        <script>
        window.onload = function() {
            history.replaceState("", "", "controles.php");
        }
        </script>

        <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>