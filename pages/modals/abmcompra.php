<!-- MODAL ADD PERSONA -->
<div class="modal fade" id="modal-abm-compra">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id="modal-abm-compra-title">Nueva Compra</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">

                <input type="hidden" class="form-control" name="id" id='modal-abm-compra-id' >

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gerencia</label>
                                <select id="modal-abm-compra-gerencia" name="gerencia" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SubGerencia</label>
                                <select id="modal-abm-compra-subgerencia" name="subgerencia" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="FechaSol">Fecha de solicitud</label>
                                <div class="input-group date" data-provide="modal-abm-compra-fecha-sol">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="FechaSol" id="modal-abm-compra-fecha-sol">
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nrosolicitud">Nro. Solicitud</label>
                                <input type="text" id="modal-abm-compra-solicitud" class="form-control" name="nrosolicitud" placeholder="SC-000000000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Solicitante</label>
                                <select id="modal-abm-compra-solicitante" name="solicitante" class="form-control">
                                </select>
                            </div>
                        </div>                        
                    </div>                  
                    <div class="form-group">
                        <label for="concepto">Concepto</label>
                        <textarea class="form-control" rows="3" name="concepto" id="modal-abm-compra-concepto"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Moneda</label>
                                <select id="modal-abm-compra-moneda" name="moneda" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="presupuesto">Presupuesto Estimado</label>
                                <input type="number" id="modal-abm-compra-presupuesto" class="form-control" name="presupuesto" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CAPEX/OPEX</label>
                                <select id="modal-abm-compra-capex-opex" name="capexopex" class="form-control">
                                </select>
                            </div>                            
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="plazo">Plazo</label>
                                <input type="number" id="modal-abm-compra-plazo" class="form-control" name="plazo" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Unidad</label>
                                <select id="modal-abm-compra-plazo-unidad" name="plazounidad" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>                    

                    <div class="form-group">
                        <div class="col-sm-3">
                            <input id="modal-abm-compra-submit" type="button" name="AddPersona" class="btn  btn-raised btn-success" value="Guardar datos">
                        </div>
                        <div class="col-sm-3">
                            <button id="modal-abm-compra-cancel" type="button"class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
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
<!-- FIN MODAL PERSONA -->
