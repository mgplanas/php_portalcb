<div class="modal fade" id="modal-abm-doc-aprobar">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-doc-aprobar-title'>Aprobar nueva versión</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_doc" placeholder="id_doc" id='modal-abm-doc-aprobar-id' >
                
                <div class="box-body">
                    <div class="row">

                        <div class="form-group col-md-4">
                                <label for="version-actual">Versión actual</label>
                                <input type="number" class="form-control" name="version-actual" placeholder="version" id='modal-abm-doc-aprobar-version-actual' disabled>
                        </div>
                        <div class="form-group col-md-4">
                                <label for="version">Versión aprobada</label>
                                <input type="number" class="form-control" name="version" placeholder="version" id='modal-abm-doc-aprobar-version'>
                        </div>

                    </div>
                    <div class="form-group">
                                <label for="nombre">Nombre de la minuta</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre" id='modal-abm-doc-aprobar-nombre'>
                    </div>                    
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-link"></i> </span>
                        <input type="text" class="form-control" name="minuta" placeholder="URL a la minuta de aprobación" id='modal-abm-doc-aprobar-minuta'>
                    </div>
                    <hr style="border: 0px solid white;">
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="Addarea" class="btn  btn-raised btn-success" value="Aprobar" id='modal-abm-doc-aprobar-submit'>
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