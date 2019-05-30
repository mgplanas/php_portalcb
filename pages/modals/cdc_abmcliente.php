<div class="modal fade" id="modal-abm-cliente">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-cliente-title'>Nuevo Cliente DC</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-cliente-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Organismo</label>
                        <select name="organismo" class="form-control" id='modal-abm-cliente-organismo'>
                            <?php
                            $organismos = mysqli_query($con, "SELECT * FROM cdc_organismo WHERE borrado = 0");
                            while($rowper = mysqli_fetch_assoc($organismos)){
                                echo "<option value=". $rowper['id'] . ">" . $rowper['razon_social'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre" id='modal-abm-cliente-nombre' required>
                    </div>
                    <div class="form-group">
                        <label for="sigla">Sigla</label>
                        <input type="text" class="form-control" name="sigla" placeholder="sigla" id='modal-abm-cliente-sigla'>
                    </div>
                    <div class="form-group">
                        <label for="cuit">CUIT</label>
                        <input type="text" class="form-control" name="cuit" placeholder="CUIT" id='modal-abm-cliente-cuit'>
                    </div>                    
                    <div class="form-group">
                        <div class="radio">
                            <label><input type="radio" name="optSector" id="opt-sector-publico" value="Publico" checked="">Sector Público</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="optSector" id="opt-sector-privado" value="Privado" checked="">Sector Privado</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-cliente-submit'>
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