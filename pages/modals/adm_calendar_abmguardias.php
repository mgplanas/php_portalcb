<div class="modal fade" id="modal-abm-cal-guardias">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-cal-guardias-title'>NOMBRE</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-cal-guardias-id' >
                <input type="hidden" class="form-control" name="idPersona" placeholder="id" id='modal-abm-cal-guardias-id-persona' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tipo de Guardia</label>
                            <select name="tipo" class="form-control" id='modal-abm-cal-guardias-tipo'>
                                <?php
                                    $tipo = mysqli_query($con, "SELECT * FROM adm_guardias_tipos WHERE borrado = 0;"); 
                                    while($row = mysqli_fetch_assoc($tipo)){
                                        echo "<option data-color=". $row['color']." value=". $row['id'] . ">" . $row['nombre'] . ' - [' . substr($row['horario_inicio'],0,5) . '-' . substr($row['horario_fin'],0,5) . "]</option>";
                                    }
                                ?>
                            </select>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechainicio">Fecha Desde</label>
                                <div class="input-group date" data-provide="modal-abm-cal-guardias-inicio">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" required="required" name="fechainicio" id="modal-abm-cal-guardias-inicio">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechafin">Fecha Hasta</label>
                                <div class="input-group date" data-provide="modal-abm-cal-guardias-fin">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" required="required" name="fechafin" id="modal-abm-cal-guardias-fin">
                                </div>                        
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea class=form-control name="observaciones" id="modal-abm-cal-guardias-observaciones"></textarea>
                            </div>                           
                        </div>
                    </div>              
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-cal-guardias-submit'>
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