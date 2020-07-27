$(function() {

    // ********************************************************************************************
    // GERENCIAS
    // ********************************************************************************************
    function setAMBClienteTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-cliente-btn-alta').click(function() {
            $('#modal-abm-cliente-title').html('Nuevo Cliente DC');
            modalAbmClienteLimpiarCampos();
            $('#modal-abm-cliente-submit').attr('name', 'A');
            $("#modal-abm-cliente").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-cliente-btn-edit').click(function() {
            $('#modal-abm-cliente-title').html('Editar Cliente DC');
            modalAbmClienteLimpiarCampos();

            $('#modal-abm-cliente-id').val($(this).data('id'));
            $('#modal-abm-cliente-nombre').val($(this).data('nombre'));
            $('#modal-abm-cliente-sigla').val($(this).data('sigla'));
            $('#modal-abm-cliente-cuit').val($(this).data('cuit'));
            if ($(this).data('sector') == 'Privado') {
                $('#opt-sector-privado').prop("checked", true);
            }
            if ($(this).data('convenio') == '1') {
                $('#modal-abm-cliente-convenio').prop("checked", true);
            }
            $("#modal-abm-cliente-organismo").val($(this).data('organismo')).change();


            $('#modal-abm-cliente-submit').attr('name', 'M');

            $("#modal-abm-cliente").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-cliente-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-cliente-id').val();
        let id_organismo = $('#modal-abm-cliente-organismo').val();
        let razon_social = $('#modal-abm-cliente-nombre').val();
        let nombre_corto = $('#modal-abm-cliente-sigla').val();
        let cuit = $('#modal-abm-cliente-cuit').val();
        let sector = $("input[name='optSector']:checked").val();
        let convenio = 0;
        if ($("#modal-abm-cliente-convenio").is(':checked')) {
            convenio = 1;
        }
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmclientedb.php',
            data: {
                operacion: op,
                id: id,
                id_organismo: id_organismo,
                razon_social: razon_social,
                nombre_corto: nombre_corto,
                cuit: cuit,
                sector: sector,
                convenio: convenio
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-cliente").modal("hide");
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
    function modalAbmClienteLimpiarCampos() {
        $('#modal-abm-cliente-id').val(0);
        $('#modal-abm-cliente-nombre').val('');
        $('#modal-abm-cliente-sigla').val('');
        $('#modal-abm-cliente-cuit').val('');
        $('#opt-sector-publico').prop("checked", true);
        $('#modal-abm-cliente-convenio').prop("checked", false);
        $("#modal-abm-cliente-organismo").val('first').change();
    }
    // ********************************************************************************************

    setAMBClienteTriggers();

});