<?php 

include("../../conexion.php");

// Script que genera las referencias (instancias de controles) para un año teniendo en cuenta la 
// periodicidad del control y la última referencia generada.actions
$anio_a_generar = 2020;

// Selecciono los controles activos
$c_activos = mysqli_query($con,"SELECT id_control, periodo FROM controles WHERE borrado='0'");
while($row = mysqli_fetch_assoc($c_activos)){

    $id_control = $row['id_control'];
    $periodicidad = $row['periodo'];
    
    echo '--------------------------------------------------------------</br>';
    echo 'Control: ' . $id_control . ' Periodicidad:' . $periodicidad . '</br>';
    //Por cada control busco la última referencia generada
    $c_ult_ref = mysqli_query($con,"SELECT * FROM referencias where id_control = " . $id_control . " AND borrado = 0 ORDER BY ano DESC, mes DESC LIMIT 1;
    ");
    $row_ref = mysqli_fetch_assoc($c_ult_ref);
    $ult_anio = $row_ref['ano'];
    $ult_mes = $row_ref['mes'];
    $ult_nro = $row_ref['nro_referencia'];
    echo 'Ultima Referencia: ' . $ult_anio . ' ' . $ult_mes . ' - ' .$ult_nro . '</br>';
    echo '--------------------------------------------------------------</br>';
    
    if ($periodicidad == 1 ) {
        $mes = 1;
    } else {
        $mes = $ult_mes + $periodicidad - 12;
    }
    while ($mes <= 12) {
        $ult_nro++;
        $insert_ref = mysqli_query($con, "INSERT INTO referencias (id_control, mes, ano, nro_referencia)
        VALUES('$id_control', '$mes', '$anio_a_generar','$ult_nro')") or die (mysqli_error());	
        echo 'Generado => ' . $anio_a_generar . ' ' . $mes . ' - ' .$ult_nro. '</br>';
        $mes = $mes + $periodicidad;
    }
    
    echo '--------------------------------------------------------------</br>';
}
?>