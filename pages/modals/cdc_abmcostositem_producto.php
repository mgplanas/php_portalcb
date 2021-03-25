<div class="modal fade" id="modal-abm-producto">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-producto-title'>Nuevo Costeo de producto</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_subcat" placeholder="id" id='modal-abm-producto-subcat-id' >
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-producto-id' >
                <!-- <h3 id="modal-abm-producto-categoria"></h3> -->
                <h3 id="modal-abm-producto-subcat"></h3>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripcion </label>
                                <input type="text" class="form-control" name="descripcion"  id='modal-abm-producto-descripcion' required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="unidad">Unidad </label>
                                <input type="text" class="form-control" name="unidad"  id='modal-abm-producto-unidad' required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="costo">Costo </label>
                                <input type="number" min="0" value="0" class="form-control" name="costo"  id='modal-abm-producto-costo' required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" id='modal-abm-producto-oculto'> Oculto
                                </label>
                                </div>                            
                            </div>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddEnte" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-producto-submit'>
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