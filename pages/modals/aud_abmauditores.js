$(function() {

    // ********************************************************************************************
    // Auditores
    // ********************************************************************************************
    function setAMBTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-auditor-btn-alta').click(function() {
            $('#modal-abm-auditor-title').html('Nuevo Auditor');
            modalAbmLimpiarCampos();
            $('#modal-abm-auditor-id-ente').val($(this).data('idente'));
            $('#modal-abm-auditor-submit').attr('name', 'A');
            $("#modal-abm-auditor").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-auditor-btn-edit').click(function() {
            $('#modal-abm-auditor-title').html('Editar Auditor');
            modalAbmLimpiarCampos();

            $('#modal-abm-auditor-id').val($(this).data('id'));
            $('#modal-abm-auditor-id-ente').val($(this).data('idente'));
            $('#modal-abm-auditor-nombre').val($(this).data('nombre'));
            $('#modal-abm-auditor-apellido').val($(this).data('apellido'));
            $('#modal-abm-auditor-dni').val($(this).data('dni'));
            $('#modal-abm-auditor-submit').attr('name', 'M');

            $("#modal-abm-auditor").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR Auditor
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-auditor-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-auditor-id').val();
        let idente = $('#modal-abm-auditor-id-ente').val();
        let nombre = $('#modal-abm-auditor-nombre').val();
        let apellido = $('#modal-abm-auditor-apellido').val();
        let dni = $('#modal-abm-auditor-dni').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/aud_abmauditoresdb.php',
            data: {
                operacion: op,
                id: id,
                id_ente: idente,
                nombre: nombre,
                apellido: apellido,
                dni: dni
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-auditor").modal("hide");
                location.reload();
                // window.location.href = window.location.href.replace(/[\?#].*|$/, "?id_ente=" + idente);
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
        $('#modal-abm-auditor-id').val(0);
        $('#modal-abm-auditor-id-ente').val(0);
        $('#modal-abm-auditor-nombre').val('');
        $('#modal-abm-auditor-apellido').val('');
        $('#modal-abm-auditor-dni').val('');
    }
    // ********************************************************************************************

    setAMBTriggers();

});