<?php
    include("../../conexion.php");
    
    $valid_extensions = array('csv'); // valid extensions
    
    $result = new stdClass();
    $result->ok = false;

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
                    $sql = 'INSERT INTO sdc_hosting_temp (`Display Name`, Nombre, Tipo, id_cliente, id_subcliente, Proyecto, Datacenter, Fecha, Hipervisor, Hostname, Pool, uuid, VCPU, RAM, Storage, `Sistema Operativo`) 
                        VALUES ("'. $getData[0] .'", "'. $getData[1] .'", "'. $getData[2] .'", "'. $getData[3] .'", "'. $getData[4] .'", "'. $getData[5] .'", "'. $getData[6] .'", "'. $getData[7] .'", "'. $getData[8] .'", "'. $getData[9] .'", "'. $getData[10] .'", "'. $getData[11] .'", "'. $getData[12] .'", "'. $getData[13] .'", "'. $getData[14] .'", "'. $getData[15] .'");';

                    $sqlRes = mysqli_query($con, $sql);
                    
                    if(!isset($sqlRes)){
                        $result->error = mysqli_error($con); 
                    }
                }

                // Cierro el archivo
                fclose($file);  
                $result->tot_imported = $counter;

                // cruzo los datos importados con los reales.
                $sql = 'SELECT count(*) as cuenta FROM sdc_hosting_temp WHERE uuid IN (SELECT uuid FROM sdc_hosting);';
                $sqlRes = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($sqlRes);
                $toBeUpdated = $row['cuenta']; 

                $result->tot_toBeInserted = $counter -  $toBeUpdated;
                $result->tot_toBeUpdated = $toBeUpdated;

                $result->ok = true;
            }
            else { $result->error = 'Archivo vacío'; }
        } 
        else { $result->error = 'Extensión inválida'; }
    }
    else { $result->error = 'invalid'; }

    echo json_encode($result);
        
?>