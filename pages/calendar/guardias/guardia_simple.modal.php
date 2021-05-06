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
                                        echo "<option data-inicio=".$row['horario_inicio']." data-fin=".$row['horario_fin']." data-color=". $row['color']." value=". $row['id'] . ">" . $row['nombre'] . ' - [' . substr($row['horario_inicio'],0,5) . '-' . substr($row['horario_fin'],0,5) . "]</option>";
                                    }
                                ?>
                            </select>
                        </div> 
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dias">Días</label>
                                <select id="modal-abm-cal-guardias-dias" class="form-control" name="dias" >
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7" selected>7</option>
                                </select>
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
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="button" name="eliminar" class="btn  btn-raised btn-danger" value="Eliminar" id='modal-abm-cal-guardias-remove'>
                        </div>
                        <div class="col-sm-6"></div>
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