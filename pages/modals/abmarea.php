<div class="modal fade" id="modal-abm-area">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-area-title'>Nueva Persona</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_area" placeholder="id_area" id='modal-abm-area-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Sub Gerencia</label>
                        <select name="subgerencia" class="form-control" id='modal-abm-area-id-subgerencia'>
                            <?php
                            mysqli_data_seek($subgerencias, 0);
                            while($rowper = mysqli_fetch_assoc($subgerencias)){
                                echo "<option value=". $rowper['id_subgerencia'] . ">" . $rowper['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre" id='modal-abm-area-nombre'>
                    </div>
                    <div class="form-group">
                        <label for="sigla">Sigla</label>
                        <input type="text" class="form-control" name="sigla" placeholder="sigla" id='modal-abm-area-sigla'>
                    </div>
                    <div class="form-group">
                        <label>Responsable</label>
                        <select name="responsable" class="form-control" id='modal-abm-area-responsable'>
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
                            <input type="button" name="Addarea" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-area-submit'>
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