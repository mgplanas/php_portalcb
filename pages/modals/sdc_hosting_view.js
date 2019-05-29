$(function() {

    let tbHosting = $('#hosting');
    let tbHostingDT = tbHosting.DataTable({
        'searching': true,
        'lengthChange': false,
        "scrollX": true,
        "scrollY": 400
    });
    // ==============================================================
    // EVENTOS
    // ==============================================================
    // SELECCION GERENCIA
    $('.modal-abm-hosting-view').click(function() {
        console.log($(this).data('organismo'));
        //Extraigo el id de la data del botón
        let idcliente = $(this).data('id');
        modalAbmHostingLimpiarCampos();
        let texto = ' - ' + $(this).data('cliente') + ' <small>[ ' + $(this).data('organismo') + ' ]</small>';
        $('#modal-abm-hosting-title').html('Servicio de Hosting' + texto);
        $('#modal-abm-hosting-submit').hide();

        // Busco los servicios
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT * FROM sdc_hosting WHERE id_cliente = ' + idcliente },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbHosting);
                $("#modal-abm-hosting").modal("show");
                tbHostingDT.columns.adjust().draw();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
        // Busco datos indicadores storage
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT SUM(storage) as qstorage, SUM(vcpu) as qvcpu, SUM(ram) as qram, count(*) as qservices FROM sdc_hosting where id_cliente = ' + idcliente },
            dataType: 'json',
            success: function(json) {
                let item = json.data[0];
                $('#modal-abm-hosting-qstorage').html(item.qstorage);
                $('#modal-abm-hosting-qram').html(item.qram);
                $('#modal-abm-hosting-qservices').html(item.qservices);
                $('#modal-abm-hosting-qvcpu').html(item.qvcpu);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmHostingLimpiarCampos() {
        tbHostingDT.clear().draw();
        $('#modal-abm-hosting-id').val(0);
        $('#modal-abm-hosting-id-cliente').val(0);
        $('#modal-abm-hosting-qram').val(0);
        $('#modal-abm-hosting-qvcpu').val(0);
        $('#modal-abm-hosting-qservices').val(0);
        $('#modal-abm-hosting-qstorage').val(0);
        $('#modal-abm-hosting-cliente').val('');
        $('#modal-abm-hosting-organismo').val('');
    }


    // populate the data table with JSON data
    function populateDataTable(response, table) {
        var length = Object.keys(response.data).length;
        console.log(response);
        for (var i = 0; i < length; i++) {
            let item = response.data[i];
            // You could also use an ajax property on the data table initialization
            table.dataTable().fnAddData([
                item.tipo,
                item.nombre,
                item.displayName,
                item.proyecto,
                item.fecha,
                item.hipervisor,
                item.hostname,
                item.pool,
                item.uuid,
                item.VCPU,
                item.RAM,
                item.storage,
                item.SO,
                item.datacenter
            ]);
        }
    }
    // ********************************************************************************************
});