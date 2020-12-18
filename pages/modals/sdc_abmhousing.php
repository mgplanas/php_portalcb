<div class="modal fade" id="modal-abm-housing">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-housing-title'>Nuevo Cliente DC</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-housing-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select name="cliente" class="form-control" id='modal-abm-housing-cliente'>
                            <?php
                            $organismos = mysqli_query($con, "SELECT * FROM cdc_cliente WHERE borrado = 0 ORDER BY razon_social");
                            while($rowper = mysqli_fetch_assoc($organismos)){
                                echo "<option value=". $rowper['id'] . ">" . $rowper['razon_social'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>      

                    <div class="form-group">
                        <div class="col-md-4">
                            <label for="energia">Energia (KVA)</label>
                            <input type="number" min="0" step="1" class="form-control floatNumber" name="energia" placeholder="KVA" id='modal-abm-housing-energia'>
                        </div>
                        <div class="col-md-4">
                            <label>Modalidad</label>
                            <select name="modalidad" class="form-control" id='modal-abm-housing-modalidad'>
                                <?php
                                $modalidad = mysqli_query($con, "SELECT * FROM sdc_housing_modalidad WHERE borrado = 0");
                                while($rowm = mysqli_fetch_assoc($modalidad)){
                                    echo "<option value=". $rowm['id'] . ">" . $rowm['descripcion'] . "</option>";
                                }
                                ?>
                            </select>             
                        </div>           
                        <div class="col-md-4">
                            <div class="form-group floatNumber">
                                <label for="m2">M2</label>
                                <input type="number" min="0" class="form-control" name="m2" placeholder="Metros cuadrados" id='modal-abm-housing-m2' required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sala">Sala</label>
                            <input type="text" class="form-control" name="sala" placeholder="sala" id='modal-abm-housing-sala'>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fila">Fila</label>
                            <input type="text" class="form-control" name="fila" placeholder="fila" id='modal-abm-housing-fila'>
                        </div>             
                    </div>             
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rack">Rack</label>
                            <input type="text" class="form-control" name="rack" placeholder="rack" id='modal-abm-housing-rack'>
                        </div>                    
                    </div>                    
                    <div class="form-group">
                        <label for="alta">Fecha Alta</label>
                        <input type="date" class="form-control" name="alta" placeholder="Fecha de Alta" id='modal-abm-housing-alta'>
                    </div>                    
                    <div class="form-group">
                        <label for="evidencia">Evidencia</label>
                        <input type="text" class="form-control" name="evidencia" placeholder="Evidencia (SS)" id='modal-abm-housing-evidencia'>
                    </div>               
                    <div class="row">
                        <div class="form-group col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id='modal-abm-housing-telco'> TELCO
                                </label>
                            </div>
                        </div>     
                    </div>
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <input type="text" class="form-control" name="observaciones" placeholder="observaciones" id='modal-abm-housing-observaciones'>
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-housing-submit'>
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
    <script>
        //integer value validation
        $('input.floatNumber').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g, '$1');
        });
    </script>
</div>