$(function() {

    // ********************************************************************************************
    // ORGANISMOS
    // ********************************************************************************************
    function setAMBOrganismoTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-servers-btn-alta').click(function() {
            $('#modal-abm-servers-title').html('Nuevo Server');
            modalAbmOrganismoLimpiarCampos();
            $('#modal-abm-servers-submit').attr('name', 'A');
            $("#modal-abm-servers").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-servers-btn-edit').click(function() {
            $('#modal-abm-servers-title').html('Editar Server');
            modalAbmOrganismoLimpiarCampos();

            $('#modal-abm-servers-id').val($(this).data('id'));
            $('#modal-abm-servers-marca').val($(this).data('marca'));
            $('#modal-abm-servers-modelo').val($(this).data('modelo'));
            $('#modal-abm-servers-serie').val($(this).data('serie'));
            $('#modal-abm-servers-memoria').val($(this).data('memoria'));
            $('#modal-abm-servers-sockets').val($(this).data('sockets'));
            $('#modal-abm-servers-nucleos').val($(this).data('nucleos'));
            $('#modal-abm-servers-sala').val($(this).data('sala'));
            $('#modal-abm-servers-fila').val($(this).data('fila'));
            $('#modal-abm-servers-rack').val($(this).data('rack'));
            $('#modal-abm-servers-unidad').val($(this).data('unidad'));
            $('#modal-abm-servers-ip').val($(this).data('ip'));
            $('#modal-abm-servers-vcenter').val($(this).data('vcenter'));
            $('#modal-abm-servers-cluster').val($(this).data('cluster'));
            $('#modal-abm-servers-hostname').val($(this).data('hostname'));
            $('#modal-abm-servers-nombre').val($(this).data('nombre'));
            $('#modal-abm-servers-sigla').val($(this).data('sigla'));
            $('#modal-abm-servers-cuit').val($(this).data('cuit'));

            $('#modal-abm-servers-submit').attr('name', 'M');

            $("#modal-abm-servers").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR ORGANISMO
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-servers-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-servers-id').val();
        let razon_social = $('#modal-abm-servers-nombre').val();
        let nombre_corto = $('#modal-abm-servers-sigla').val();
        let cuit = $('#modal-abm-servers-cuit').val();
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
                $("#modal-abm-servers").modal("hide");
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
        $('#modal-abm-servers-id').val(0);
        $('#modal-abm-servers-nombre').val('');
        $('#modal-abm-servers-sigla').val('');
        $('#modal-abm-servers-cuit').val('');
        $('#opt-sector-publico').prop("checked", true);
    }
    // ********************************************************************************************

    setAMBOrganismoTriggers();

});