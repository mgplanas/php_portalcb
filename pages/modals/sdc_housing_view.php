<div class="modal fade" id="modal-abm-housing">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-housing-title'>Servicio de Housing</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_cliente" placeholder="id_cliente" id='modal-abm-housing-id-cliente' >
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-housing-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label for="nombre">M2</label>
                        <input type="text" class="form-control" name="m2" placeholder="m2" id='modal-abm-housing-m2'>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Sala</label>
                        <input type="text" class="form-control" name="Sala" placeholder="Sala" id='modal-abm-housing-sala'>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Fila</label>
                        <input type="text" class="form-control" name="fila" placeholder="fila" id='modal-abm-housing-fila'>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Rack</label>
                        <input type="text" class="form-control" name="rack" placeholder="rack" id='modal-abm-housing-rack'>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Observaciones</label>
                        <input type="text" class="form-control" name="observaciones" placeholder="observaciones" id='modal-abm-housing-observaciones'>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="Addarea" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-housing-submit'>
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