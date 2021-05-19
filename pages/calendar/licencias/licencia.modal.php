<div class="modal fade" id="modal-abm-cal-lic">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-cal-lic-title'>NOMBRE</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-cal-lic-id' >
                <input type="hidden" class="form-control" name="idPersona" placeholder="id" id='modal-abm-cal-lic-id-persona' value="<?=$id_rowp ?>">
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechainicio">Inicio de licencia</label>
                                <input type="date" id="modal-abm-cal-lic-inicio" required="required" name="fechainicio">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechafin">Fin de licencia</label>
                                <input type="date" id="modal-abm-cal-lic-fin" required="required" name="fechafin">
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o"></i> Duración</label><br>
                                <label id="modal-abm-cal-lic-duracion"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea class=form-control name="observaciones" id="modal-abm-cal-lic-observaciones"></textarea>
                            </div>                           
                        </div>
                    </div>              
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="button" name="eliminar" class="btn  btn-raised btn-danger" value="Eliminar" id='modal-abm-cal-lic-remove'>
                        </div>
                        <div class="col-sm-6"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-cal-lic-submit'>
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