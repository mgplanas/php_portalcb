<?php
    include("../conexion.php");

    //$query_calendar = "SELECT id_calendario, start ,end ,title FROM calendario";
    //$query_calendar = "SELECT id_calendario, start ,end ,title, tc.color, tc.allDay FROM calendario as c
    //    INNER JOIN tipo_calendario as tc ON c.tipo=tc.id_tipo_calendario";
    $query_calendar = "SELECT id_item_calendario, tc.color, c.tipo, tc.allDay, CONCAT(c.startDay, ' ', c.startTime) as start , CONCAT(c.endDay, ' ', c.endTime)                         as end , c.titulo as title
                        FROM item_calendario as c
                        INNER JOIN tipo_calendario as tc ON c.tipo=tc.id_tipo_calendario
                        INNER JOIN persona as p ON c.persona=p.id_persona
                        WHERE c.tipo='4'
                        UNION ALL 
                        SELECT id_item_calendario, tc.color, c.tipo, tc.allDay, CONCAT(c.startDay, ' ', tc.start) as start , CONCAT(c.endDay, ' ', tc.end) as end , CONCAT(tc.titulo, ' - ', p.apellido, ', ', p.nombre) as title
                        FROM item_calendario as c
                        INNER JOIN tipo_calendario as tc ON c.tipo=tc.id_tipo_calendario
                        INNER JOIN persona as p ON c.persona=p.id_persona
                        WHERE c.tipo!='4'";

    $query = mysqli_query($con, $query_calendar) or die('Query failed: ' . mysql_error());

    $events = array();
    $json = array();

   while ( $row = mysqli_fetch_assoc($query))  {
	$json['id_calendario']=$row['id_item_calendario'];
    $json['start']=$row['start'];
    $json['end']=$row['end'];
    $json['title']=$row['title'];
    $json['color']=$row['color'];
    $json['tipo']=$row['tipo'];
    $json['allDay']=(bool)$row['allDay'];
       
    array_push($events, $json);
    }
    
    echo json_encode($events);

    
?>