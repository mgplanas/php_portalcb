<div class="modal fade" id="modal-abm-cal-registro">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-cal-registro-title'>NOMBRE</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-cal-registro-id' >
                <input type="hidden" class="form-control" name="idPersona" placeholder="id" id='modal-abm-cal-registro-id-persona' value="<?=$id_rowp ?>">
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechainicio">Inicio del trabajo</label>
                                <input type="datetime-local" id="modal-abm-cal-registro-inicio" required="required" name="fechainicio">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechafin">Fin del trabajo</label>
                                <input type="datetime-local" id="modal-abm-cal-registro-fin" required="required" name="fechafin">
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o"></i> Duración</label><br>
                                <label id="modal-abm-cal-registro-duracion"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="modal-abm-cal-registro-programada"> Es una tarea programada?
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Justificación</label>
                                <textarea class=form-control name="observaciones" id="modal-abm-cal-registro-justificacion"></textarea>
                            </div>                           
                        </div>
                    </div>              
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="button" name="eliminar" class="btn  btn-raised btn-danger" value="Eliminar" id='modal-abm-cal-registro-remove'>
                        </div>
                        <div class="col-sm-6"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-cal-registro-submit'>
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