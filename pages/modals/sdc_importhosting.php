<div class="modal fade" id="modal-import-hosting">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-import-hosting-title'>Importación masiva de servicios de Hosting</h2>
        </div>
        <div class="modal-body">
            
            <div class="box-body">
            <!-- form start -->
                <form id="modal-import-hosting-form" action="./helpers/sdc_importhosting.php" method="post" enctype="multipart/form-data">
                    <div class="panel panel-default">
                        <div class="panel-heading">Importar Archivo</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <input id="modal-import-hosting-file" type="file" accept=".csv" name="image" />
                                <p class="help-block">El archivo debe ser ingresado en formato CSV.</p>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary pull-right" data-loading-text='<i class="fa fa-spinner fa-spin"></i> Processing Order' id="modal-import-hosting-submit">Upload</button>
                            </div>
                        </div>
                    </div>
                    <div id="modal-import-hosting-status" class="panel panel-danger">
                        <div id="modal-import-hosting-status-hd" class="panel-heading">Errores</div>
                        <div id="modal-import-hosting-status-body" class="panel-body">
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>             
            <div id="err"></div>
        </div>
    </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>