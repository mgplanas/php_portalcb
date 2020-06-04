$(function() {


    //Populo los campos
    $.getJSON("./helpers/getAsyncDataFromDB.php", { query: 'SELECT * FROM cdc_costos_items WHERE borrado = 0 ORDER BY nivel, id ' },
        function(response) {
            let container = $('#modal-abm-costos-categorias-card');
            let n1 = response.data.filter(v => v.nivel == 1);
            n1.forEach(categoria => {
                let catId = categoria.id;
                let catIdHtml = 'modal-abm-costos-cat-' + catId;

                let cat = $('<a href="#' + catIdHtml + '" class="list-group-item" data-toggle="collapse"></a>')
                    .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
                    .append(categoria.descripcion);
                let cat_container = $('<div class="list-group collapse" id="' + catIdHtml + '"></div>');

                let n2 = response.data.filter(v => v.nivel == 2 && v.parent == catId);
                n2.forEach(subcat => {
                    let subcatID = subcat.id;
                    let scatIdHtml = 'modal-abm-costos-scat-' + subcatID;

                    let scat = $('<a href="#' + scatIdHtml + '" class="list-group-item" data-toggle="collapse"></a>')
                        .append($('<i class="glyphicon glyphicon-chevron-right"></i>'))
                        .append(subcat.descripcion);
                    let scat_container = $('<div class="list-group collapse" id="' + scatIdHtml + '"></div>');

                    let n3 = response.data.filter(v => v.nivel == 3 && v.parent == subcatID);
                    n3.forEach(cat_item => {
                        let itemID = cat_item.id;

                        let itcat = $('<a class="list-group-item"></a>')
                            .append(cat_item.descripcion);
                        scat_container.append(itcat);
                    });
                    cat_container.append(scat);
                    cat_container.append(scat_container);
                });
                container.append(cat);
                container.append(cat_container);
            });

            $('.list-group-item').on('click', function() {
                $('.glyphicon', this)
                    .toggleClass('glyphicon-chevron-right')
                    .toggleClass('glyphicon-chevron-down');
            });


        }
    ).fail(function(jqXHR, errorText) {
        console.log(errorText);
    });



});