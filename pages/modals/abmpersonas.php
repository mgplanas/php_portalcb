<!-- MODAL ADD PERSONA -->
<div class="modal fade" id="modal-abm-persona">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id="modal-abm-persona-title">Nueva Persona</h2>
            <?php
            $gerencias = mysqli_query($con, "SELECT * FROM gerencia ORDER BY nombre ASC");
            $grupos = mysqli_query($con, "SELECT * FROM grupo ORDER BY nombre ASC");
            if(isset($_POST['AddPersona'])){
                
                $legajo = mysqli_real_escape_string($con,(strip_tags($_POST["legajo"],ENT_QUOTES)));//Escanpando caracteres
                $nombre = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres
                $apellido = mysqli_real_escape_string($con,(strip_tags($_POST["apellido"],ENT_QUOTES)));//Escanpando caracteres 
                $cargo = mysqli_real_escape_string($con,(strip_tags($_POST["cargo"],ENT_QUOTES)));//Escanpando caracteres 
                $gerencia = mysqli_real_escape_string($con,(strip_tags($_POST["gerencia"],ENT_QUOTES)));//Escanpando caracteres 
                $email = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres 
                $grupo = mysqli_real_escape_string($con,(strip_tags($_POST["grupo"],ENT_QUOTES)));//Escanpando caracteres 
                $contacto = mysqli_real_escape_string($con,(strip_tags($_POST["contacto"],ENT_QUOTES)));//Escanpando caracteres 
                
                // Si la gerencia no es ciberseguridad limpio el valor del grupo
                if ($gerencia != 1) {
                    $grupo = 0;
                }
                //Inserto Control
                $insert_persona = mysqli_query($con, "INSERT INTO persona(legajo, nombre, apellido, cargo, gerencia, email, grupo, contacto, borrado) 
                                                    VALUES ('$legajo','$nombre','$apellido', '$cargo', '$gerencia', '$email', '$grupo', '$contacto', 0)") or die(mysqli_error());	
                $lastInsert = mysqli_insert_id($con);
                $insert_audit = mysqli_query($con, "INSERT INTO auditoria (evento, item, id_item, fecha, usuario) 
                            VALUES ('1', '2', '$lastInsert', now(), '$user')") or die(mysqli_error());
                unset($_POST);
                if($insert_persona){
                $_SESSION['formSubmitted'] = 3;
                echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                }else{
                $_SESSION['formSubmitted'] = 9;
                echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
                }				
            }				
            ?>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                <div class="box-body">
                    <div class="form-group">
                        <label for="legajo">Legajo</label>
                        <input type="text" id="modal-abm-persona-legajo" class="form-control" name="legajo" placeholder="Legajo">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="modal-abm-persona-nombre" class="form-control" name="nombre" placeholder="Nombre">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="modal-abm-persona-apellido" class="form-control" name="apellido" placeholder="Apellido">
                    </div>
                    <div class="form-group">
                        <label for="email">Dirección E-mail</label>
                        <input type="text" id="modal-abm-persona-email" class="form-control" name="email" placeholder="E-mail corporativo">
                    </div>
                    <div class="form-group">
                        <label for="contacto">Contacto</label>
                        <input type="text" id="modal-abm-persona-contacto" class="form-control" name="contacto" placeholder="Nro de contacto">
                    </div>
                    <div class="form-group">
                        <label for="cargo">Cargo</label>
                        <input type="text" id="modal-abm-persona-cargo" class="form-control" name="cargo" placeholder="Cargo">
                    </div>
                    <div class="form-group">
                        <label>Gerencia</label>
                        <select id="modal-abm-persona-gerencia" name="gerencia" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>SubGerencia</label>
                        <select id="modal-abm-persona-subgerencia" name="subgerencia" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Area</label>
                        <select id="modal-abm-persona-area" name="area" class="form-control">
                        </select>
                    </div>
                    <div class="form-group" id="modal-abm-persona-grupo-div">
                        <label>Grupo</label>
                        <select id="modal-abm-persona-grupo" name="grupo" class="form-control" id="gruposelector">
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <input id="modal-abm-persona-submit" type="button" name="AddPersona" class="btn  btn-raised btn-success" value="Guardar datos">
                        </div>
                        <div class="col-sm-3">
                            <button id="modal-abm-persona-cancel" type="button"class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- FIN MODAL PERSONA -->