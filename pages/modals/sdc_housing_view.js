$(function() {

    let tbHousing = $('#housing');

    // ==============================================================
    // EVENTOS
    // ==============================================================
    // SELECCION GERENCIA
    $('.modal-abm-housing-view').click(function() {

        //Extraigo el id de la data del botón
        let idcliente = $(this).data('id');
        $('#modal-abm-housing-title').html('Servicio de Housing');
        modalAbmHousingLimpiarCampos();
        let texto = $(this).data('cliente') + ' <small>[ ' + $(this).data('organismo') + ' ]</small> ';
        let sector = ($(this).data('sector') == 'Publico' ? ' <span class="label label-success">Sector Público</span> ' : ' <span class="label label-danger">Sector Privado</span> ');
        let tipo = ($(this).data('tipo') == 'I' ? ' <span class="label label-success">Uso Interno</span> ' : ' <span class="label label-danger">Cliente</span> ');
        $('#modal-abm-housing-title').html(texto + tipo + sector);
        $('#modal-abm-housing-submit').hide();

        // REcreo la tabla
        tbHousing.DataTable({
            "ajax": {
                type: 'POST',
                url: './helpers/getAsyncDataFromDB.php',
                data: { query: 'SELECT H.id, H.id_cliente, H.m2, H.sala, H.fila, H.rack, H.observaciones, H.energia, H.fecha_alta, H.evidencia FROM sdc_housing as H WHERE H.id_cliente = ' + idcliente },
                // data: { query: 'SELECT 1 as id, "Mariano" as name, "papa" as position FROM sdc_hosting WHERE id_cliente = ' + 21 },

            },
            "dataSrc": function(json) {
                console.log(json);
            },
            "columns": [
                { "data": "energia" },
                { "data": "m2" },
                { "data": "sala" },
                { "data": "fila" },
                { "data": "rack" },
                { "data": "fecha_alta" },
                { "data": "evidencia" },
                { "data": "observaciones" }
            ]
        });

        $("#modal-abm-housing").modal("show");

    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmHousingLimpiarCampos() {
        tbHousing.DataTable().clear().destroy();
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