$(function() {

    let ddlGerencias = $('#modal-abm-compra-gerencia');
    let ddlSubGerencias = $('#modal-abm-compra-subgerencia');
    let ddlMonedasPresupuesto = $('#modal-abm-compra-moneda');
    let ddlMonedasOC = $('#modal-abm-compra-moneda-oc');
    let ddlSolicitante = $('#modal-abm-compra-solicitante');
    let ddlPlazoUnidad = $('#modal-abm-compra-plazo-unidad');
    let ddlCapexOpex = $('#modal-abm-compra-capex-opex');
    let ddlPasoActual = $('#modal-abm-compra-paso-actual');
    let ddlProceso = $('#modal-abm-compra-proceso');
    let ddlProveedor = $('#modal-abm-compra-proveedor');
    let ddlEstados = $('#modal-abm-compra-estado');

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
            ddlCapexOpex.val('C').change();
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
                    if (selectedValue && selectedValue == this.id_persona) {
                        ddlSolicitante.append($("<option />").val(this.id_persona).text(this.apellido + ', ' + this.nombre).attr('selected', 'selected'));
                    } else {
                        ddlSolicitante.append($("<option />").val(this.id_persona).text(this.apellido + ', ' + this.nombre));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
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
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlMonedasPresupuesto.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }

    function refreshEstados(selectedValue) {
        // Limpio combos
        ddlEstados.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM adm_com_estados WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlEstados.append($("<option />").val(this.id).text(this.descripcion).attr('selected', 'selected'));
                    } else {
                        ddlEstados.append($("<option />").val(this.id).text(this.descripcion));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlEstados.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }
    // refresh DDL
    function refreshMonedaOC(selectedValue) {
        // Limpio combos
        ddlMonedasOC.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM adm_monedas WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlMonedasOC.append($("<option />").val(this.id).text(this.sigla).attr('selected', 'selected'));
                    } else {
                        ddlMonedasOC.append($("<option />").val(this.id).text(this.sigla));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlMonedasOC.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }

    function refreshProceso(selectedValue) {
        // Limpio combos
        ddlProceso.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM adm_com_procesos WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlProceso.append($("<option />").val(this.id).text(this.descripcion).attr('selected', 'selected'));
                    } else {
                        ddlProceso.append($("<option />").val(this.id).text(this.descripcion));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlProceso.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }

    function refreshProveedor(selectedValue) {
        // Limpio combos
        ddlProveedor.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM adm_com_proveedores WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlProveedor.append($("<option />").val(this.id).text(this.razon_social).attr('selected', 'selected'));
                    } else {
                        ddlProveedor.append($("<option />").val(this.id).text(this.razon_social));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlProveedor.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }

    function refreshPasoActual(selectedValue) {
        // Limpio combos
        ddlPasoActual.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM adm_com_pasos WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id) {
                        ddlPasoActual.append($("<option />").val(this.id).text(this.descripcion).attr('selected', 'selected'));
                    } else {
                        ddlPasoActual.append($("<option />").val(this.id).text(this.descripcion));
                    }
                });
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlPasoActual.val('first').change();
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
                if (!selectedValue || parseInt(selectedValue) == 0) {
                    ddlGerencias.val('first').change();
                }
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }
    // refresh DDL
    function refreshSubGerencias(idGerencia, selectedValue) {

        // console.log(idGerencia, selectedValue);

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
                    if (!selectedValue || parseInt(selectedValue) == 0) {
                        ddlSubGerencias.val('first').change();
                    }

                }
            ).fail(function(jqXHR, errorText) {
                console.log(jqXHR, errorText);
            });

        }
    }

    // ==============================================================
    // MANEJO DE EVENTOS DEL FORM
    // ==============================================================
    $("#modal-abm-compra-presupuesto").blur(function() {
        this.value = parseFloat(this.value).toFixed(0);
    });
    $("#modal-abm-compra-monto-oc").blur(function() {
        this.value = parseFloat(this.value).toFixed(0);
    });


    // Cargo subgerencias
    ddlGerencias.on('change', function() {
        let idgerencia = $('option:selected', this).val();
        refreshSubGerencias(idgerencia);
    });
    // Activo validaciones de Adjudicacion
    ddlEstados.on('change', function() {
        let idEstado = $('option:selected', this).val();

        //$('#modal-abm-compra-fecha-oc').attr('required', (idEstado == '2'));
        //$('#modal-abm-compra-oc').attr('required', (idEstado == '2'));
    });
    // Calculo la fecha fin del contrato
    $('#modal-abm-compra-calc-ff').on('click', function() {
        let fechaoc = $('#modal-abm-compra-fecha-oc').val();
        fechaoc = fechaoc.split("/").reverse().join("-");
        if (fechaoc != '') {
            let dtOC = new Date(fechaoc);
            let plazo = parseInt($('#modal-abm-compra-plazo').val());
            let unidad = parseInt($('#modal-abm-compra-plazo-unidad').val());
            dtOC.setMonth(dtOC.getMonth() + (plazo * unidad));
            let ff = dtOC.toISOString().split('T')[0];
            ff = ff.split("-").reverse().join("/");
            $('#modal-abm-compra-fecha-fin').val(ff);
        }
    });
    // Alta de proveedor
    $('#modal-abm-compra-proveedor-add').on('click', function() {
        let razon_social = $('#modal-abm-compra-proveedor-add-text').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmproveedordb.php',
            data: {
                operacion: 'A',
                razon_social: razon_social
            },
            dataType: 'json',
            success: function(json) {
                $('#modal-abm-compra-proveedor-add-text').val('');
                $('#modal-abm-compra-proveedor-add').attr('disabled', true);
                refreshProveedor(json.id);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });

    });
    // Ingreso de proveedor
    $('#modal-abm-compra-proveedor-add-text').on('input', function() {
        let searchtext = $(this).val();
        $('#modal-abm-compra-proveedor-add').attr('disabled', ($(this).val() == ''));
        // busco en el combo así se selecciona automáticamente
        if (searchtext !== '') {
            let __founded = false;
            $.each($("#modal-abm-compra-proveedor option"), function() {
                let texto = $(this).text().toUpperCase();
                let id = $(this).val();
                if (texto.indexOf(searchtext.toUpperCase()) !== -1) {
                    __founded = true;
                    $("#modal-abm-compra-proveedor").val(id).change();
                    return __founded;
                }
            });
            if (!__founded) $("#modal-abm-compra-proveedor").val(0).change();
        } else {
            $("#modal-abm-compra-proveedor").val(0).change();
        }
    });

    // ==============================================================
    // LIMPIEZA DE CAMPOS 
    // ==============================================================
    function modalAbmComprasLimpiarCampos() {
        $('#modal-abm-compra-id').val(0);
        $('#modal-abm-compra-fecha-sol').val('');
        $('#modal-abm-compra-solicitud').val('');
        $('#modal-abm-compra-concepto').val('');
        $('#modal-abm-compra-presupuesto').val('');
        $('#modal-abm-compra-plazo').val(1);
        $('#modal-abm-compra-fecha-oc').val('');
        $('#modal-abm-compra-fecha-fin').val('');
        $('#modal-abm-compra-oc').val('');
        $('#modal-abm-compra-monto-oc').val('');
        // $('#modal-abm-compra-grupo-div').hide();

        ddlGerencias.val('first').change();
        ddlSolicitante.val('first').change();
        ddlMonedasPresupuesto.val(1).change();
        ddlCapexOpex.val('C').change();
        ddlPlazoUnidad.val(1).change();
        ddlPasoActual.val('first').change();
        ddlMonedasOC.val(1).change();
        ddlProveedor.val('first').change();
        ddlProceso.val('first').change();
        ddlEstados.val('first').change();
    }

    // ==============================================================
    // ALTA DE COMPRA
    // ==============================================================
    $('#modal-abm-compra-btn-alta').click(function() {
        $('#modal-abm-compra-title').html('Nuevo Seguimiento de Compra');
        refreshGerencias($('#compra-id-gerencia').val());
        refreshSubGerencias($('#compra-id-gerencia').val());
        refreshMonedaPresupuesto();
        refreshSolicitante();
        refreshPlazoUnidad();
        refreshCapexOpex();
        refreshMonedaOC();
        refreshPasoActual(1);
        refreshProceso();
        refreshProveedor();
        refreshEstados(1);
        modalAbmComprasLimpiarCampos();

        $('#modal-abm-compra-form').attr('name', 'A');

        $("#modal-abm-compra").modal("show");
    });

    // ==============================================================
    // EDITAR COMPRA
    // ==============================================================
    $('.modal-abm-compra-btn-edit').click(function() {
        $('#modal-abm-compra-title').html('Editar Compra');
        modalAbmComprasLimpiarCampos();

        let id = $(this).data('id');

        //Populo los campos
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM adm_compras WHERE id = ' + id },
            function(response) {
                compra = response.data[0];
                console.log(compra);
                $('#modal-abm-compra-id').val(compra.id);
                $('#modal-abm-compra-fecha-sol').val(compra.fecha_solicitud.split('-').reverse().join('/'));
                $('#modal-abm-compra-solicitud').val(compra.nro_solicitud);
                $('#modal-abm-compra-concepto').val(compra.concepto);
                $('#modal-abm-compra-presupuesto').val(compra.pre_monto);
                $('#modal-abm-compra-plazo').val(compra.plazo_valor);
                if (compra.fecha_oc && compra.fecha_oc !== '0000-00-00') {
                    $('#modal-abm-compra-fecha-oc').val(compra.fecha_oc);
                }
                if (compra.fecha_fin_contrato && compra.fecha_fin_contrato !== '0000-00-00') {
                    $('#modal-abm-compra-fecha-fin').val(compra.fecha_fin_contrato);
                }
                $('#modal-abm-compra-oc').val(compra.nro_oc);
                $('#modal-abm-compra-monto-oc').val(compra.oc_monto);
                $('#modal-abm-compra-paso-actual-id').val(compra.id_paso_actual);
                refreshProveedor(compra.id_proveedor);
                refreshProceso(compra.id_proceso);
                refreshPasoActual(compra.id_paso_actual);
                refreshPlazoUnidad(compra.plazo_unidad);
                refreshCapexOpex(compra.capex_opex);
                refreshMonedaPresupuesto(compra.pre_id_moneda);
                refreshMonedaOC(compra.oc_id_moneda);
                refreshSolicitante(compra.id_solicitante);
                refreshEstados(compra.id_estado);
                refreshGerencias(compra.id_gerencia);
                refreshSubGerencias(compra.id_gerencia, compra.id_subgerencia);
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });



        $('#modal-abm-compra-form').attr('name', 'M');

        $("#modal-abm-compra").modal("show");
    });

    // ==============================================================
    // BAJA
    // ==============================================================
    $('.modal-abm-compra-btn-baja').click(function() {
        if (confirm('Esta seguro de borrar la compra?')) {
            let id = $(this).data('id');
            // Ejecuto
            $.ajax({
                type: 'POST',
                url: './helpers/abmcompradb.php',
                data: {
                    operacion: 'B',
                    id: id
                },
                dataType: 'json',
                success: function(json) {
                    if (!json.ok) {
                        alert(json.err);
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText, error);
                }
            });

        }
    });

    // ==============================================================
    // SUBMIT FORM
    // ==============================================================
    $('#modal-abm-compra-form').submit(function() {

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
        // $fecha_limite= $_POST['fecha_limite'];
        let id_estado = $('#modal-abm-compra-estado').val();
        let id_paso_actual = $('#modal-abm-compra-paso-actual').val();
        let id_paso_actual_original = $('#modal-abm-compra-paso-actual-id').val();
        let fecha_oc = $('#modal-abm-compra-fecha-oc').val();
        let fecha_fin_contrato = $('#modal-abm-compra-fecha-fin').val();
        let nro_oc = $('#modal-abm-compra-oc').val();
        let oc_monto = $('#modal-abm-compra-monto-oc').val();
        let oc_id_moneda = $('#modal-abm-compra-moneda-oc').val();
        let id_proveedor = $('#modal-abm-compra-proveedor').val();
        let id_proceso = $('#modal-abm-compra-proceso').val();
        let tags = $('#modal-abm-compra-tags').val();

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
                plazo_unidad: plazo_unidad,
                id_estado: id_estado,
                id_paso_actual: id_paso_actual,
                id_paso_actual_original: id_paso_actual_original,
                fecha_oc: fecha_oc,
                nro_oc: nro_oc,
                oc_monto: oc_monto,
                oc_id_moneda: oc_id_moneda,
                id_proveedor: id_proveedor,
                id_proceso: id_proceso,
                tags: tags,
                fecha_fin_contrato: fecha_fin_contrato
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-compra").modal("hide");
                location.reload();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // CONFIG PLUGGINS
    // ==============================================================
    $('#modal-abm-compra-fecha-sol').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
    $('#modal-abm-compra-fecha-oc').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
    $('#modal-abm-compra-fecha-fin').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
});