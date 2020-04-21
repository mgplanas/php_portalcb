<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

if (!isset($_GET["id_instancia"])){
	header('Location: ./aud_instancias.php');
}
$id_instancia = mysqli_real_escape_string($con,(strip_tags($_GET["id_instancia"],ENT_QUOTES)));

$page_title="Auditores"; 
$user=$_SESSION['usuario'];

/// BORRADO DE Auditores
if(isset($_GET['aksi']) == 'delete'){
	// escaping, additionally removing everything that could be (html/javascript-) code
	$id_auditor = mysqli_real_escape_string($con,(strip_tags($_GET["id_auditor"],ENT_QUOTES)));
    //Elimino ENTE
    $delete_control = mysqli_query($con, "UPDATE aud_rel_ins_aud SET borrado='1' WHERE id_instancia='$id_instancia' AND id_auditor='$id_auditor'");
  
    if(!$delete_control){
        $_SESSION['formSubmitted'] = 9;
    }
}

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

//Get Access
$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);				
        
// Get INSTANCIA
//Get user query
$instancia_aud = mysqli_query($con, "SELECT * FROM aud_instancias WHERE id = '$id_instancia' AND borrado = 0");
$row_instancia = mysqli_fetch_assoc($instancia_aud);
?>
<style>
.dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}

select[multiple], select[size] {
    height: 300px !important;
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

   <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
<input type="hidden" id='modal-abm-auditor-id-instancia' value="<?=$id_instancia ?>">
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
	<section class="content-header">
      <h1>Gestión de Auditores de la instancia de auditoría <strong><?= $row_instancia['nombre'] ?></strong></h1>
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
					<h2 class="box-title">Asignación de Auditores</h2>
				</div>
            </div>

            <!-- /.box-header -->
			<div class="box-body">
                <!-- form start -->
                <form method="post" role="form" action="">

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group" id="modal-abm-auditor-ente-div">
                                <label>Ente Auditor</label>
                                <select id="modal-abm-auditor-ente" name="ente" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Auditores disponibles</label>
                                <select multiple class="form-control" id="modal-abm-auditor-disponibles"></select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button id="modal-abm-auditor-asignar" type="button"class="btn btn-success pull-left ">Asignar <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                        <div class="col-md-1">
                            <button id="modal-abm-auditor-quitar" type="button"class="btn btn-danger pull-left "><i class="fa fa-arrow-circle-left"></i> Quitar</button>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Auditores Asignados</label>
                                <select multiple class="form-control" id="modal-abm-auditor-asignados"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <button id="modal-abm-auditor-submit" type="button"class="btn btn-primary pull-left "><i class="fa fa-save"></i> Guardar Asignaciones</button>
                            </div>
                        </div>
                    </div>
                </form>
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
  <?php include_once('./site_footer.php'); ?>

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
        let id_instancia = $('#modal-abm-auditor-id-instancia').val();
        let ddlEntes = $('#modal-abm-auditor-ente');
        let lstDisponibles = $('#modal-abm-auditor-disponibles');
        let lstAsignados = $('#modal-abm-auditor-asignados');
        let id_ente_asignados = 0;

        // refresh DDL
        function refreshEntes(selectedValue) {
            // Limpio combos
            ddlEntes.empty();

            //Populo los Entes
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT id, razon_social FROM aud_entes WHERE borrado = 0 ORDER BY razon_social' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlEntes.append($("<option />").val(this.id).text(this.razon_social).attr('selected', 'selected'));
                    } else {
                        ddlEntes.append($("<option />").val(this.id).text(this.razon_social));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlEntes.val('first').change();
                } else {
                    refreshDisponibles(selectedValue);
                }
            }
            ).fail(function(jqXHR, errorText) {
                console.log(errorText);
            });
        }     

        // refresh Asignados
        function refreshAsignados() {
            // Limpio combos
            lstAsignados.empty();
            
            //Populo los Asignados
            let qry = 'SELECT A.id, A.id_ente, A.apellido, A.nombre FROM aud_auditores as A INNER JOIN aud_rel_ins_aud AS R ON R.id_instancia = ' + id_instancia + ' AND A.id = R.id_auditor WHERE R.borrado = 0 AND A.borrado = 0 ORDER BY A.apellido';
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: qry },
            function(response) {
                $.each(response.data, function() {
                    id_ente_asignados = this.id_ente;
                    lstAsignados.append($("<option />").val(this.id).text(this.apellido + ', ' + this.nombre));
                });
                refreshEntes(id_ente_asignados);
                
            }
            ).fail(function(jqXHR, errorText) {
                console.log(errorText);
            });
        }     
        // refresh Disponobles
        function refreshDisponibles(id_ente) {
            // Limpio combos
            lstDisponibles.empty();
            
            //Populo los Asignados
            let qry = 'SELECT A.id, A.id_ente, A.apellido, A.nombre FROM aud_auditores as A WHERE A.id_ente = ' + id_ente + ' AND A.borrado = 0 AND A.id NOT IN (SELECT R.id_auditor FROM aud_rel_ins_aud AS R WHERE R.id_instancia = ' + id_instancia + ' AND R.borrado = 0 AND R.id_auditor = A.id) ORDER BY A.apellido';
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: qry },
            function(response) {
                $.each(response.data, function() {
                    lstDisponibles.append($("<option />").val(this.id).text(this.apellido + ', ' + this.nombre));
                });
            }
            ).fail(function(jqXHR, errorText) {
                console.log(errorText);
            });
        }     
        
        // ==============================================================
        // MANEJO DE EVENTOS DEL FORM
        // ==============================================================
        // Cargo auditores del ente
        ddlEntes.on('change', function() {
            let idEnte = $('option:selected', this).val();
            if (idEnte) {
                refreshDisponibles(idEnte);
            }
        });      

        $('#modal-abm-auditor-asignar').on('click', function() {
            return !$('#modal-abm-auditor-disponibles option:selected').remove().appendTo('#modal-abm-auditor-asignados');
        });
        $('#modal-abm-auditor-quitar').on('click', function() {
            return !$('#modal-abm-auditor-asignados option:selected').remove().appendTo('#modal-abm-auditor-disponibles');
        });

        // ==============================================================
        // GUARDAR ASIGNACION
        // ==============================================================
        // ejecución de guardado async
        $('#modal-abm-auditor-submit').click(function() {
            // Recupero datos del formulario
            var auditoresAsignados = [];
            $('#modal-abm-auditor-asignados option').each(function() {
                auditoresAsignados.push($(this).val());
            });

            if (!auditoresAsignados.length) return;

            // Ejecuto
            $.ajax({
                type: 'POST',
                url: './helpers/aud_abminsauditoresdb.php',
                data: {
                    id_instancia: id_instancia,
                    auditores: auditoresAsignados
                },
                dataType: 'json',
                success: function(json) {
                    $("#modal-abm-ente").modal("hide");
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText, error);
                }
            });
        });




        refreshAsignados();
    });
</script>
<script>
    window.onload = function() {
        history.replaceState("", "", "aud_insauditores.php?id_instancia=<?= $id_instancia ?>");
    }
</script>
<script>
  $(function() {
      /** add active class and stay opened when selected */
      var url = window.location;

      // for sidebar menu entirely but not cover treeview
      $('ul.sidebar-menu a').filter(function() {
        return this.href == url;
      }).parent().addClass('active');

      // for treeview
      $('ul.treeview-menu a').filter(function() {
        return this.href == url;
      }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');    
  });
</script>
</body>
</html>