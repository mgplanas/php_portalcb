$(function() {

    // ==============================================================
    // EVENTOS
    // ==============================================================
    // SELECCION GERENCIA
    $('.modal-abm-housing-view').click(function() {

        //Extraigo el id de la data del botón
        let idcliente = $(this).data('id');
        $('#modal-abm-housing-title').html('Servicio de Housing');
        modalAbmHousingLimpiarCampos();
        $('#modal-abm-housing-submit').hide();

        // Busco el servicio
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT id, id_cliente, m2, sala, fila, rack, observaciones FROM sdc_housing WHERE id_cliente = ' + idcliente },
            dataType: 'json',
            success: function(json) {
                let item = json.data[0];
                $('#modal-abm-housing-id').val(item.id);
                $('#modal-abm-housing-id-cliente').val(item.id_cliente);
                $('#modal-abm-housing-m2').val(item.m2);
                $('#modal-abm-housing-sala').val(item.sala);
                $('#modal-abm-housing-fila').val(item.fila);
                $('#modal-abm-housing-rack').val(item.rack);
                $('#modal-abm-housing-observaciones').val(item.observaciones);
                $("#modal-abm-housing").modal("show");
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmHousingLimpiarCampos() {
        $('#modal-abm-housing-id').val(0);
        $('#modal-abm-housing-id-cliente').val(0);
        $('#modal-abm-housing-m2').val('');
        $('#modal-abm-housing-sala').val('');
        $('#modal-abm-housing-fila').val('');
        $('#modal-abm-housing-rack').val('');
        $('#modal-abm-housing-observaciones').val('');
    }
    // ********************************************************************************************
});