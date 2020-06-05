<div class="modal fade" id="modal-abm-costodet">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-costodet-title'>Nuevo Costeo de producto</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-costodet-id' >
                <h1 id="modal-abm-costodet-categoria"></h1>
                <h2 id="modal-abm-costodet-subcategoria"></h2>
                <h3 id="modal-abm-costodet-producto"></h3>
                <h3 id="modal-abm-costodet-unidad"></h3>
                <div class="box-body">
                    <div class="form-group">
                        <label for="costo">Costo USD </label>
                        <input type="number" min="0" class="form-control" name="costo"  id='modal-abm-costodet-costo' required>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad </label>
                        <input type="number" min="0" class="form-control" name="cantidad"  id='modal-abm-costodet-cantidad' required>
                    </div>
                    <div class="form-group">
                        <label for="costo_ot">Costo (única vez) </label>
                        <input type="number" min="0" class="form-control" name="costo_ot"  id='modal-abm-costodet-costo-ot' required>
                    </div>
                    <div class="form-group">
                        <label for="costo_recurrente">Costo recurrente </label>
                        <input type="number" min="0" class="form-control" name="costo_ot"  id='modal-abm-costodet-costo-ot' required>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddEnte" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-costodet-submit'>
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