$(function() {

    let ddlGerencias = $('#modal-abm-persona-gerencia');
    let ddlSubGerencias = $('#modal-abm-persona-subgerencia');
    let ddlAreas = $('#modal-abm-persona-area');
    let ddlGrupos = $('#modal-abm-persona-grupo');

    // refresh DDL
    function refreshGerencias(selectedValue) {
        // Limpio combos
        ddlGerencias.empty();
        ddlSubGerencias.empty();
        ddlAreas.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM gerencia WHERE borrado = 0' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id_gerencia) {
                        ddlGerencias.append($("<option />").val(this.id_gerencia).text(this.nombre).attr('selected', 'selected'));
                        // if (idgerencia == 1) {
                        //     $('#modal-abm-persona-grupo-div').show();
                        // } else {
                        //     $('#modal-abm-persona-grupo-div').hide();
                        // }
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
        ddlAreas.empty();

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

    // refresh DDL
    function refreshAreas(idSubGerencia, selectedValue) {
        // Limpio combos
        ddlAreas.empty();

        if (idSubGerencia) {
            //Populo las areas
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM area WHERE id_subgerencia = ' + idSubGerencia },
                function(response) {
                    $.each(response.data, function() {
                        if (selectedValue && selectedValue == this.id_area) {
                            ddlAreas.append($("<option />").val(this.id_area).text(this.nombre).attr('selected', 'selected'));
                        } else {
                            ddlAreas.append($("<option />").val(this.id_area).text(this.nombre));
                        }
                    });
                    if (!selectedValue) ddlAreas.val('first').change();
                }
            ).fail(function(jqXHR, errorText) {
                console.log(jqXHR, errorText);
            });

        }
    }
    // refresh DDL
    function refreshGrupos(selectedValue) {
        // Limpio combos
        ddlGrupos.empty();

        //Populo las areas
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM grupo ORDER BY nombre' },
            function(response) {
                $.each(response.data, function() {
                    if (selectedValue && selectedValue == this.id_grupo) {
                        ddlGrupos.append($("<option />").val(this.id_grupo).text(this.nombre).attr('selected', 'selected'));
                    } else {
                        ddlGrupos.append($("<option />").val(this.id_grupo).text(this.nombre));
                    }
                });
                if (!selectedValue) ddlGrupos.val('first').change();
            }
        ).fail(function(jqXHR, errorText) {
            console.log(jqXHR, errorText);
        });

    }

    // EVENTOS DDL
    ddlGerencias.on('change', function() {
        let idgerencia = $('option:selected', this).val();
        refreshSubGerencias(idgerencia);
        // if (idgerencia == 1)
        //     $('#modal-abm-persona-grupo-div').show();
        // else
        //     $('#modal-abm-persona-grupo-div').hide();
    });
    ddlSubGerencias.on('change', function() {
        refreshAreas($('option:selected', this).val());
    });

    function modalAbmPersonaLimpiarCampos() {
        $('#modal-abm-persona-id').val(0);
        $('#modal-abm-persona-legajo').val('');
        $('#modal-abm-persona-nombre').val('');
        $('#modal-abm-persona-apellido').val('');
        $('#modal-abm-persona-email').val('');
        $('#modal-abm-persona-contacto').val('');
        $('#modal-abm-persona-cargo').val('');
        // $('#modal-abm-persona-grupo-div').hide();

        ddlGerencias.val('first').change();
    }
    // ALTA
    // seteo boton trigger para el alta de gerencia
    $('#modal-abm-persona-btn-alta').click(function() {
        $('#modal-abm-persona-title').html('Nueva Persona');
        modalAbmPersonaLimpiarCampos();
        $('#modal-abm-persona-submit').attr('name', 'A');

        $("#modal-abm-persona").modal("show");
    });

    $('.modal-abm-persona-btn-edit').click(function() {
        $('#modal-abm-persona-title').html('Editar Persona');
        modalAbmPersonaLimpiarCampos();

        //$('#modal-abm-persona-id').val($(this).data('id'));
        $('#modal-abm-persona-legajo').val($(this).data('legajo'));
        $('#modal-abm-persona-nombre').val($(this).data('nombre'));
        $('#modal-abm-persona-apellido').val($(this).data('apellido'));
        $('#modal-abm-persona-email').val($(this).data('email'));
        $('#modal-abm-persona-contacto').val($(this).data('contacto'));
        $('#modal-abm-persona-cargo').val($(this).data('cargo'));
        refreshGerencias($(this).data('idgerencia'));
        refreshSubGerencias($(this).data('idgerencia'), $(this).data('idsubgerencia'));
        refreshAreas($(this).data('idsubgerencia'), $(this).data('idarea'));
        refreshGrupos($(this).data('grupo'));

        $('#modal-abm-persona-submit').attr('name', 'M');

        $("#modal-abm-persona").modal("show");
    });

    function populateGroups(id_gerencia) {
        //Limpio los grupos
        $("#modal-abm-persona-grupo").empty().append('<option selected="selected" value="0">Ninguno</option>');
        //Populo los grupos
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT * FROM grupo WHERE id_gerencia =' + id_gerencia + ' ORDER BY nombre ASC;' },
            dataType: 'json',
            success: function(json) {

                console.log(json)
                console.log("data" in json)
                if ("data" in json == true) {
                    // Use jQuery's each to iterate over the opts value
                    $.each(json.data, function(i, d) {
                        // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
                        $('#modal-abm-persona-grupo').append('<option value="' + d.id_grupo + '">' + d.nombre + '</option>');
                    });
                }
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    }

    //Seto el trigger si la gerencia cambia 
    $('#modal-abm-persona-gerencia').on('change', function() {
        populateGroups($("#modal-abm-persona-gerencia").val());
    });

    // disparo el cambio en el load;
    populateGroups($("#modal-abm-persona-gerencia").val());

    refreshGerencias();
});