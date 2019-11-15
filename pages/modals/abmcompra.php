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
            <form id="modal-abm-compra-form" method="post" role="form" action="" >
                <div class="nav-tabs-custom">
                    <!-- BOTON -->
                    <div class="pull-right" style="margin: 10px;">
                        <!-- <button id="btn-showhide-comments" type="button" class="btn" onclick="showComments()"><i class="fa fa-comments"></i>&nbsp;&nbsp;Comentarios</button> -->
                        <!-- <button id="btn-group-table" type="button" class="btn"><i class="fa fa-fa-outdent"></i>&nbsp;&nbsp;Agrupar</button> -->
                        <!-- <button id="modal-abm-compra-btn-alta" type="button" class="btn btbn-block btn-primary btn-sm">Nueva Compra</button> -->
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_compra" data-toggle="tab">General</a></li>
                        <li><a href="#tab_seg" data-toggle="tab">Seguimiento</a></li>
                        <li><a href="#tab_adjudicacion" data-toggle="tab">Adjucicación</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- En proceso -->
                        <div class="tab-pane active" id="tab_compra">
                            <input type="hidden" class="form-control" name="id" id='modal-abm-compra-id' >
                            <input type="hidden" class="form-control" name="pasoactual_id" id='modal-abm-compra-paso-actual-id' >
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="FechaSol">Fecha de solicitud</label>
                                            <div class="input-group date" data-provide="modal-abm-compra-fecha-sol">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" required="required" name="FechaSol" id="modal-abm-compra-fecha-sol">
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nrosolicitud">Nro. Solicitud</label>
                                            <input type="text" id="modal-abm-compra-solicitud" class="form-control" name="nrosolicitud" placeholder="SC-000000000" required="required">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Solicitante</label>
                                            <select id="modal-abm-compra-solicitante" name="solicitante" class="form-control">
                                            </select>
                                        </div>
                                    </div>                        
                                </div>                  
                                <div class="form-group">
                                    <label for="concepto">Concepto</label>
                                    <textarea class="form-control" rows="3" name="concepto" id="modal-abm-compra-concepto" required="required"></textarea>
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
                                </div>                    

                            </div>                            
                        </div>
                        <div class="tab-pane" id="tab_seg">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Paso Actual</label>
                                            <select id="modal-abm-compra-paso-actual" name="pasoactual" class="form-control">
                                            </select>
                                        </div>
                                    </div>                                             
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Estado</label>
                                            <select id="modal-abm-compra-estado" name="estado" class="form-control">
                                            </select>
                                        </div>
                                    </div>   
                                </div>                                
                            </div>                                                           
                        </div>
                        <div class="tab-pane" id="tab_adjudicacion">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="FechaOC">Fecha de OC</label>
                                            <div class="input-group date" data-provide="modal-abm-compra-fecha-oc">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" name="FechaOC" id="modal-abm-compra-fecha-oc">
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nrooc">Nro. OC</label>
                                            <input type="text" id="modal-abm-compra-oc" class="form-control" name="nrooc" placeholder="OC-000000000">
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Moneda</label>
                                            <select id="modal-abm-compra-moneda-oc" name="moneda_oc" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="montooc">Monto OC</label>
                                            <input type="number" id="modal-abm-compra-monto-oc" class="form-control" name="montooc" placeholder="0">
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="plazo">Plazo</label>
                                            <input type="number" id="modal-abm-compra-plazo" class="form-control" name="plazo" placeholder="0" min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Unidad</label>
                                            <select id="modal-abm-compra-plazo-unidad" name="plazounidad" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label style="color: white;">calcular</label>
                                            <button id="modal-abm-compra-calc-ff" title="Calcular fecha de finalización del contrato en base al plazo" type="button"class="btn btn-success"><i class="fa fa-calculator" ></i></button>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="FechaFIN">Fecha Fin Contrato</label>
                                            <div class="input-group date" data-provide="modal-abm-compra-fecha-fin">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" name="FechaFIN" id="modal-abm-compra-fecha-fin">
                                            </div>                        
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Ingrese o seleccione un Proveedor</label>
                                            <div class="input-group">
                                                <!-- /btn-group -->
                                                <input id="modal-abm-compra-proveedor-add-text" type="text" class="form-control" placeholder="Ingrese la razón social para dar de alta un proveedor nuevo">
                                                <div class="input-group-btn">
                                                    <button id="modal-abm-compra-proveedor-add" type="button" class="btn btn-success" disabled="disabled">Agregar Proveedor</button>
                                                </div>
                                            </div>                                            
                                            <select id="modal-abm-compra-proveedor" name="proveedor" class="form-control">
                                            </select>
                                        </div>
                                    </div>                                      
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Proceso de Compra</label>
                                            <select id="modal-abm-compra-proceso" name="proceso" class="form-control">
                                            </select>
                                        </div>
                                    </div>                        

                                </div>   
                            </div>                                                             
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3 pull-right">
                            <button id="modal-abm-compra-cancel" type="button"class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>&nbsp;
                            <input id="modal-abm-compra-submit" type="submit" name="AddPersona" class="btn  btn-raised btn-success" value="Guardar datos">
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
