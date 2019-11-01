<div class="modal fade" id="modal-abm-subgerencia">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-subgerencia-title'>Nueva Persona</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_subgerencia" placeholder="id_subgerencia" id='modal-abm-subgerencia-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Gerencia</label>
                        <select name="gerencia" class="form-control" id='modal-abm-subgerencia-id-gerencia'>
                            <?php
                            mysqli_data_seek($gerencias, 0);
                            while($rowper = mysqli_fetch_assoc($gerencias)){
                                echo "<option value=". $rowper['id_gerencia'] . ">" . $rowper['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre" id='modal-abm-subgerencia-nombre'>
                    </div>
                    <div class="form-group">
                        <label for="sigla">Sigla</label>
                        <input type="text" class="form-control" name="sigla" placeholder="sigla" id='modal-abm-subgerencia-sigla'>
                    </div>
                    <div class="form-group">
                        <label>Responsable</label>
                        <select name="responsable" class="form-control" id='modal-abm-subgerencia-responsable'>
                            <?php
                            mysqli_data_seek($personas, 0);
                            while($rowper = mysqli_fetch_assoc($personas)){
                                echo "<option value=". $rowper['id_persona'] . ">" . $rowper['apellido'] . ' ' . $rowper['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddsubGerencia" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-subgerencia-submit'>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
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