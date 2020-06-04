$(function() {

    function addCategory_orig(categoria, parent) {
        let catId = categoria.id;
        let catIdHtml = 'modal-abm-costos-cat-' + catId;

        let cat = $('<a href="#' + catIdHtml + '" class="list-group-item categorias" data-toggle="collapse"></a>')
            .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
            .append(categoria.descripcion);
        let cat_container = $('<div class="list-group collapse" id="' + catIdHtml + '"></div>');

        parent.append(cat);
        parent.append(cat_container);
        return cat_container;
    }

    function addCategory(categoria, parent) {
        let catId = categoria.id;
        let catIdHtml = 'modal-abm-costos-cat-' + catId;

        let cat = $('<div class="list-group-item categorias row" data-toggle="collapse"></div>')
            .append(
                $('<div class="col-md-1"></div>').append(
                    $('<a class="action-item" data-toggle="collapse" data-target="#' + catIdHtml + '"></a>')
                    .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
                )
            )
            .append(
                $('<div class="col-md-9"></div>').append(categoria.descripcion)
            )
            .append(
                $('<div class="col-md-1"></div>').append(
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
                $('<div class="col-md-1"></div>').append(
                    $('<a class="action-item" data-toggle="collapse" data-target="#' + catIdHtml + '"></a>')
                    .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
                )
            )
            .append(
                $('<div class="col-md-9"></div>').append(subcat.descripcion)
            )
            .append(
                $('<div class="col-md-1"></div>').append(
                    $('<a title="Agregar item"></a>')
                    .append($('<i class="fa fa-plus"></i>'))
                )
            );
        let cat_container = $('<div class="list-group collapse" id="' + catIdHtml + '"></div>');

        parent.append(cat);
        parent.append(cat_container);
        return cat_container;
    }

    function addSubCategory_orig(subcat, parent) {
        let subcatID = subcat.id;
        let scatIdHtml = 'modal-abm-costos-scat-' + subcatID;

        let scat = $('<a href="#' + scatIdHtml + '" class="list-group-item sub-categorias" data-toggle="collapse"></a>')
            .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
            .append(subcat.descripcion);
        let scat_container = $('<div class="list-group collapse" id="' + scatIdHtml + '"></div>');
        parent.append(scat);
        parent.append(scat_container);
        return scat_container;
    }

    function addSubCatItem(cat_item, parent) {
        let itemID = cat_item.id;

        let itcat = $('<a class="list-group-item producto-servicio"></a>')
            .append(cat_item.descripcion);
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
        }
    ).fail(function(jqXHR, errorText) {
        console.log(errorText);
    });



});