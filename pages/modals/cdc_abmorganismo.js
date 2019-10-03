$(function() {

    // ********************************************************************************************
    // ORGANISMOS
    // ********************************************************************************************
    function setAMBOrganismoTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-organismo-btn-alta').click(function() {
            $('#modal-abm-organismo-title').html('Nuevo Organismo');
            modalAbmOrganismoLimpiarCampos();
            $('#modal-abm-organismo-submit').attr('name', 'A');
            $("#modal-abm-organismo").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-organismo-btn-edit').click(function() {
            $('#modal-abm-organismo-title').html('Editar Organismo');
            modalAbmOrganismoLimpiarCampos();

            $('#modal-abm-organismo-id').val($(this).data('id'));
            $('#modal-abm-organismo-nombre').val($(this).data('nombre'));
            $('#modal-abm-organismo-sigla').val($(this).data('sigla'));
            $('#modal-abm-organismo-cuit').val($(this).data('cuit'));
            if ($(this).data('sector') == 'Privado') {
                $('#opt-sector-privado').prop("checked", true);
            }


            $('#modal-abm-organismo-submit').attr('name', 'M');

            $("#modal-abm-organismo").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR ORGANISMO
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-organismo-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-organismo-id').val();
        let razon_social = $('#modal-abm-organismo-nombre').val();
        let nombre_corto = $('#modal-abm-organismo-sigla').val();
        let cuit = $('#modal-abm-organismo-cuit').val();
        let sector = $("input[name='optSector']:checked").val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmorganismodb.php',
            data: {
                operacion: op,
                id: id,
                razon_social: razon_social,
                nombre_corto: nombre_corto,
                cuit: cuit,
                sector: sector
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-organismo").modal("hide");
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
    function modalAbmOrganismoLimpiarCampos() {
        $('#modal-abm-organismo-id').val(0);
        $('#modal-abm-organismo-nombre').val('');
        $('#modal-abm-organismo-sigla').val('');
        $('#modal-abm-organismo-cuit').val('');
        $('#opt-sector-publico').prop("checked", true);
    }
    // ********************************************************************************************

    setAMBOrganismoTriggers();

});