$(function() {

    var item_costeo;
    // ==============================================================
    // Gestion categorias DOM
    // ==============================================================
    function addCategory(categoria, parent) {
        let catId = categoria.id;
        let catIdHtml = 'modal-abm-costos-cat-' + catId;

        let cat = $('<div class="list-group-item categorias row" data-toggle="collapse"></div>')
            .append(
                $('<div class="col-sm-1"></div>').append(
                    $('<a class="action-item" data-toggle="collapse" data-target="#' + catIdHtml + '"></a>')
                    .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
                )
            )
            .append(
                $('<div class="col-sm-8"></div>').append(categoria.descripcion)
            )
            .append(
                $('<div class="col-sm-1"></div>').append(
                    $('<a data-categoria="' + catId + '" title="Agregar sub-categoría" class="modal-abm-costos-subcat-add"></a>')
                    .append($('<i class="fa fa-plus-square"></i>'))
                )
            );
        let cat_container = $('<div class="list-group collapse" id="' + catIdHtml + '"></div>');

        parent.append(cat);
        parent.append(cat_container);
        return cat_container;
    }

    function addSubCategory(subcat, parent) {
        let catId = subcat.id;
        let catIdHtml = 'modal-abm-costos-scat-' + catId;

        let cat = $('<div class="list-group-item sub-categorias row" data-toggle="collapse"></div>')
            .append(
                $('<div class="col-sm-1"></div>').append(
                    $('<a class="action-item" data-toggle="collapse" data-target="#' + catIdHtml + '"></a>')
                    .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
                )
            )
            .append(
                $('<div class="col-sm-8"></div>').append(subcat.descripcion)
            )
            .append(
                $('<div class="col-sm-1"></div>').append(
                    $('<a data-subcatdes="' + subcat.descripcion + '" data-subcategoria="' + catId + '" title="Agregar item" class="modal-abm-costos-item-add"></a>')
                    .append($('<i class="fa fa-plus-square"></i>'))
                )
            );
        let cat_container = $('<div class="list-group collapse" id="' + catIdHtml + '"></div>');

        parent.append(cat);
        parent.append(cat_container);
        return cat_container;
    }

    function addSubCatItem(cat_item, parent) {
        let itemID = cat_item.id;

        let it = $('<div class="list-group-item row"></div>')
            .append($('<div class="col-sm-10"></div>').append(cat_item.descripcion))
            .append(
                $('<div class="col-sm-2 align-middle"></div>')
                .append(
                    $('<a title="Agregar a la planilla de costeo" class="producto-servicio"></a>').attr('data-id', cat_item.id)
                    .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
                )
            );

        let itcat = $('<a class="list-group-item producto-servicio"></a>')
            .append(cat_item.descripcion);
        itcat.attr('data-id', cat_item.id);
        parent.append(it);

        return it;
    }

    //Populo los campos
    $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM cdc_costos_items WHERE borrado = 0 ORDER BY nivel, id ' },
        function(response) {
            let container = $('#modal-abm-costos-categorias-card');

            let n1 = response.data.filter(v => v.nivel == 1);
            n1.forEach(categoria => {

                let cat_container = addCategory(categoria, container);

                let n2 = response.data.filter(v => v.nivel == 2 && v.parent == categoria.id);
                n2.forEach(subcat => {

                    let scat_container = addSubCategory(subcat, cat_container);

                    let n3 = response.data.filter(v => v.nivel == 3 && v.parent == subcat.id);
                    n3.forEach(cat_item => {

                        let cat_it = addSubCatItem(cat_item, scat_container);
                    });
                });
            });

            $('#modal-abm-costos-categorias').prepend(
                $('<div class="text-right"></div>')
                .append('Nueva Categoría')
                .append(
                    $('<a type="button" id="modal-abm-costos-cat-add" class="btn"></a>')
                    .append($('<i class="fa fa-plus-square primary"></i>'))
                )
            );

            setAMBCosteoTriggers();
        }
    ).fail(function(jqXHR, errorText) {
        console.log(errorText);
    });

    // ********************************************************************************************
    // MODAL DE AGREGADO DE PRODUCTOS AL COSTEO
    // (err, item) => error, item (cdc_costos_items)
    // ********************************************************************************************
    // carga el item_costo de la base
    function findItemCosteo(id, callback) {
        let strquery = 'SELECT ci.id, ci.descripcion, ci.unidad, ci.costo_unidad,';
        strquery += 'cat.descripcion as categoria, cat.id as cat_id,';
        strquery += 'subcat.descripcion as subcategoria, subcat.id as subcat_id ';
        strquery += 'FROM cdc_costos_items as ci ';
        strquery += 'INNER JOIN cdc_costos_items as subcat ON ci.parent = subcat.id ';
        strquery += 'INNER JOIN cdc_costos_items as cat ON subcat.parent = cat.id ';
        strquery += 'WHERE ci.id = ' + id;
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: strquery },
            function(response) {
                if (!response.data || !response.data[0]) {
                    return callback('No hay datos');
                }
                return callback(null, response.data[0]);
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
            return callback(errorText);
        });

    }
    // carga el item_costo de la base
    function findDetalleCosteo(id, callback) {
        let strquery = 'SELECT cd.id, cd.id_costo, cd.id_costo_item, cd.costo_usd as costo_unidad, cd.cantidad, cd.costo_unica_vez, cd.costo_recurrente,';
        strquery += 'ci.descripcion as descripcion, ci.unidad as unidad, ';
        strquery += 'cat.descripcion as categoria, cat.id as cat_id,';
        strquery += 'subcat.descripcion as subcategoria, subcat.id as subcat_id ';
        strquery += 'FROM cdc_costos_detalle as cd ';
        strquery += 'INNER JOIN cdc_costos_items as ci ON cd.id_costo_item = ci.id ';
        strquery += 'INNER JOIN cdc_costos_items as subcat ON ci.parent = subcat.id ';
        strquery += 'INNER JOIN cdc_costos_items as cat ON subcat.parent = cat.id ';
        strquery += 'WHERE cd.id = ' + id;
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: strquery },
            function(response) {
                if (!response.data || !response.data[0]) {
                    return callback('No hay datos');
                }
                return callback(null, response.data[0]);
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
            return callback(errorText);
        });

    }

    function setAMBCosteoTriggers() {
        // ==============================================================
        // GESTION DE ITEMS DE COSTEO
        // ==============================================================
        // ALTA
        $('.producto-servicio').off('click').on('click', function() {
            $('#modal-abm-costodet-title').html('Agregar costeo de Producto/Servicio');
            modalAbmCosteoLimpiarCampos();
            $('#modal-abm-costodet-id-costo-item').val($(this).data('id'));

            findItemCosteo($(this).data('id'), function(err, item) {
                if (err) return alert(err);

                item_costeo = item;
                $('#modal-abm-costodet-title').html(item_costeo.categoria + ' <small>[' + item_costeo.subcategoria + ']</small>');
                $('#modal-abm-costodet-unidad').html('<strong>Unidad: </strong>' + item_costeo.unidad);
                $('#modal-abm-costodet-producto').html('<strong>Producto: </strong>' + item_costeo.descripcion);
                $('#modal-abm-costodet-costo').val(item_costeo.costo_unidad);
                $('#modal-abm-costodet-cantidad').val(1);
                $('#modal-abm-costodet-costo-recurrente').val(item_costeo.costo_unidad);

                $('#modal-abm-costodet-submit').attr('name', 'A');
                $("#modal-abm-costodet").modal("show");
            });
        });

        //cambio de costo/cantidad
        $('#modal-abm-costodet-costo, #modal-abm-costodet-cantidad').change(function() {
            let cantidad = $('#modal-abm-costodet-cantidad').val();
            let costo = $('#modal-abm-costodet-costo').val();
            $('#modal-abm-costodet-costo-recurrente').val(costo * cantidad);
        });

        // BORRAR
        $('.modal-abm-costodet-btn-baja').off('click').on('click', function(event) {
            event.stopPropagation();
            let id = $(this).data('id');
            let tr = $(this).closest('tr');
            let descripcion = $(this).data('descripcion');
            if (confirm('¿Está seguro que desea eliminar el costeo de ' + descripcion + '?')) {
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/cdc_abmcostosdetdb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        $('#costeo').dataTable().fnDeleteRow(tr);
                        item_costeo = null;
                        return;
                    },
                    error: function(xhr, status, error) {
                        item_costeo = null;
                        return alert(xhr.responseText, error);
                    }
                });

            }
        });
        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-costodet-btn-edit').off('click').on('click', function() {
            modalAbmCosteoLimpiarCampos();
            $('#modal-abm-costodet-id').val($(this).data('id'));
            $('#modal-abm-costodet-row-id').val($(this).data('row'));

            findDetalleCosteo($(this).data('id'), function(err, item) {
                if (err) return alert(err);

                item_costeo = {
                    id: item.id_costo_item,
                    descripcion: item.descripcion,
                    unidad: item.unidad,
                    costo_unidad: item.costo_unidad,
                    categoria: item.categoria,
                    subcategoria: item.subcategoria,
                };
                $('#modal-abm-costodet-id-costo-item').val(item.id_costo_item);
                $('#modal-abm-costodet-title').html(item.categoria + ' <small>[' + item.subcategoria + ']</small>');
                $('#modal-abm-costodet-unidad').html('<strong>Unidad: </strong>' + item.unidad);
                $('#modal-abm-costodet-producto').html('<strong>Producto: </strong>' + item.descripcion);
                $('#modal-abm-costodet-costo').val(item.costo_unidad);
                $('#modal-abm-costodet-costo-ot').val(item.costo_unica_vez);
                $('#modal-abm-costodet-cantidad').val(item.cantidad);
                $('#modal-abm-costodet-costo-recurrente').val(item.costo_recurrente);

                $('#modal-abm-costodet-submit').attr('name', 'M');
                $("#modal-abm-costodet").modal("show");
            });
        });


        // ==============================================================
        // CATEGORIAS / ITEMS
        // ==============================================================
        $('.action-item').off('click').on('click', function() {
            $('.glyphicon', this)
                .toggleClass('glyphicon-chevron-right')
                .toggleClass('glyphicon-chevron-down');
        });
        // ADD SUB CATEGORY
        $('.modal-abm-costos-subcat-add').off('click').on('click', function(event) {
            let catID = $(this).data('categoria');
            let subcat = prompt('Nombre de la SubCategoría:', '');
            let cat_container = $('#modal-abm-costos-cat-' + catID);
            if (subcat) {
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/cdc_abmcostositemdb.php',
                    data: {
                        operacion: 'A',
                        id: 0,
                        parent: catID,
                        nivel: 2,
                        descripcion: subcat
                    },
                    dataType: 'json',
                    success: function(json) {
                        addSubCategory(json, cat_container);
                        setAMBCosteoTriggers();
                        return;
                    },
                    error: function(xhr, status, error) {
                        item_costeo = null;
                        return alert(xhr.responseText, error);
                    }
                });
            }
        });
        // ADD ITEM MODAL
        $('.modal-abm-costos-item-add').off('click').on('click', function(event) {

            modalAbmCosteoItemLimpiarCampos();
            $('#modal-abm-costoitem-subcat-id').val($(this).data('subcategoria'));
            $('#modal-abm-costoitem-id').val(0);
            $('#modal-abm-costoitem-title').html('Agregar nuevo Producto/Servicio');
            $('#modal-abm-costoitem-subcat').html($(this).data('subcatdes'));
            $('#modal-abm-costoitem').modal("show");
        });
        // ADD ITEM
        $('#modal-abm-costoitem-submit').off('click').on('click', function(event) {
            let subcatID = $('#modal-abm-costoitem-subcat-id').val();
            let container = $('#modal-abm-costos-scat-' + subcatID);
            let producto = $('#modal-abm-costoitem-descripcion').val();
            let unidad = $('#modal-abm-costoitem-unidad').val();
            let costo = $('#modal-abm-costoitem-costo').val();
            if (producto) {
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/cdc_abmcostositemdb.php',
                    data: {
                        operacion: 'A',
                        id: 0,
                        parent: subcatID,
                        nivel: 3,
                        descripcion: producto,
                        unidad: unidad,
                        costo_usd: costo
                    },
                    dataType: 'json',
                    success: function(json) {
                        addSubCatItem(json, container);

                        setAMBCosteoTriggers();
                        $('#modal-abm-costoitem').modal("hide");
                        return;
                    },
                    error: function(xhr, status, error) {
                        item_costeo = null;
                        $('#modal-abm-costoitem').modal("hide");
                        return alert(xhr.responseText, error);
                    }
                });
            }
        });
        // ADD CATEGORY
        $('#modal-abm-costos-cat-add').off('click').on('click', function(event) {
            let cat = prompt('Nombre de la Categoría:', '');
            let container = $('#modal-abm-costos-categorias-card');
            if (cat) {
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/cdc_abmcostositemdb.php',
                    data: {
                        operacion: 'A',
                        id: 0,
                        parent: null,
                        nivel: 1,
                        descripcion: cat
                    },
                    dataType: 'json',
                    success: function(json) {
                        addCategory(json, container);
                        setAMBCosteoTriggers();
                        return;
                    },
                    error: function(xhr, status, error) {
                        item_costeo = null;
                        return alert(xhr.responseText, error);
                    }
                });
            }
        });

        // ==============================================================
        // GUARDAR ITEM DE COSTEO
        // ==============================================================
        // ejecución de guardado async
        $('#modal-abm-costodet-submit').off('click').on(function() {
            // Recupero datos del formulario
            let op = $(this).attr('name');
            let id = $('#modal-abm-costodet-id').val();
            let row_id = $('#modal-abm-costodet-row-id').val();
            let id_costo_item = item_costeo.id;
            let id_costo = $('#modal-abm-costos-id').val();
            let costo_usd = $('#modal-abm-costodet-costo').val();
            let cantidad = $('#modal-abm-costodet-cantidad').val();
            let costo_recurrente = $('#modal-abm-costodet-costo-recurrente').val();
            let costo_unica_vez = $('#modal-abm-costodet-costo-ot').val();
            // Ejecuto
            $.ajax({
                type: 'POST',
                url: './helpers/cdc_abmcostosdetdb.php',
                data: {
                    operacion: op,
                    id: id,
                    id_costo_item: id_costo_item,
                    id_costo: id_costo,
                    costo_usd: costo_usd,
                    cantidad: cantidad,
                    costo_recurrente: costo_recurrente,
                    costo_unica_vez: costo_unica_vez
                },
                dataType: 'json',
                success: function(json) {
                    $("#modal-abm-costodet").modal("hide");
                    if (op == 'A') {
                        $('#costeo').dataTable().fnAddData([{
                            "id": json.id,
                            "id_costo_item": json.id_costo_item,
                            "id_costo": json.id_costo,
                            "categoria": item_costeo.categoria,
                            "subcategoria": item_costeo.subcategoria,
                            "descripcion": item_costeo.descripcion,
                            "unidad": item_costeo.unidad,
                            "costo_usd": costo_usd,
                            "cantidad": cantidad,
                            "costo_unica_vez": costo_unica_vez,
                            "costo_recurrente": costo_recurrente
                        }]);
                    } else {
                        $('#costeo').dataTable().fnUpdate({
                            "id": id,
                            "id_costo_item": id_costo_item,
                            "id_costo": id_costo,
                            "categoria": item_costeo.categoria,
                            "subcategoria": item_costeo.subcategoria,
                            "descripcion": item_costeo.descripcion,
                            "unidad": item_costeo.unidad,
                            "costo_usd": costo_usd,
                            "cantidad": cantidad,
                            "costo_unica_vez": costo_unica_vez,
                            "costo_recurrente": costo_recurrente
                        }, row_id);
                    }
                    item_costeo = null;
                },
                error: function(xhr, status, error) {
                    item_costeo = null;
                    alert(xhr.responseText, error);
                }
            });
        });

        // ==============================================================
        // ALTA / ACTUALIZACION PLANILLA
        // ==============================================================
        // ALTA
        $('#modal-abm-costos-submit').off('click').on('click', function() {
            // Recupero datos del formulario
            let op = $(this).attr('name');
            let id = $('#modal-abm-costos-id').val();
            let cliente = $('#modal-abm-costos-cliente').val();
            let servicio = $('#modal-abm-costos-servicio').val();
            let fecha = $('#modal-abm-costos-fecha').val();
            let meses = $('#modal-abm-costos-meses').val();
            let duracion = $('#modal-abm-costos-dc').val();
            let cm = $('#modal-abm-costos-cm').val();
            let inflacion = $('#modal-abm-costos-inflacion').val();
            let cotizacion_usd = $('#modal-abm-costos-usd').val();
            // Ejecuto
            $.ajax({
                type: 'POST',
                url: './helpers/cdc_abmcostosdb.php',
                data: {
                    operacion: op,
                    id: id,
                    cliente: cliente,
                    servicio: servicio,
                    fecha: fecha,
                    meses_contrato: meses,
                    duracion: duracion,
                    cm: cm,
                    cotizacion_usd: cotizacion_usd,
                    inflacion: inflacion
                },
                dataType: 'json',
                success: function(json) {
                    location.href = 'cdc_abmcostos.php?planilla=' + json.id;
                },
                error: function(xhr, status, error) {
                    item_costeo = null;
                    alert(xhr.responseText, error);
                }
            });
        });
    }



    // ==============================================================
    // AUXILIARES
    // ==============================================================
    function modalAbmCosteoLimpiarCampos() {
        $('#modal-abm-costodet-row-id').val(0);
        $('#modal-abm-costodet-id').val(0);
        $('#modal-abm-costodet-title').html('');
        $('#modal-abm-costodet-unidad').html('');
        $('#modal-abm-costodet-producto').html('');
        $('#modal-abm-costodet-costo').val(0);
        $('#modal-abm-costodet-cantidad').val(1);
        $('#modal-abm-costodet-costo-recurrente').val(0);
    }

    function modalAbmCosteoItemLimpiarCampos() {
        $('#modal-abm-costoitem-subcat-id').val(0);
        $('#modal-abm-costoitem-id').val(0);
        $('#modal-abm-costoitem-title').html('');
        $('#modal-abm-costoitem-unidad').val('');
        $('#modal-abm-costoitem-descripcion').val('');
        $('#modal-abm-costoitem-costo').val(0);
    }

    // ==============================================================
    // TABLE FUNCTIONS
    // ==============================================================
    function createTableCosteo() {
        let id = $('#modal-abm-costos-id').val();
        let strquery = 'SELECT cd.id, cd.id_costo, cd.id_costo_item, cd.costo_usd, cd.cantidad, cd.costo_unica_vez, cd.costo_recurrente,';
        strquery += 'ci.descripcion as descripcion, ci.unidad as unidad, ';
        strquery += 'cat.descripcion as categoria, cat.id as cat_id,';
        strquery += 'subcat.descripcion as subcategoria, subcat.id as subcat_id ';
        strquery += 'FROM cdc_costos_detalle as cd ';
        strquery += 'INNER JOIN cdc_costos_items as ci ON cd.id_costo_item = ci.id ';
        strquery += 'INNER JOIN cdc_costos_items as subcat ON ci.parent = subcat.id ';
        strquery += 'INNER JOIN cdc_costos_items as cat ON subcat.parent = cat.id ';
        strquery += 'WHERE cd.borrado = 0 AND cd.id_costo = ' + id + ';';

        return $('#costeo').DataTable({
            "scrollY": "100vh",
            "scrollX": true,
            "scrollCollapse": true,
            "paging": false,
            "deferRender": true,
            "ajax": {
                type: 'POST',
                url: './helpers/getAsyncDataFromDB.php',
                data: { query: strquery },
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                }
            },
            "dataSrc": function(json) {
                // console.log(json);
            },
            "columns": [
                { "data": "categoria" },
                { "data": "subcategoria" },
                { "data": "descripcion" },
                { "data": "unidad" },
                { "data": "costo_usd" },
                { "data": "cantidad" },
                { "data": "costo_unica_vez" },
                { "data": "costo_recurrente" },
                { "data": "unidad" }
            ],
            'order': [
                [0, 'asc'],
                [1, 'asc']
            ],
            'rowGroup': {
                'dataSrc': ['categoria', 'subcategoria']
            },
            'columnDefs': [{
                    'targets': [0, 1],
                    'visible': false
                },
                {
                    'targets': [-1],
                    'render': function(data, type, row, meta) {

                        return '<a data-row="' + meta.row + '" data-id="' + row.id + '" data-iditem="' + row.id_costo_item + '" data-idcosto="' + row.id_costo + '" title="editar" class="modal-abm-costodet-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit"></i></a>' +
                            '<a data-row="' + meta.row + '" data-id="' + row.id + '" data-descripcion="' + row.descripcion + '" title="eliminar" class="modal-abm-costodet-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash" style="color: red;"></i></a>';
                    }
                }
            ],
            'dom': 'rtipB',
            'buttons': [{
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',

                },
                {
                    extend: 'excel',
                    text: 'Excel',
                }
            ]

        });

    }

    var tbCosteos = createTableCosteo();

    $('#modal-abm-costos-fecha').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });
    tbCosteos.on('draw', function() {
        setAMBCosteoTriggers();
    });

    $('#modal-abm-costos-search-btn').on('click', function() {
        let texto_a_buscar = $('#modal-abm-costos-search-text').val();
        $('#costeo').dataTable().fnFilter(texto_a_buscar);
    });
    $('#modal-abm-costos-search-text').on('keyup', function() {
        let texto_a_buscar = $('#modal-abm-costos-search-text').val();
        $('#costeo').dataTable().fnFilter(texto_a_buscar);
    });
    setAMBCosteoTriggers();


});