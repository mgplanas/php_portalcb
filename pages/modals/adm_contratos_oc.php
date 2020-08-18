<style>
.td-oc-title {
    font-size: 16px;
    width: 20%;
}
.td-oc-value {
    font-size: 16px;
    font-weigth: bold;
}
.td-oc-icon {
    font-size: 20px;
    width: 10%;
}
</style>
<div class="modal fade" id="modal-abm-contrato-oc-show">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title text-center" id='modal-abm-contrato-oc-show-title'>OC</h2>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <table id="ocs" class="table table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="td-oc-icon text-center"><i class="fa fa-calendar-plus-o"></i></td>
                                <td class="td-oc-title"><strong>Fecha OC:</strong></td>
                                <td id="modal-abm-contrato-oc-show-fecha" class="td-oc-value"></td>
                            </tr>
                            <tr>
                                <td class="td-oc-icon text-center"><i class="fa fa-dollar"></i></td>
                                <td class="td-oc-title"><strong>Monto:</strong></td>
                                <td id="modal-abm-contrato-oc-show-monto" class="td-oc-value"></td>
                            </tr>
                            <tr>
                                <td class="td-oc-icon text-center"><i class="fa fa-calendar"></i></td>
                                <td class="td-oc-title"><strong>Vencimiento Cto.</strong></td>
                                <td id="modal-abm-contrato-oc-show-vto" class="td-oc-value"></td>
                            </tr>
                            <tr>
                                <td class="td-oc-icon text-center"><i class="fa fa-truck"></i></td>
                                <td class="td-oc-title"><strong>Proveedor:</strong></td>
                                <td id="modal-abm-contrato-oc-show-proveedor" class="td-oc-value"></td>
                            </tr>
                            <tr>
                                <td class="td-oc-icon text-center"><i class="fa fa-gears"></i></td>
                                <td class="td-oc-title"><strong>Proceso:</strong></td>
                                <td id="modal-abm-contrato-oc-show-proceso" class="td-oc-value"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row">
                <div class="col-sm-10"></div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                </div>
            </div>
        </div>
    </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>