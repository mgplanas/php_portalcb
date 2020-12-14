$(function() {

    // ********************************************************************************************
    // ISO 9K
    // ********************************************************************************************
    function setAMBbcraTriggers() {

        // BAJA
        $('.modal-abm-bcra-btn-baja').click(function() {
            if (confirm('Esta seguro de borrar los datos del ítem ' + $(this).data('codigo') + '?')) {
                let id = $(this).data('id');
                let version = $('#versionselector :selected').val();
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/abmbcradb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if (!json.ok) {
                            alert(json.err);
                        } else {
                            window.location.href = "bcra.php?version=".concat(version);
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
        $('#modal-abm-bcra-btn-alta').click(function() {
            $('#modal-abm-bcra-title').html('Nuevo Item bcra');
            $('#modal-abm-bcra-user').val($(this).data('usuario'));
            $('#modal-abm-bcra-rowindex').val($(this).parents('tr').index());
            modalAbmbcraLimpiarCampos();
            $('#modal-abm-bcra-submit').attr('name', 'A');
            $("#modal-abm-bcra").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-bcra-btn-edit').click(function() {
            $('#modal-abm-bcra-title').html('Editar Item bcra');
            $('#modal-abm-bcra-rowindex').val($(this).parents('tr').index());
            modalAbmbcraLimpiarCampos();

            let referentes_ids = [];
            if ($(this).data('referentes')) {
                referentes_ids = $(this).data('referentes').toString().split(',');
            }
            $('#modal-abm-bcra-id').val($(this).data('id'));
            $('#modal-abm-bcra-version-id').val($('#versionselector :selected').val());
            $('#modal-abm-bcra-grupo').val($(this).data('grupo')).change();
            $('#modal-abm-bcra-responsable').val($(this).data('responsable')).change();
            $('#modal-abm-bcra-referentes').val(referentes_ids).change();
            $('#modal-abm-bcra-madurez').val($(this).data('madurez')).change();
            $('#modal-abm-bcra-version').val($('#versionselector :selected').text());
            $('#modal-abm-bcra-codigo').val($(this).data('codigo'));
            $('#modal-abm-bcra-descripcion').val($(this).data('descripcion'));
            $('#modal-abm-bcra-implementacion').val($(this).data('implementacion'));
            $('#modal-abm-bcra-escenarios').val($(this).data('escenarios'));
            $('#modal-abm-bcra-documentacion').val($(this).data('documentacion'));
            $('#modal-abm-bcra-evidencia').val($(this).data('evidencia'));
            $('#modal-abm-bcra-user').val($(this).data('usuario'));
            $('#modal-abm-bcra-submit').attr('name', 'M');

            $("#modal-abm-bcra").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-bcra-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-bcra-id').val();
        let versionid = $('#modal-abm-bcra-version-id').val();
        let grupo = $('#modal-abm-bcra-grupo').val();
        let responsable = $('#modal-abm-bcra-responsable').val();
        let referentes = $('#modal-abm-bcra-referentes').val();
        let madurez = $('#modal-abm-bcra-madurez').val();
        let version = $('#modal-abm-bcra-version').val();
        let codigo = $('#modal-abm-bcra-codigo').val();
        let descripcion = $('#modal-abm-bcra-descripcion').val();
        let implementacion = $('#modal-abm-bcra-implementacion').val();
        let evidencia = $('#modal-abm-bcra-evidencia').val();
        let usuario = $('#modal-abm-bcra-user').val();
        let escenarios = $('#modal-abm-bcra-escenarios').val();
        let documentacion = $('#modal-abm-bcra-documentacion').val();

        // valido si no es excluido que hayan puesto un responsable
        if (responsable == 0 && madurez != 2) {
            alert('Se debe ingresar un responsable');
            return;
        }

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmbcradb.php',
            data: {
                operacion: op,
                id: id,
                versionid: versionid,
                grupo: grupo,
                responsable: responsable,
                referentes: referentes,
                madurez: madurez,
                version: version,
                codigo: codigo,
                descripcion: descripcion,
                implementacion: implementacion,
                evidencia: evidencia,
                usuario: usuario,
                escenarios: escenarios,
                documentacion: documentacion
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-bcra").modal("hide");
                if (!json.ok) {
                    if (json.err.indexOf('UNIQUE')) {
                        alert('El código del ítem debe ser único para el grupo.');
                    } else {
                        alert(json.err);
                    }
                } else {
                    window.location.href = "bcra.php?version=".concat(json.version);
                }
                // refreshDataRow($('#modal-abm-bcra-rowindex').val());
                // setAMBbcraTriggers();
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
        let tableISO = $('#bcra').dataTable();
        let data = tableISO.fnGetData(index);
        console.log(data);
        data[3] = 'changed';
        tableISO.fnUpdate(data, index, undefined, false);
    };


    function modalAbmbcraLimpiarCampos() {
        $('#modal-abm-bcra-id').val(0);
        $('#modal-abm-bcra-version-id').val($('#versionselector :selected').val());
        $('#modal-abm-bcra-grupo').val('first').change();
        $('#modal-abm-bcra-responsable').val('0').change();
        $('#modal-abm-bcra-referentes').val([]);
        $('#modal-abm-bcra-madurez').val('first').change();
        $('#modal-abm-bcra-version').val($('#versionselector :selected').text());
        $('#modal-abm-bcra-codigo').val('');
        $('#modal-abm-bcra-descripcion').val('');
        $('#modal-abm-bcra-implementacion').val('');
        $('#modal-abm-bcra-evidencia').val('');
        $('#modal-abm-bcra-user').val('');
        $('#modal-abm-bcra-escenarios').val('');
        $('#modal-abm-bcra-documentacion').val('');
    }
    // ********************************************************************************************

    setAMBbcraTriggers();

});