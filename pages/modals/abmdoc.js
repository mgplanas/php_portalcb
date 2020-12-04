$(function() {

    // ********************************************************************************************
    // Documentos
    // ********************************************************************************************
    function calcNextReview(fecha_vigencia, frecuencia) {
        if (fecha_vigencia && frecuencia) {
            fecha_vigencia = fecha_vigencia.split("/").reverse().join("-");
            let dtNext = new Date(fecha_vigencia);
            dtNext.setDate(dtNext.getDate() + frecuencia);
            let ff = dtNext.toISOString().split('T')[0];
            ff = ff.split("-").reverse().join("/");
            $('#modal-abm-doc-next').val(ff);
        }
        return;
    }

    function setAMBTriggers() {
        // Calculo Fecha proxima revisión
        $('#modal-abm-doc-vigencia').on('change', function() {
            let fecha_vigencia = $('#modal-abm-doc-vigencia').val();
            let frecuencia = parseInt($('#modal-abm-doc-frecuencia').val());
            calcNextReview(fecha_vigencia, frecuencia);
        });
        $('#modal-abm-doc-frecuencia').on('change', function() {
            let fecha_vigencia = $('#modal-abm-doc-vigencia').val();
            let frecuencia = parseInt($('#modal-abm-doc-frecuencia').val());
            calcNextReview(fecha_vigencia, frecuencia);
        });

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
            let id = $(this).data('id');
            $('#modal-abm-doc-id').val(id);
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM doc_documentos WHERE id = ' + id },
                function(response) {
                    doc = response.data[0];
                    $('#modal-abm-doc-tipodoc').val(doc.id_tipo).change();
                    $('#modal-abm-doc-version').val(doc.version);
                    $('#modal-abm-doc-nombre').val(doc.nombre);
                    $('#modal-abm-doc-doclink').val(doc.path);
                    $('#modal-abm-doc-owner').val(doc.id_owner).change();
                    $('#modal-abm-doc-area').val(doc.id_area).change();
                    doc.vigencia = doc.vigencia.split(' ')[0].split('-').reverse().join('/');
                    doc.proxima_actualizacion = doc.proxima_actualizacion.split(' ')[0].split('-').reverse().join('/');
                    doc.comunicado = doc.comunicado.split(' ')[0].split('-').reverse().join('/');
                    if (doc.vigencia && doc.vigencia !== '00/00/0000') {
                        $('#modal-abm-doc-vigencia').val(doc.vigencia);
                    }
                    if (doc.proxima_actualizacion && doc.proxima_actualizacion !== '00/00/0000') {
                        $('#modal-abm-doc-frecuencia').val(doc.frecuencia_revision);
                    }
                    $('#modal-abm-doc-next').val(doc.proxima_actualizacion);
                    $('#modal-abm-doc-periodicidad').val(doc.id_periodicidad_com).change();
                    $('#modal-abm-doc-forma').val(doc.id_forma_com).change();
                    if (doc.comunicado && doc.comunicado !== '00/00/0000') {
                        $('#modal-abm-doc-comunicado').val(doc.comunicado);
                    }
                }
            ).fail(function(jqXHR, errorText) {
                console.log(errorText);
            });


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
        let tipodoc = $('#modal-abm-doc-tipodoc').val();
        let version = $('#modal-abm-doc-version').val();
        let nombre = $('#modal-abm-doc-nombre').val();
        let doclink = $('#modal-abm-doc-doclink').val();
        let owner = $('#modal-abm-doc-owner').val();
        let area = $('#modal-abm-doc-area').val();
        let vigencia = $('#modal-abm-doc-vigencia').val();
        let frecuencia = $('#modal-abm-doc-frecuencia').val();
        let next = $('#modal-abm-doc-next').val();
        let periodicidad = $('#modal-abm-doc-periodicidad').val();
        let forma = $('#modal-abm-doc-forma').val();
        let comunicado = $('#modal-abm-doc-comunicado').val();

        vigencia = vigencia ? vigencia.split('/').reverse().join('-') : '';
        next = next ? next.split('/').reverse().join('-') : '';
        comunicado = comunicado ? comunicado.split('/').reverse().join('-') : '';
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmdocdb.php',
            data: {
                operacion: op,
                id: id,
                tipodoc: tipodoc,
                version: version,
                nombre: nombre,
                doclink: doclink,
                owner: owner,
                area: area,
                vigencia: vigencia,
                frecuencia: frecuencia,
                next: next,
                periodicidad: periodicidad,
                forma: forma,
                comunicado: comunicado
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
        let aprobado_path = $('#modal-abm-doc-aprobar-minuta').val();
        let aprobado_minuta = $('#modal-abm-doc-aprobar-nombre').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmdocdb.php',
            data: {
                operacion: op,
                id: id,
                version: version,
                aprobado_path: aprobado_path,
                aprobado_minuta: aprobado_minuta,
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
        $('#modal-abm-doc-tipodoc').val('first').change();
        $('#modal-abm-doc-version').val('');
        $('#modal-abm-doc-nombre').val('');
        $('#modal-abm-doc-doclink').val('');
        $('#modal-abm-doc-owner').val('first').change();
        $('#modal-abm-doc-area').val('first').change();
        $('#modal-abm-doc-vigencia').val('');
        $('#modal-abm-doc-frecuencia').val(365);
        $('#modal-abm-doc-next').val('');
        $('#modal-abm-doc-periodicidad').val('first').change();
        $('#modal-abm-doc-forma').val('first').change();
        $('#modal-abm-doc-comunicado').val('');
    }

    function modalAbmLimpiarCamposAprobar() {
        $('#modal-abm-doc-aprobar-id').val(0);
        $('#modal-abm-doc-aprobar-version').val(0);
        $('#modal-abm-doc-aprobar-minuta').val('');
        $('#modal-abm-doc-aprobar-nombre').val('');
    }
    // ********************************************************************************************

    setAMBTriggers();

    // ==============================================================
    // CONFIG PLUGGINS
    // ==============================================================
    $('#modal-abm-doc-vigencia').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
    $('#modal-abm-doc-comunicado').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });

});