<div class="modal fade" id="modal-abm-dnl">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-dnl-title'>Titulo</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" id='modal-abm-dnl-id' >
              
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="Fecha">Fecha</label>
                                <div class="input-group date" data-provide="modal-abm-dnl-fecha">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="Fecha" id="modal-abm-dnl-fecha">
                                </div>                        
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <input type="text" class="form-control pull-right" name="descripcion" id="modal-abm-dnl-descripcion">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea class=form-control name="observaciones" id="modal-abm-dnl-observaciones"></textarea>
                            </div>                           
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-2">
                                <input type="button" name="AddItem" class="btn btn-raised btn-success" value="Guardar" id='modal-abm-dnl-submit'>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                            </div>
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