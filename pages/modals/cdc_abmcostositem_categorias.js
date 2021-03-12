$(function() {
    // ==============================================================
    // Configuracion de las tablas
    // ==============================================================
    // GERENCIAS
    let tbCategorias = $('#tbCategorias');
    let tbCategoriasDT = tbCategorias.DataTable({
        'paging': false,
        'lengthChange': false,
        'searching': false,
        'ordering': false,
        'info': false,
        'autoWidth': false,
        "columnDefs": [{
            "targets": [0],
            "visible": false
        }]
    });

    // SUBGERENCIAS
    let tbsubCategorias = $('#tbsubCategorias');
    let tbsubCategoriasDT = tbsubCategorias.DataTable({
        'paging': false,
        'lengthChange': false,
        'searching': false,
        'ordering': false,
        'info': false,
        'autoWidth': false,
        "columnDefs": [{
            "targets": [0],
            "visible": false
        }]
    });

    //AREAS
    let tbProductos = $('#tbProductos');
    let tbProductosDT = tbProductos.DataTable({
        'paging': false,
        'lengthChange': false,
        'searching': false,
        'ordering': false,
        'info': false,
        'autoWidth': false,
        "columnDefs": [{
            "targets": [0],
            "visible": false
        }]
    });

    // ==============================================================
    // EVENTOS
    // ==============================================================
    // SELECCION GERENCIA
    $('#tbCategorias tbody').on('click', 'tr', function() {

        //Extraigo el id de la data de la row
        let idgerencia = tbCategoriasDT.row(this).data()[0];
        // Pinto o despinto la row
        if ($(this).hasClass('rowselected')) {
            $(this).removeClass('rowselected');
        } else {
            tbCategoriasDT.$('tr.rowselected').removeClass('rowselected');
            $(this).addClass('rowselected');
        }

        // Limpio las demas tablas
        tbsubCategorias.DataTable().clear().draw();
        tbProductos.DataTable().clear().draw();

        //Populo las subgerencias
        refreshSubGerencias(idgerencia);
    });

    // SELECCION EN SUBGERENCIA
    $('#tbsubCategorias tbody').on('click', 'tr', function() {

        let idsubgerencia = tbsubCategoriasDT.row(this).data()[0];
        // let idsubgerencia = $(this).data('id');
        // Pinto o despinto la row
        if ($(this).hasClass('rowselected')) {
            $(this).removeClass('rowselected');
        } else {
            tbsubCategoriasDT.$('tr.rowselected').removeClass('rowselected');
            $(this).addClass('rowselected');
        }
        // Limpio las demas tablas
        tbProductos.DataTable().clear().draw();
        //Populo las areas
        refreshAreas(idsubgerencia);
    });

    refreshGerencias();

    // populate the data table with JSON data
    function populateDataTable(response, table, buttoneditclass) {
        var length = Object.keys(response.data).length;
        for (var i = 0; i < length; i++) {
            let item = response.data[i];
            // You could also use an ajax property on the data table initialization
            let button = '<a data-id="' + item.id + '" data-sigla="' + item.sigla + '"  data-nombre="' + item.nombre + '" data-responsable="' + item.responsable + '" title="editar" class="' + buttoneditclass + ' btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>';
            table.dataTable().fnAddData([
                item.id,
                item.sigla,
                item.responsable,
                item.nombre,
                item.responsableNombre,
                button
            ]);
        }
    }

    // ********************************************************************************************
    // GERENCIAS
    // ********************************************************************************************
    function setGerenciaTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-gerencia-btn-alta').click(function() {
            $('#modal-abm-gerencia-title').html('Nueva Gerencia');
            modalAbmGerenciaLimpiarCampos();
            $('#modal-abm-gerencia-submit').attr('name', 'A');

            $("#modal-abm-gerencia").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-gerencia-btn-edit').click(function() {
            $('#modal-abm-gerencia-title').html('Editar Gerencia');
            modalAbmGerenciaLimpiarCampos();

            $('#modal-abm-gerencia-id').val($(this).data('id'));
            $('#modal-abm-gerencia-nombre').val($(this).data('nombre'));
            $('#modal-abm-gerencia-sigla').val($(this).data('sigla'));
            $("#modal-abm-gerencia-responsable").val($(this).data('responsable')).change();


            $('#modal-abm-gerencia-submit').attr('name', 'M');

            $("#modal-abm-gerencia").modal("show");
        });
    }

    // refresh tables
    function refreshGerencias() {
        // Limpio tablas
        tbCategorias.DataTable().clear().draw();
        tbsubCategorias.DataTable().clear().draw();
        tbProductos.DataTable().clear().draw();

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT g.id_gerencia as id, g.sigla, g.responsable, g.nombre, CONCAT(p.apellido, " ", p.nombre) as responsableNombre FROM gerencia as g LEFT JOIN persona as p ON g.responsable = p.id_persona WHERE g.borrado = 0' },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbCategorias, 'modal-abm-gerencia-btn-edit');
                setGerenciaTriggers();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    }

    // ==============================================================
    // GUARDAR GERENCIAS
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-gerencia-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id_gerencia = $('#modal-abm-gerencia-id').val();
        let nombre = $('#modal-abm-gerencia-nombre').val();
        let sigla = $('#modal-abm-gerencia-sigla').val();
        let responsable = $("#modal-abm-gerencia-responsable").val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmgerenciadb.php',
            data: {
                operacion: op,
                id: id_gerencia,
                nombre: nombre,
                sigla: sigla,
                responsable: responsable
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-gerencia").modal("hide");
                refreshGerencias();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmGerenciaLimpiarCampos() {
        $('#modal-abm-gerencia-id').val(0);
        $('#modal-abm-gerencia-nombre').val('');
        $('#modal-abm-gerencia-sigla').val('');
        $("#modal-abm-gerencia-responsable").val('first').change();
    }
    // ********************************************************************************************


    // ********************************************************************************************
    // SUB GERENCIAS
    // ********************************************************************************************
    function setSubGerenciaTriggers(idgerencia) {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-subgerencia-btn-alta').click(function() {
            $('#modal-abm-subgerencia-title').html('Nueva SubGerencia');
            modalAbmSubGerenciaLimpiarCampos(idgerencia);
            $('#modal-abm-subgerencia-submit').attr('name', 'A');

            $("#modal-abm-subgerencia").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-subgerencia-btn-edit').click(function() {
            $('#modal-abm-subgerencia-title').html('Editar SubGerencia');
            modalAbmSubGerenciaLimpiarCampos(idgerencia);

            $('#modal-abm-subgerencia-id').val($(this).data('id'));
            $('#modal-abm-subgerencia-nombre').val($(this).data('nombre'));
            $('#modal-abm-subgerencia-sigla').val($(this).data('sigla'));
            $("#modal-abm-subgerencia-responsable").val($(this).data('responsable')).change();


            $('#modal-abm-subgerencia-submit').attr('name', 'M');

            $("#modal-abm-subgerencia").modal("show");
        });
    }

    // refresh tables
    function refreshSubGerencias(idgerencia) {
        // Limpio tablas
        tbsubCategorias.DataTable().clear().draw();
        tbProductos.DataTable().clear().draw();

        // Seteo el id de gerencia seleccionado
        $('#modal-abm-subgerencia-btn-alta').attr('id_gerencia', idgerencia);

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT g.id_subgerencia as id, g.sigla, g.responsable, g.nombre, CONCAT(p.apellido, " ", p.nombre) as responsableNombre FROM subgerencia as g LEFT JOIN persona as p ON g.responsable = p.id_persona WHERE id_gerencia =' + idgerencia },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbsubCategorias, 'modal-abm-subgerencia-btn-edit');
                setSubGerenciaTriggers(idgerencia);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    }

    // ==============================================================
    // GUARDAR SUBGERENCIAS
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-subgerencia-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id_gerencia = $('#modal-abm-subgerencia-id-gerencia').val();
        let id_subgerencia = $('#modal-abm-subgerencia-id').val();
        let nombre = $('#modal-abm-subgerencia-nombre').val();
        let sigla = $('#modal-abm-subgerencia-sigla').val();
        let responsable = $("#modal-abm-subgerencia-responsable").val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmsubgerenciadb.php',
            data: {
                operacion: op,
                id_gerencia: id_gerencia,
                id: id_subgerencia,
                nombre: nombre,
                sigla: sigla,
                responsable: responsable
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-subgerencia").modal("hide");
                refreshSubGerencias(id_gerencia);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmSubGerenciaLimpiarCampos(idgerencia) {
        $("#modal-abm-subgerencia-id-gerencia").val(idgerencia).change();
        $("#modal-abm-subgerencia-id-gerencia").attr('disabled', 'disabled')
        $('#modal-abm-subgerencia-id').val(0);
        $('#modal-abm-subgerencia-nombre').val('');
        $('#modal-abm-subgerencia-sigla').val('');
        $("#modal-abm-subgerencia-responsable").val('first').change();
    }
    // ********************************************************************************************


    // ********************************************************************************************
    // AREAS
    // ********************************************************************************************
    function setAreaTriggers(idsubgerencia) {
        // ALTA
        console.log('SETTRR', idsubgerencia);
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-area-btn-alta').click(function() {
            $('#modal-abm-area-title').html('Nueva Área');
            modalAbmAreaLimpiarCampos(idsubgerencia);
            $('#modal-abm-area-submit').attr('name', 'A');

            $("#modal-abm-area").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-area-btn-edit').click(function() {
            $('#modal-abm-area-title').html('Editar Área');
            modalAbmAreaLimpiarCampos(idsubgerencia);

            $('#modal-abm-area-id').val($(this).data('id'));
            $('#modal-abm-area-nombre').val($(this).data('nombre'));
            $('#modal-abm-area-sigla').val($(this).data('sigla'));
            $("#modal-abm-area-responsable").val($(this).data('responsable')).change();


            $('#modal-abm-area-submit').attr('name', 'M');

            $("#modal-abm-area").modal("show");
        });
    }

    // refresh tables
    function refreshAreas(idsubgerencia) {

        console.log('refresh', idsubgerencia);
        // Limpio tablas
        tbProductos.DataTable().clear().draw();

        // Seteo el id de gerencia seleccionado
        $('#modal-abm-area-btn-alta').attr('id_subgerencia', idsubgerencia);

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT g.id_subgerencia as id, g.sigla, g.responsable, g.nombre, CONCAT(p.apellido, " ", p.nombre) as responsableNombre FROM area as g LEFT JOIN persona as p ON g.responsable = p.id_persona WHERE id_subgerencia =' + idsubgerencia },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbProductos, 'modal-abm-area-btn-edit');
                setAreaTriggers(idsubgerencia);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    }

    // ==============================================================
    // GUARDAR AREA
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-area-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id_subgerencia = $('#modal-abm-area-id-subgerencia').val();
        let id_area = $('#modal-abm-area-id').val();
        let nombre = $('#modal-abm-area-nombre').val();
        let sigla = $('#modal-abm-area-sigla').val();
        let responsable = $("#modal-abm-area-responsable").val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmareadb.php',
            data: {
                operacion: op,
                id_subgerencia: id_subgerencia,
                id: id_area,
                nombre: nombre,
                sigla: sigla,
                responsable: responsable
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-area").modal("hide");
                refreshAreas(id_subgerencia);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmAreaLimpiarCampos(idsubgerencia) {
        $("#modal-abm-area-id-subgerencia").val(idsubgerencia).change();
        $("#modal-abm-area-id-subgerencia").attr('disabled', 'disabled')
        $('#modal-abm-area-id').val(0);
        $('#modal-abm-area-nombre').val('');
        $('#modal-abm-area-sigla').val('');
        $("#modal-abm-area-responsable").val('first').change();
    }
    // ********************************************************************************************

});