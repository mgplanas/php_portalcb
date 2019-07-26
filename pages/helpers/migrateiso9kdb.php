<?php
    //----------------------------------------------------------------------
    // SCRIPT DE MIGRACION DE DESA A XXXX
    //----------------------------------------------------------------------
    /*Datos de conexion a la base de datos FUENTE*/
    $db_host_SRC = "localhost";
    $db_user_SRC = "root";
    $db_pass_SRC = "";
    $db_name_SRC = "controls";

    /*Datos de conexion a la base de datos TARGET*/
    $db_host_TRG = "192.168.24.122";
    $db_user_TRG = "admin";
    $db_pass_TRG = "Swap33done";
    $db_name_TRG = "controls";

    // CONECTO LA FUENTE
    $con_SRC = mysqli_connect($db_host_SRC, $db_user_SRC, $db_pass_SRC, $db_name_SRC);
    if(mysqli_connect_errno()){
        echo 'No se pudo conectar a la base de datos : '.mysqli_connect_error();
    }
    mysqli_set_charset($con_SRC,"utf8");


    // CONECTO EL DESTINO
    $con_TRG = mysqli_connect($db_host_TRG, $db_user_TRG, $db_pass_TRG, $db_name_TRG);
    if(mysqli_connect_errno()){
        echo 'No se pudo conectar a la base de datos : '.mysqli_connect_error();
    }
    mysqli_set_charset($con_TRG,"utf8");

    mysqli_autocommit($con_TRG, false);
    //RECUPERO EL NIVEL 1 de la fuente y voy armando la estructura en el destino a medida voy navegando la fuente.
    $sqlnivel1 = 'select i.*, p.nombre, p.apellido from item_iso9k as i 
                    LEFT JOIN persona as p on i.responsable = p.id_persona
                    where i.borrado = 0
                    and i.version = 1
                    and i.nivel = 1 
                    order by i.id_item_iso9k';
    $resnivel1 = mysqli_query($con_SRC, $sqlnivel1);
    while($row = mysqli_fetch_assoc($resnivel1)){
        $sqlinsertN1 = 'INSERT INTO item_iso9k (codigo, titulo, nivel, version, borrado) values ("'. $row['codigo'].'", "'. $row['titulo'].'", 1, 2, 0 );';
        $insnivel1 = mysqli_query($con_TRG, $sqlinsertN1);
        $idN1 = mysqli_insert_id($con_TRG);
    
        $sqlnivel2 = 'select i.*, p.nombre, p.apellido from item_iso9k as i 
                        LEFT JOIN persona as p on i.responsable = p.id_persona
                        where i.borrado = 0
                        and i.parent = '. $row['id_item_iso9k'] . '
                        order by i.id_item_iso9k';
        $resnivel2 = mysqli_query($con_SRC, $sqlnivel2);
        while($rowN2 = mysqli_fetch_assoc($resnivel2)){
            $sqlinsertN2 = 'INSERT INTO item_iso9k (codigo, titulo, descripcion, nivel, version, parent, borrado) values ("'. $rowN2['codigo'].'", "'. $rowN2['titulo'].'", "'. $rowN2['descripcion'].'", 2, 2,'. $idN1 .', 0 );' ;
            $insnivel2 = mysqli_query($con_TRG, $sqlinsertN2);
            $idN2 = mysqli_insert_id($con_TRG);


            $sqlnivel3 = 'select i.*, p.nombre, p.apellido from item_iso9k as i 
                            LEFT JOIN persona as p on i.responsable = p.id_persona
                            where i.borrado = 0
                            and i.parent = '. $rowN2['id_item_iso9k'] . '
                            order by i.id_item_iso9k';
            $resnivel3 = mysqli_query($con_SRC, $sqlnivel3);
            while($rowN3 = mysqli_fetch_assoc($resnivel3)){

                $sqlResponsable = 'SELECT id_persona FROM persona WHERE apellido = "' . $rowN3['apellido'] . '" AND nombre = "' . $rowN3['nombre'] . '" AND borrado = 0';
                $resResponsable = mysqli_query($con_SRC, $sqlResponsable);
                $resp = mysqli_fetch_assoc($resResponsable);
                $idResponsable = $resp['id_persona'];

                $sqlinsertN3 = 'INSERT INTO item_iso9k(
                    codigo
                   ,titulo
                   ,descripcion
                   ,madurez
                   ,implementacion
                   ,responsable
                   ,evidencia
                   ,modificado
                   ,borrado
                   ,usuario
                   ,nivel
                   ,parent
                   ,version
                 ) VALUES (
                   "' . $rowN3['codigo'] . '"
                   , "' . $rowN3['titulo'] . '"  
                   , "' . ($rowN3['descripcion'] ? $rowN3['descripcion'] : 'null') . '"  
                   , ' . $rowN3['madurez']. '   
                   ,"' . ($rowN3['implementacion'] ? $rowN3['implementacion'] : 'null') . '"  
                   , ' . $idResponsable . '   
                   ,"' . ($rowN3['evidencia'] ? $rowN3['evidencia'] : 'null') . '"  
                   ,NOW(),0,"sysadmin",3, '. $idN2 . ', 2)'; 
                $insnivel3 = mysqli_query($con_TRG, $sqlinsertN3);
                $idN3 = mysqli_insert_id($con_TRG);
            }
        }
    }
    // if ($resultado) {
    mysqli_commit($con_TRG);
    // } else {
    //     mysqli_rollback($con_TRG);
    // }
    //mysqli_rollback($con_TRG);
    mysqli_autocommit($con_TRG, true);
?> 