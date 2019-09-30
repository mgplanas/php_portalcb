$(function() {

    // ********************************************************************************************
    // HOUSING
    // ********************************************************************************************
    function setImportHostingServicesTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-import-hosting-btn-import').click(function() {
            $('#modal-import-hosting-title').html('Importar masivamente Servicios de Hosting');
            modalImportHostingServicesLimpiarCampos();
            $('#modal-import-hosting-submit').attr('name', 'R');
            $("#modal-import-hosting").modal("show");
        });

    }


    // ==============================================================
    // GUARDAR HOUSING
    // ==============================================================
    // ejecución de guardado async
    $('#modal-import-hosting-submit').click(function(e) {
        e.preventDefault();

        $("#modal-import-hosting-status-body").empty();

        var btn = $(this);
        var formData = new FormData();
        var inputFile = $("#modal-import-hosting-file")[0];
        var hasHeading = $("#modal-import-hosting-heading").is(':checked');
        if (!inputFile.files.length) {
            alert('Debe seleccionar un archivo para importar.');
            return;
        }
        formData.append(inputFile.name, inputFile.files[0]);
        formData.append('hasHeading', hasHeading);

        formData.append('op', 'READ');

        btn.button('loading');
        $.ajax({
            url: "./helpers/sdc_importhosting.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                btn.button('reset');
                if (!data.ok) {
                    if (data.error) {
                        $("#modal-import-hosting-status-body").append('<p>' + data.error + '</p>');
                    } else {
                        $("#modal-import-hosting-status-body")
                            .append('<p>- Registros con campos de clientes vacíos: ' + data.tot_emptyClients + '</p>')
                            .append('<p>- Registros con clientes nuevos: ' + data.tot_newClients + '</p>')
                            .append('<p>Se debe solucionar todos los inconvenientes reportados antes de continuar la importación</p>');
                    }
                } else {
                    if (confirm('Se importarán ' + data.tot_toBeInserted + '\n ¿Desea continuar?')) {
                        btn.button('loading');
                        $.ajax({
                            type: 'POST',
                            url: './helpers/sdc_importhosting.php',
                            data: { op: 'APPLY' },
                            dataType: 'json',
                            success: function(data) {
                                btn.button('reset');
                                alert('Se ha realizado la importación de registros con éxito.');
                                $("#modal-import-hosting").modal('toggle');
                                location.reload();
                            },
                            error: function(e) {
                                alert(e);
                                btn.button('reset');
                                $("#modal-import-hosting-status-body").append(e);
                            }
                        });
                    }
                    modalImportHostingServicesLimpiarCampos();
                }
            },
            error: function(e) {
                btn.button('reset');
                $("#modal-import-hosting-status-body").append(e);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalImportHostingServicesLimpiarCampos() {
        $('#modal-import-hosting-file').val('');
        $("#modal-import-hosting-form")[0].reset();
        $("#modal-import-hosting-status-body").empty();
    }
    // ********************************************************************************************

    setImportHostingServicesTriggers();

});