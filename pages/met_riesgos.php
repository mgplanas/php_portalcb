<!DOCTYPE html>
<?php
include("../conexion.php");

session_start();

if (!isset($_SESSION['usuario'])){
	header('Location: ../index.html');
}

$user=$_SESSION['usuario'];

//Get user query
$persona = mysqli_query($con, "SELECT * FROM persona WHERE email='$user'");
$rowp = mysqli_fetch_assoc($persona);
$id_rowp = $rowp['id_persona'];
$per_id_gerencia = $rowp['gerencia'];
// GERENCIA DE CIBER SEGURIDAD = 1 
// PUEDE VER TODO

// RIESGO
$sqlTmpRiesgos = "SELECT 1 as qv 
                  FROM riesgo 
                  INNER JOIN persona as p ON riesgo.responsable = p.id_persona
                  WHERE riesgo.n_resid:comparacion AND riesgo.borrado=0 AND riesgo.estado='0'
                  AND ( 1 = :per_id_gerencia OR  p.gerencia = :per_id_gerencia )";
$qv = mysqli_query($con, strtr($sqlTmpRiesgos, array(':comparacion' => '<=3', ':per_id_gerencia' => $per_id_gerencia)));
$rqv = mysqli_num_rows($qv);
$qa = mysqli_query($con,strtr($sqlTmpRiesgos, array(':comparacion' => '=4', ':per_id_gerencia' => $per_id_gerencia)));
$rqa = mysqli_num_rows($qa);
$qr = mysqli_query($con,strtr($sqlTmpRiesgos, array(':comparacion' => '>6', ':per_id_gerencia' => $per_id_gerencia)));
$rqr = mysqli_num_rows($qr);


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
    <!-- Content Header (Page header) -->
	<section class="content-header">
      <h1>
        Métricas 
        <small>Riesgos</small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
	<!--------------------------
     | Your Page Content Here |
     -------------------------->
	    <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                        <h3><?php
                            $query_count_riesgos = "SELECT 1 as total 
                            FROM riesgo 
                            INNER JOIN persona as p ON riesgo.responsable = p.id_persona
                            WHERE riesgo.borrado='0' 
                            and riesgo.estado='0'
                            AND ( 1 = $per_id_gerencia OR  p.gerencia = $per_id_gerencia )";
                            $count_riesgos = mysqli_query($con, $query_count_riesgos);
                            echo '
                            <td> ' . mysqli_num_rows($count_riesgos) . ' </td>
                            <td>';
                            ?></h3>
                        <p>Total de Riesgos abiertos</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-flash"></i>
                        </div>
                        <a href="./pages/riesgos.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                    <div class="box box-warning">
                        <div class="box-header with-border">
                        <h3 class="box-title">Nivel de riesgo residual</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        </div>
                        <div class="box-body">
                        <canvas id="pieChartRR" style="height:250px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>                    
                </div>  
                <div class="col-lg-9 col-xs-9">
                    <div class="row">
                        <!-- Matriz Riesgo Inherente -->
                        <div class="col-lg-6 col-xs-6">
                            <?php
                                //querys de datos matriz inherente
                                //Riesgos Inherentes
                                $q14 = "SELECT count(*) as q14 FROM riesgo WHERE probabilidad=1 && i_result=4 && borrado=0 && estado=0";
                                $result14 = mysqli_query($con, $q14);
                                $row14 = mysqli_fetch_assoc($result14);
                                $q13 = "SELECT count(*) as q13 FROM riesgo WHERE probabilidad=1 && i_result=3 && borrado=0 && estado=0";
                                $result13 = mysqli_query($con, $q13);
                                $row13 = mysqli_fetch_assoc($result13);
                                $q12 = "SELECT count(*) as q12 FROM riesgo WHERE probabilidad=1 && i_result=2 && borrado=0 && estado=0";
                                $result12 = mysqli_query($con, $q12);
                                $row12 = mysqli_fetch_assoc($result12);
                                $q11 = "SELECT count(*) as q11 FROM riesgo WHERE probabilidad=1 && i_result=1 && borrado=0 && estado=0";
                                $result11 = mysqli_query($con, $q11);
                                $row11 = mysqli_fetch_assoc($result11);
                                $q24 = "SELECT count(*) as q24 FROM riesgo WHERE probabilidad=2 && i_result=4 && borrado=0 && estado=0";
                                $result24 = mysqli_query($con, $q24);
                                $row24 = mysqli_fetch_assoc($result24);
                                $q23 = "SELECT count(*) as q23 FROM riesgo WHERE probabilidad=2 && i_result=3 && borrado=0 && estado=0";
                                $result23 = mysqli_query($con, $q23);
                                $row23 = mysqli_fetch_assoc($result23);
                                $q22 = "SELECT count(*) as q22 FROM riesgo WHERE probabilidad=2 && i_result=2 && borrado=0 && estado=0";
                                $result22 = mysqli_query($con, $q22);
                                $row22 = mysqli_fetch_assoc($result22);
                                $q21 = "SELECT count(*) as q21 FROM riesgo WHERE probabilidad=2 && i_result=1 && borrado=0 && estado=0";
                                $result21 = mysqli_query($con, $q21);
                                $row21 = mysqli_fetch_assoc($result21);
                                $q34 = "SELECT count(*) as q34 FROM riesgo WHERE probabilidad=3 && i_result=4 && borrado=0 && estado=0";
                                $result34 = mysqli_query($con, $q34);
                                $row34 = mysqli_fetch_assoc($result34);
                                $q33 = "SELECT count(*) as q33 FROM riesgo WHERE probabilidad=3 && i_result=3 && borrado=0 && estado=0";
                                $result33 = mysqli_query($con, $q33);
                                $row33 = mysqli_fetch_assoc($result33);
                                $q32 = "SELECT count(*) as q32 FROM riesgo WHERE probabilidad=3 && i_result=2 && borrado=0 && estado=0";
                                $result32 = mysqli_query($con, $q32);
                                $row32 = mysqli_fetch_assoc($result32);
                                $q31 = "SELECT count(*) as q31 FROM riesgo WHERE probabilidad=3 && i_result=1 && borrado=0 && estado=0";
                                $result31 = mysqli_query($con, $q31);
                                $row31 = mysqli_fetch_assoc($result31);
                                $q44 = "SELECT count(*) as q44 FROM riesgo WHERE probabilidad=4 && i_result=4 && borrado=0 && estado=0";
                                $result44 = mysqli_query($con, $q44);
                                $row44 = mysqli_fetch_assoc($result44);
                                $q43 = "SELECT count(*) as q43 FROM riesgo WHERE probabilidad=4 && i_result=3 && borrado=0 && estado=0";
                                $result43 = mysqli_query($con, $q43);
                                $row43 = mysqli_fetch_assoc($result43);
                                $q42 = "SELECT count(*) as q42 FROM riesgo WHERE probabilidad=4 && i_result=2 && borrado=0 && estado=0";
                                $result42 = mysqli_query($con, $q42);
                                $row42 = mysqli_fetch_assoc($result42);
                                $q41 = "SELECT count(*) as q41 FROM riesgo WHERE probabilidad=4 && i_result=1 && borrado=0 && estado=0";
                                $result41 = mysqli_query($con, $q41);
                                $row41 = mysqli_fetch_assoc($result41);	
                            ?>
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                <h3 class="box-title">Cantidad según Matriz de riesgo inherente</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                                </div>
                                <div class="box-body">
                                <table border=1 cellspacing=0 cellpadding=0
                                    style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
                                    <tr>
                                        <td width=30 valign=top
                                            style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=114
                                            style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=387 colspan=4
                                            style='width:289.95pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>PROBABILIDAD DE OCURRENCIA<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:1'>
                                        <td width=30 valign=top
                                            style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=114
                                            style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>IMPROBABLE</p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADA</p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>PROBABLE</p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CERTEZA</p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:2;height:63.3pt'>
                                        <td width=30 rowspan=4 style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>I<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>M<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>P<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>A<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>C<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>T<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>O</span></p>
                                        </td>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CATASTRÓFICO</p>
                                        </td>
                                        <td width=98 style='text-align:center; width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                <div class="box_a" style="color:black">
                                                    <?php echo $row14['q14']; ?></div>
                                        </td>
                                        <td width=99 style='text-align:center; width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                                <div class="box_a" style="color:black">
                                                    <?php echo $row24['q24']; ?></div>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row34['q34']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row44['q44']; ?></p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:3;height:63.4pt'>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MAYOR</p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row13['q13']; ?></p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row23['q23']; ?></p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row33['q33']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row43['q43']; ?></p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:4;height:70.8pt'>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADO</p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row12['q12']; ?></p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row22['q22']; ?></p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row32['q32']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row42['q42']; ?></p>
                                        </td>
                                    </tr>
                                    <tr
                                        style='mso-yfti-irow:5;mso-yfti-lastrow:yes;height:62.4pt'>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MENOR</p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row11['q11']; ?></p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row21['q21']; ?></p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row31['q31']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row41['q41']; ?></p>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                                <!-- /.box-body -->
                            </div>      
                        </div>
                        <!-- Matriz Riesgo Residual -->
                        <div class="col-lg-6 col-xs-6">
                            <?php
                                //querys de datos matriz RESIDUAL
                                $q14r = "SELECT count(*) as q14r FROM riesgo WHERE p_resid=1 && i_resid=4 && borrado=0 && estado=0";
                                $result14r = mysqli_query($con, $q14r);
                                $row14r = mysqli_fetch_assoc($result14r);
                                $q13r = "SELECT count(*) as q13r FROM riesgo WHERE p_resid=1 && i_resid=3 && borrado=0 && estado=0";
                                $result13r = mysqli_query($con, $q13r);
                                $row13r = mysqli_fetch_assoc($result13r);
                                $q12r = "SELECT count(*) as q12r FROM riesgo WHERE p_resid=1 && i_resid=2 && borrado=0 && estado=0";
                                $result12r = mysqli_query($con, $q12r);
                                $row12r = mysqli_fetch_assoc($result12r);
                                $q11r = "SELECT count(*) as q11r FROM riesgo WHERE p_resid=1 && i_resid=1 && borrado=0 && estado=0";
                                $result11r = mysqli_query($con, $q11r);
                                $row11r = mysqli_fetch_assoc($result11r);
                                $q24r = "SELECT count(*) as q24r FROM riesgo WHERE p_resid=2 && i_resid=4 && borrado=0 && estado=0";
                                $result24r = mysqli_query($con, $q24r);
                                $row24r = mysqli_fetch_assoc($result24r);
                                $q23r = "SELECT count(*) as q23r FROM riesgo WHERE p_resid=2 && i_resid=3 && borrado=0 && estado=0";
                                $result23r = mysqli_query($con, $q23r);
                                $row23r = mysqli_fetch_assoc($result23r);
                                $q22r = "SELECT count(*) as q22r FROM riesgo WHERE p_resid=2 && i_resid=2 && borrado=0 && estado=0";
                                $result22r = mysqli_query($con, $q22r);
                                $row22r = mysqli_fetch_assoc($result22r);
                                $q21r = "SELECT count(*) as q21r FROM riesgo WHERE p_resid=2 && i_resid=1 && borrado=0 && estado=0";
                                $result21r = mysqli_query($con, $q21r);
                                $row21r = mysqli_fetch_assoc($result21r);
                                $q34r = "SELECT count(*) as q34r FROM riesgo WHERE p_resid=3 && i_resid=4 && borrado=0 && estado=0";
                                $result34r = mysqli_query($con, $q34r);
                                $row34r = mysqli_fetch_assoc($result34r);
                                $q33r = "SELECT count(*) as q33r FROM riesgo WHERE p_resid=3 && i_resid=3 && borrado=0 && estado=0";
                                $result33r = mysqli_query($con, $q33r);
                                $row33r = mysqli_fetch_assoc($result33r);
                                $q32r = "SELECT count(*) as q32r FROM riesgo WHERE p_resid=3 && i_resid=2 && borrado=0 && estado=0";
                                $result32r = mysqli_query($con, $q32r);
                                $row32r = mysqli_fetch_assoc($result32r);
                                $q31r = "SELECT count(*) as q31r FROM riesgo WHERE p_resid=3 && i_resid=1 && borrado=0 && estado=0";
                                $result31r = mysqli_query($con, $q31r);
                                $row31r = mysqli_fetch_assoc($result31r);
                                $q44r = "SELECT count(*) as q44r FROM riesgo WHERE p_resid=4 && i_resid=4 && borrado=0 && estado=0";
                                $result44r = mysqli_query($con, $q44r);
                                $row44r = mysqli_fetch_assoc($result44r);
                                $q43r = "SELECT count(*) as q43r FROM riesgo WHERE p_resid=4 && i_resid=3 && borrado=0 && estado=0";
                                $result43r = mysqli_query($con, $q43r);
                                $row43r = mysqli_fetch_assoc($result43r);
                                $q42r = "SELECT count(*) as q42r FROM riesgo WHERE p_resid=4 && i_resid=2 && borrado=0 && estado=0";
                                $result42r = mysqli_query($con, $q42r);
                                $row42r = mysqli_fetch_assoc($result42r);
                                $q41r = "SELECT count(*) as q41r FROM riesgo WHERE p_resid=4 && i_resid=1 && borrado=0 && estado=0";
                                $result41r = mysqli_query($con, $q41r);
                                $row41r = mysqli_fetch_assoc($result41r);	
                            ?>
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                <h3 class="box-title">Cantidad según Matriz de riesgo residual</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                                </div>
                                <div class="box-body">
                                <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
                                    style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
                                    <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
                                        <td width=30 valign=top
                                            style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=114
                                            style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=387 colspan=4
                                            style='width:289.95pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>PROBABILIDAD DE OCURRENCIA<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:1'>
                                        <td width=30 valign=top
                                            style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=114
                                            style='width:85.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>
                                                <o:p>&nbsp;</o:p>
                                            </p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>IMPROBABLE</p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADA</p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>PROBABLE</p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CERTEZA</p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:2;height:63.3pt'>
                                        <td width=30 rowspan=4 style='width:16.6pt;border:none;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>I<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>M<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>P<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>A<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>C<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>T<o:p></o:p></span></p>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>O</span></p>
                                        </td>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>CATASTRÓFICO</p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row14r['q14r']; ?></p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row24r['q24r']; ?></p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row34r['q34r']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.3pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row44r['q44r']; ?></p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:3;height:63.4pt'>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MAYOR</p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row13r['q13r']; ?></p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row23r['q23r']; ?></p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row33r['q33r']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:63.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row43r['q43r']; ?></p>
                                        </td>
                                    </tr>
                                    <tr style='mso-yfti-irow:4;height:70.8pt'>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MODERADO</p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row12r['q12r']; ?></p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row22r['q22r']; ?></p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row32r['q32r']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:red;padding:0cm 5.4pt 0cm 5.4pt;height:70.8pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row42r['q42r']; ?></p>
                                        </td>
                                    </tr>
                                    <tr
                                        style='mso-yfti-irow:5;mso-yfti-lastrow:yes;height:62.4pt'>
                                        <td width=114 style='width:85.7pt;border:none;border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'>MENOR</p>
                                        </td>
                                        <td width=98 style='width:73.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row11r['q11r']; ?></p>
                                        </td>
                                        <td width=99 style='width:74.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row21r['q21r']; ?></p>
                                        </td>
                                        <td width=96 style='width:72.3pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:#00B050;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row31r['q31r']; ?></p>
                                        </td>
                                        <td width=93 style='width:70.05pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:62.4pt'>
                                            <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal'><?php echo $row41r['q41r']; ?></p>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                                <!-- /.box-body -->
                            </div>      
                        </div>                    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- /.content Riesgos-->

		        </div>         
            </div>    
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
    <strong>Seguridad Informática  - <a href="../site.php">ARSAT S.A.</a></strong>
  </footer>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- ChartJS -->
<script src="../bower_components/chart.js/Chart.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
	var pieChartCanvas3 = $('#pieChartRR').get(0).getContext('2d')
    var pieChartRR       = new Chart(pieChartCanvas3)
    var PieData3        = [
      {
        value    :  <?php echo $rqr; ?>,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Alto'
      },
      {
        value    : <?php echo $rqv; ?>,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Bajo'
      },
      {
        value    : <?php echo $rqa; ?>,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Medio'
      }
	]

    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
	
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChartRR.Doughnut(PieData3, pieOptions)
  })
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>