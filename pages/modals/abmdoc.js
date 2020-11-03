$(function() {

    // ********************************************************************************************
    // Documentos
    // ********************************************************************************************
    function setAMBTriggers() {
        // APROBAR
        // seteo boton trigger para el alta de gerencia
        $('.modal-abm-docs-btn-aprobar').on('click', function() {
            $('#modal-abm-doc-title').html('Aprobar versión de documento');
            modalAbmLimpiarCamposAprobar();
            $('#modal-abm-doc-aprobar-id').val($(this).data('id'));
            $('#modal-abm-doc-aprobar-version').val($(this).data('version') + 1);
            $('#modal-abm-doc-aprobar-version-actual').val($(this).data('version'));
            $('#modal-abm-doc-aprobar-submit').attr('name', 'P');
            $("#modal-abm-doc-aprobar").modal("show");
        });
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-doc-btn-alta').on('click', function() {
            $('#modal-abm-doc-title').html('Nueva documentación');
            modalAbmLimpiarCampos();
            $('#modal-abm-doc-submit').attr('name', 'A');
            $("#modal-abm-doc").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-doc-btn-edit').on('click', function() {
            $('#modal-abm-doc-title').html('Editar Documentación');
            modalAbmLimpiarCampos();

            $('#modal-abm-doc-id').val($(this).data('id'));
            $('#modal-abm-doc-nombre').val($(this).data('nombre'));

            $('#modal-abm-doc-submit').attr('name', 'M');
            $("#modal-abm-doc").modal("show");
        });

        // REVIEW
        $('.modal-abm-docs-btn-review').on('click', function() {
            let id = $(this).data('id');
            let frecuencia = $(this).data('frecuencia');
            let vigencia = new Date();
            let proxima_actualizacion = new Date();
            proxima_actualizacion.setDate(proxima_actualizacion.getDate() + frecuencia);

            if (confirm("Desea registrar la revisión del Documento [" + $(this).data('nombre') + "]?")) {
                $.ajax({
                    type: 'POST',
                    url: './helpers/abmdocdb.php',
                    data: {
                        operacion: 'R',
                        id: id,
                        proxima_actualizacion: proxima_actualizacion.toISOString().split('T')[0]
                    },
                    dataType: 'json',
                    success: function(json) {
                        alert('La revisión ha sido registrada correctamente');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText, error);
                    }
                });
            }
        });
    }


    // ==============================================================
    // GUARDAR Documentación
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-doc-submit').on('click', function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-doc-id').val();
        let nombre = $('#modal-abm-doc-nombre').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmprocesosdb.php',
            data: {
                operacion: op,
                id: id,
                nombre: nombre,
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-doc").modal("hide");
                location.reload();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });
    // APROBAR
    $('#modal-abm-doc-aprobar-submit').on('click', function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-doc-aprobar-id').val();
        let version = $('#modal-abm-doc-aprobar-version').val();
        let aprobado_minuta = $('#modal-abm-doc-aprobar-minuta').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmdocdb.php',
            data: {
                operacion: op,
                id: id,
                version: version,
                aprobado_minuta: aprobado_minuta
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-doc").modal("hide");
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
        $('#modal-abm-doc-id').val(0);
        $('#modal-abm-doc-nombre').val('');
    }

    function modalAbmLimpiarCamposAprobar() {
        $('#modal-abm-doc-aprobar-id').val(0);
        $('#modal-abm-doc-aprobar-version').val(0);
        $('#modal-abm-doc-aprobar-minuta').val('');
    }
    // ********************************************************************************************

    setAMBTriggers();

});