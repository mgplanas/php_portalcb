<div class="modal fade" id="modal-abm-ente">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-ente-title'>Nuevo Ente Auditor</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-ente-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label for="nombre">Razón Social</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Razón Social" id='modal-abm-ente-nombre' required>
                    </div>
                    <div class="form-group">
                        <label for="cuit">CUIT</label>
                        <input type="text" class="form-control" name="cuit" placeholder="CUIT" id='modal-abm-ente-cuit'>
                    </div>                    
                    <div class="form-group">
                        <label for="obs">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="modal-abm-ente-observaciones"></textarea>                        
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddEnte" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-ente-submit'>
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