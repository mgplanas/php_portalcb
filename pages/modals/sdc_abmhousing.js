$(function() {

    // ********************************************************************************************
    // HOUSING
    // ********************************************************************************************
    function setAMBHousingTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-housing-btn-alta').click(function() {
            $('#modal-abm-housing-title').html('Nuevo Servicio de Housing');
            modalAbmHousingLimpiarCampos();
            $('#modal-abm-housing-submit').attr('name', 'A');
            $("#modal-abm-housing").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-housing-btn-edit').click(function() {
            $('#modal-abm-housing-title').html('Editar Servicio de Housing');
            modalAbmHousingLimpiarCampos();

            $('#modal-abm-housing-id').val($(this).data('id'));
            $('#modal-abm-housing-m2').val($(this).data('m2'));
            $('#modal-abm-housing-sala').val($(this).data('sala'));
            $('#modal-abm-housing-fila').val($(this).data('fila'));
            $('#modal-abm-housing-rack').val($(this).data('rack'));
            $('#modal-abm-housing-evidencia').val($(this).data('evidencia'));
            $('#modal-abm-housing-energia').val($(this).data('energia'));
            $('#modal-abm-housing-alta').val($(this).data('alta'));
            $('#modal-abm-housing-observaciones').val($(this).data('observaciones'));
            $("#modal-abm-housing-cliente").val($(this).data('cliente')).change();
            $("#modal-abm-housing-modalidad").val($(this).data('modalidad')).change();


            $('#modal-abm-housing-submit').attr('name', 'M');

            $("#modal-abm-housing").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR HOUSING
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-housing-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-housing-id').val();
        let id_cliente = $('#modal-abm-housing-cliente').val();
        let modalidad = $('#modal-abm-housing-modalidad').val();
        let m2 = $('#modal-abm-housing-m2').val();
        let sala = $('#modal-abm-housing-sala').val();
        let fila = $('#modal-abm-housing-fila').val();
        let rack = $('#modal-abm-housing-rack').val();
        let energia = $('#modal-abm-housing-energia').val();
        let evidencia = $('#modal-abm-housing-evidencia').val();
        let alta = $('#modal-abm-housing-alta').val();
        let observaciones = $('#modal-abm-housing-observaciones').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/sdc_abmhousingdb.php',
            data: {
                operacion: op,
                id: id,
                id_cliente: id_cliente,
                m2: m2,
                sala: sala,
                fila: fila,
                rack: rack,
                energia: energia,
                evidencia: evidencia,
                alta: alta,
                observaciones: observaciones,
                modalidad: modalidad
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-housing").modal("hide");
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
    function modalAbmHousingLimpiarCampos() {
        $('#modal-abm-housing-id').val(0);
        $('#modal-abm-housing-m2').val('');
        $('#modal-abm-housing-sala').val('');
        $('#modal-abm-housing-fila').val('');
        $('#modal-abm-housing-rack').val('');
        $('#modal-abm-housing-energia').val('');
        $('#modal-abm-housing-evidencia').val('');
        $('#modal-abm-housing-alta').val('');
        $('#modal-abm-housing-observaciones').val('');
        $("#modal-abm-housing-cliente").prop("selectedIndex", 0);
        $("#modal-abm-housing-modalidad").prop("selectedIndex", 0);
        // $("#modal-abm-housing-cliente").val(0).change();
    }
    // ********************************************************************************************

    setAMBHousingTriggers();

});