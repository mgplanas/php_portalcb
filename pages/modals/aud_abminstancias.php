<div class="modal fade" id="modal-abm-instancia">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-instancia-title'>Nueva Instancia de Auditoría</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-instancia-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="nombre" id='modal-abm-instancia-nombre' required>
                    </div>
                    <div class="form-group">
                        <label>Ente Auditor</label>
                        <select id="modal-abm-instancia-ente" name="ente" class="form-control">
                        </select>
                    </div>                    
                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <textarea class="form-control" name="descripcion" id="modal-abm-instancia-descripcion"></textarea>                        
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inicio">Inicio</label>
                                <div class="input-group date" data-provide="modal-abm-instancia-inicio">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="inicio" id="modal-abm-instancia-inicio">
                                </div>                        
                            </div>
                        </div>                                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fin">Fin</label>
                                <div class="input-group date" data-provide="modal-abm-instancia-fin">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="fin" id="modal-abm-instancia-fin">
                                </div>                        
                            </div>
                        </div>                        
                    </div>
                                        
                    <div class="form-group">
                        <label for="obs">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="modal-abm-instancia-observaciones"></textarea>                        
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddInstancia" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-instancia-submit'>
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