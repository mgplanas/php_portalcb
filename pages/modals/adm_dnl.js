$(function() {

    // ********************************************************************************************
    // ISO 9K
    // ********************************************************************************************
    function setAMBDNLTriggers() {

        // BAJA
        $('.modal-abm-dnl-btn-baja').click(function() {
            if (confirm('Esta seguro de borrar los datos del ítem ' + $(this).data('descripcion') + '?')) {
                let id = $(this).data('id');
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/abmdnldb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if (!json.ok) {
                            alert(json.err);
                        } else {
                            window.location.href = "feriados.php";
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
        $('#modal-abm-dnl-btn-alta').click(function() {
            $('#modal-abm-dnl-title').html('Nuevo Día No Laborable');
            modalAbmDNLLimpiarCampos();
            $('#modal-abm-dnl-submit').attr('name', 'A');
            $("#modal-abm-dnl").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-dnl-btn-edit').click(function() {
            $('#modal-abm-dnl-title').html('Editar Día no laborable');
            modalAbmDNLLimpiarCampos();

            $('#modal-abm-dnl-id').val($(this).data('id'));
            $('#modal-abm-dnl-fecha').val($(this).data('fecha').split('-').reverse().join("/"));
            $('#modal-abm-dnl-descripcion').val($(this).data('descripcion'));
            $('#modal-abm-dnl-submit').attr('name', 'M');

            $("#modal-abm-dnl").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-dnl-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-dnl-id').val();
        let descripcion = $('#modal-abm-dnl-descripcion').val();
        let fecha = $('#modal-abm-dnl-fecha').val().split('/').reverse().join("-");

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmdnldb.php',
            data: {
                operacion: op,
                id: id,
                descripcion: descripcion,
                fecha: fecha
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-dnl").modal("hide");
                if (!json.ok) {
                    alert(json.err);
                } else {
                    window.location.href = "feriados.php";
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
        daysOfWeekDisabled: [0, 6]
    });

    setAMBDNLTriggers();


});