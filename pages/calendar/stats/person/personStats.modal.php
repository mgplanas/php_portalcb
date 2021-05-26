<div class="modal fade" id="modal-cal-per-stat">
  
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-cal-per-stat-title'>NOMBRE</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id" placeholder="id" id='modal-cal-per-stat-id' >
                <input type="hidden" class="form-control" name="idPersona" placeholder="id" id='modal-cal-per-stat-id-persona' >
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="small-box bg-green" id='modal-cal-per-stat-hs-per-container'>
                                    <div class="inner">
                                        <h3 id='modal-cal-per-stat-hs-per'>99</h3>
                                        <p>[Hs] período</p>
                                        </div>
                                        <div class="icon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <!-- <a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                    </a> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="small-box bg-yellow" id='modal-cal-per-stat-hs-anual-container'>
                                    <div class="inner">
                                        <h3 id='modal-cal-per-stat-hs-anual'>99</h3>
                                        <p>[Hs] Año actual</p>
                                        </div>
                                        <div class="icon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <!-- <a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                    </a> -->
                                </div>

                            </div>
                            <!-- small box -->
                        </div>
                        <div class="col-md-6">
                            <!-- small box -->
                            <canvas id="gx_acumulado_mensual" height="212" style="display: block; height:400"></canvas>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Salir</button>
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