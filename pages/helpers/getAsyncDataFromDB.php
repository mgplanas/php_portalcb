<?php
    include("../../conexion.php");

    if (isset($_POST['query'])) {
        $query = $_POST['query'];
    } else {
        $query = $_GET['query'];
    }

    $result = mysqli_query($con,$query);

    $rows = array("data" => array());
    // while($r = mysqli_fetch_array($result)) {
    while($r = mysqli_fetch_assoc($result)) {
        // $rows[] = $r;
        array_push($rows["data"], $r);
    }
    echo json_encode($rows);

    // mysqli_close($con);
?>