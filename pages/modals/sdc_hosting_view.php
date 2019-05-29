<div class="modal fade" id="modal-abm-hosting">
    <div class="modal-dialog" style="width:1500px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-hosting-title'>Servicio de Hosting</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_cliente" placeholder="id_cliente" id='modal-abm-hosting-id-cliente' >
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-abm-hosting-id' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3 id='modal-abm-hosting-qservices'>0</h3>
                                    <p>Total Servicios</p>
                                </div>
                                <div class="icon"><i class="fa fa-gears"></i></div>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3 id='modal-abm-hosting-qvcpu'>0</h3>
                                <p>Total VCPU</p>
                            </div>
                            <div class="icon"><i class="fa fa-cubes"></i></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3 id='modal-abm-hosting-qram'>0</h3>
                                    <p>Total RAM</p>
                                </div>
                                <div class="icon"><i class="fa fa-server"></i></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3 id='modal-abm-hosting-qstorage'>0</h3>
                                    <p>Total Storage</p>
                                </div>
                                <div class="icon"><i class="fa fa-database"></i></div>
                            </div>
                        </div>                        
                    </div>
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
                                    <table id="hosting" class="display" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Nombre</th>
                                                <th>Display Name</th>
                                                <th>Proyecto</th>
                                                <th>Fecha</th>
                                                <th>Hipervisor</th>
                                                <th>Hostname</th>
                                                <th>Pool</th>
                                                <th>UUID</th>
                                                <th>VCPU</th>
                                                <th>RAM</th>
                                                <th>Storage</th>
                                                <th>Sistema Operativo</th>
                                                <th>Datacenter</th>
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