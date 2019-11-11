<div id="modal-abm-compra-comments" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h3 class="box-title"><i class="fa fa-comments-o"></i>&nbsp;&nbsp;&nbsp;Comentarios</h3>
            </div>
            <div class="box box-primary">
                <div class="modal-body">
                        <div class="chat" id="chat-box"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="input-group"><input id="popover-comment" class="form-control">
                <div class="input-group-btn">
                        <button id="popup-comment-submit" type="button" class="btn btn-success" onclick="addcomment();"><i class="fa fa-plus"></i></button>
                </div>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
            </div>	
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->