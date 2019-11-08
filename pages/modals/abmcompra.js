$(function() {

    let ddlGerencias = $('#modal-abm-compra-gerencia');
    let ddlSubGerencias = $('#modal-abm-compra-subgerencia');
    let ddlMonedasPresupuesto = $('#modal-abm-compra-moneda');
    let ddlSolicitante = $('#modal-abm-compra-solicitante');
    let ddlPlazoUnidad = $('#modal-abm-compra-plazo-unidad');
    let ddlCapexOpex = $('#modal-abm-compra-capex-opex');

    // refresh DDL
    function refreshPlazoUnidad(selectedValue) {
        // Limpio combos
        ddlPlazoUnidad.empty();

        // Meses o Años (1 y 12)
        if (selectedValue && selectedValue == 1) {
            ddlPlazoUnidad.append($("<option />").val(1).text('Meses').attr('selected', 'selected'));
        } else {
            ddlPlazoUnidad.append($("<option />").val(1).text('Meses'));
        }
        if (selectedValue && selectedValue == 12) {
            ddlPlazoUnidad.append($("<option />").val(12).text('Años').attr('selected', 'selected'));
        } else {
            ddlPlazoUnidad.append($("<option />").val(12).text('Años'));
        }
        if (!selectedValue) {
            ddlPlazoUnidad.val('first').change();
        }
    }
    // refresh DDL
    function refreshCapexOpex(selectedValue) {
        // Limpio combos
        ddlCapexOpex.empty();

        // Meses o Años (1 y 12)
        if (selectedValue && selectedValue == 'C') {
            ddlCapexOpex.append($("<option />").val('C').text('CAPEX').attr('selected', 'selected'));
        } else {
            ddlCapexOpex.append($("<option />").val('C').text('CAPEX'));
        }
        if (selectedValue && selectedValue == 'O') {
            ddlCapexOpex.append($("<option />").val('O').text('OPEX').attr('selected', 'selected'));
        } else {
            ddlCapexOpex.append($("<option />").val('O').text('OPEX'));
        }
        if (!selectedValue) {
            ddlCapexOpex.val('first').change();
        }
    }
    // refresh DDL
    function refreshSolicitante(selectedValue) {
        // Limpio combos
        ddlSolicitante.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT id_persona, apellido, nombre FROM persona WHERE borrado = 0 ORDER BY apellido, nombre' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlSolicitante.append($("<option />").val(this.id_persona).text(this.apellido + ', ' + this.nombre).attr('selected', 'selected'));
                    } else {
                        ddlSolicitante.append($("<option />").val(this.id_persona).text(this.apellido + ', ' + this.nombre));
                    }
                });
                if (!selectedValue) {
                    ddlSolicitante.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }
    // refresh DDL
    function refreshMonedaPresupuesto(selectedValue) {
        // Limpio combos
        ddlMonedasPresupuesto.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM adm_monedas WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlMonedasPresupuesto.append($("<option />").val(this.id).text(this.sigla).attr('selected', 'selected'));
                    } else {
                        ddlMonedasPresupuesto.append($("<option />").val(this.id).text(this.sigla));
                    }
                });
                if (!selectedValue) {
                    ddlMonedasPresupuesto.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }
    // refresh DDL
    function refreshGerencias(selectedValue) {
        // Limpio combos
        ddlGerencias.empty();
        ddlSubGerencias.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM gerencia WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id_gerencia) {
                        ddlGerencias.append($("<option />").val(this.id_gerencia).text(this.nombre).attr('selected', 'selected'));
                    } else {
                        ddlGerencias.append($("<option />").val(this.id_gerencia).text(this.nombre));
                    }
                });
                if (!selectedValue) {
                    ddlGerencias.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }
    // refresh DDL
    function refreshSubGerencias(idGerencia, selectedValue) {

        console.log(idGerencia, selectedValue);

        // Limpio combos
        ddlSubGerencias.empty();

        if (idGerencia) {
            //Populo las areas
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM subgerencia WHERE id_gerencia = ' + idGerencia },
                function(response) {
                    $.each(response.data, function() {
                        if (selectedValue && selectedValue == this.id_subgerencia) {
                            ddlSubGerencias.append($("<option />").val(this.id_subgerencia).text(this.nombre).attr('selected', 'selected'));
                        } else {
                            ddlSubGerencias.append($("<option />").val(this.id_subgerencia).text(this.nombre));
                        }
                    });
                    if (!selectedValue) {
                        ddlSubGerencias.val('first').change();
                    }

                }
            ).fail(function(jqXHR, errorText) {
                console.log(jqXHR, errorText);
            });

        }
    }

    // EVENTOS DDL
    ddlGerencias.on('change', function() {
        let idgerencia = $('option:selected', this).val();
        refreshSubGerencias(idgerencia);
    });

    function modalAbmComprasLimpiarCampos() {
        $('#modal-abm-compra-id').val(0);
        $('#modal-abm-compra-fecha-sol').val('');
        $('#modal-abm-compra-solicitud').val('');
        $('#modal-abm-compra-concepto').val('');
        $('#modal-abm-compra-presupuesto').val('');
        $('#modal-abm-compra-plazo').val('');
        // $('#modal-abm-compra-grupo-div').hide();

        ddlGerencias.val('first').change();
        ddlSolicitante.val('first').change();
        ddlMonedasPresupuesto.val('first').change();
        ddlCapexOpex.val('first').change();
        ddlPlazoUnidad.val('first').change();
    }
    // ALTA
    // seteo boton trigger para el alta de gerencia
    $('#modal-abm-compra-btn-alta').click(function() {
        $('#modal-abm-compra-title').html('Nuevo Seguimiento de Compra');
        modalAbmComprasLimpiarCampos();
        $('#modal-abm-compra-submit').attr('name', 'A');

        $("#modal-abm-compra").modal("show");
    });

    $('.modal-abm-compra-btn-edit').click(function() {
        $('#modal-abm-compra-title').html('Editar Compra');
        modalAbmComprasLimpiarCampos();

        $('#modal-abm-compra-id').val($(this).data('id'));
        $('#modal-abm-compra-legajo').val($(this).data('legajo'));
        $('#modal-abm-compra-nombre').val($(this).data('nombre'));
        $('#modal-abm-compra-apellido').val($(this).data('apellido'));
        $('#modal-abm-compra-email').val($(this).data('email'));
        $('#modal-abm-compra-contacto').val($(this).data('contacto'));
        $('#modal-abm-compra-cargo').val($(this).data('cargo'));
        $('#modal-abm-compra-gerencia').val($(this).data('idgerencia'));
        $('#modal-abm-compra-subgerencia').val($(this).data('idsubgerencia'));
        $('#modal-abm-compra-area').val($(this).data('idarea'));
        $('#modal-abm-compra-grupo').val($(this).data('grupo'));
        refreshGerencias($(this).data('idgerencia'));
        refreshSubGerencias($(this).data('idgerencia'), $(this).data('idsubgerencia'));

        $('#modal-abm-compra-submit').attr('name', 'M');

        $("#modal-abm-compra").modal("show");
    });


    $('#modal-abm-compra-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-compra-id').val();
        let fecha = $('#modal-abm-compra-fecha-sol').val();
        let solicitud = $('#modal-abm-compra-solicitud').val();
        let concepto = $('#modal-abm-compra-concepto').val();
        let presupuesto = $('#modal-abm-compra-presupuesto').val();
        let plazo = $('#modal-abm-compra-plazo').val();
        let gerencia = $('#modal-abm-compra-gerencia').val();
        let subgerencia = $('#modal-abm-compra-subgerencia').val();
        let solicitante = $('#modal-abm-compra-solicitante').val();
        let moneda = $('#modal-abm-compra-moneda').val();
        let capexopex = $('#modal-abm-compra-capex-opex').val();
        let plazo_unidad = $('#modal-abm-compra-plazo-unidad').val();

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmcompradb.php',
            data: {
                operacion: op,
                id: id,
                fecha: fecha,
                solicitud: solicitud,
                concepto: concepto,
                presupuesto: presupuesto,
                plazo: plazo,
                gerencia: gerencia,
                subgerencia: subgerencia,
                solicitante: solicitante,
                moneda: moneda,
                capexopex: capexopex,
                plazo_unidad: plazo_unidad
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-compra").modal("hide");
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });


    refreshGerencias();
    refreshMonedaPresupuesto();
    refreshSolicitante();
    refreshPlazoUnidad();
    refreshCapexOpex();
});