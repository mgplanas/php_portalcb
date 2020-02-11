<div class="modal fade" id="modal-abm-per">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-per-title'>Titulo</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" id='modal-abm-per-id' >
              
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="fecha_desde">Fecha Desde</label>
                                <div class="input-group date" data-provide="modal-abm-per-fecha-desde">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="fecha_desde" id="modal-abm-per-fecha-desde">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="fecha_hasta">Fecha Hasta</label>
                                <div class="input-group date" data-provide="modal-abm-per-fecha-hasta">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="fecha_hasta" id="modal-abm-per-fecha-hasta">
                                </div>                        
                            </div>
                        </div>


                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddItem" class="btn btn-raised btn-success" value="Guardar" id='modal-abm-per-submit'>
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