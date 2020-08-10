$(function() {

    // ********************************************************************************************
    // GERENCIAS
    // ********************************************************************************************
    function setAMBTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-contrato-btn-alta').click(function() {
            $('#modal-abm-contrato-title').html('Nuevo Seguimiento de contrato');
            modalAbmLimpiarCampos();
            $('#modal-abm-contrato-submit').attr('name', 'A');
            $("#modal-abm-contrato").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-contrato-btn-edit').click(function() {
            $('#modal-abm-contrato-title').html('Editar Seguimiento de contrato');
            modalAbmLimpiarCampos();

            $('#modal-abm-contrato-id').val($(this).data('id'));
            $('#modal-abm-contrato-proveedor').val($(this).data('proveedor'));
            $('#modal-abm-contrato-subgerencia').val($(this).data('subgerencia'));
            $('#modal-abm-contrato-vencimiento').val($(this).data('vencimiento'));
            $('#modal-abm-contrato-oc').val($(this).data('oc'));

            $('#modal-abm-contrato-submit').attr('name', 'M');

            $("#modal-abm-contrato").modal("show");
        });

        // Busqueda OC
        $('#modal-abm-contrato-oc-search-btn').click(function() {
            // Recupero datos del oc
            let oc = $('#modal-abm-contrato-oc').val();
            if (!oc) return;

            let strquery = "SELECT * FROM adm_compras ";
            strquery += "WHERE nro_oc = '" + oc + "' AND borrado = 0";
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: strquery },
                function(response) {
                    if (!response.data || !response.data[0]) {
                        alert('No existe OC');
                        modalAbmLimpiarCamposAdjuntos();
                        return;
                    }
                    actualizarCamposConOC(response.data[0]);
                    return;
                }
            ).fail(function(jqXHR, errorText) {
                console.log(errorText);
                return alert(errorText);
            });

        });

    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-contrato-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-contrato-id').val();
        let id_proveedor = $('#modal-abm-contrato-proveedor').val();
        let id_subgerencia = $('#modal-abm-contrato-subgerencia').val();
        let oc = $('#modal-abm-contrato-oc').val();
        let tipo_mantenimiento = $('#modal-abm-contrato-tipo').val();
        let vencimiento = $('#modal-abm-contrato-vencimiento').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmclientedb.php',
            data: {
                operacion: op,
                id: id,
                id_proveedor: id_proveedor,
                id_subgerencia: id_subgerencia,
                oc: oc,
                tipo_mantenimiento: tipo_mantenimiento,
                vencimiento: vencimiento
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-contrato").modal("hide");
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
        $('#modal-abm-contrato-oc').val('');
        modalAbmLimpiarCamposAdjuntos();
    }

    function modalAbmLimpiarCamposAdjuntos() {
        $('#modal-abm-contrato-id').val(0);
        $('#modal-abm-contrato-vencimiento').val('');
        $('#modal-abm-contrato-tipo').val('');
        $("#modal-abm-contrato-proveedor").val('first').change();
        $("#modal-abm-contrato-subgerencia").val('first').change();
    }

    // Actualiza el modal con los campos de la OC
    function actualizarCamposConOC(compra) {
        $('#modal-abm-contrato-vencimiento').val(compra.fecha_fin_contrato.split('-').reverse().join('/'));
        $('#modal-abm-contrato-tipo').val(compra.concepto);
        $("#modal-abm-contrato-proveedor").val(compra.id_proveedor).change();
        $("#modal-abm-contrato-subgerencia").val(compra.id_subgerencia).change();
    }
    // ********************************************************************************************

    setAMBTriggers();

});