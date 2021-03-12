$(function() {
    // ==============================================================
    // Configuracion de las tablas
    // ==============================================================
    // categoriaS
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

    // SUBcategoriaS
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
    // SELECCION categoria
    $('#tbCategorias tbody').on('click', 'tr', function() {

        //Extraigo el id de la data de la row
        let idcategoria = tbCategoriasDT.row(this).data()[0];
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

        //Populo las subcategorias
        refreshSubcategorias(idcategoria);
    });

    // SELECCION EN SUBcategoria
    $('#tbsubCategorias tbody').on('click', 'tr', function() {

        let idsubcategoria = tbsubCategoriasDT.row(this).data()[0];
        // let idsubcategoria = $(this).data('id');
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
        refreshAreas(idsubcategoria);
    });

    refreshcategorias();

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
    // categoriaS
    // ********************************************************************************************
    function setcategoriaTriggers() {
        // ALTA
        // seteo boton trigger para el alta de categoria
        $('#modal-abm-categoria-btn-alta').click(function() {
            $('#modal-abm-categoria-title').html('Nueva categoria');
            modalAbmcategoriaLimpiarCampos();
            $('#modal-abm-categoria-submit').attr('name', 'A');

            $("#modal-abm-categoria").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de categoria
        $('.modal-abm-categoria-btn-edit').click(function() {
            $('#modal-abm-categoria-title').html('Editar categoria');
            modalAbmcategoriaLimpiarCampos();

            $('#modal-abm-categoria-id').val($(this).data('id'));
            $('#modal-abm-categoria-nombre').val($(this).data('nombre'));
            $('#modal-abm-categoria-sigla').val($(this).data('sigla'));
            $("#modal-abm-categoria-responsable").val($(this).data('responsable')).change();


            $('#modal-abm-categoria-submit').attr('name', 'M');

            $("#modal-abm-categoria").modal("show");
        });
    }

    // refresh tables
    function refreshcategorias() {
        // Limpio tablas
        tbCategorias.DataTable().clear().draw();
        tbsubCategorias.DataTable().clear().draw();
        tbProductos.DataTable().clear().draw();

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT g.id_categoria as id, g.sigla, g.responsable, g.nombre, CONCAT(p.apellido, " ", p.nombre) as responsableNombre FROM categoria as g LEFT JOIN persona as p ON g.responsable = p.id_persona WHERE g.borrado = 0' },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbCategorias, 'modal-abm-categoria-btn-edit');
                setcategoriaTriggers();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    }

    // ==============================================================
    // GUARDAR categoriaS
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-categoria-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id_categoria = $('#modal-abm-categoria-id').val();
        let nombre = $('#modal-abm-categoria-nombre').val();
        let sigla = $('#modal-abm-categoria-sigla').val();
        let responsable = $("#modal-abm-categoria-responsable").val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmcategoriadb.php',
            data: {
                operacion: op,
                id: id_categoria,
                nombre: nombre,
                sigla: sigla,
                responsable: responsable
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-categoria").modal("hide");
                refreshcategorias();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmcategoriaLimpiarCampos() {
        $('#modal-abm-categoria-id').val(0);
        $('#modal-abm-categoria-nombre').val('');
        $('#modal-abm-categoria-sigla').val('');
        $("#modal-abm-categoria-responsable").val('first').change();
    }
    // ********************************************************************************************


    // ********************************************************************************************
    // SUB categoriaS
    // ********************************************************************************************
    function setSubcategoriaTriggers(idcategoria) {
        // ALTA
        // seteo boton trigger para el alta de categoria
        $('#modal-abm-subcategoria-btn-alta').click(function() {
            $('#modal-abm-subcategoria-title').html('Nueva Subcategoria');
            modalAbmSubcategoriaLimpiarCampos(idcategoria);
            $('#modal-abm-subcategoria-submit').attr('name', 'A');

            $("#modal-abm-subcategoria").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de categoria
        $('.modal-abm-subcategoria-btn-edit').click(function() {
            $('#modal-abm-subcategoria-title').html('Editar Subcategoria');
            modalAbmSubcategoriaLimpiarCampos(idcategoria);

            $('#modal-abm-subcategoria-id').val($(this).data('id'));
            $('#modal-abm-subcategoria-nombre').val($(this).data('nombre'));
            $('#modal-abm-subcategoria-sigla').val($(this).data('sigla'));
            $("#modal-abm-subcategoria-responsable").val($(this).data('responsable')).change();


            $('#modal-abm-subcategoria-submit').attr('name', 'M');

            $("#modal-abm-subcategoria").modal("show");
        });
    }

    // refresh tables
    function refreshSubcategorias(idcategoria) {
        // Limpio tablas
        tbsubCategorias.DataTable().clear().draw();
        tbProductos.DataTable().clear().draw();

        // Seteo el id de categoria seleccionado
        $('#modal-abm-subcategoria-btn-alta').attr('id_categoria', idcategoria);

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT g.id_subcategoria as id, g.sigla, g.responsable, g.nombre, CONCAT(p.apellido, " ", p.nombre) as responsableNombre FROM subcategoria as g LEFT JOIN persona as p ON g.responsable = p.id_persona WHERE id_categoria =' + idcategoria },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbsubCategorias, 'modal-abm-subcategoria-btn-edit');
                setSubcategoriaTriggers(idcategoria);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    }

    // ==============================================================
    // GUARDAR SUBcategoriaS
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-subcategoria-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id_categoria = $('#modal-abm-subcategoria-id-categoria').val();
        let id_subcategoria = $('#modal-abm-subcategoria-id').val();
        let nombre = $('#modal-abm-subcategoria-nombre').val();
        let sigla = $('#modal-abm-subcategoria-sigla').val();
        let responsable = $("#modal-abm-subcategoria-responsable").val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/abmsubcategoriadb.php',
            data: {
                operacion: op,
                id_categoria: id_categoria,
                id: id_subcategoria,
                nombre: nombre,
                sigla: sigla,
                responsable: responsable
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-subcategoria").modal("hide");
                refreshSubcategorias(id_categoria);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmSubcategoriaLimpiarCampos(idcategoria) {
        $("#modal-abm-subcategoria-id-categoria").val(idcategoria).change();
        $("#modal-abm-subcategoria-id-categoria").attr('disabled', 'disabled')
        $('#modal-abm-subcategoria-id').val(0);
        $('#modal-abm-subcategoria-nombre').val('');
        $('#modal-abm-subcategoria-sigla').val('');
        $("#modal-abm-subcategoria-responsable").val('first').change();
    }
    // ********************************************************************************************


    // ********************************************************************************************
    // AREAS
    // ********************************************************************************************
    function setAreaTriggers(idsubcategoria) {
        // ALTA
        console.log('SETTRR', idsubcategoria);
        // seteo boton trigger para el alta de categoria
        $('#modal-abm-area-btn-alta').click(function() {
            $('#modal-abm-area-title').html('Nueva Área');
            modalAbmAreaLimpiarCampos(idsubcategoria);
            $('#modal-abm-area-submit').attr('name', 'A');

            $("#modal-abm-area").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de categoria
        $('.modal-abm-area-btn-edit').click(function() {
            $('#modal-abm-area-title').html('Editar Área');
            modalAbmAreaLimpiarCampos(idsubcategoria);

            $('#modal-abm-area-id').val($(this).data('id'));
            $('#modal-abm-area-nombre').val($(this).data('nombre'));
            $('#modal-abm-area-sigla').val($(this).data('sigla'));
            $("#modal-abm-area-responsable").val($(this).data('responsable')).change();


            $('#modal-abm-area-submit').attr('name', 'M');

            $("#modal-abm-area").modal("show");
        });
    }

    // refresh tables
    function refreshAreas(idsubcategoria) {

        console.log('refresh', idsubcategoria);
        // Limpio tablas
        tbProductos.DataTable().clear().draw();

        // Seteo el id de categoria seleccionado
        $('#modal-abm-area-btn-alta').attr('id_subcategoria', idsubcategoria);

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT g.id_subcategoria as id, g.sigla, g.responsable, g.nombre, CONCAT(p.apellido, " ", p.nombre) as responsableNombre FROM area as g LEFT JOIN persona as p ON g.responsable = p.id_persona WHERE id_subcategoria =' + idsubcategoria },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbProductos, 'modal-abm-area-btn-edit');
                setAreaTriggers(idsubcategoria);
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
        let id_subcategoria = $('#modal-abm-area-id-subcategoria').val();
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
                id_subcategoria: id_subcategoria,
                id: id_area,
                nombre: nombre,
                sigla: sigla,
                responsable: responsable
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-area").modal("hide");
                refreshAreas(id_subcategoria);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmAreaLimpiarCampos(idsubcategoria) {
        $("#modal-abm-area-id-subcategoria").val(idsubcategoria).change();
        $("#modal-abm-area-id-subcategoria").attr('disabled', 'disabled')
        $('#modal-abm-area-id').val(0);
        $('#modal-abm-area-nombre').val('');
        $('#modal-abm-area-sigla').val('');
        $("#modal-abm-area-responsable").val('first').change();
    }
    // ********************************************************************************************

});