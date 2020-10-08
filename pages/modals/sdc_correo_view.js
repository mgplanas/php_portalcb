$(function() {


    let tbcorreo = $('#correo');

    // ==============================================================
    // EVENTOS
    // ==============================================================
    $('.modal-abm-correo-view').click(function() {
        //Extraigo el id de la data del botón
        let idcliente = $(this).data('id');
        modalAbmcorreoLimpiarCampos();
        let texto = $(this).data('cliente') + ' <small>[ ' + $(this).data('organismo') + ' ]</small> ';
        let sector = ($(this).data('sector') == 'Publico' ? ' <span class="label label-success">Sector Público</span> ' : ' <span class="label label-danger">Sector Privado</span> ');
        let tipo = ($(this).data('tipo') == 'I' ? ' <span class="label label-success">Uso Interno</span> ' : ' <span class="label label-danger">Cliente</span> ');
        $('#modal-abm-correo-title').html(texto + tipo + sector);
        $('#modal-abm-correo-submit').hide();

        // REcreo la tabla
        tbcorreo.DataTable({
            "ajax": {
                type: 'POST',
                url: './helpers/getAsyncDataFromDB.php',
                data: { query: 'SELECT * FROM vw_sdc_correo WHERE id_cliente = ' + idcliente },
                // data: { query: 'SELECT id, nombre, displayName, proyecto, datacenter, DATE_FORMAT(fecha, "%Y-%m-%d") as fecha, hipervisor, hostname, pool, uuid, VCPU, RAM, ROUND(storage,3) as storage, SO FROM sdc_correo WHERE id_cliente = ' + idcliente },
                // data: { query: 'SELECT 1 as id, "Mariano" as name, "papa" as position FROM sdc_correo WHERE id_cliente = ' + 21 },

            },
            "dataSrc": function(json) {
                console.log(json);
            },
            "columns": [
                { "data": "nombre" },
                { "data": "displayName" },
                { "data": "proyecto" },
                { "data": "fecha" },
                { "data": "hipervisor" },
                { "data": "hostname" },
                { "data": "pool" },
                { "data": "uuid" },
                { "data": "VCPU" },
                { "data": "RAM" },
                { "data": "storage" },
                { "data": "SO" }
            ]
        });

        $("#modal-abm-correo").modal("show");
        // $.ajax({
        //     type: 'POST',
        //     url: './helpers/getAsyncDataFromDB.php',
        //     data: { query: 'SELECT id, nombre, displayName, proyecto, datacenter, DATE_FORMAT(fecha, "%Y-%m-%d") as fecha, hipervisor, hostname, pool, uuid, VCPU, RAM, ROUND(storage,3) as storage, SO FROM sdc_correo WHERE id_cliente = ' + idcliente },
        //     dataType: 'json',
        //     success: function(json) {
        //         myJsonData = json;
        //         populateDataTable(myJsonData, tbcorreo);
        //         $("#modal-abm-correo").modal("show");
        //         tbcorreoDT.columns.adjust().draw();
        //     },
        //     error: function(xhr, status, error) {
        //         alert(xhr.responseText, error);
        //     }
        // });
        // Busco datos indicadores storage
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT CONVERT(SUM(storage),UNSIGNED) as qstorage, SUM(vcpu) as qvcpu, CONVERT(SUM(ram),UNSIGNED) as qram, count(*) as qservices FROM vw_sdc_correo where id_cliente = ' + idcliente },
            dataType: 'json',
            success: function(json) {
                let item = json.data[0];
                $('#modal-abm-correo-qstorage').html(item.qstorage);
                $('#modal-abm-correo-qram').html(item.qram);
                $('#modal-abm-correo-qservices').html(item.qservices);
                $('#modal-abm-correo-qvcpu').html(item.qvcpu);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function round(num, places) {
        return +(Math.round(num + "e+" + places) + "e-" + places);
    }

    function modalAbmcorreoLimpiarCampos() {
        tbcorreo.DataTable().clear().destroy();
        $('#modal-abm-correo-id').val(0);
        $('#modal-abm-correo-id-cliente').val(0);
        $('#modal-abm-correo-qram').val(0);
        $('#modal-abm-correo-qvcpu').val(0);
        $('#modal-abm-correo-qservices').val(0);
        $('#modal-abm-correo-qstorage').val(0);
        $('#modal-abm-correo-cliente').val('');
        $('#modal-abm-correo-organismo').val('');
    }


    // populate the data table with JSON data
    function populateDataTable(response, table) {
        var length = Object.keys(response.data).length;
        console.log(response);
        for (var i = 0; i < length; i++) {
            let item = response.data[i];
            // You could also use an ajax property on the data table initialization
            table.dataTable().fnAddData([
                //item.tipo,
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
                item.SO
                //item.datacenter
            ]);
        }
    }
    // ********************************************************************************************
});