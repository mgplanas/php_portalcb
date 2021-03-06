$(function() {

    // ********************************************************************************************
    // ENTES
    // ********************************************************************************************
    let ddlEntes = $('#modal-abm-instancia-ente');
    // refresh DDL
    function refreshEntes(selectedValue, disabled = false) {
        // Limpio combos
        ddlEntes.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM aud_entes WHERE borrado = 0 ORDER BY razon_social;' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlEntes.append($("<option />").val(this.id).text(this.razon_social).attr('selected', 'selected'));
                    } else {
                        ddlEntes.append($("<option />").val(this.id).text(this.razon_social));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlEntes.val('first').change();
                }
                ddlEntes.prop('disabled', disabled);
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }

    function setAMBEnteTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-instancia-btn-alta').click(function() {
            $('#modal-abm-instancia-title').html('Nueva Instancia de Auditoría');
            modalAbmEnteLimpiarCampos();
            refreshEntes();
            $('#modal-abm-instancia-submit').attr('name', 'A');
            $("#modal-abm-instancia").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-instancia-btn-edit').click(function() {
            $('#modal-abm-instancia-title').html('Editar Instancia de Auditoría');
            modalAbmEnteLimpiarCampos();

            $('#modal-abm-instancia-id').val($(this).data('id'));
            $('#modal-abm-instancia-nombre').val($(this).data('nombre'));
            refreshEntes($(this).data('ente'), ($(this).data('auditores') > 0));

            $('#modal-abm-instancia-observaciones').val($(this).data('observaciones'));
            $('#modal-abm-instancia-descripcion').val($(this).data('descripcion'));
            $('#modal-abm-instancia-inicio').val($(this).data('inicio'));
            $('#modal-abm-instancia-fin').val($(this).data('fin'));
            $('#modal-abm-instancia-submit').attr('name', 'M');

            $("#modal-abm-instancia").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR ENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-instancia-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-instancia-id').val();
        let nombre = $('#modal-abm-instancia-nombre').val();
        let observaciones = $('#modal-abm-instancia-observaciones').val();
        let descripcion = $('#modal-abm-instancia-descripcion').val();
        let inicio = $('#modal-abm-instancia-inicio').val();
        let fin = $('#modal-abm-instancia-fin').val();
        let ente = ddlEntes.val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/aud_abminstanciasdb.php',
            data: {
                operacion: op,
                id: id,
                nombre: nombre,
                observaciones: observaciones,
                descripcion: descripcion,
                fecha_inicio: inicio,
                fecha_fin: fin,
                id_ente: ente
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-instancia").modal("hide");
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
    // ==============================================================
    function modalAbmEnteLimpiarCampos() {
        $('#modal-abm-instancia-id').val(0);
        $('#modal-abm-instancia-nombre').val('');
        $('#modal-abm-instancia-observaciones').val('');
        $('#modal-abm-instancia-descripcion').val('');
        $('#modal-abm-instancia-inicio').val('');
        $('#modal-abm-instancia-fin').val('');

    }
    // ********************************************************************************************

    setAMBEnteTriggers();

    // ==============================================================
    // CONFIG PLUGGINS
    // ==============================================================
    $('#modal-abm-instancia-inicio').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
    $('#modal-abm-instancia-fin').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
});