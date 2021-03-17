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
            "targets": [0, 1, 2],
            "visible": false
        }],
        'rowCallback': function(row, data, index) {
            if (data[2] == '1') {
                $(row).css('background-color', 'lightgray');
            } else {
                $(row).css('background-color', 'white');
            }
        }
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
            "targets": [0, 1, 2],
            "visible": false
        }],
        'rowCallback': function(row, data, index) {
            if (data[2] == '1') {
                $(row).css('background-color', 'lightgray');
            } else {
                $(row).css('background-color', 'white');
            }
        }
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
            "targets": [0, 1, 2],
            "visible": false
        }, {
            'targets': [4],
            'className': 'dt-body-center'
        }, {
            'targets': [5],
            'className': 'dt-body-right'
        }],
        'rowCallback': function(row, data, index) {
            if (data[2] == '1') {
                $(row).css('background-color', 'lightgray');
            } else {
                $(row).css('background-color', 'white');
            }
        }
    });

    // ==============================================================
    // EVENTOS
    // ==============================================================
    // SELECCION categoria
    $('#tbCategorias tbody').on('click', 'tr', function() {

        //Extraigo el id de la data de la row
        let idcategoria = tbCategoriasDT.row(this).data()[0];
        let description = tbCategoriasDT.row(this).data()[3];
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
        refreshSubcategorias(idcategoria, description);
    });

    // SELECCION EN SUBcategoria
    $('#tbsubCategorias tbody').on('click', 'tr', function() {

        let idsubcategoria = tbsubCategoriasDT.row(this).data()[0];
        let description = tbsubCategoriasDT.row(this).data()[3];
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
        refreshProductos(idsubcategoria, description);
    });

    refreshcategorias();

    // populate the data table with JSON data
    function populateDataTable(response, table, buttonclass) {
        var length = Object.keys(response.data).length;
        for (var i = 0; i < length; i++) {
            let item = response.data[i];
            // You could also use an ajax property on the data table initialization
            let button = '<div style="display: inline-flex;"><a data-id="' + item.id + '" data-nivel="' + item.nivel + '" data-oculto="' + item.oculto + '"  data-descripcion="' + item.descripcion + '" title="editar" class="' + buttonclass + '-edit btn" style="padding: 5px !important;"><i class="glyphicon glyphicon-edit"></i></a>';
            // if (item.oculto == "1") {
            //     button += '<a data-id="' + item.id + '" data-nivel="' + item.nivel + '"  data-descripcion="' + item.descripcion + '" title="visualizar" class="' + buttonclass + '-display btn" style="padding: 5px !important;"><i class="fa fa-eye-slash"></i></a>';
            // } else {
            //     button += '<a data-id="' + item.id + '" data-nivel="' + item.nivel + '"  data-descripcion="' + item.descripcion + '" title="ocultar" class="' + buttonclass + '-hide btn" style="padding: 5px !important;"><i class="fa fa-eye"></i></a>';
            // }
            button += '<a data-id="' + item.id + '" data-descripcion="' + item.descripcion + '" title="eliminar" class="' + buttonclass + '-delete btn" style="padding: 5px !important;color: red;"><i class="fa fa-trash"></i></a>';
            button += '</div>';
            table.dataTable().fnAddData([
                item.id,
                item.nivel,
                item.oculto,
                item.descripcion,
                button
            ]);
        }
    }
    // populate the data table with JSON data
    function populateDataTableProductos(response, table, buttonclass) {
        var length = Object.keys(response.data).length;
        for (var i = 0; i < length; i++) {
            let item = response.data[i];
            // You could also use an ajax property on the data table initialization
            let button = '<div style="display: inline-flex;"><a data-id="' + item.id + '" data-nivel="' + item.nivel + '"  data-oculto="' + item.oculto + '"  data-descripcion="' + item.descripcion + '"  data-unidad="' + item.unidad + '"  data-costo="' + item.costo_unidad + '" title="editar" class="' + buttonclass + '-edit btn" style="padding: 5px !important;"><i class="glyphicon glyphicon-edit"></i></a>';
            // if (item.oculto == "1") {
            //     button += '<a data-id="' + item.id + '" data-nivel="' + item.nivel + '"  data-descripcion="' + item.descripcion + '"  data-unidad="' + item.unidad + '"  data-costo-unidad="' + item.costo_unidad + '" title="visualizar" class="' + buttonclass + '-display btn"><i class="fa fa-eye-slash"></i></a>';
            // } else {
            //     button += '<a data-id="' + item.id + '" data-nivel="' + item.nivel + '"  data-descripcion="' + item.descripcion + '"  data-unidad="' + item.unidad + '"  data-costo-unidad="' + item.costo_unidad + '" title="ocultar" class="' + buttonclass + '-display btn"><i class="fa fa-eye"></i></a>';
            // }
            button += '<a data-id="' + item.id + '" data-descripcion="' + item.descripcion + '" title="eliminar" class="' + buttonclass + '-delete btn" style="padding: 5px !important;color: red;"><i class="fa fa-trash"></i></a>';
            button += '</div>';
            table.dataTable().fnAddData([
                item.id,
                item.nivel,
                item.oculto,
                item.descripcion,
                item.unidad,
                item.costo_unidad,
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
        $('#modal-abm-categoria-btn-alta').on('click', function() {
            $('#modal-abm-categoria-title').html('Nueva categoria');
            modalAbmcategoriaLimpiarCampos();
            $('#modal-abm-categoria-submit').attr('name', 'A');

            $("#modal-abm-categoria").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de categoria
        $('.modal-abm-categoria-btn-edit').on('click', function(e) {
            e.stopPropagation();
            $('#modal-abm-categoria-title').html('Editar categoria');
            modalAbmcategoriaLimpiarCampos();

            $('#modal-abm-categoria-id').val($(this).data('id'));
            $('#modal-abm-categoria-descripcion').val($(this).data('descripcion'));
            if ($(this).data('oculto') == '1') {
                $('#modal-abm-categoria-oculto').prop("checked", true);
            }

            $('#modal-abm-categoria-submit').attr('name', 'M');

            $("#modal-abm-categoria").modal("show");
        });

        // BORRAR
        // seteo boton trigger para el edit de categoria
        $('.modal-abm-categoria-btn-delete').on('click', function(e) {
            e.stopPropagation();
            let id = $(this).data('id');
            if (confirm('¿Está seguro de eliminar el item: ' + $(this).data('descripcion'))) {
                $.ajax({
                    type: 'POST',
                    url: './helpers/cdc_abmcostositemdb.php',
                    data: {
                        operacion: 'B',
                        id: id,
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
            }
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
            data: { query: 'SELECT id, parent, nivel, descripcion, unidad, costo_unidad, borrado, descripcion_item, observaciones, oculto FROM controls.cdc_costos_items WHERE nivel = 1 and borrado = 0;' },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbCategorias, 'modal-abm-categoria-btn');
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
    $('#modal-abm-categoria-submit').on('click', function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-categoria-id').val();
        let parent = null;
        let nivel = 1;
        let descripcion = $('#modal-abm-categoria-descripcion').val();
        let unidad = '';
        let costo_usd = 0;
        let oculto = 0;
        if ($("#modal-abm-categoria-oculto").is(':checked')) {
            oculto = 1;
        }
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmcostositemdb.php',
            data: {
                operacion: op,
                id: id,
                parent: parent,
                nivel: nivel,
                descripcion: descripcion,
                unidad: unidad,
                costo_usd: costo_usd,
                oculto: oculto
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
        $('#modal-abm-categoria-descripcion').val('');
        $('#modal-abm-categoria-oculto').prop("checked", false);
    }
    // ********************************************************************************************


    // ********************************************************************************************
    // SUB categoriaS
    // ********************************************************************************************
    function setSubcategoriaTriggers(idcategoria, catDescription) {
        // ALTA
        // seteo boton trigger para el alta de categoria
        $('#modal-abm-subcategoria-btn-alta').on('click', function() {
            $('#modal-abm-subcategoria-title').html('Nueva Subcategoria de ' + catDescription);
            modalAbmSubcategoriaLimpiarCampos(idcategoria);
            $('#modal-abm-subcategoria-submit').attr('name', 'A');

            $("#modal-abm-subcategoria").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de categoria
        $('.modal-abm-subcategoria-btn-edit').on('click', function() {
            $('#modal-abm-subcategoria-title').html('Editar Subcategoria');
            modalAbmSubcategoriaLimpiarCampos(idcategoria);

            $('#modal-abm-subcategoria-id').val($(this).data('id'));
            $('#modal-abm-subcategoria-descripcion').val($(this).data('descripcion'));
            if ($(this).data('oculto') == '1') {
                $('#modal-abm-subcategoria-oculto').prop("checked", true);
            }


            $('#modal-abm-subcategoria-submit').attr('name', 'M');

            $("#modal-abm-subcategoria").modal("show");
        });

        // BORRAR
        // seteo boton trigger para el edit de subcategoria
        $('.modal-abm-subcategoria-btn-delete').on('click', function(e) {
            e.stopPropagation();
            let id = $(this).data('id');
            if (confirm('¿Está seguro de eliminar el item: ' + $(this).data('descripcion'))) {
                $.ajax({
                    type: 'POST',
                    url: './helpers/cdc_abmcostositemdb.php',
                    data: {
                        operacion: 'B',
                        id: id,
                    },
                    dataType: 'json',
                    success: function(json) {
                        refreshSubcategorias(idcategoria, catDescription);
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText, error);
                    }
                });
            }
        });
    }

    // refresh tables
    function refreshSubcategorias(idcategoria, description) {
        // Limpio tablas
        tbsubCategorias.DataTable().clear().draw();
        tbProductos.DataTable().clear().draw();

        // Seteo el id de categoria seleccionado
        $('#modal-abm-subcategoria-btn-alta').attr('id_categoria', idcategoria);
        $('#modal-abm-subcategoria-btn-alta').attr('description', description);

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT id, parent, nivel, descripcion, unidad, costo_unidad, borrado, descripcion_item, observaciones, oculto FROM controls.cdc_costos_items WHERE nivel = 2 and borrado = 0 and parent =' + idcategoria },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTable(myJsonData, tbsubCategorias, 'modal-abm-subcategoria-btn');
                setSubcategoriaTriggers(idcategoria, description);
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
    $('#modal-abm-subcategoria-submit').on('click', function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-subcategoria-id').val();
        let parent = $('#modal-abm-subcategoria-id-categoria').val();
        let nivel = 2;
        let descripcion = $('#modal-abm-subcategoria-descripcion').val();
        let unidad = '';
        let costo_usd = 0;
        let oculto = 0;
        if ($("#modal-abm-subcategoria-oculto").is(':checked')) {
            oculto = 1;
        }
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmcostositemdb.php',
            data: {
                operacion: op,
                id: id,
                parent: parent,
                nivel: nivel,
                descripcion: descripcion,
                unidad: unidad,
                costo_usd: costo_usd,
                oculto: oculto
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-subcategoria").modal("hide");
                refreshSubcategorias(parent, $('#modal-abm-subcategoria-btn-alta').attr('description'));
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
        $("#modal-abm-subcategoria-id-categoria").val(idcategoria);
        $('#modal-abm-subcategoria-id').val(0);
        $('#modal-abm-subcategoria-descripcion').val('');
        $('#modal-abm-subcategoria-oculto').prop("checked", false);
    }
    // ********************************************************************************************


    // ********************************************************************************************
    // PRODUCTOS
    // ********************************************************************************************
    function setProductosTriggers(idsubcategoria, subDescripcion) {
        // ALTA
        // seteo boton trigger para el alta de categoria
        $('#modal-abm-producto-btn-alta').on('click', function() {
            $('#modal-abm-producto-title').html('Nuevo producto/servicio de ' + subDescripcion);
            modalAbmProductosLimpiarCampos(idsubcategoria);
            $('#modal-abm-producto-submit').attr('name', 'A');

            $("#modal-abm-producto").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de categoria
        $('.modal-abm-producto-btn-edit').on('click', function() {
            $('#modal-abm-producto-title').html('Editar Producto/Servicio');
            modalAbmProductosLimpiarCampos(idsubcategoria);

            $('#modal-abm-producto-id').val($(this).data('id'));
            $('#modal-abm-producto-descripcion').val($(this).data('descripcion'));
            $('#modal-abm-producto-unidad').val($(this).data('unidad'));
            $('#modal-abm-producto-costo').val($(this).data('costo'));
            if ($(this).data('oculto') == '1') {
                $('#modal-abm-producto-oculto').prop("checked", true);
            }

            $('#modal-abm-producto-submit').attr('name', 'M');

            $("#modal-abm-producto").modal("show");
        });

        // BORRAR
        $('.modal-abm-producto-btn-delete').on('click', function(e) {
            e.stopPropagation();
            let id = $(this).data('id');
            let tr = $(this).closest('tr');
            if (confirm('¿Está seguro de eliminar el item: ' + $(this).data('descripcion'))) {
                $.ajax({
                    type: 'POST',
                    url: './helpers/cdc_abmcostositemdb.php',
                    data: {
                        operacion: 'B',
                        id: id,
                    },
                    dataType: 'json',
                    success: function(json) {
                        //refreshProductos(idsubcategoria, subDescripcion);
                        tbProductos.dataTable().fnDeleteRow(tr);
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText, error);
                    }
                });
            }
        });
    }

    // refresh tables
    function refreshProductos(idsubcategoria, subDescripcion) {

        // Limpio tablas
        tbProductos.DataTable().clear().draw();

        // Seteo el id de categoria seleccionado
        $('#modal-abm-producto-btn-alta').attr('id_subcategoria', idsubcategoria);

        //Populo las areas
        $.ajax({
            type: 'POST',
            url: './helpers/getAsyncDataFromDB.php',
            data: { query: 'SELECT id, parent, nivel, descripcion, unidad, costo_unidad, borrado, descripcion_item, observaciones, oculto FROM controls.cdc_costos_items WHERE nivel = 3 and borrado = 0 and parent =' + idsubcategoria },
            dataType: 'json',
            success: function(json) {
                myJsonData = json;
                populateDataTableProductos(myJsonData, tbProductos, 'modal-abm-producto-btn');
                setProductosTriggers(idsubcategoria, subDescripcion);
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });
    }

    // ==============================================================
    // GUARDAR PRODUCTO
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-producto-submit').on('click', function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-producto-id').val();
        let parent = $('#modal-abm-producto-subcat-id').val();
        let nivel = 3;
        let descripcion = $('#modal-abm-producto-descripcion').val();
        let unidad = $('#modal-abm-producto-unidad').val();
        let costo_usd = $('#modal-abm-producto-costo').val();;
        let oculto = 0;
        if ($("#modal-abm-producto-oculto").is(':checked')) {
            oculto = 1;
        }
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmcostositemdb.php',
            data: {
                operacion: op,
                id: id,
                parent: parent,
                nivel: nivel,
                descripcion: descripcion,
                unidad: unidad,
                costo_usd: costo_usd,
                oculto: oculto
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-producto").modal("hide");
                refreshProductos(parent, $('#modal-abm-producto-btn-alta').attr('description'));
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText, error);
            }
        });

    });

    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmProductosLimpiarCampos(idsubcategoria) {
        $("#modal-abm-producto-title").val('');
        $("#modal-abm-producto-subcat").val('');
        $("#modal-abm-producto-subcat-id").val(idsubcategoria);
        $('#modal-abm-producto-id').val(0);
        $('#modal-abm-producto-descripcion').val('');
        $('#modal-abm-producto-unidad').val('');
        $('#modal-abm-producto-costo').val(0);
        $('#modal-abm-producto-oculto').prop("checked", false);
    }
    // ********************************************************************************************

});