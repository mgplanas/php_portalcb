$(function() {

    // ********************************************************************************************
    // ISO 27K
    // ********************************************************************************************
    function setAMBISO27kTriggers() {

        // BAJA
        $('.modal-abm-iso27k-btn-baja').click(function() {
            if (confirm('Esta seguro de borrar los datos del ítem ' + $(this).data('codigo') + '?')) {
                let id = $(this).data('id');
                let version = $('#versionselector :selected').val();
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/abmiso27kdb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if (!json.ok) {
                            alert(json.err);
                        } else {
                            window.location.href = "iso27k.php?version=".concat(version);
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
        $('#modal-abm-iso27k-btn-alta').click(function() {
            $('#modal-abm-iso27k-title').html('Nuevo Item ISO27K');
            $('#modal-abm-iso27k-user').val($(this).data('usuario'));
            $('#modal-abm-iso27k-rowindex').val($(this).parents('tr').index());
            modalAbmISO27KLimpiarCampos();
            $('#modal-abm-iso27k-submit').attr('name', 'A');
            $("#modal-abm-iso27k").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-iso27k-btn-edit').click(function() {
            $('#modal-abm-iso27k-title').html('Editar Item ISO27K');
            $('#modal-abm-iso27k-rowindex').val($(this).parents('tr').index());
            modalAbmISO27KLimpiarCampos();

            let referentes_ids = [];
            if ($(this).data('referentes')) {
                referentes_ids = $(this).data('referentes').split(',');
            }
            $('#modal-abm-iso27k-id').val($(this).data('id'));
            $('#modal-abm-iso27k-version-id').val($('#versionselector :selected').val());
            $('#modal-abm-iso27k-grupo').val($(this).data('grupo')).change();
            $('#modal-abm-iso27k-subgrupo').val($(this).data('subgrupo')).change();
            $('#modal-abm-iso27k-responsable').val($(this).data('responsable')).change();
            $('#modal-abm-iso27k-referentes').val(referentes_ids).change();
            $('#modal-abm-iso27k-madurez').val($(this).data('madurez')).change();
            $('#modal-abm-iso27k-version').val($('#versionselector :selected').text());
            $('#modal-abm-iso27k-codigo').val($(this).data('codigo'));
            $('#modal-abm-iso27k-titulo').val($(this).data('titulo'));
            $('#modal-abm-iso27k-descripcion').val($(this).data('descripcion'));
            $('#modal-abm-iso27k-implementacion').val($(this).data('implementacion'));
            $('#modal-abm-iso27k-evidencia').val($(this).data('evidencia'));
            $('#modal-abm-iso27k-user').val($(this).data('usuario'));
            $('#modal-abm-iso27k-submit').attr('name', 'M');

            $("#modal-abm-iso27k").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-iso27k-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-iso27k-id').val();
        let versionid = $('#modal-abm-iso27k-version-id').val();
        let grupo = $('#modal-abm-iso27k-grupo').val();
        let subgrupo = $('#modal-abm-iso27k-subgrupo').val();
        let responsable = $('#modal-abm-iso27k-responsable').val();
        let referentes = $('#modal-abm-iso27k-referentes').val();
        let madurez = $('#modal-abm-iso27k-madurez').val();
        let version = $('#modal-abm-iso27k-version').val();
        let codigo = $('#modal-abm-iso27k-codigo').val();
        let titulo = $('#modal-abm-iso27k-titulo').val();
        let descripcion = $('#modal-abm-iso27k-descripcion').val();
        let implementacion = $('#modal-abm-iso27k-implementacion').val();
        let evidencia = $('#modal-abm-iso27k-evidencia').val();
        let usuario = $('#modal-abm-iso27k-user').val();

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmiso27kdb.php',
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
                $("#modal-abm-iso27k").modal("hide");
                if (!json.ok) {
                    if (json.err.indexOf('UNIQUE')) {
                        alert('El código del ítem debe ser único para el grupo/subgrupo.');
                    } else {
                        alert(json.err);
                    }
                } else {
                    window.location.href = "iso27k.php?version=".concat(json.version);
                }
                // refreshDataRow($('#modal-abm-iso27k-rowindex').val());
                // setAMBISO27kTriggers();
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
        let tableISO = $('#iso27k').dataTable();
        let data = tableISO.fnGetData(index);
        console.log(data);
        data[3] = 'changed';
        tableISO.fnUpdate(data, index, undefined, false);
    };


    function modalAbmISO27KLimpiarCampos() {
        $('#modal-abm-iso27k-id').val(0);
        $('#modal-abm-iso27k-version-id').val($('#versionselector :selected').val());
        $('#modal-abm-iso27k-grupo').val('first').change();
        $('#modal-abm-iso27k-subgrupo').val('first').change();
        $('#modal-abm-iso27k-responsable').val('first').change();
        $('#modal-abm-iso27k-referentes').val([]);
        $('#modal-abm-iso27k-madurez').val('first').change();
        $('#modal-abm-iso27k-version').val($('#versionselector :selected').text());
        $('#modal-abm-iso27k-codigo').val('');
        $('#modal-abm-iso27k-titulo').val('');
        $('#modal-abm-iso27k-descripcion').val('');
        $('#modal-abm-iso27k-implementacion').val('');
        $('#modal-abm-iso27k-evidencia').val('');
        $('#modal-abm-iso27k-user').val('');
    }
    // ********************************************************************************************

    setAMBISO27kTriggers();

});