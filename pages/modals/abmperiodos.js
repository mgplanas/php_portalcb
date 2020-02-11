$(function() {

    // ********************************************************************************************
    // ISO 9K
    // ********************************************************************************************
    function setAMBPerTriggers() {

        // BAJA
        $('.modal-abm-per-btn-baja').click(function() {
            if (confirm('Esta seguro de borrar el periodo #' + $(this).data('id') + '?')) {
                let id = $(this).data('id');
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/abmperiodosdb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if (!json.ok) {
                            alert(json.err);
                        } else {
                            window.location.href = "cmp_periodos.php";
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
        $('#modal-abm-per-btn-alta').click(function() {
            $('#modal-abm-per-title').html('Nuevo Período de Liquidación');
            modalAbmPerLimpiarCampos();
            $('#modal-abm-per-submit').attr('name', 'A');
            $("#modal-abm-per").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-per-btn-edit').click(function() {
            $('#modal-abm-per-title').html('Editar Período');
            modalAbmPerLimpiarCampos();

            $('#modal-abm-per-id').val($(this).data('id'));
            $('#modal-abm-per-fecha-desde').val($(this).data('fechadesde').split('-').reverse().join("/"));
            $('#modal-abm-per-fecha-hasta').val($(this).data('fechahasta').split('-').reverse().join("/"));
            $('#modal-abm-per-submit').attr('name', 'M');

            $("#modal-abm-per").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-per-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-per-id').val();
        let fechadesde = $('#modal-abm-per-fecha-desde').val().split('/').reverse().join("-");
        let fechahasta = $('#modal-abm-per-fecha-hasta').val().split('/').reverse().join("-");

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmperiodosdb.php',
            data: {
                operacion: op,
                id: id,
                fechahasta: fechahasta,
                fechadesde: fechadesde
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-per").modal("hide");
                if (!json.ok) {
                    alert(json.err);
                } else {
                    window.location.href = "cmp_periodos.php";
                }
                // refreshDataRow($('#modal-abm-per-rowindex').val());
                // setAMBPerTriggers();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    function modalAbmPerLimpiarCampos() {
        $('#modal-abm-per-id').val(0);
        $('#modal-abm-per-fecha-desde').val('');
        $('#modal-abm-per-fecha-hasta').val('');
    }
    // ********************************************************************************************


    // ==============================================================
    // CONFIG PLUGGINS
    // ==============================================================
    $('#modal-abm-per-fecha-desde').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        //daysOfWeekDisabled: [0, 6]
    });
    $('#modal-abm-per-fecha-hasta').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        //daysOfWeekDisabled: [0, 6]
    });

    setAMBPerTriggers();


});