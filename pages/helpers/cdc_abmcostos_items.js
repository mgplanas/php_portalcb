$(function() {
    // ==============================================================
    // TABLE FUNCTIONS
    // ==============================================================
    function createTableCategorias() {
        let id = $('#modal-abm-categorias-id').val();
        let strquery = 'SELECT ci.id, ci.parent, ci.nivel, ci.descripcion as producto, ci.unidad, ci.costo_unidad, ci.descripcion_item as descripcion, ci.observaciones, ';
        strquery += 'cat.descripcion as categoria, cat.id as cat_id,';
        strquery += 'subcat.descripcion as subcategoria, subcat.id as subcat_id ';
        strquery += 'FROM cdc_costos_items as ci ';
        strquery += 'INNER JOIN cdc_costos_items as subcat ON ci.parent = subcat.id ';
        strquery += 'INNER JOIN cdc_costos_items as cat ON subcat.parent = cat.id ';
        strquery += 'WHERE ci.borrado = 0 AND ci.nivel = 3;';

        return $('#categorias').DataTable({
            // "scrollY": "100vh",
            // "scrollX": true,
            // "scrollCollapse": true,
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
                { "data": "producto" },
                { "data": "unidad" },
                { "data": "costo_unidad" },
                { "data": "descripcion" },
                { "data": "observaciones" },
                { "data": "acciones" }
            ],
            'order': [
                [0, 'asc'],
                [1, 'asc']
            ],
            'rowGroup': {
                'dataSrc': ['categoria', 'subcategoria'],
                'startRender': function(rows, group, level) {
                    console.log(rows);
                    let row = $('<div class="row"></div>');
                    let colGroup = $('<div class="col-md-9"></div>').append(group);
                    let colButton = $('<div class="col-md-3 text-right"></div>').append('PPPP');
                    row.append(colGroup);
                    row.append(colButton);
                    // return '<div class="col-md-10">' + group + '</div><div class="col-md-2 text-right"></div>';
                    return row;
                }
            },
            'columnDefs': [{
                    'targets': [0, 1],
                    'visible': false
                },
                {
                    'targets': [-1],
                    'render': function(data, type, row, meta) {
                        let btns = '<a data-row="' + meta.row + '" data-id="' + row.id + '" data-iditem="' + row.id_costo_item + '" data-idcosto="' + row.id_costo + '" ';
                        btns += 'data-cat_id="' + row.cat_id + '" ';
                        btns += 'data-categoria="' + row.categoria + '" ';
                        btns += 'data-costo_unidad="' + row.costo_unidad + '" ';
                        btns += 'data-producto="' + row.producto + '" ';
                        btns += 'data-id="' + row.id + '" ';
                        btns += 'data-subcat_id="' + row.subcat_id + '" ';
                        btns += 'data-subcategoria="' + row.subcategoria + '" ';
                        btns += 'data-unidad="' + row.unidad + '" ';
                        btns += 'data-descripcion="' + row.descripcion_item + '" ';
                        btns += 'data-observaciones="' + row.observaciones + '" ';
                        btns += 'title="editar" class="modal-abm-categorias-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit"></i></a>' +
                            '<a data-row="' + meta.row + '" data-id="' + row.id + '" data-descripcion="' + row.descripcion + '" title="eliminar" class="modal-abm-costodet-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash" style="color: red;"></i></a>';
                        return btns;
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

    }

    var tbCosteos = createTableCategorias();

});