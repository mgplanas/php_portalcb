<div class="modal fade" id="modal-abm-bcra">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-bcra-title'>Titulo</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" id='modal-abm-bcra-id' >
                <input type="hidden" class="form-control" name="versionid"  id='modal-abm-bcra-version-id' >
                <input type="hidden" class="form-control" name="usuario"  id='modal-abm-bcra-user' >
                <input type="hidden" class="form-control" name="rowindex" id='modal-abm-bcra-rowindex' >
              
                <div class="box-body">
                    <div class="form-group">
                        <label for="version">Versión de Matriz</label>
                        <input type="text" class="form-control" name="version" id="modal-abm-bcra-version" readonly>                  
                    </div> 
				    <div class="form-group">
                        <label>Grupo</label>
                        <select name="grupo" class="form-control" id="modal-abm-bcra-grupo">
                            <?php
                                $grupos = mysqli_query($con, "SELECT id_item_bcra, CONCAT(codigo, ' - ' , titulo) as titulo FROM item_bcra WHERE nivel = 1 AND version = " . $current_version );
                                while($rowgrupos = mysqli_fetch_array($grupos)){
                                    echo "<option value='". $rowgrupos['id_item_bcra'] . "'>" .$rowgrupos['titulo'] . "</option>";										
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input type="text" class="form-control" name="codigo" id="modal-abm-bcra-codigo">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class=form-control  name="descripcion" id="modal-abm-bcra-descripcion"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="escenarios">Escenarios</label>
                        <textarea class=form-control  name="escenarios" id="modal-abm-bcra-escenarios"></textarea>
                    </div>
				    <div class="form-group">
                        <label>Responsable</label>
                        <select name="responsable" class="form-control" id="modal-abm-bcra-responsable">
                            <?php
                                $personasn = mysqli_query($con, "SELECT * FROM persona  WHERE borrado=0 ORDER BY apellido, nombre");
                                while($rowps = mysqli_fetch_array($personasn)){
                                    echo "<option value='". $rowps['id_persona'] . "'>" .$rowps['apellido'] . ", " . $rowps['nombre']. " - " .$rowps['cargo'] ."</option>";										
                                }
                            ?>
                        </select>
                    </div>
				    <div class="form-group">
                        <label>Referentes</label>
                        <select name="referentes[]" class="form-control custom-select selectpicker show-tick" multiple id="modal-abm-bcra-referentes">
                            <?php
                                mysqli_data_seek($personasn,0);
                                while($rowps = mysqli_fetch_array($personasn)){
                                    echo "<option value='". $rowps['id_persona'] . "' data-subtext='".$rowps['cargo'] ."'>" .$rowps['apellido'] . ", " . $rowps['nombre']. "</option>";										
                                }
                            ?>
                        </select>
                    </div>
				    <div class="form-group">
                        <label>Cumplimiento</label>
                        <select name="madurez" class="form-control" id="modal-abm-bcra-madurez">
                                <?php
                                    $q_madurez = mysqli_query($con, "SELECT * FROM madurez");
                                    while($rowmd = mysqli_fetch_array($q_madurez)){
                                        echo "<option value='". $rowmd['id_madurez'] . "'>" .$rowmd['nivel'] . "</option>";										
                                    }
                                ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="implementacion">Implementación</label>
                        <textarea class=form-control name="implementacion" id="modal-abm-bcra-implementacion"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="documentacion">Documentación</label>
                        <textarea class=form-control name="documentacion" id="modal-abm-bcra-documentacion"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="evidencia">Evidencia</label>
                        <textarea class=form-control name="evidencia" id="modal-abm-bcra-evidencia"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddItem" class="btn btn-raised btn-success" value="Guardar" id='modal-abm-bcra-submit'>
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