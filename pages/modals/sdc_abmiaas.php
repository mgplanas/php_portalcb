<div class="modal fade" id="modal-abm-iaas">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-iaas-title'>Nueva reserva IAAS</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-iaas-id' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <label>Cliente</label>
                            <select name="cliente" class="form-control" id='modal-abm-iaas-cliente'>
                                <?php
                                $organismos = mysqli_query($con, "SELECT * FROM cdc_cliente WHERE borrado = 0 ORDER BY razon_social");
                                while($rowper = mysqli_fetch_assoc($organismos)){
                                    echo "<option value=". $rowper['id'] . ">" . $rowper['razon_social'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>     
                    </div>     
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="plataforma">Plataforma</label>
                                <input type="text" class="form-control" name="plataforma" placeholder="plataforma" id='modal-abm-iaas-plataforma'>
                            </div>
                            <div class="col-md-6">
                                <label for="reserva">Reserva</label>
                                <input type="text" class="form-control" name="reserva" placeholder="reserva" id='modal-abm-iaas-reserva'>
                            </div>             
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="ram_capacidad">RAM [GB]</label>
                                <input type="number" min="0" step="1" class="form-control floatNumber" name="ram_capacidad" placeholder="GB" id='modal-abm-iaas-ram_capacidad'>
                            </div>
                            <div class="col-md-6">
                                <label for="storage_capacidad">Storage [GB]</label>
                                <input type="number" min="0" step="1" class="form-control floatNumber" name="storage_capacidad" placeholder="GB" id='modal-abm-iaas-storage_capacidad'>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="ram_uso">Uso RAM [GB]</label>
                                <input type="number" min="0" step="1" class="form-control floatNumber" name="ram_uso" placeholder="GB" id='modal-abm-iaas-ram_uso'>
                            </div>
                            <div class="col-md-6">
                                <label for="storage_uso">Uso Storage [GB]</label>
                                <input type="number" min="0" step="1" class="form-control floatNumber" name="storage_uso" placeholder="GB" id='modal-abm-iaas-storage_uso'>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <input type="text" class="form-control" name="observaciones" placeholder="observaciones" id='modal-abm-iaas-observaciones'>
                        </div>                    
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-iaas-submit'>
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