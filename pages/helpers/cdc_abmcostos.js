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
                    $('<a title="Agregar sub-categoría"></a>')
                    .append($('<i class="fa fa-plus"></i>'))
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
                    $('<a title="Agregar item"></a>')
                    .append($('<i class="fa fa-plus"></i>'))
                )
            );
        let cat_container = $('<div class="list-group collapse" id="' + catIdHtml + '"></div>');

        parent.append(cat);
        parent.append(cat_container);
        return cat_container;
    }

    function addSubCatItem(cat_item, parent) {
        let itemID = cat_item.id;

        let itcat = $('<a class="list-group-item producto-servicio"></a>')
            .append(cat_item.descripcion);
        itcat.attr('data-id', cat_item.id);
        parent.append(itcat);

        return itcat;
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

            $('.action-item').on('click', function() {
                $('.glyphicon', this)
                    .toggleClass('glyphicon-chevron-right')
                    .toggleClass('glyphicon-chevron-down');
            });

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
        // ALTA
        // seteo boton trigger para el alta de gerencia
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
            let rowID = $(this).data('row');
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
    }


    // ==============================================================
    // GUARDAR COSTEO
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-costodet-submit').click(function() {
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
    // ********************************************************************************************


    // ==============================================================
    // TABLE FUNCTIONS
    // ==============================================================
    let strquery = 'SELECT cd.id, cd.id_costo, cd.id_costo_item, cd.costo_usd, cd.cantidad, cd.costo_unica_vez, cd.costo_recurrente,';
    strquery += 'ci.descripcion as descripcion, ci.unidad as unidad, ';
    strquery += 'cat.descripcion as categoria, cat.id as cat_id,';
    strquery += 'subcat.descripcion as subcategoria, subcat.id as subcat_id ';
    strquery += 'FROM cdc_costos_detalle as cd ';
    strquery += 'INNER JOIN cdc_costos_items as ci ON cd.id_costo_item = ci.id ';
    strquery += 'INNER JOIN cdc_costos_items as subcat ON ci.parent = subcat.id ';
    strquery += 'INNER JOIN cdc_costos_items as cat ON subcat.parent = cat.id ';
    strquery += 'WHERE cd.borrado = 0;';

    var tbCosteos = $('#costeo').DataTable({
        "scrollY": 400,
        "scrollX": true,
        "paging": true,
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
        'dom': 'Bfrtip',
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

    tbCosteos.on('draw', function() {
        setAMBCosteoTriggers();
    });
    setAMBCosteoTriggers();


});