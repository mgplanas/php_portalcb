<div class="modal fade" id="modal-abm-servers">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-servers-title'>Nuevo SERVER DC</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-servers-id' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marca" placeholder="Marca" id='modal-abm-servers-marca' required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modelo">Modelo</label>
                            <input type="text" class="form-control" name="modelo" placeholder="Modelo" id='modal-abm-servers-modelo' required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="serie">Nro. Serie</label>
                            <input type="text" class="form-control" name="serie" placeholder="Nro. serie" id='modal-abm-servers-serie' >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="memoria">Memoria</label>
                            <input type="number" class="form-control" name="memoria" placeholder="GB" id='modal-abm-servers-memoria' >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sockets">Sockets</label>
                            <input type="number" class="form-control" name="sockets" placeholder="sockets" id='modal-abm-servers-sockets' >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nucleos">Núcleos</label>
                            <input type="number" class="form-control" name="nucleos" placeholder="nucleos" id='modal-abm-servers-nucleos' required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="sala">Sala</label>
                            <input type="text" class="form-control" name="sala" placeholder="sala" id='modal-abm-servers-sala' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fila">Fila</label>
                            <input type="number" class="form-control" name="fila" placeholder="fila" id='modal-abm-servers-fila' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="rack">Rack</label>
                            <input type="number" class="form-control" name="rack" placeholder="rack" id='modal-abm-servers-rack' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="unidad">Unidad</label>
                            <input type="number" class="form-control" name="unidad" placeholder="unidad" id='modal-abm-servers-unidad' >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="infra">Infraestructura</label>
                            <input type="text" class="form-control" name="infra" placeholder="Tipo de Infra." id='modal-abm-servers-infra' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="ip">IP</label>
                            <input type="text" class="form-control" name="ip" placeholder="IP" id='modal-abm-servers-ip' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="vcenter">Hypervisor</label>
                            <input type="text" class="form-control" name="vcenter" placeholder="vcenter" id='modal-abm-servers-vcenter' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cluster">Cluster</label>
                            <input type="text" class="form-control" name="cluster" placeholder="cluster" id='modal-abm-servers-cluster' >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="orquestador">Orquestador</label>
                            <input type="text" class="form-control" name="orquestador" placeholder="orquestador" id='modal-abm-servers-orquestador' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="hostname">HostName</label>
                            <input type="text" class="form-control" name="hostname" placeholder="hostname" id='modal-abm-servers-hostname' >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="eos">End of Support</label>
                            <div class="input-group date" data-provide="dpeos">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" class="form-control pull-right" name="eos" id="modal-abm-servers-eos">
                            </div>                        
                        </div>
                        <div class="form-group col-md-3">
                            <label for="eol">End of Life</label>
                            <div class="input-group date" data-provide="dpeol">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" class="form-control pull-right" name="eol" id="modal-abm-servers-eol">
                            </div>                        
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-servers-submit'>
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