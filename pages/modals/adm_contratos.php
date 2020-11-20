<div class="modal fade" id="modal-abm-contrato">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-contrato-title'>Nuevo Seguimiento contrato</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-contrato-id' >
                
                <div class="box-body">
                    <div class="col-md-5 input-group">
                        <input type="text" placeholder="OC-XXXXXX" class="form-control" id="modal-abm-contrato-oc" name="oc">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary" id="modal-abm-contrato-oc-search-btn"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>     
                    <div class="form-group">
                        <label>Subgerencia</label>
                        <select name="subgerencia" class="form-control" id='modal-abm-contrato-subgerencia'>
                            <?php
                            $subgerencias = mysqli_query($con, 
                                "SELECT s.id_subgerencia, s.nombre, r.nombre as resp_nombre, r.apellido as resp_apellido 
                                   FROM subgerencia as s 
                                   INNER JOIN persona as r ON s.responsable = r.id_persona 
                                   WHERE id_gerencia = 2 AND s.borrado = 0 ORDER BY nombre"); // SOLO GTI
                            while($rowper = mysqli_fetch_assoc($subgerencias)){
                                echo "<option value=". $rowper['id_subgerencia'] . ">" . $rowper['nombre'] . ' - ' . $rowper['resp_apellido'] . ', ' . $rowper['resp_nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div> 
                    <div class="form-group">
                        <label>Proveedor</label>
                        <select name="proveedor" class="form-control" id='modal-abm-contrato-proveedor'>
                            <?php
                            $proveedores = mysqli_query($con, "SELECT * FROM adm_com_proveedores WHERE borrado = 0 ORDER BY razon_social");
                            while($rowper = mysqli_fetch_assoc($proveedores)){
                                echo "<option value=". $rowper['id'] . ">" . $rowper['razon_social'] . "</option>";
                            }
                            ?>
                        </select>
                    </div> 
                    <div class="form-group">
                        <label for="tipo">Mantenimiento/Soporte</label>
                        <input type="text" class="form-control" name="tipo" placeholder="" id='modal-abm-contrato-tipo' required>
                    </div>                    
                 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vencimiento">Fecha de Vencimiento</label>
                                <div class="input-group date" data-provide="modal-abm-contrato-vencimiento">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" required="required" name="FechaSol" id="modal-abm-contrato-vencimiento">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Crítico</label>
                                <select name="criticidad" class="form-control" id='modal-abm-contrato-criticidad'>
                                    <?php
                                    $critididades = mysqli_query($con, "SELECT * FROM adm_criticidad WHERE borrado = 0 ORDER BY id");
                                    while($rowper = mysqli_fetch_assoc($critididades)){
                                        echo "<option value=". $rowper['id'] . ">" . $rowper['criticidad'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                    </div>              

                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-contrato-submit'>
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