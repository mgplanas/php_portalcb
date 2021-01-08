<div class="modal fade" id="modal-abm-storage">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-storage-title'>Nuevo Equipo de Storage</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-storage-id' >
                <!-- <h3 id="modal-abm-storage-categoria"></h3> -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="costo">Nombre Equipo </label>
                                <input type="text" class="form-control" name="nombre"  id='modal-abm-storage-nombre' required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select name="categoria" class="form-control" id='modal-abm-storage-categoria'>
                                    <?php
                                    $categorias = mysqli_query($con, "SELECT * FROM sto_categorias WHERE borrado = 0 ORDER BY nombre;");
                                    while($rowper = mysqli_fetch_assoc($categorias)){
                                        echo "<option value=". $rowper['id'] . ">" . $rowper['nombre'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="capacidad_fisica">Capacidad Física [TB] </label>
                                <input type="number" min="0" class="form-control" name="capacidad_fisica"  id='modal-abm-storage-costo-capacidad-fisica' required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asignacion_recomendada">Asignación Recomendada [%] </label>
                                <input type="number" min="0" class="form-control" name="asignacion_recomendada"  id='modal-abm-storage-asignacion-recomendada' required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="capacidad_asignable">Capacidad Asignable [TB] </label>
                                <input type="number" min="0" class="form-control" name="capacidad_asignable"  id='modal-abm-storage-costo-capacidad-asignable' required disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asignacion_max">Estimado Asignación Máx. [%] </label>
                                <input type="number" min="0" class="form-control" name="asignacion_max"  id='modal-abm-storage-asignacion-max' required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddEnte" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-storage-submit'>
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