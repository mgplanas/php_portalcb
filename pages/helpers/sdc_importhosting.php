<?php
    include("../../conexion.php");
    
    $valid_extensions = array('csv'); // valid extensions
    $hasHeading = $_POST['hasHeading'];

    $result = new stdClass();
    $result->ok = false;

    if ($_POST['op'] == 'READ') {

        if($_FILES['image'])
        {
            $img = $_FILES['image']['name'];
            $filename = $_FILES['image']['tmp_name'];
            $size = $_FILES['image']['size'];
            // get uploaded file's extension
            
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            
            // can upload same image using rand function
            // $final_image = rand(1000,1000000).$img;
            // check's valid format
            $result->state = 'VALIDATING INPUT FILE';
            if(in_array($ext, $valid_extensions)) { 

                if($size > 0) {

                    $result->state = 'INSERT DATA INTO TEMP TABLE';
                    
                    //Borro la temporal
                    $sqlRes = mysqli_query($con, 'TRUNCATE TABLE sdc_hosting_temp;');

                    // Leo el archivo y lo inserto en la temporal
                    $file = fopen($filename, "r");
                    $counter = 0;
                    while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
                    {
                        $counter++;

                        if (!$hasHeading OR ($hasHeading && $counter > 1)) {

                            $vcpu = str_replace(',', '.', $getData[11]);
                            $ram = str_replace(',', '.', $getData[12]);
                            $storage = str_replace(',', '.', $getData[13]);
                            $fecha = DateTime::createFromFormat('d/m/Y', $getData[6])->format('Y-m-d');

                            $sql = 'INSERT INTO sdc_hosting_temp (`Display Name`, Nombre, Tipo, id_cliente, Proyecto, Datacenter, Fecha, Hipervisor, Hostname, Pool, uuid, VCPU, RAM, Storage, `Sistema Operativo`) 
                                    VALUES ("'. $getData[0] .'", "'. $getData[1] .'", "'. $getData[2] .'", "'. $getData[3] .'", "'. $getData[4] .'", "'. $getData[5] .'", "'. $fecha .'", "'. $getData[7] .'", "'. $getData[8] .'", "'. $getData[9] .'", "'. $getData[10] .'", "'. $vcpu .'", "'. $ram .'", "'. $storage .'", "'. $getData[14] .'");';
    
                            $sqlRes = mysqli_query($con, $sql);
                            
                            if(!isset($sqlRes)){
                                $result->error = mysqli_error($con); 
                                echo json_encode($result);
                                die();
                            }
                        }
                    }

                    // Cierro el archivo
                    fclose($file);  
                    $result->tot_imported = $counter;

                    $result->state = 'VERIFICANDO REGISTROS CON CLIENTES VACIOS';
                    // Verifico de que el campo cliente este lleno.
                    $sql = 'SELECT count(*) as cuenta FROM sdc_hosting_temp WHERE (id_cliente is null OR id_cliente = "")';
                    $sqlRes = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($sqlRes);
                    $servicesWithoutClient = $row['cuenta']; 
                    
                    $result->state = 'VERIFICANDO CLIENTES NUEVOS';
                    $sql = 'SELECT count(*) as cuenta FROM sdc_hosting_temp 
                            WHERE (id_cliente is not null) AND id_cliente NOT IN (SELECT cuit FROM cdc_cliente)';
                    $sqlRes = mysqli_query($con, $sql);
                    $newClients = $row['cuenta'];
                    // $newClients = array();             
                    // while ($row = mysqli_fetch_assoc($sqlRes)) {
                    //     if ($row['id_cliente']<>'' && $row['id_cliente'] <> 'id_cliente') array_push($newClients, $row['id_cliente']);
                    // }
                    
                    // $result->state = 'SERVIOS A SER ACTUALIZADOS';
                    // // cruzo los datos importados con los reales.
                    // $sql = 'SELECT count(1) as cuenta 
                    //         FROM sdc_hosting_temp AS T
                    //         INNER JOIN sdc_hosting AS S ON T.uuid = S.uuid
                    //         INNER JOIN cdc_cliente AS C ON T.id_cliente = C.cuit';  
                    // $sqlRes = mysqli_query($con, $sql);
                    // $row = mysqli_fetch_assoc($sqlRes);
                    // $toBeUpdated = $row['cuenta']; 

                    $result->state = 'SERVIOS A SER AGREGADOS';
                    // cruzo los datos importados con los reales.
                    $sql = 'SELECT count(1) as cuenta 
                            FROM sdc_hosting_temp AS T';  
                    $sqlRes = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($sqlRes);
                    $toBeInserted = $row['cuenta']; 

                    $result->tot_toBeInserted = $toBeInserted;
                    // $result->tot_toBeUpdated = $toBeUpdated;
                    $result->tot_emptyClients = $servicesWithoutClient;
                    $result->tot_newClients = $newClients;
                    // $result->tot_newClients = $newClients;

                    $result->ok = ($result->tot_emptyClients == 0 && $result->tot_newClients == 0);

                }
                else { $result->error = 'Archivo vacío'; }
            } 
            else { $result->error = 'Extensión inválida'; }
        }
        else { $result->error = 'invalid'; }
    }
    else if ($_POST['op'] == 'APPLY') {

        // $result->state = 'ACTUALIZACION DE REGISTROS IMPORTADOS';
        // // cruzo los datos importados con los reales.
        // $sql = 'UPDATE sdc_hosting AS S
        //         INNER JOIN sdc_hosting_temp AS T ON T.uuid = S.uuid
        //         INNER JOIN cdc_cliente AS C ON T.id_cliente = C.cuit
        //         SET 
        //         S.fecha = T.Fecha,
        //         S.VCPU = T.VCPU,
        //         S.RAM = T.RAM,
        //         S.storage = T.Storage,
        //         S.id_cliente = C.id,
        //         S.tipo = T.Tipo,
        //         S.nombre = T.Nombre,
        //         S.displayName = T.`Display Name`,
        //         S.proyecto = T.Proyecto,
        //         S.datacenter = T.Datacenter,
        //         S.hipervisor = T.Hipervisor,
        //         S.hostname = T.Hostname,
        //         S.pool = T.Pool,
        //         S.SO = T.`Sistema Operativo`';  
        // $sqlRes = mysqli_query($con, $sql);
        // if(!isset($sqlRes)){
        //     $result->error = mysqli_error($con); 
        //     return;
        // }


        $result->state = 'BACKUP CURRENT DATA';
        //Borro la temporal de Backup
        $sqlRes = mysqli_query($con, 'TRUNCATE TABLE sdc_hosting_bck;');

        // Hago backup de la informacion.
        $sql = 'INSERT INTO sdc_hosting_bck SELECT * FROM sdc_hosting;';  
        $sqlRes = mysqli_query($con, $sql);
        if(!isset($sqlRes)){
            $result->error = mysqli_error($con); 
            return;
        }
        
        //Borro la SCD_HOSTING
        $sqlRes = mysqli_query($con, 'TRUNCATE TABLE sdc_hosting;');

        $result->state = 'INGRESO DE NUEVOS DE REGISTROS IMPORTADOS';
        // cruzo los datos importados con los reales.
        $sql = 'INSERT INTO sdc_hosting (
                fecha,
                VCPU,
                RAM,
                storage,
                id_cliente,
                tipo,
                nombre,
                displayName,
                proyecto,
                datacenter,
                hipervisor,
                hostname,
                pool,
                uuid,
                SO
                )
                SELECT  T.Fecha,
                        T.VCPU,
                        T.RAM,
                        T.Storage,
                        C.id,
                        T.Tipo,
                        T.Nombre,
                        T.`Display Name`,
                        T.Proyecto,
                        T.Datacenter,
                        T.Hipervisor,
                        T.Hostname,
                        T.Pool,
                        T.uuid,
                        T.`Sistema Operativo`
                        FROM sdc_hosting_temp AS T
                        INNER JOIN cdc_cliente AS C ON T.id_cliente = C.cuit and C.borrado =0';
        $sqlRes = mysqli_query($con, $sql);
        if(!isset($sqlRes)){
            $result->error = mysqli_error($con); 
            return;
        }
        $result->ok = true;
    }
    echo json_encode($result);
        
?>