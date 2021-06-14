<div class="modal fade" id="modal-abm-doc">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-doc-title'>Nueva Documentación</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-doc-id' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tipo de Documento</label>
                            <select name="tipodoc" class="form-control" id='modal-abm-doc-tipodoc'>
                                <?php
                                    $tipodocs = mysqli_query($con, "SELECT id, tipo, descripcion FROM doc_tipos WHERE borrado = 0 ORDER BY tipo;"); // SOLO GTI
                                    while($row = mysqli_fetch_assoc($tipodocs)){
                                        echo "<option value=". $row['id'] . ">" . $row['tipo'] . ' - ' . $row['descripcion'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div> 
                        <div class="col-md-6 form-group">
                            <label for="version">Versión</label>
                            <input type="number" min="1" class="form-control" name="version" placeholder="" id='modal-abm-doc-version' required>
                        </div>                    
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre del documento</label>
                        <input type="text" class="form-control" name="nombre" placeholder="" id='modal-abm-doc-nombre' required>
                    </div>                    
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-link"></i> </span>
                        <input type="text" class="form-control" name="doclink" placeholder="URL al documento" id='modal-abm-doc-doclink'>
                    </div>               
                    <hr> 
                    <div class="form-group">
                        <label>Owner</label>
                        <select name="owner" class="form-control" id="modal-abm-doc-owner">
                            <?php
                                $personasn = mysqli_query($con, "SELECT * FROM persona  WHERE borrado=0 ORDER BY apellido, nombre");
                                while($rowps = mysqli_fetch_array($personasn)){
                                    echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                }
                            ?>
                        </select>
                    </div> 
                    <div class="form-group">
                        <label>Área</label>
                        <select name="owner" class="form-control" id="modal-abm-doc-area">
                            <?php
                                $areas = mysqli_query($con, "SELECT * FROM doc_areas  WHERE borrado=0 ORDER BY area;");
                                while($rowps = mysqli_fetch_array($areas)){
                                    echo "<option value='". $rowps['id'] . "'>" .$rowps['descripcion'] ."</option>";										
                                }
                            ?>
                        </select>
                    </div>                     
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vencimiento">Vigencia</label>
                                <div class="input-group date" data-provide="modal-abm-doc-vigencia">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" required="required" name="FechaVigencia" id="modal-abm-doc-vigencia">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="frecuencia">Frecuencia</label>
                                <input type="number" min="1" max="365" class="form-control" name="frecuencia" placeholder="días" id='modal-abm-doc-frecuencia' required>
                            </div>                    
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Vencimiento</label>
                                <input type="text" class="form-control" name="nombre" placeholder="" id='modal-abm-doc-next' disabled="disabled">
                            </div>                                    
                        </div>
                    </div>              
                    <hr>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Periodicidad de comunicación</label>
                            <select name="periodicidad" class="form-control" id="modal-abm-doc-periodicidad">
                                <?php
                                    $periodicidad = mysqli_query($con, "SELECT * FROM doc_periodicidad  WHERE borrado=0 ORDER BY periodicidad;");
                                    while($rowps = mysqli_fetch_array($periodicidad)){
                                        echo "<option value='". $rowps['id'] . "'>" .$rowps['periodicidad'] ."</option>";										
                                    }
                                ?>
                            </select>
                        </div> 
                        <div class="col-md-6 form-group">
                            <label>Forma</label>
                            <select name="forma" class="form-control" id="modal-abm-doc-forma">
                                <?php
                                    $forma = mysqli_query($con, "SELECT * FROM doc_formas_com  WHERE borrado=0 ORDER BY forma;");
                                    while($rowps = mysqli_fetch_array($forma)){
                                        echo "<option value='". $rowps['id'] . "'>" .$rowps['forma'] ."</option>";										
                                    }
                                ?>
                            </select>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <label for="comunidado">Fecha Comunicación</label>
                            <div class="input-group date" data-provide="modal-abm-doc-comunicado">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" required="required" name="FechaComunicado" id="modal-abm-doc-comunicado">
                            </div>                        
                        </div>                    
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <?php if ($rq_sec['admin_doc'] == '1') { ?>
                                <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-doc-submit'>
                            <?php } ?>
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