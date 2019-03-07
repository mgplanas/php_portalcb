<?php
    include("../conexion.php");
    
    $dispositivos = "SELECT i.id_dispositivo as id, t.nombre as labels 
                        FROM controls.dispositivo as i
                        INNER JOIN tipo_dispositivo as t on i.tipo = t.id_tipoDispositivo
                        WHERE borrado='0';";
    $query = mysqli_query($con, $dispositivos) or die('Query failed: ' . mysql_error());
    $data = array();
    $props = array();

    while ( $row = mysqli_fetch_assoc($query))  {
       $row_data['id']=$row['id'];
       $row_data['labels']=$row['labels'];
        
        $idp=$row['id'];
       
        $prop_disp = "SELECT i.etiqueta, i.descripcion, i.marca, i.modelo, i.serial, i.interfaces, i.ip_address, u.ubicacion
                    FROM controls.dispositivo as i
                    INNER JOIN ubicacion as u on i.ubicacion = u.id_ubicacion
                    WHERE borrado='0' AND i.id_dispositivo='$idp';";
        $queryprops = mysqli_query($con, $prop_disp);
        while ($rowprop = mysqli_fetch_assoc($queryprops)){
                $props['etiqueta']=$rowprop['etiqueta'];
                $props['descripcion']=$rowprop['descripcion'];
                $props['marca']=$rowprop['marca'];
                $props['modelo']=$rowprop['modelo'];
                $props['serial']=$rowprop['serial'];
                $props['interfaces']=$rowprop['interfaces'];
                $props['ip_address']=$rowprop['ip_address'];
                $props['ubicacion']=$rowprop['ubicacion'];
        };
        $row_data['properties']=$props;
         array_push($data,$row_data);
    }

    $vinculos = "SELECT v.id_vinculo as id, v.nombre as type, v.source as startNode, v.target as endNode 
                    FROM controls.vinculo as v 
                    WHERE borrado='0';";
    $query = mysqli_query($con, $vinculos) or die('Query failed: ' . mysql_error());

    $link = array();
    $pl = array();

   while ( $row = mysqli_fetch_assoc($query))  {
	//$link[]=$row;
       $row_link['id']=$row['id'];
       $row_link['type']=$row['type'];
       $row_link['startNode']=$row['startNode'];
       $row_link['endNode']=$row['endNode'];
       
       $idv=$row['id'];
       $prop_link = "SELECT v.descripcion, v.vlans, v.velocidad, v.segurizado, v.portchannel, v.cobre, v.fibra
                        FROM controls.vinculo as v
                        WHERE borrado='0' AND id_vinculo='$idv';";
       $queryprops = mysqli_query($con, $prop_link);
       while ($rowpropv = mysqli_fetch_assoc($queryprops)){
           $pl['descripcion']=$rowpropv['descripcion'];
           $pl['vlans']=$rowpropv['vlans'];
           $pl['velocidad']=$rowpropv['velocidad'];
           $pl['segurizado']=$rowpropv['segurizado'];
           $pl['portchannel']=$rowpropv['portchannel'];
           $pl['cobre']=$rowpropv['cobre'];
           $pl['fibra']=$rowpropv['fibra'];
       };
       $row_link['properties']=$pl;
       array_push($link,$row_link);
    }

    //define json neo4j format
    $header = '{' . PHP_EOL . ' "results":[{' . PHP_EOL . '     "columns": ["user", "entity"],' . PHP_EOL . '           "data": [{' . PHP_EOL . '               "graph": {' . PHP_EOL . '                   "nodes": ' . '                          ';
    $separator = ',' . PHP_EOL . '              "relationships": ';
    $tail = '}' . PHP_EOL . '}]' . PHP_EOL . '}],' . PHP_EOL . ' "errors": []' . PHP_EOL . '}';

    //write to json file
    $file = fopen("../json/inventario.json","w");
    echo fwrite($file,$header);
    echo fwrite($file,json_encode($data, JSON_PRETTY_PRINT));
    echo fwrite($file,$separator);
    echo fwrite($file,json_encode($link, JSON_PRETTY_PRINT));
    echo fwrite($file,$tail);
    fclose($file);

    //echo
    echo json_encode($data, JSON_PRETTY_PRINT);
    echo json_encode($link, JSON_PRETTY_PRINT);
    
?>