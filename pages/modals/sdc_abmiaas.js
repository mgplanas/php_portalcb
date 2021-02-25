$(function() {

    // ********************************************************************************************
    // IAAS
    // ********************************************************************************************
    let tbvms = $('#vms');

    function setAMBTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-iaas-btn-alta').click(function() {
            $('#modal-abm-iaas-title').html('Nueva Reserva de VRA');
            modalAbmLimpiarCampos();
            $('#modal-abm-iaas-submit').attr('name', 'A');
            $("#modal-abm-iaas").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-iaas-btn-edit').click(function() {
            $('#modal-abm-iaas-title').html('Editar Reserva de VRA');
            modalAbmLimpiarCampos();

            $('#modal-abm-iaas-id').val($(this).data('id'));
            $('#modal-abm-iaas-id_cliente').val($(this).data('id_cliente'));
            $('#modal-abm-iaas-plataforma').val($(this).data('plataforma'));
            $('#modal-abm-iaas-reserva').val($(this).data('reserva'));
            $('#modal-abm-iaas-ram_capacidad').val($(this).data('ram_capacidad'));
            $('#modal-abm-iaas-ram_uso').val($(this).data('ram_uso'));
            $('#modal-abm-iaas-storage_capacidad').val($(this).data('storage_capacidad'));
            $('#modal-abm-iaas-storage_uso').val($(this).data('storage_uso'));
            $('#modal-abm-iaas-observaciones').val($(this).data('observaciones'));


            $('#modal-abm-iaas-submit').attr('name', 'M');

            $("#modal-abm-iaas").modal("show");
        });

        //View
        $('.modal-abm-vms-view').on('click', function() {
            //Extraigo el id de la data del botón
            let idcliente = $(this).data('id');
            let reserva = $(this).data('reserva');
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
                    data: { query: 'SELECT * FROM vw_sdc_iaas WHERE id_cliente = ' + idcliente + ' AND reserva = "' + reserva + '"' },
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
                ],
                'dom': 'Bfrtip',
                'buttons': [{
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A4',

                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                    }
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

    }


    // ==============================================================
    // GUARDAR IAAS
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-iaas-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-iaas-id').val();
        let id_cliente = $('#modal-abm-iaas-id_cliente').val();
        let plataforma = $('#modal-abm-iaas-plataforma').val();
        let reserva = $('#modal-abm-iaas-reserva').val();
        let ram_capacidad = $('#modal-abm-iaas-ram_capacidad').val();
        let ram_uso = $('#modal-abm-iaas-ram_uso').val();
        let storage_capacidad = $('#modal-abm-iaas-storage_capacidad').val();
        let storage_uso = $('#modal-abm-iaas-storage_uso').val();
        let observaciones = $('#modal-abm-iaas-observaciones').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/sdc_abmiaasdb.php',
            data: {
                operacion: op,
                id: id,
                id_cliente: id_cliente,
                plataforma: plataforma,
                reserva: reserva,
                ram_capacidad: ram_capacidad,
                ram_uso: ram_uso,
                storage_capacidad: storage_capacidad,
                storage_uso: storage_uso,
                observaciones: observaciones
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-iaas").modal("hide");
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
        $('#modal-abm-iaas-id').val(0);
        $("#modal-abm-iaas-id_cliente").prop("selectedIndex", 0);
        $('#modal-abm-iaas-plataforma').val('');
        $('#modal-abm-iaas-reserva').val('');
        $('#modal-abm-iaas-ram_capacidad').val(0);
        $('#modal-abm-iaas-ram_uso').val(0);
        $('#modal-abm-iaas-storage_capacidad').val(0);
        $('#modal-abm-iaas-storage_uso').val(0);
        $('#modal-abm-iaas-observaciones').val('');
    }

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

    setAMBTriggers();

    $(".floatNumber").blur(function() {
        this.value = parseFloat(this.value).toFixed(0);
    });

    $('#iaas').DataTable({
        'language': { 'emptyTable': 'No hay datos' },
        'paging': true,
        'pageLength': 20,
        'lengthChange': false,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'dom': 'Bfrtip',
        'buttons': [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',

            },
            {
                extend: 'excel',
                text: 'Excel',
            }
        ]

    });
});