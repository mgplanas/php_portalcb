$(function() {

    function renderCurrencyField(value) {
        return parseFloat(value).toLocaleString('es-AR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }
    let tbiaas = $('#iaas');

    // ==============================================================
    // EVENTOS
    // ==============================================================
    $('.modal-abm-iaas-view').click(function() {

        //Extraigo el id de la data del botón
        let idcliente = $(this).data('id');

        $('#modal-abm-iaas-title').html('Reserva de IAAS');
        modalAbmiaasLimpiarCampos();
        let texto = $(this).data('cliente') + ' <small>[ ' + $(this).data('organismo') + ' ]</small> ';
        let sector = ($(this).data('sector') == 'Publico' ? ' <span class="label label-success">Sector Público</span> ' : ' <span class="label label-danger">Sector Privado</span> ');
        let tipo = ($(this).data('tipo') == 'I' ? ' <span class="label label-success">Uso Interno</span> ' : ' <span class="label label-danger">Cliente</span> ');
        $('#modal-abm-iaas-title').html(texto + tipo + sector);
        $('#modal-abm-iaas-submit').hide();

        // REcreo la tabla
        tbiaas.DataTable({
            "ajax": {
                type: 'POST',
                url: './helpers/getAsyncDataFromDB.php',
                data: { query: 'SELECT H.id, H.id_cliente, H.plataforma, H.reserva, H.ram_capacidad, H.ram_uso, H.storage_capacidad, H.storage_uso, H.observaciones FROM sdc_iaas as H WHERE H.id_cliente = ' + idcliente },

            },
            "dataSrc": function(json) {
                console.log(json);
            },
            "columns": [
                { "data": "plataforma" },
                { "data": "reserva" },
                { "data": "ram_capacidad", "render": (data) => renderCurrencyField(data) },
                { "data": "storage_capacidad", "render": (data) => renderCurrencyField(data) },
                { "data": "ram_uso", "render": (data) => renderCurrencyField(data) },
                { "data": "storage_uso", "render": (data) => renderCurrencyField(data) },
                { "data": "observaciones" }
            ],
            "columnDefs": [{
                "targets": [2, 3, 4, 5],
                "className": "dt-body-right"
            }]
        });

        $("#modal-abm-iaas").modal("show");

    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmiaasLimpiarCampos() {
        tbiaas.DataTable().clear().destroy();
        $('#modal-abm-iaas-id').val(0);
        $('#modal-abm-iaas-id_cliente').val(0);
        $('#modal-abm-iaas-plataforma').val('');
        $('#modal-abm-iaas-reserva').val('');
        $('#modal-abm-iaas-ram_capacidad').val('');
        $('#modal-abm-iaas-ram_uso').val('');
        $('#modal-abm-iaas-storage_capacidad').val('');
        $('#modal-abm-iaas-storage_uso').val('');
        $('#modal-abm-iaas-observaciones').val('');
    }
    // ********************************************************************************************
});