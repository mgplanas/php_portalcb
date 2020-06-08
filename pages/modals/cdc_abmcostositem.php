<div class="modal fade" id="modal-abm-costoitem">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-costoitem-title'>Nuevo Costeo de producto</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_subcat" placeholder="id" id='modal-abm-costoitem-subcat-id' >
                <!-- <h3 id="modal-abm-costoitem-categoria"></h3> -->
                <h3 id="modal-abm-costoitem-cat"></h3>
                <h4 id="modal-abm-costoitem-subcat"></h4>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripcion </label>
                                <input type="text" class="form-control" name="descripcion"  id='modal-abm-costoitem-descripcion' required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="unidad">Unidad </label>
                                <input type="text" class="form-control" name="unidad"  id='modal-abm-costoitem-unidad' required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="costo">Costo </label>
                                <input type="number" min="0" value="1" class="form-control" name="costo"  id='modal-abm-costoitem-costo' required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddEnte" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-costoitem-submit'>
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