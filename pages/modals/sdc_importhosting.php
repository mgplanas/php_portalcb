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
                
                    <div class="form-group">
                        <input id="modal-import-hosting-file" type="file" accept=".csv" name="image" />
                        <button type="button" class="btn btn-primary" data-loading-text='<i class="fa fa-spinner fa-spin"></i> Processing Order' id="modal-import-hosting-submit">Upload</button>

                        <p class="help-block">El archivo debe ser ingresado en formato CSV.</p>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <!-- <input type="button" name="AddCliente" class="btn  btn-raised btn-success" value="Guardar" id='modal-import-hosting-submit'> -->
                        </div>
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