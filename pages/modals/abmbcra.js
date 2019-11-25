$(function() {

    // ********************************************************************************************
    // ISO 9K
    // ********************************************************************************************
    function setAMBISO9kTriggers() {

        // BAJA
        $('.modal-abm-iso9k-btn-baja').click(function() {
            if (confirm('Esta seguro de borrar los datos del ítem ' + $(this).data('codigo') + '?')) {
                let id = $(this).data('id');
                let version = $('#versionselector :selected').val();
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/abmiso9kdb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if (!json.ok) {
                            alert(json.err);
                        } else {
                            window.location.href = "iso9k.php?version=".concat(version);
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
        $('#modal-abm-iso9k-btn-alta').click(function() {
            $('#modal-abm-iso9k-title').html('Nuevo Item ISO9K');
            $('#modal-abm-iso9k-user').val($(this).data('usuario'));
            $('#modal-abm-iso9k-rowindex').val($(this).parents('tr').index());
            modalAbmISO9KLimpiarCampos();
            $('#modal-abm-iso9k-submit').attr('name', 'A');
            $("#modal-abm-iso9k").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-iso9k-btn-edit').click(function() {
            $('#modal-abm-iso9k-title').html('Editar Item ISO9K');
            $('#modal-abm-iso9k-rowindex').val($(this).parents('tr').index());
            modalAbmISO9KLimpiarCampos();

            let referentes_ids = [];
            if ($(this).data('referentes')) {
                referentes_ids = $(this).data('referentes').toString().split(',');
            }
            $('#modal-abm-iso9k-id').val($(this).data('id'));
            $('#modal-abm-iso9k-version-id').val($('#versionselector :selected').val());
            $('#modal-abm-iso9k-grupo').val($(this).data('grupo')).change();
            $('#modal-abm-iso9k-subgrupo').val($(this).data('subgrupo')).change();
            $('#modal-abm-iso9k-responsable').val($(this).data('responsable')).change();
            $('#modal-abm-iso9k-referentes').val(referentes_ids).change();
            $('#modal-abm-iso9k-madurez').val($(this).data('madurez')).change();
            $('#modal-abm-iso9k-version').val($('#versionselector :selected').text());
            $('#modal-abm-iso9k-codigo').val($(this).data('codigo'));
            $('#modal-abm-iso9k-titulo').val($(this).data('titulo'));
            $('#modal-abm-iso9k-descripcion').val($(this).data('descripcion'));
            $('#modal-abm-iso9k-implementacion').val($(this).data('implementacion'));
            $('#modal-abm-iso9k-evidencia').val($(this).data('evidencia'));
            $('#modal-abm-iso9k-user').val($(this).data('usuario'));
            $('#modal-abm-iso9k-submit').attr('name', 'M');

            $("#modal-abm-iso9k").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-iso9k-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-iso9k-id').val();
        let versionid = $('#modal-abm-iso9k-version-id').val();
        let grupo = $('#modal-abm-iso9k-grupo').val();
        let subgrupo = $('#modal-abm-iso9k-subgrupo').val();
        let responsable = $('#modal-abm-iso9k-responsable').val();
        let referentes = $('#modal-abm-iso9k-referentes').val();
        let madurez = $('#modal-abm-iso9k-madurez').val();
        let version = $('#modal-abm-iso9k-version').val();
        let codigo = $('#modal-abm-iso9k-codigo').val();
        let titulo = $('#modal-abm-iso9k-titulo').val();
        let descripcion = $('#modal-abm-iso9k-descripcion').val();
        let implementacion = $('#modal-abm-iso9k-implementacion').val();
        let evidencia = $('#modal-abm-iso9k-evidencia').val();
        let usuario = $('#modal-abm-iso9k-user').val();

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmiso9kdb.php',
            data: {
                operacion: op,
                id: id,
                versionid: versionid,
                grupo: grupo,
                subgrupo: subgrupo,
                responsable: responsable,
                referentes: referentes,
                madurez: madurez,
                version: version,
                codigo: codigo,
                titulo: titulo,
                descripcion: descripcion,
                implementacion: implementacion,
                evidencia: evidencia,
                usuario: usuario
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-iso9k").modal("hide");
                if (!json.ok) {
                    if (json.err.indexOf('UNIQUE')) {
                        alert('El código del ítem debe ser único para el grupo/subgrupo.');
                    } else {
                        alert(json.err);
                    }
                } else {
                    window.location.href = "iso9k.php?version=".concat(json.version);
                }
                // refreshDataRow($('#modal-abm-iso9k-rowindex').val());
                // setAMBISO9kTriggers();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function refreshDataRow(index) {
        let tableISO = $('#iso9k').dataTable();
        let data = tableISO.fnGetData(index);
        console.log(data);
        data[3] = 'changed';
        tableISO.fnUpdate(data, index, undefined, false);
    };


    function modalAbmISO9KLimpiarCampos() {
        $('#modal-abm-iso9k-id').val(0);
        $('#modal-abm-iso9k-version-id').val($('#versionselector :selected').val());
        $('#modal-abm-iso9k-grupo').val('first').change();
        $('#modal-abm-iso9k-subgrupo').val('first').change();
        $('#modal-abm-iso9k-responsable').val('first').change();
        $('#modal-abm-iso9k-referentes').val([]);
        $('#modal-abm-iso9k-madurez').val('first').change();
        $('#modal-abm-iso9k-version').val($('#versionselector :selected').text());
        $('#modal-abm-iso9k-codigo').val('');
        $('#modal-abm-iso9k-titulo').val('');
        $('#modal-abm-iso9k-descripcion').val('');
        $('#modal-abm-iso9k-implementacion').val('');
        $('#modal-abm-iso9k-evidencia').val('');
        $('#modal-abm-iso9k-user').val('');
    }
    // ********************************************************************************************

    setAMBISO9kTriggers();

});