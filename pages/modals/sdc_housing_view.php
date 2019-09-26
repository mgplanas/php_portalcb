<div class="modal fade" id="modal-abm-housing">
    <div class="modal-dialog" style="width:900px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-housing-title'>Servicio de Housing</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_cliente" placeholder="id_cliente" id='modal-abm-housing-id-cliente' >
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-housing-id' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <div class="col-sm-6" style="text-align:left">
                                        <h2 class="box-title">Listados de servicios</h2>
                                    </div>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="housing" class="display" width="100%">
                                        <thead>
                                            <tr>
                                                <!-- <th>Tipo</th> -->
                                                <th>Energía</th>
                                                <th>M2</th>
                                                <th>Sala</th>
                                                <th>Fila</th>
                                                <th>Rack</th>
                                                <th>Alta</th>
                                                <th>Evidencia</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>                
                </div>
            </form>
        </div>
    </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>