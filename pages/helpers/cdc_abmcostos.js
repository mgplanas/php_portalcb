$(function() {

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
    function setAMBCosteoTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('.producto-servicio').click(function() {
            $('#modal-abm-costodet-title').html('Agregar costeo de Producto/Servicio');
            modalAbmCosteoLimpiarCampos();
            $('#modal-abm-costodet-submit').attr('name', 'A');
            $("#modal-abm-costodet").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-costodet-btn-edit').click(function() {
            $('#modal-abm-costodet-title').html('Editar Organismo');
            modalAbmCosteoLimpiarCampos();

            $('#modal-abm-costodet-id').val($(this).data('id'));
            $('#modal-abm-costodet-nombre').val($(this).data('nombre'));
            $('#modal-abm-costodet-sigla').val($(this).data('sigla'));
            $('#modal-abm-costodet-cuit').val($(this).data('cuit'));
            if ($(this).data('sector') == 'Privado') {
                $('#opt-sector-privado').prop("checked", true);
            }


            $('#modal-abm-costodet-submit').attr('name', 'M');

            $("#modal-abm-costodet").modal("show");
        });
    }


    // ==============================================================
    // GUARDAR ORGANISMO
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-costodet-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-costodet-id').val();
        let razon_social = $('#modal-abm-costodet-nombre').val();
        let nombre_corto = $('#modal-abm-costodet-sigla').val();
        let cuit = $('#modal-abm-costodet-cuit').val();
        let sector = $("input[name='optSector']:checked").val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/cdc_abmorganismodb.php',
            data: {
                operacion: op,
                id: id,
                razon_social: razon_social,
                nombre_corto: nombre_corto,
                cuit: cuit,
                sector: sector
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-costodet").modal("hide");
                location.reload();
            },
            error: function(xhr, status, error) {
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