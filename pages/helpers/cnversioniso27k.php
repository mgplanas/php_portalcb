<?php
//     //----------------------------------------------------------------------
//     // SCRIPT DE CREACIN DE UNA NUEVA VERSION ISO27K
//     //----------------------------------------------------------------------
//     /*Datos de conexion a la base de datos FUENTE*/
// //     $db_host = "localhost";
// //     $db_user = "root";
// //     $db_pass = "";
// //     $db_name = "controls";
// //     $iso_curr_ver = 3;
//     $db_host = "192.168.26.122";
//     $db_user = "admin";
//     $db_pass = "Swap33done";
//     $db_name = "controls";
//     $iso_curr_ver = 2;

//     // CONECTO LA FUENTE
//     $con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
//     if(mysqli_connect_errno()){
//         echo 'No se pudo conectar a la base de datos : '.mysqli_connect_error();
//     }
//     mysqli_set_charset($con,"utf8");


//     // CREO LA NUEVA VERSION
//     $sqlinsertVersion = 'INSERT INTO iso27k_version(numero,descripcion,modificacion,borrado)
//                          VALUES ("5.0", "última", now(),0)';
//     $insver = mysqli_query($con, $sqlinsertVersion);
//     $id_new_ver = mysqli_insert_id($con);    

//     mysqli_autocommit($con, false);
//     //RECUPERO EL NIVEL 1 de la fuente y voy armando la estructura en el destino a medida voy navegando la fuente.
//     $sqlnivel1 = 'select i.*, p.nombre, p.apellido from item_iso27k as i 
//                     LEFT JOIN persona as p on i.responsable = p.id_persona
//                     where i.borrado = 0
//                     and i.version = '.$iso_curr_ver.'
//                     and i.nivel = 1 
//                     order by i.id_item_iso27k';
//     $resnivel1 = mysqli_query($con, $sqlnivel1);
//     while($row = mysqli_fetch_assoc($resnivel1)){
//         $sqlinsertN1 = 'INSERT INTO item_iso27k (codigo, titulo, nivel, version, borrado) values ("'. $row['codigo'].'", "'. $row['titulo'].'", 1, '.$id_new_ver.', 0 );';
//         $insnivel1 = mysqli_query($con, $sqlinsertN1);
//         $idN1 = mysqli_insert_id($con);
    
//         $sqlnivel2 = 'select i.*, p.nombre, p.apellido from item_iso27k as i 
//                         LEFT JOIN persona as p on i.responsable = p.id_persona
//                         where i.borrado = 0
//                         and i.parent = '. $row['id_item_iso27k'] . '
//                         order by i.id_item_iso27k';
//         $resnivel2 = mysqli_query($con, $sqlnivel2);
//         while($rowN2 = mysqli_fetch_assoc($resnivel2)){
//             $sqlinsertN2 = 'INSERT INTO item_iso27k (codigo, titulo, descripcion, nivel, version, parent, borrado) values ("'. $rowN2['codigo'].'", "'. $rowN2['titulo'].'", "'. $rowN2['descripcion'].'", 2, '.$id_new_ver.','. $idN1 .', 0 );' ;
//             $insnivel2 = mysqli_query($con, $sqlinsertN2);
//             $idN2 = mysqli_insert_id($con);


//             $sqlnivel3 = 'select i.*, p.nombre, p.apellido from item_iso27k as i 
//                             LEFT JOIN persona as p on i.responsable = p.id_persona
//                             where i.borrado = 0
//                             and i.parent = '. $rowN2['id_item_iso27k'] . '
//                             order by i.id_item_iso27k';
//             $resnivel3 = mysqli_query($con, $sqlnivel3);
//             while($rowN3 = mysqli_fetch_assoc($resnivel3)){

//                 $sqlResponsable = 'SELECT id_persona FROM persona WHERE apellido = "' . $rowN3['apellido'] . '" AND nombre = "' . $rowN3['nombre'] . '" AND borrado = 0';
//                 $resResponsable = mysqli_query($con, $sqlResponsable);
//                 $resp = mysqli_fetch_assoc($resResponsable);
//                 $idResponsable = $resp['id_persona'];

//                 $sqlinsertN3 = 'INSERT INTO item_iso27k(
//                     codigo
//                    ,titulo
//                    ,descripcion
//                    ,madurez
//                    ,implementacion
//                    ,responsable
//                    ,evidencia
//                    ,modificado
//                    ,borrado
//                    ,usuario
//                    ,nivel
//                    ,parent
//                    ,version
//                  ) VALUES (
//                    "' . $rowN3['codigo'] . '"
//                    , "' . $rowN3['titulo'] . '"  
//                    , "' . ($rowN3['descripcion'] ? $rowN3['descripcion'] : 'null') . '"  
//                    , ' . $rowN3['madurez']. '   
//                    ,"' . ($rowN3['implementacion'] ? $rowN3['implementacion'] : 'null') . '"  
//                    , ' . $idResponsable . '   
//                    ,"' . ($rowN3['evidencia'] ? $rowN3['evidencia'] : 'null') . '"  
//                    ,NOW(),0,"sysadmin",3, '. $idN2 . ', '.$id_new_ver.')'; 
//                    $insnivel3 = mysqli_query($con, $sqlinsertN3);
//                    $idN3 = mysqli_insert_id($con);
                   
//                 // // RESPONSABLES
//                 $sqlRef = 'select id_persona FROM iso27k_refs where borrado = 0 and id_item_iso27k = '.$rowN3['id_item_iso27k'];
//                 $resRefs = mysqli_query($con, $sqlRef);
//                 while($rowRef = mysqli_fetch_assoc($resRefs)){      
//                         $sqlcloneReferentes = 'INSERT INTO iso27k_refs(id_item_iso27k, id_persona) VALUES ('.$idN3.', '.$rowRef['id_persona'].');'; 
//                         $cloneref = mysqli_query($con, $sqlcloneReferentes);
//                         $idcloneref = mysqli_insert_id($con);
//                 }          
//             }
//         }
//     }
//     // if ($resultado) {
//     mysqli_commit($con);
//     // } else {
//     //     mysqli_rollback($con);
//     // }
//     //mysqli_rollback($con);
//     mysqli_autocommit($con, true);
//     echo "Finished.";
?> 