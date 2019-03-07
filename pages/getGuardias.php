<?php
    include("../conexion.php");

     $query_calendar = "SELECT id_activacion_guardia, CONCAT(a.startDay, ' ', a.startTime) as start , CONCAT(a.endDay, ' ', a.endTime) as end , CONCAT                       (p.apellido, ', ', p.nombre, ' - ', a.title) as title
                        FROM activacion_guardia as a
                            INNER JOIN persona as p ON a.persona=p.id_persona";

    $query = mysqli_query($con, $query_calendar) or die('Query failed: ' . mysql_error());

    $events = array();
    $json = array();

   while ( $row = mysqli_fetch_assoc($query))  {
	$json['id_calendario']=$row['id_activacion_guardia'];
    $json['start']=$row['start'];
    $json['end']=$row['end'];
    $json['title']=$row['title'];


       
    array_push($events, $json);
    }
    
    echo json_encode($events);

    
?>