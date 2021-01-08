$(function() {

    // ********************************************************************************************
    // TRIGGERS DOM
    // ********************************************************************************************
    function setAMBTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-storage-btn-alta').click(function() {
            $('#modal-abm-storage-title').html('Nuevo Equipo de Storage');
            modalAbmLimpiarCampos();
            $('#modal-abm-storage-submit').attr('name', 'A');
            $("#modal-abm-storage").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-storage-btn-edit').click(function() {
            $('#modal-abm-storage-title').html('Editar Equipo');
            modalAbmLimpiarCampos();

            $('#modal-abm-storage-id').val($(this).data('id'));
            $('#modal-abm-storage-nombre').val($(this).data('nombre'));
            $('#modal-abm-storage-categoria').val($(this).data('categoria'));
            $('#modal-abm-storage-capacidad-fisica').val($(this).data('capacidad-fisica'));
            $('#modal-abm-storage-asignacion-recomendada').val($(this).data('asignacion-recomendada'));
            $('#modal-abm-storage-asignacion-max').val($(this).data('asignacion-max'));


            $('#modal-abm-storage-submit').attr('name', 'M');

            $("#modal-abm-storage").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-storage-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-storage-id').val();
        let nombre = $('#modal-abm-storage-nombre').val();
        let categoria = $('#modal-abm-storage-categoria').val();
        let capacidad_fisica = $('#modal-abm-storage-capacidad-fisica').val();
        let asignacion_recomendada = $('#modal-abm-storage-asignacion-recomendada').val();
        let asignacion_max = $('#modal-abm-storage-asignacion-max').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmclientedb.php',
            data: {
                operacion: op,
                id: id,
                id_organismo: id_organismo,
                razon_social: razon_social,
                nombre_corto: nombre_corto,
                cuit: cuit,
                sector: sector,
                convenio: convenio,
                servicio_correo: servicio_correo,
                ejecutivo_cuenta: ejecutivo_cuenta
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-storage").modal("hide");
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
        $('#modal-abm-storage-id').val(0);
        $('#modal-abm-storage-nombre').val('');
        $('#modal-abm-storage-sigla').val('');
        $('#modal-abm-storage-cuit').val('');
        $('#opt-sector-publico').prop("checked", true);
        $('#modal-abm-storage-convenio').prop("checked", false);
        $("#modal-abm-storage-organismo").val('first').change();
        $('#modal-abm-storage-correo').prop("checked", false);
        $("#modal-abm-storage-ejecutivo").val('first').change();
    }
    // ********************************************************************************************

    setAMBTriggers();

});