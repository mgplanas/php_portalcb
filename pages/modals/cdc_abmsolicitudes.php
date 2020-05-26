<div class="modal fade" id="modal-abm-solicitud">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-solicitud-title'>Nueva Solicitud de Infra</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-solicitud-id' >
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Requiriente</label>
                        <select name="cliente" class="form-control" id='modal-abm-solicitud-cliente'>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FechaSol">Fecha de solicitud</label>
                                <div class="input-group date" data-provide="modal-abm-solicitud-fecha-sol">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" required="required" name="FechaSol" id="modal-abm-solicitud-fecha-sol">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="cliente" class="form-control" id='modal-abm-solicitud-estado'>
                                    <?php
                                    $estados = mysqli_query($con, "SELECT * FROM cdc_estados_solicitud");
                                    while($rowper = mysqli_fetch_assoc($estados)){
                                        echo "<option value=". $rowper['id'] . ">" . $rowper['nombre'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>    
                        </div>
                    </div>              
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" name="titulo" placeholder="" id='modal-abm-solicitud-titulo' required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción Solicitud</label>
                        <textarea class="form-control" rows="3" name="descripcion" id="modal-abm-solicitud-descripcion"></textarea>
                    </div>                          
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input name="convenio" id='modal-abm-solicitud-convenio' type="checkbox"> Posee convenio?
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input name="propuesta" id='modal-abm-solicitud-propuesta' type="checkbox"> Posee Propuesta Comercial?
                            </label>
                        </div>
                    </div>
                    <div id="modal-abm-solicitud-propuesta-detalle-div">
                        <div class="form-group" >
                            <label for="propuesta_detalle">Detalle Propuesta Comercial</label>
                            <textarea class="form-control" rows="3" name="propuesta_detalle" id="modal-abm-solicitud-propuesta-detalle"></textarea>
                        </div>                    
                    </div>
                    <div class="form-group">
                        <label for="ss">#Solicitud de Serevicio</label>
                        <input type="text" class="form-control" name="ss" placeholder="SSXXXXXX" id='modal-abm-solicitud-ss'>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input name="chw" id='modal-abm-solicitud-chw' type="checkbox"> Implica compra de HW?
                            </label>
                        </div>                        
                    </div>
                    <div  id="modal-abm-solicitud-chw-detalle-div">
                        <div class="form-group">
                            <label for="chw_detalle">Detalle Compra de HW</label>
                            <textarea class="form-control" rows="3" name="chw_detalle" id="modal-abm-solicitud-chw-detalle"></textarea>
                        </div>   
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input name="tiene_sc" id='modal-abm-solicitud-tiene-sc' type="checkbox"> Tiene SC?
                                </label>
                            </div>                        
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sc">#Solicitud de compra</label>
                                    <input type="text" class="form-control" name="sc" placeholder="SCXXXXXX" id='modal-abm-solicitud-sc'>
                                </div>                       
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo">Costo</label>
                                    <input type="text" class="form-control" name="costo" id='modal-abm-solicitud-costo'>
                                </div>   
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="solicitante">Solicitante</label>
                        <input type="text" class="form-control" name="solicitante" id='modal-abm-solicitud-solicitante'>
                    </div>   
                    <div class="form-group">
                        <label for="contactos">Detalle Contactos</label>
                        <textarea class="form-control" rows="3" name="contactos" id="modal-abm-solicitud-contactos"></textarea>
                    </div>                      
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-solicitud-submit'>
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