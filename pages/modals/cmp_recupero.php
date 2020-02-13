<div class="modal fade" id="modal-cmp-recupero">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-cmp-recupero-title'>Titulo</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" id='modal-cmp-recupero-id' >
              
                <div class="box-body">
                    <div class="form-group">
                        <label>Persona</label>
                        <select name="persona" class="form-control" id="modal-cmp-recupero-persona">
                            <?php
                                $personasn = mysqli_query($con, "SELECT * FROM persona  WHERE borrado=0 ORDER BY apellido, nombre");
                                while($rowps = mysqli_fetch_array($personasn)){
                                    echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                }
                            ?>
                        </select>
                    </div>                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Días de recupero</label>
                                <select name="dias" class="form-control" id="modal-cmp-recupero-dias">
                                    <option value="0">0.5</option>;										
                                    <option value="1">1</option>;										
                                    <option value="2">2</option>;										
                                    <option value="3">3</option>;										
                                    <option value="4">4</option>;										
                                    <option value="5">5</option>;										
                                </select>
                            </div>                    

                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="Fecha">Fecha</label>
                                <div class="input-group date" data-provide="modal-cmp-recupero-fecha">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="Fecha" id="modal-cmp-recupero-fecha">
                                </div>                        
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <input type="text" class="form-control pull-right" name="descripcion" id="modal-cmp-recupero-descripcion">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddItem" class="btn btn-raised btn-success" value="Guardar" id='modal-cmp-recupero-submit'>
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