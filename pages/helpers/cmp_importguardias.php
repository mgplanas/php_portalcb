<?php
    include("../../conexion.php");
    
    
    // $result = new stdClass();
    // try {
    //     // Turn autocommit off
    //     mysqli_autocommit($con,FALSE);
    //     mysqli_begin_transaction($con);
    // } catch (Exception $e) {
    //     $result->error1=$e->getMessage();
    // }
    // try {
    //     mysqli_commit($con);
    //     mysqli_autocommit($con,TRUE);
    // } catch (Exception $e) {
    //     $result->error2=$e->getMessage();
    // }


    $result->ok = false;
    echo json_encode($result);  
?>