$(function() {


    let tbHosting = $('#hosting');
    let tbHostingDT = tbHosting.DataTable({
        'paging': false,
        'searching': true,
        'lengthChange': false,

        "scrollY": 300
    });
    // ==============================================================
    // EVENTOS
    // ==============================================================
    // SELECCION GERENCIA
    $('.modal-abm-hosting-view').click(function() {
        //Extraigo el id de la data del botón
        let idcliente = $(this).data('id');
        modalAbmHostingLimpiarCampos();
        let texto = $(this).data('cliente') + ' <small>[ ' + $(this).data('organismo') + ' ]</small> ';
        let sector = ($(this).data('sector') == 'Publico' ? ' <span class="label label-success">Sector Público</span> ' : ' <span class="label label-danger">Sector Privado</span> ');
        let tipo = ($(this).data('tipo') == 'I' ? ' <span class="label label-success">Uso Interno</span> ' : ' <span class="label label-danger">Cliente</span> ');
        $('#modal-abm-hosting-title').html(texto + tipo + sector);
        $('#modal-abm-hosting-submit').hide();

        // Busco los servicios
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT id, nombre, displayName, proyecto, datacenter, DATE_FORMAT(fecha, "%Y-%m-%d") as fecha, hipervisor, hostname, pool, uuid, VCPU, RAM, ROUND(storage,3) as storage, SO FROM sdc_hosting WHERE id_cliente = ' + idcliente },
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
            data: { query: 'SELECT CONVERT(SUM(storage),UNSIGNED) as qstorage, SUM(vcpu) as qvcpu, CONVERT(SUM(ram),UNSIGNED) as qram, count(*) as qservices FROM sdc_hosting where id_cliente = ' + idcliente },
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
    function round(num, places) {
        return +(Math.round(num + "e+" + places) + "e-" + places);
    }

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