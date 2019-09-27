<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: index.html');
}
$page_title="Novedades";
$user=$_SESSION['usuario'];
//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user' AND borrado = 0");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];

$q_sec = mysqli_query($con,"SELECT * FROM permisos WHERE id_persona='$id_rowp'");
$rq_sec = mysqli_fetch_assoc($q_sec);

if ($rq_sec['soc']=='0'){
	header('Location: ../site.php');
}


//Get Personas
$personas = mysqli_query($con, "SELECT * FROM persona");

$hoy=date("d.m.y");			
		
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
<!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
    <!-- daterange picker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

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
      <h1>
        Novedades USI
       </h1>
        <div class="col-md-12" style="text-align:right;">
            <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modal-novedad"><i class="fa fa-plus"></i> Nueva Novedad</button>
         </div>
    </section>
    <!-- Main content -->
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
    <section class="content">
    <div class="row">
        <div class="col-md-12">
         <!-- The time line -->

                <?php
                $query_novedad = "SELECT n.*, (select count(*) from comentario where n.id_novedad = novedad and tipo='1') as comentarios, (select count(*) from                      leido_novedad where n.id_novedad = novedad) as leidos ,p.nombre, p.apellido, 
                                        date(creado) as dia, time(creado) as hora  
                                        FROM novedad as n 
                                        LEFT JOIN persona as p on n.persona = p.id_persona
                                        WHERE n.borrado='0'";

                $sql_novedad = mysqli_query($con, $query_novedad.' ORDER BY id_novedad DESC');
                					if(mysqli_num_rows($sql_novedad) == 0){
						echo '<tr><td colspan="8">No hay novedades que mostrar.</td></tr>';
                }else{
                       
                        $sql_last_row = mysqli_query($con, $query_novedad.' ORDER BY id_novedad DESC');
                        $rowin = mysqli_fetch_assoc($sql_last_row);
                        $dia_ref = $rowin['dia'];   
                        $today= date("Y-m-d");
                        echo'
                            <ul class="timeline">
                            <li class="time-label">
                                <span ';
                              if($dia_ref == $today){
                                  echo'
                                        class="bg-yellow">Hoy : '.$rowin['dia'].'';
                              }else{
                                  echo'
                                        class="bg-blue">'.$rowin['dia'].'';
                              }          
                                        
                                  echo '</span>
                            </li>';
                                         
                    while($rown = mysqli_fetch_assoc($sql_novedad)){
                        if ($rown['dia'] != $dia_ref){
                            $id_nove=$rown['id_novedad'];
                            $query_array_leidos = "SELECT group_concat(p.apellido) as leidos FROM leido_novedad as l
                                                    LEFT JOIN persona as p ON p.id_persona=l.persona
                                                    WHERE l.novedad='$id_nove'";
                            $sql_array_leidos = mysqli_query($con, $query_array_leidos);
                            $rowal = mysqli_fetch_assoc($sql_array_leidos);
                             
                            echo'
                            <li class="time-label">
                                <span class="bg-blue">'.$rown['dia'].'</span>
                            </li>';
                            echo '
                            <li>
                              <i class="fa fa-envelope bg-blue"></i>

                              <div class="timeline-item">
                                <div class="box box-widget collapsed-box">
                                <div class="box-header with-border">
                                  <div class="user-block">
                                    <img class="img-circle" src="../dist/img/icon_user.png" alt="User Image">
                                    <span class="username"><a href="#">'.$rown['apellido'].' '.$rown['nombre']. '</a></span>
                                    <span class="description">'.$rown['titulo'].' - '.$rown['hora'].'</span>
                                    
                                  </div>
                                  <!-- /.user-block -->
                                  <div class="box-tools">
                                  
                                      <span >'.$rown['leidos'].' leidos - '.$rown['comentarios'].' comentarios</span>
                                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                  </div>
                                  <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                  <!-- post text -->
                                  <p>'.$rown['texto'].'</p>';
                                
                                $id_novedad = $rown['id_novedad'];
                                $query_leido = "SELECT * FROM leido_novedad
                                                WHERE novedad='$id_novedad' AND persona='$id_rowp'";

                                $sql_leido = mysqli_query($con, $query_leido);
                					
                                if(mysqli_num_rows($sql_leido) == 0){
						              echo '<button type="button" class="btn btn-default btn-xs" onclick="updateleido('.$rown['id_novedad'].', \''.$id_rowp.'\');"><i class="fa fa-thumbs-o-up"></i> Leido</button>';
                                }else{
                                  echo'<button type="button" class="btn btn-default btn-xs disabled"><i class="fa fa-thumbs-o-up"></i> Leido</button>';
                                }
                                echo'<span class="text-muted pull-right">' .$rowal['leidos'].'</span>';
                                echo'
                                </div>
                                <!-- /.box-body -->
                                
                                <div class="box-footer box-comments">';
                                $id_novedad = $rown['id_novedad'];
                                $query_comentario = "SELECT c.*, p.nombre, p.apellido FROM comentario as c 
                                            LEFT JOIN persona as p on c.persona = p.id_persona
                                            WHERE c.borrado='0' AND c.novedad='$id_novedad' AND c.tipo='1'";

                                $sql_comentario = mysqli_query($con, $query_comentario.' ORDER BY id_comentario DESC');        
                                while($rowcom = mysqli_fetch_assoc($sql_comentario)){
                              
                                    echo'
                                      <!-- /.box-comment -->
                                      <div class="box-comment">
                                        <!-- User image -->
                                        <img class="img-circle img-sm" src="../dist/img/icon_user.png" alt="User Image">

                                        <div class="comment-text">
                                              <span class="username">
                                                '.$rowcom['apellido'].' '.$rowcom['nombre']. '
                                                <span class="text-muted pull-right">'.$rowcom['creado'].'</span>
                                              </span><!-- /.username -->
                                            '.$rowcom['texto'].'
                                        </div>
                                        <!-- /.comment-text -->
                                      </div>
                                      <!-- /.box-comment -->
                                    ';
                                };

                                 echo'
                                </div>
                                <!-- /.box-footer -->
                                <div class="box-footer">
                                  <form method="post">
                                    <img class="img-responsive img-circle img-sm" src="../dist/img/icon_user.png" alt="Alt Text">
                                    <!-- .img-push is used to add margin to elements next to floating images -->
                                    <div class="img-push">
                                      <input type="text" name="comment" id="comment" class="form-control input-sm" onkeydown="submitComment('.$rown['id_novedad'].', '.$rowp['id_persona'].', this.form.comment.value);" placeholder="Press enter to post comment">
                                    </div>
                                  </form>
                                </div>
                                <!-- /.box-footer -->
                              </div>
                              </div>
                            </li>';

                        }else{
                                $id_nove=$rown['id_novedad'];
                                $query_array_leidos = "SELECT group_concat(p.apellido) as leidos FROM controls.leido_novedad as l
                                                        LEFT JOIN persona as p ON p.id_persona=l.persona
                                                        WHERE l.novedad='$id_nove'";
                                $sql_array_leidos = mysqli_query($con, $query_array_leidos);
                                $rowal = mysqli_fetch_assoc($sql_array_leidos);
                                echo '
                                    <li>
                                      <i class="fa fa-envelope bg-blue"></i>

                                      <div class="timeline-item">
                                        <div class="box box-widget collapsed-box">
                                        <div class="box-header with-border">
                                          <div class="user-block">
                                            <img class="img-circle" src="../dist/img/icon_user.png" alt="User Image">
                                            <span class="username"><a href="#">'.$rown['apellido'].' '.$rown['nombre']. '</a></span>
                                            <span class="description">'.$rown['titulo'].' - '.$rown['hora'].'</span>

                                          </div>
                                          <!-- /.user-block -->
                                          <div class="box-tools">

                                              <span >'.$rown['leidos'].' leidos - '.$rown['comentarios'].' comentarios</span>
                                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                          </div>
                                          <!-- /.box-tools -->
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                          <!-- post text -->
                                          <p>'.$rown['texto'].'</p>';

                                        $id_novedad = $rown['id_novedad'];
                                        $query_leido = "SELECT * FROM leido_novedad
                                                        WHERE novedad='$id_novedad' AND persona='$id_rowp'";

                                        $sql_leido = mysqli_query($con, $query_leido);

                                        if(mysqli_num_rows($sql_leido) == 0){
                                              echo '<button type="button" class="btn btn-default btn-xs" onclick="updateleido('.$rown['id_novedad'].', \''.$id_rowp.'\');"><i class="fa fa-thumbs-o-up"></i> Leido</button>';
                                        }else{

                                        echo'
                                        <button type="button" class="btn btn-default btn-xs disabled"><i class="fa fa-thumbs-o-up"></i> Leido</button>
                                        <span class="text-muted pull-right">' .$rowal['leidos'].'</span>';

                                        };
                                        echo'
                                        </div>
                                        <!-- /.box-body -->

                                        <div class="box-footer box-comments">';
                                        $id_novedad = $rown['id_novedad'];
                                        $query_comentario = "SELECT c.*, p.nombre, p.apellido FROM comentario as c 
                                                    LEFT JOIN persona as p on c.persona = p.id_persona
                                                    WHERE c.borrado='0' AND c.novedad='$id_novedad' and c.tipo='1'";

                                        $sql_comentario = mysqli_query($con, $query_comentario.' ORDER BY id_comentario DESC');        
                                        while($rowcom = mysqli_fetch_assoc($sql_comentario)){

                                            echo'
                                              <!-- /.box-comment -->
                                              <div class="box-comment">
                                                <!-- User image -->
                                                <img class="img-circle img-sm" src="../dist/img/icon_user.png" alt="User Image">

                                                <div class="comment-text">
                                                      <span class="username">
                                                        '.$rowcom['apellido'].' '.$rowcom['nombre']. '
                                                        <span class="text-muted pull-right">'.$rowcom['creado'].'</span>
                                                      </span><!-- /.username -->
                                                    '.$rowcom['texto'].'
                                                </div>
                                                <!-- /.comment-text -->
                                              </div>
                                              <!-- /.box-comment -->
                                            ';
                                        };

                                         echo'
                                        </div>
                                        <!-- /.box-footer -->
                                        <div class="box-footer">
                                          <form method="post">
                                            <img class="img-responsive img-circle img-sm" src="../dist/img/icon_user.png" alt="Alt Text">
                                            <!-- .img-push is used to add margin to elements next to floating images -->
                                            <div class="img-push">
                                              <input type="text" name="comment" id="comment" class="form-control input-sm" onkeydown="submitComment('.$rown['id_novedad'].', '.$rowp['id_persona'].', this.form.comment.value);" placeholder="Press enter to post comment">
                                            </div>
                                          </form>
                                        </div>
                                        <!-- /.box-footer -->
                                      </div>
                                      </div>
                                    </li>';
                            
                    }
                }
                
                echo'                        
                    <li class="time-label">
                        <span class="bg-green">
                    Inicio Novedades
                  </span>
            </li>
            <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
          </ul>
          </div>
          ';                    
             }
                ?>
            <!-- timeline time label -->

            <!-- timeline item 
            <li>
              <i class="fa fa-warning bg-yellow"></i>

              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 6:07 PM</span>

                <h3 class="timeline-header"><a href="#">Gustavo Rossi -</a> Problemas con el Cluster 4800</h3>

                <div class="timeline-body">
                  El Cluster 4800 de Checkpoint esta con problemas, no instalar políticas!
                </div>
                
              </div>
            </li>
            END timeline item -->
            <!-- timeline time label -->
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>  

    <div class="modal fade" id="modal-novedad">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Nueva novedad - USI<br>
  
                <?php
                    if(isset($_POST['Addnov'])){
                        $titulo = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));//Escanpando caracteres
                        $texto = mysqli_real_escape_string($con,(strip_tags($_POST["texto"],ENT_QUOTES)));//Escanpando caracteres

                        $insert_novedad = mysqli_query($con, "INSERT INTO novedad (titulo, texto, persona, creado, usuario) 
                        VALUES ('$titulo', '$texto', '$id_rowp', now(), '$user')") or die(mysqli_error());

                        if($insert_novedad){
                            echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';}
                    }				
                ?>
        </div>
        <div class="modal-body">
                <!-- form start -->
            <form method="post" role="form" action="">
              <div class="box-body">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" value="">
                </div>
                <div class="form-group">
                  <label for="texto">Texto de la novedad</label>
                  <textarea class="form-control" rows="5" name="texto" id="texto" value=""></textarea>
                </div>
                 <div class="form-group">
                    <div class="col-sm-3">
                        <input type="submit" name="Addnov" class="btn  btn-raised btn-success" value="Guardar datos">
                    </div>
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
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
<!-- Modal to Event Details -->

<!--Modal-->
    <!-- /.End content -->
  </div>
  <!-- Main Footer -->
  <?php include_once('./site_footer.php'); ?>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
    window.onload = function() {
        history.replaceState("", "", "novedades.php");
    }
</script>
  
<script>
  function updateleido(id_novedad, id_persona)
  {   

  var  datap=id_novedad;
  var  rmvfile=id_persona;

  $.ajax({
   type:'post',
      url:'setLeido.php',
          data:{rmvfile: rmvfile, datap: datap},
 
 });
      location.reload(true);
 
}
</script>
<script>
  function submitComment(id_novedad, id_persona, comment)
  {   
      if(event.key === 'Enter') {
        var  dataid=id_novedad;
        var  datap=id_persona;
        var  datac=comment;
        var  datat='1';
 
          $.ajax({
           type:'post',
              url:'submitComment.php',
                  data:{dataid: dataid, datap: datap, datac: datac, datat:datat},

         });
              //location.href = './novedades.php';
            location.reload(true);
              }
 
}
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>