$(function() {

    // ********************************************************************************************
    // ORGANISMOS
    // ********************************************************************************************
    function setAMBTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-proceso-btn-alta').click(function() {
            $('#modal-abm-proceso-title').html('Nuevo Proceso');
            modalAbmLimpiarCampos();
            $('#modal-abm-proceso-submit').attr('name', 'A');
            $("#modal-abm-proceso").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-proceso-btn-edit').click(function() {
            $('#modal-abm-proceso-title').html('Editar Proceso');
            modalAbmLimpiarCampos();

            $('#modal-abm-proceso-id').val($(this).data('id'));
            $('#modal-abm-proceso-nombre').val($(this).data('nombre'));

            $('#modal-abm-proceso-submit').attr('name', 'M');
            $("#modal-abm-proceso").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR PROCESO
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-proceso-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-proceso-id').val();
        let nombre = $('#modal-abm-proceso-nombre').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmprocesosdb.php',
            data: {
                operacion: op,
                id: id,
                nombre: nombre,
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-proceso").modal("hide");
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
    function modalAbmLimpiarCampos() {
        $('#modal-abm-proceso-id').val(0);
        $('#modal-abm-proceso-nombre').val('');
    }
    // ********************************************************************************************

    setAMBTriggers();

});