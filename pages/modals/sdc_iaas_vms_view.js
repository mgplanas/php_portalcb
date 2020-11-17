$(function() {


    let tbvms = $('#vms');

    // ==============================================================
    // EVENTOS
    // ==============================================================
    $('.modal-abm-vms-view').on('click', function() {
        //Extraigo el id de la data del botón
        let idcliente = $(this).data('id');
        modalAbmvmsLimpiarCampos();
        let texto = $(this).data('cliente') + ' <small>[ ' + $(this).data('organismo') + ' ]</small> ';
        let sector = ($(this).data('sector') == 'Publico' ? ' <span class="label label-success">Sector Público</span> ' : ' <span class="label label-danger">Sector Privado</span> ');
        let tipo = ($(this).data('tipo') == 'I' ? ' <span class="label label-success">Uso Interno</span> ' : ' <span class="label label-danger">Cliente</span> ');
        $('#modal-abm-vms-title').html(texto + tipo + sector);
        $('#modal-abm-vms-submit').hide();

        // REcreo la tabla
        tbvms.DataTable({
            "ajax": {
                type: 'POST',
                url: './helpers/getAsyncDataFromDB.php',
                data: { query: 'SELECT * FROM vw_sdc_iaas WHERE id_cliente = ' + idcliente },
                // data: { query: 'SELECT id, nombre, displayName, proyecto, datacenter, DATE_FORMAT(fecha, "%Y-%m-%d") as fecha, hipervisor, hostname, pool, uuid, VCPU, RAM, ROUND(storage,3) as storage, SO FROM sdc_vms WHERE id_cliente = ' + idcliente },
                // data: { query: 'SELECT 1 as id, "Mariano" as name, "papa" as position FROM sdc_vms WHERE id_cliente = ' + 21 },

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

        $("#modal-abm-vms").modal("show");
        // $.ajax({
        //     type: 'POST',
        //     url: './helpers/getAsyncDataFromDB.php',
        //     data: { query: 'SELECT id, nombre, displayName, proyecto, datacenter, DATE_FORMAT(fecha, "%Y-%m-%d") as fecha, hipervisor, hostname, pool, uuid, VCPU, RAM, ROUND(storage,3) as storage, SO FROM sdc_vms WHERE id_cliente = ' + idcliente },
        //     dataType: 'json',
        //     success: function(json) {
        //         myJsonData = json;
        //         populateDataTable(myJsonData, tbvms);
        //         $("#modal-abm-vms").modal("show");
        //         tbvmsDT.columns.adjust().draw();
        //     },
        //     error: function(xhr, status, error) {
        //         alert(xhr.responseText, error);
        //     }
        // });
        // Busco datos indicadores storage
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT CONVERT(SUM(storage),UNSIGNED) as qstorage, SUM(vcpu) as qvcpu, CONVERT(SUM(ram),UNSIGNED) as qram, count(*) as qservices FROM vw_sdc_iaas where id_cliente = ' + idcliente },
            dataType: 'json',
            success: function(json) {
                let item = json.data[0];
                $('#modal-abm-vms-qstorage').html(item.qstorage);
                $('#modal-abm-vms-qram').html(item.qram);
                $('#modal-abm-vms-qvcpu').html(item.qvcpu);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });


    function modalAbmvmsLimpiarCampos() {
        tbvms.DataTable().clear().destroy();
        $('#modal-abm-vms-id').val(0);
        $('#modal-abm-vms-id-cliente').val(0);
        $('#modal-abm-vms-qram').val(0);
        $('#modal-abm-vms-qvcpu').val(0);
        $('#modal-abm-vms-qstorage').val(0);
        $('#modal-abm-vms-cliente').val('');
        $('#modal-abm-vms-organismo').val('');
    }

    // ********************************************************************************************
});