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
            success: function(data) {
                btn.button('reset');
                if (!data.ok) {
                    $("#err").html(data.error).fadeIn();
                } else {
                    $("#modal-import-hosting-form")[0].reset();
                }
            },
            error: function(e) {
                btn.button('reset');
                $("#err").html(e).fadeIn();
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalImportHostingServicesLimpiarCampos() {
        $('#modal-import-hosting-file').val('');
    }
    // ********************************************************************************************

    setImportHostingServicesTriggers();

});