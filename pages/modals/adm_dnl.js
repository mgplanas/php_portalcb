$(function() {

    // ********************************************************************************************
    // ISO 9K
    // ********************************************************************************************
    function setAMBDNLTriggers() {

        // BAJA
        $('.modal-abm-dnl-btn-baja').on('click', function() {
            if (confirm('Esta seguro de borrar los datos del ítem ' + $(this).data('descripcion') + '?')) {
                let id = $(this).data('id');
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/abm_calendar_eventsdb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if (!json.ok) {
                            alert(json.err);
                        } else {
                            window.location.href = "adm_dnl.php";
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText, error);
                    }
                });

            }
        });

        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-dnl-btn-alta').on('click', function() {
            $('#modal-abm-dnl-title').html('Nuevo Día No Laborable');
            modalAbmDNLLimpiarCampos();
            $('#modal-abm-dnl-submit').attr('name', 'A');
            $("#modal-abm-dnl").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-dnl-btn-edit').on('click', function() {
            $('#modal-abm-dnl-title').html('Editar Día no laborable');
            modalAbmDNLLimpiarCampos();

            $('#modal-abm-dnl-id').val($(this).data('id'));
            $('#modal-abm-dnl-fecha').val($(this).data('fecha').slice(0, 10).split('-').reverse().join("/"));
            $('#modal-abm-dnl-descripcion').val($(this).data('descripcion'));
            $('#modal-abm-dnl-observaciones').val($(this).data('observaciones'));
            $('#modal-abm-dnl-submit').attr('name', 'M');

            $("#modal-abm-dnl").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-dnl-submit').on('click', function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-dnl-id').val();
        let descripcion = $('#modal-abm-dnl-descripcion').val();
        let observaciones = $('#modal-abm-dnl-observaciones').val();
        let fecha_inicio = $('#modal-abm-dnl-fecha').val().split('/').reverse().join("-");

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/adm_calendar_eventsdb.php',
            data: {
                operacion: op,
                id: id,
                color: '#grey',
                descripcion: descripcion,
                estado: 1,
                fecha_fin: fecha_inicio,
                fecha_inicio: fecha_inicio,
                is_all_day: 1,
                is_background: 1,
                is_programmed: 0,
                observaciones: observaciones
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-dnl").modal("hide");
                if (!json.ok) {
                    alert(json.err);
                } else {
                    window.location.href = "adm_dnl.php";
                }
                // refreshDataRow($('#modal-abm-dnl-rowindex').val());
                // setAMBDNLTriggers();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    function modalAbmDNLLimpiarCampos() {
        $('#modal-abm-dnl-id').val(0);
        $('#modal-abm-dnl-descripcion').val('');
        $('#modal-abm-dnl-observaciones').val('');
        $('#modal-abm-dnl-fecha').val('');
    }
    // ********************************************************************************************


    // ==============================================================
    // CONFIG PLUGGINS
    // ==============================================================
    $('#modal-abm-dnl-fecha').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        // daysOfWeekDisabled: [0, 6]
    });

    setAMBDNLTriggers();

    $('#tbDNL').DataTable({
        'language': { 'emptyTable': 'No hay Registros' },
        'ordering': true,
        'paging': true,
        'pageLength': 20,
        'lengthChange': false,
        'searching': true,

        'info': true,
        'autoWidth': false,
        'dom': 'Bfrtp',
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