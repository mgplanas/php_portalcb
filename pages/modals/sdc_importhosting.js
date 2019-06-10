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
        var formData = new FormData(this);
        var inputFile = $("#modal-import-hosting-file")[0];
        formData.append(inputFile.name, inputFile.files[0]);
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
                    $("#modal-import-hosting-status-body")
                        .append('<p>- Registros con campos de clientes vacíos: ' + data.tot_emptyClients + '</p>')
                        .append('<p>- Registros con clientes nuevos: ' + data.tot_newClients + '</p>')
                        .append('<p>Se debe solucionar todos los inconvenientes reportados antes de continuar la importación</p>');
                } else {
                    if (confirm('Se actualizarán ' + data.tot_toBeUpdated + ' registros e insertarán ' + data.tot_toBeInserted + '\n ¿Desea continuar?')) {
                        alert('ok');
                    } else {
                        alert('cancel');
                    };
                    $("#modal-import-hosting-form")[0].reset();
                    $("#modal-import-hosting-status-body").empty();
                }
            },
            error: function(e) {
                btn.button('reset');
                $("#modal-import-hosting-status-body").append(data);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalImportHostingServicesLimpiarCampos() {
        $('#modal-import-hosting-file').val('');
        $("#modal-import-hosting-status-body").empty();
    }
    // ********************************************************************************************

    setImportHostingServicesTriggers();

});