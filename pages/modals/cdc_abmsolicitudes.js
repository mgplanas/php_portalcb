$(function() {

    // ********************************************************************************************
    // Cliente
    // ********************************************************************************************
    let ddlCliente = $('#modal-abm-solicitud-cliente');

    // refresh DDL
    function refreshClientes(selectedValue, disabled = false) {
        // Limpio combos
        ddlCliente.empty();

        //Populo las Clientes
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM cdc_cliente WHERE borrado = 0 ORDER BY razon_social;' },
            function(response) {
                console.log(response.data);
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlCliente.append($("<option />").val(this.id).text(this.razon_social).attr('selected', 'selected'));
                    } else {
                        ddlCliente.append($("<option />").val(this.id).text(this.razon_social));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlCliente.val('first').change();
                }
                ddlCliente.prop('disabled', disabled);
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }

    function showHide(value, divid) {
        if (value) {
            console.log('show', value, divid);
            $(divid).show();
        } else {
            console.log('hide', value, divid);
            $(divid).hide();
        }
    }

    function setAMBTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-solicitud-btn-alta').click(function() {
            $('#modal-abm-solicitud-title').html('Nueva Solicitud de Infraestrucutra');
            modalAbmLimpiarCampos();
            $('#modal-abm-solicitud-submit').attr('name', 'A');
            $("#modal-abm-solicitud").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-solicitud-btn-edit').click(function() {
            $('#modal-abm-solicitud-title').html('Editar Solicitud de Infraestrucutra');
            $('#modal-abm-solicitud-submit').attr('name', 'M');
            modalAbmLimpiarCampos();

            $('#modal-abm-solicitud-id').val($(this).data('id'));
            loadSolicitud($(this).data('id'), function(err) {
                if (err) {
                    return alert(err);
                } else {
                    $("#modal-abm-solicitud").modal("show");
                }
            });

        });

        // Checks
        $('#modal-abm-solicitud-propuesta').on('change', function() {
            showHide(this.checked, '#modal-abm-solicitud-propuesta-detalle-div');
        });
        $('#modal-abm-solicitud-chw').on('change', function() {
            showHide(this.checked, '#modal-abm-solicitud-chw-detalle-div');
        });
    }

    // Actualiza el form con los datos de la solicitud
    function loadSolicitud(id, callback) {
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM cdc_solicitudes WHERE id = ' + id },
            function(response) {
                if (!response.data || !response.data[0]) {
                    return callback('No hay datos');
                }
                var sol = response.data[0];
                refreshClientes(sol.id_cliente);
                $('#modal-abm-solicitud-fecha-sol').val(sol.fecha.split(' ')[0].split('-').reverse().join("/"));
                // $('#modal-abm-solicitud-fecha-sol').val(sol.fecha);
                $('#modal-abm-solicitud-estado').val(sol.estado);
                $('#modal-abm-solicitud-convenio').val(sol.tiene_convenio);
                $('#modal-abm-solicitud-propuesta').prop('checked', (sol.tiene_pc != 0));
                $('#modal-abm-solicitud-propuesta-detalle').val(sol.pc_descripcion);
                $('#modal-abm-solicitud-titulo').val(sol.titulo);
                $('#modal-abm-solicitud-descripcion').val(sol.descripcion);
                $('#modal-abm-solicitud-ss').val(sol.ss);
                $('#modal-abm-solicitud-chw').prop('checked', (sol.compra_hw != 0));
                $('#modal-abm-solicitud-chw-detalle').val(sol.descripcion_compra);
                $('#modal-abm-solicitud-tiene-sc').prop('checked', (sol.tiene_sc != 0));
                $('#modal-abm-solicitud-sc').val(sol.sc_numero);
                $('#modal-abm-solicitud-costo').val(sol.costo);
                $('#modal-abm-solicitud-solicitante').val(sol.nombre_solicitante);
                $('#modal-abm-solicitud-contactos').val(sol.contacto_solicitante);

                showHide(sol.tiene_pc != '0', '#modal-abm-solicitud-propuesta-detalle-div');
                showHide(sol.compra_hw != '0', '#modal-abm-solicitud-chw-detalle-div');
                return callback(null);
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
            return callback(errorText);
        });

    }
    // ==============================================================
    // GUARDAR ENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-solicitud-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-solicitud-id').val();
        let id_cliente = ddlCliente.val();
        let fecha = $('#modal-abm-solicitud-fecha-sol').val();
        let titulo = $('#modal-abm-solicitud-titulo').val();
        let estado = $('#modal-abm-solicitud-estado').val();
        let convenio = $('#modal-abm-solicitud-convenio').val();
        let propuesta = $('#modal-abm-solicitud-propuesta').val();
        let propuesta_detalle = $('#modal-abm-solicitud-propuesta-detalle').val();
        let descripcion = $('#modal-abm-solicitud-descripcion').val();
        let ss = $('#modal-abm-solicitud-ss').val();
        let chw = $('#modal-abm-solicitud-chw').val();
        let chw_detalle = $('#modal-abm-solicitud-chw-detalle').val();
        let tiene_sc = $('#modal-abm-solicitud-tiene-sc').val();
        let sc = $('#modal-abm-solicitud-sc').val();
        let costo = $('#modal-abm-solicitud-costo').val();
        let solicitante = $('#modal-abm-solicitud-solicitante').val();
        let contactos = $('#modal-abm-solicitud-contactos').val();

        convenio = (convenio == 'on' ? 1 : 0);
        propuesta = (propuesta == 'on' ? 1 : 0);
        chw = (chw == 'on' ? 1 : 0);
        tiene_sc = (tiene_sc == 'on' ? 1 : 0);
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmsolicitudesdb.php',
            data: {
                operacion: op,
                id: id,
                id_cliente: id_cliente,
                fecha: fecha,
                titulo: titulo,
                estado: estado,
                convenio: convenio,
                propuesta: propuesta,
                propuesta_detalle: propuesta_detalle,
                descripcion: descripcion,
                ss: ss,
                chw: chw,
                chw_detalle: chw_detalle,
                tiene_sc: tiene_sc,
                sc: sc,
                costo: costo,
                solicitante: solicitante,
                contactos: contactos,
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-solicitud").modal("hide");
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
    // ==============================================================
    function modalAbmLimpiarCampos() {
        $('#modal-abm-solicitud-id').val(0);
        refreshClientes(0);
        $("#modal-abm-solicitud-fecha-sol").datepicker().datepicker("setDate", new Date());
        $('#modal-abm-solicitud-titulo').val('');
        $('#modal-abm-solicitud-estado').val(1);
        $('#modal-abm-solicitud-convenio').val('');
        $('#modal-abm-solicitud-propuesta').prop('checked', false);
        $('#modal-abm-solicitud-propuesta-detalle').val('');
        $('#modal-abm-solicitud-descripcion').val('');
        $('#modal-abm-solicitud-ss').val('');
        $('#modal-abm-solicitud-chw').prop('checked', false);
        $('#modal-abm-solicitud-chw-detalle').val('');
        $('#modal-abm-solicitud-tiene-sc').prop('checked', false);
        $('#modal-abm-solicitud-sc').val('');
        $('#modal-abm-solicitud-costo').val(0);
        $('#modal-abm-solicitud-solicitante').val('');
        $('#modal-abm-solicitud-contactos').val('');
        showHide(false, '#modal-abm-solicitud-propuesta-detalle-div');
        showHide(false, '#modal-abm-solicitud-chw-detalle-div');

    }
    // ********************************************************************************************

    setAMBTriggers();

    // ==============================================================
    // CONFIG PLUGGINS
    // ==============================================================
    $('#modal-abm-solicitud-fecha-sol').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
});