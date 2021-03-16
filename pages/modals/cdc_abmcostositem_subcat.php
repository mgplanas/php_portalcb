<div class="modal fade" id="modal-abm-subcategoria">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id='modal-abm-subcategoria-title'>Nueva categoría de productos/servicios</h2>
        </div>
        <div class="modal-body">
            <!-- form start -->
            <form method="post" role="form" action="">
                
                <input type="hidden" class="form-control" name="id_subcat" placeholder="id" id='modal-abm-subcategoria-id' >
                <input type="hidden" class="form-control" name="id_cat" placeholder="id" id='modal-abm-subcategoria-id-categoria' >
                <!-- <h3 id="modal-abm-subcategoria-subcategoria"></h3> -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripción </label>
                                <input type="text" class="form-control" name="descripcion"  id='modal-abm-subcategoria-descripcion' required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" id='modal-abm-subcategoria-oculto'> Oculto
                                </label>
                                </div>                            
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-2">
                            <input type="button" name="AddsubCategoria" class="btn  btn-raised btn-success" value="Guardar" id='modal-abm-subcategoria-submit'>
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