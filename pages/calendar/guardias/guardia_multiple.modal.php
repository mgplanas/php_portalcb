<div class="modal fade" id="modal-abm-cal-guardias-mul">
  
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-cal-guardias-mul-title'>NOMBRE</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-cal-guardias-mul-id' >
                <input type="hidden" class="form-control" name="idPersona" placeholder="id" id='modal-abm-cal-guardias-mul-id-persona' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Tipo de Guardia</label>
                                    <select name="tipo" class="form-control" id='modal-abm-cal-guardias-mul-tipo'>
                                        <?php
                                            $tipo = mysqli_query($con, "SELECT * FROM adm_guardias_tipos WHERE borrado = 0;"); 
                                            while($row = mysqli_fetch_assoc($tipo)){
                                                echo "<option data-inicio=".$row['horario_inicio']." data-fin=".$row['horario_fin']." data-color=". $row['color']." value=". $row['id'] . ">" . $row['nombre'] . ' - [' . substr($row['horario_inicio'],0,5) . '-' . substr($row['horario_fin'],0,5) . "]</option>";
                                            }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fechainicio">Comienzo</label>
                                        <div class="input-group date" data-provide="modal-abm-cal-guardias-mul-inicio">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" required="required" name="fechainicio" id="modal-abm-cal-guardias-mul-inicio">
                                        </div>                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dias">Días</label>
                                        <select id="modal-abm-cal-guardias-mul-dias" class="form-control" name="dias" >
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
                                <div class="col-md-2">
                                    <div class="form-group" style="margin-left: -10px;">
                                        <label style="color: transparent">Agregar</label>
                                        <button type="button" class="btn btn-primary" id="modal-abm-cal-guardias-mul-add" title="Agregar período"><i class="fa fa-plus"></i></button>  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="modal-abm-cal-guardias-mul-tabla" class="display" width="100%">
                                        <thead>
                                            <th>ComienzoISO</th>
                                            <th>FinISO</th>
                                            <th width="40%">Comienzo</th>
                                            <th width="40%">Fin</th>
                                            <th width="10px">Días</th>
                                            <th width="10px" style="text-align: right;"><i class="fa fa-bolt" /></th>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Aplicar esquema de guardia a:</label> 
                                <select style="height:250px"  name="personas[]" class="form-control custom-select selectpicker show-tick" multiple id="modal-abm-cal-guardias-mul-personas">
                                    <?php
                                        $empleados = mysqli_query($con, "SELECT * FROM persona WHERE area = 7 and borrado = 0 ORDER BY apellido,nombre DESC;"); 
                                        while($row = mysqli_fetch_assoc($empleados)){
                                            echo "<option value=". $row['id_persona'] . ">" . $row['apellido'] . ', '. $row['nombre']  . "</option>";
                                        }
                                    ?>
                                </select>
                                <p class="text-aqua">Presione CTRL para selección múltiple</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea class=form-control name="observaciones" id="modal-abm-cal-guardias-mul-observaciones"></textarea>
                            </div>                           
                        </div>
                    </div>              
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-cal-guardias-mul-submit'>
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