$(function() {

    // ********************************************************************************************
    // IAAS
    // ********************************************************************************************

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

        //integer value validation
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