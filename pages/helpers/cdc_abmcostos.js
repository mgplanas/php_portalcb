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
    // ********************************************************************************************
    // Actualiza el form con los datos del item seleccionado
    function loadItemCosteo(id, callback) {
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
                var producto = response.data[0];
                item_costeo = producto;
                $('#modal-abm-costodet-title').html(producto.categoria + ' <small>[' + producto.subcategoria + ']</small>');
                $('#modal-abm-costodet-unidad').html('<strong>Unidad: </strong>' + producto.unidad);
                $('#modal-abm-costodet-producto').html('<strong>Producto: </strong>' + producto.descripcion);
                $('#modal-abm-costodet-costo').val(producto.costo_unidad);
                $('#modal-abm-costodet-cantidad').val(1);
                $('#modal-abm-costodet-costo-recurrente').val(producto.costo_unidad);
                return callback(null);
            }
        ).fail(function(jqXHR, errorText) {
            item_costeo = null;
            console.log(errorText);
            return callback(errorText);
        });

    }

    function setAMBCosteoTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('.producto-servicio').click(function() {
            $('#modal-abm-costodet-title').html('Agregar costeo de Producto/Servicio');
            modalAbmCosteoLimpiarCampos();
            $('#modal-abm-costodet-id-costo-item').val($(this).data('id'));

            loadItemCosteo($(this).data('id'), function(err) {
                if (err) return alert(err);

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

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-costodet-btn-edit').click(function() {
            $('#modal-abm-costodet-title').html('Editar Organismo');
            modalAbmCosteoLimpiarCampos();
            $('#modal-abm-costodet-id').val($(this).data('id'));

            $('#modal-abm-costodet-submit').attr('name', 'M');

            $("#modal-abm-costodet").modal("show");
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
        let o = item_costeo;
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
                $('#costeo').dataTable().fnAddData([{
                    "categoria": item_costeo.categoria,
                    "subcategoria": item_costeo.subcategoria,
                    "descripcion": item_costeo.descripcion,
                    "unidad": item_costeo.unidad,
                    "costo_usd": costo_usd,
                    "cantidad": cantidad,
                    "costo_unica_vez": costo_unica_vez,
                    "costo_recurrente": costo_recurrente
                }]);
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
        $('#modal-abm-costodet-id').val(0);
        $('#modal-abm-costodet-nombre').val('');
        $('#modal-abm-costodet-sigla').val('');
        $('#modal-abm-costodet-cuit').val('');
        $('#opt-sector-publico').prop("checked", true);
    }
    // ********************************************************************************************

    setAMBCosteoTriggers();


});