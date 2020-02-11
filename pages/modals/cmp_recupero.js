$(function() {

    // ********************************************************************************************
    // ISO 9K
    // ********************************************************************************************
    function setAMBRecuperoTriggers() {

        // BAJA
        $('.modal-cmp-recupero-btn-baja').click(function() {
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
        $('#modal-cmp-recupero-btn-alta').click(function() {
            $('#modal-cmp-recupero-title').html('Ingreso de recupero de horas');
            modalAbmRecuperoLimpiarCampos();
            $('#modal-cmp-recupero-submit').attr('name', 'A');
            $("#modal-cmp-recupero").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-cmp-recupero-btn-edit').click(function() {
            $('#modal-cmp-recupero-title').html('Editar Registro de recupero');
            modalAbmRecuperoLimpiarCampos();

            $('#modal-cmp-recupero-id').val($(this).data('id'));
            $('#modal-cmp-recupero-fecha').val($(this).data('fecha').split('-').reverse().join("/"));
            $('#modal-cmp-recupero-descripcion').val($(this).data('descripcion'));
            $('#modal-cmp-recupero-submit').attr('name', 'M');

            $("#modal-cmp-recupero").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-cmp-recupero-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-cmp-recupero-id').val();
        let descripcion = $('#modal-cmp-recupero-descripcion').val();
        let fecha = $('#modal-cmp-recupero-fecha').val().split('/').reverse().join("-");
        let persona = $('#modal-cmp-recupero-persona').val();

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmrecuperodb.php',
            data: {
                operacion: op,
                id: id,
                descripcion: descripcion,
                fecha: fecha,
                persona: persona
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-cmp-recupero").modal("hide");
                if (!json.ok) {
                    alert(json.err);
                } else {
                    window.location.href = "compensatorios.php";
                }
                // refreshDataRow($('#modal-cmp-recupero-rowindex').val());
                // setAMBRecuperoTriggers();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    function modalAbmRecuperoLimpiarCampos() {
        $('#modal-cmp-recupero-id').val(0);
        $('#modal-cmp-recupero-descripcion').val('');
        $('#modal-cmp-recupero-fecha').val('');
        $('#modal-cmp-recupero-persona').val('');
    }
    // ********************************************************************************************


    // ==============================================================
    // CONFIG PLUGGINS
    // ==============================================================
    $('#modal-cmp-recupero-fecha').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });

    setAMBRecuperoTriggers();


});