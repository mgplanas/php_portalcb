$(function() {

    // ********************************************************************************************
    // ENTES
    // ********************************************************************************************
    function setAMBEnteTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-ente-btn-alta').click(function() {
            $('#modal-abm-ente-title').html('Nuevo Ente Auditor');
            modalAbmEnteLimpiarCampos();
            $('#modal-abm-ente-submit').attr('name', 'A');
            $("#modal-abm-ente").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-ente-btn-edit').click(function() {
            $('#modal-abm-ente-title').html('Editar Ente Auditor');
            modalAbmEnteLimpiarCampos();

            $('#modal-abm-ente-id').val($(this).data('id'));
            $('#modal-abm-ente-nombre').val($(this).data('nombre'));
            $('#modal-abm-ente-observaciones').val($(this).data('observaciones'));
            $('#modal-abm-ente-cuit').val($(this).data('cuit'));
            $('#modal-abm-ente-submit').attr('name', 'M');

            $("#modal-abm-ente").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR ENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-ente-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-ente-id').val();
        let razon_social = $('#modal-abm-ente-nombre').val();
        let observaciones = $('#modal-abm-ente-observaciones').val();
        let cuit = $('#modal-abm-ente-cuit').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/aud_abmentedb.php',
            data: {
                operacion: op,
                id: id,
                razon_social: razon_social,
                observaciones: observaciones,
                cuit: cuit
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-ente").modal("hide");
                location.reload();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmEnteLimpiarCampos() {
        $('#modal-abm-ente-id').val(0);
        $('#modal-abm-ente-nombre').val('');
        $('#modal-abm-ente-observaciones').val('');
        $('#modal-abm-ente-cuit').val('');
    }
    // ********************************************************************************************

    setAMBEnteTriggers();

});