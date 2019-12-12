$(function() {

    // ********************************************************************************************
    // ORGANISMOS
    // ********************************************************************************************
    function setAMBOrganismoTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-servers-btn-alta').click(function() {
            $('#modal-abm-servers-title').html('Nuevo Server');
            modalAbmServidoresLimpiarCampos();
            $('#modal-abm-servers-submit').attr('name', 'A');
            $("#modal-abm-servers").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-servers-btn-edit').click(function() {
            $('#modal-abm-servers-title').html('Editar Server');
            modalAbmServidoresLimpiarCampos();

            $('#modal-abm-servers-id').val($(this).data('id'));
            $('#modal-abm-servers-marca').val($(this).data('marca'));
            $('#modal-abm-servers-modelo').val($(this).data('modelo'));
            $('#modal-abm-servers-serie').val($(this).data('serie'));
            $('#modal-abm-servers-memoria').val($(this).data('memoria'));
            $('#modal-abm-servers-sockets').val($(this).data('sockets'));
            $('#modal-abm-servers-nucleos').val($(this).data('nucleos'));
            $('#modal-abm-servers-sala').val($(this).data('sala'));
            $('#modal-abm-servers-fila').val($(this).data('fila'));
            $('#modal-abm-servers-rack').val($(this).data('rack'));
            $('#modal-abm-servers-unidad').val($(this).data('unidad'));
            $('#modal-abm-servers-ip').val($(this).data('ip'));
            $('#modal-abm-servers-vcenter').val($(this).data('vcenter'));
            $('#modal-abm-servers-cluster').val($(this).data('cluster'));
            $('#modal-abm-servers-hostname').val($(this).data('hostname'));
            $('#modal-abm-servers-nombre').val($(this).data('nombre'));
            $('#modal-abm-servers-sigla').val($(this).data('sigla'));
            $('#modal-abm-servers-cuit').val($(this).data('cuit'));

            $('#modal-abm-servers-submit').attr('name', 'M');

            $("#modal-abm-servers").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR ORGANISMO
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-servers-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-servers-id').val();
        let marca = $('#modal-abm-servers-marca').val();
        let modelo = $('#modal-abm-servers-modelo').val();
        let serie = $('#modal-abm-servers-serie').val();
        let memoria = $('#modal-abm-servers-memoria').val();
        let sockets = $('#modal-abm-servers-sockets').val();
        let nucleos = $('#modal-abm-servers-nucleos').val();
        let sala = $('#modal-abm-servers-sala').val();
        let fila = $('#modal-abm-servers-fila').val();
        let rack = $('#modal-abm-servers-rack').val();
        let unidad = $('#modal-abm-servers-unidad').val();
        let ip = $('#modal-abm-servers-ip').val();
        let vcenter = $('#modal-abm-servers-vcenter').val();
        let cluster = $('#modal-abm-servers-cluster').val();
        let hostname = $('#modal-abm-servers-hostname').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmservidoresdb.php',
            data: {
                operacion: op,
                id: id,
                marca: marca,
                modelo: modelo,
                serie: serie,
                memoria: memoria,
                sockets: sockets,
                nucleos: nucleos,
                sala: sala,
                fila: fila,
                rack: rack,
                unidad: unidad,
                ip: ip,
                vcenter: vcenter,
                cluster: cluster,
                hostname: hostname
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-servers").modal("hide");
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
    function modalAbmServidoresLimpiarCampos() {
        $('#modal-abm-servers-id').val(0);
        $('#modal-abm-servers-marca').val('');
        $('#modal-abm-servers-modelo').val('');
        $('#modal-abm-servers-serie').val('');
        $('#modal-abm-servers-memoria').val('');
        $('#modal-abm-servers-sockets').val('');
        $('#modal-abm-servers-nucleos').val('');
        $('#modal-abm-servers-sala').val('');
        $('#modal-abm-servers-fila').val('');
        $('#modal-abm-servers-rack').val('');
        $('#modal-abm-servers-unidad').val('');
        $('#modal-abm-servers-ip').val('');
        $('#modal-abm-servers-vcenter').val('');
        $('#modal-abm-servers-cluster').val('');
        $('#modal-abm-servers-hostname').val('');
        $('#modal-abm-servers-nombre').val('');
        $('#modal-abm-servers-sigla').val('');
        $('#modal-abm-servers-cuit').val('');
    }
    // ********************************************************************************************

    setAMBOrganismoTriggers();

});