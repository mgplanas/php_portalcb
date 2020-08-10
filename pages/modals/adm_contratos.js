$(function() {

    // ********************************************************************************************
    // GERENCIAS
    // ********************************************************************************************
    function setAMBTriggers() {
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-contrato-btn-alta').off('click').on('click', function() {
            $('#modal-abm-contrato-title').html('Nuevo Seguimiento de contrato');
            modalAbmLimpiarCampos();
            $('#modal-abm-contrato-submit').attr('name', 'A');
            $("#modal-abm-contrato").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-contrato-btn-edit').off('click').on('click', function() {
            $('#modal-abm-contrato-title').html('Editar Seguimiento de contrato');
            modalAbmLimpiarCampos();

            $('#modal-abm-contrato-id').val($(this).data('id'));
            $('#modal-abm-contrato-proveedor').val($(this).data('proveedor'));
            $('#modal-abm-contrato-subgerencia').val($(this).data('subgerencia'));
            $('#modal-abm-contrato-vencimiento').val(moment($(this).data('vencimiento')).format('DD/MM/YYYY'));
            $('#modal-abm-contrato-oc').val($(this).data('oc'));
            $('#modal-abm-contrato-tipo').val($(this).data('tipo'));

            $('#modal-abm-contrato-submit').attr('name', 'M');

            $("#modal-abm-contrato").modal("show");
        });

        // ==============================================================
        // BAJA
        // ==============================================================
        $('.modal-abm-contrato-btn-baja').off('click').on('click', function() {
            if (confirm('Esta seguro de borrar el seguimiento del contrato?')) {
                let id = $(this).data('id');
                // Ejecuto
                $.ajax({
                    type: 'POST',
                    url: './helpers/adm_contratosdb.php',
                    data: {
                        operacion: 'B',
                        id: id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if (!json.ok) {
                            alert(json.err);
                        } else {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText, error);
                    }
                });

            }
        });


        // Busqueda OC
        $('#modal-abm-contrato-oc-search-btn').off('click').on('click', function() {
            // Recupero datos del oc
            let oc = $('#modal-abm-contrato-oc').val();
            if (!oc) return;

            let existe = false;
            tbCosteos.rows().every(function(rowIdx, tableLoop, rowLoop) {
                if (oc == this.data().oc) {
                    alert('Ya existe un seguimiento para dicha OC');
                    existe = true;
                    return;
                }
            });
            if (existe) return;

            let strquery = "SELECT * FROM adm_compras ";
            strquery += "WHERE nro_oc = '" + oc + "' AND borrado = 0";
            $.getJSON("./helpers/getAsyncDataFromDB.php", { query: strquery },
                function(response) {
                    if (!response.data || !response.data[0]) {
                        alert('No existe OC');
                        modalAbmLimpiarCamposAdjuntos();
                        return;
                    }
                    actualizarCamposConOC(response.data[0]);
                    return;
                }
            ).fail(function(jqXHR, errorText) {
                console.log(errorText);
                return alert(errorText);
            });

        });

    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-contrato-submit').click(function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-contrato-id').val();
        let id_proveedor = $('#modal-abm-contrato-proveedor').val();
        let id_subgerencia = $('#modal-abm-contrato-subgerencia').val();
        let oc = $('#modal-abm-contrato-oc').val();
        let tipo_mantenimiento = $('#modal-abm-contrato-tipo').val();
        let vencimiento = $('#modal-abm-contrato-vencimiento').val();
        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/adm_contratosdb.php',
            data: {
                operacion: op,
                id: id,
                id_proveedor: id_proveedor,
                id_subgerencia: id_subgerencia,
                oc: oc,
                tipo_mantenimiento: tipo_mantenimiento,
                vencimiento: vencimiento
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-contrato").modal("hide");
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
    function modalAbmLimpiarCampos() {
        $('#modal-abm-contrato-oc').val('');
        modalAbmLimpiarCamposAdjuntos();
    }

    function modalAbmLimpiarCamposAdjuntos() {
        $('#modal-abm-contrato-id').val(0);
        $('#modal-abm-contrato-vencimiento').val('');
        $('#modal-abm-contrato-tipo').val('');
        $("#modal-abm-contrato-proveedor").val('first').change();
        $("#modal-abm-contrato-subgerencia").val('first').change();
    }

    // Actualiza el modal con los campos de la OC
    function actualizarCamposConOC(compra) {
        $('#modal-abm-contrato-vencimiento').val(compra.fecha_fin_contrato.split('-').reverse().join('/'));
        $('#modal-abm-contrato-tipo').val(compra.concepto);
        $("#modal-abm-contrato-proveedor").val(compra.id_proveedor).change();
        $("#modal-abm-contrato-subgerencia").val(compra.id_subgerencia).change();
    }
    // ********************************************************************************************

    // ==============================================================
    // TABLE FUNCTIONS
    // ==============================================================
    function createTableContrato() {
        let strquery = 'SELECT c.id, c.id_proveedor, c.id_subgerencia, c.oc, c.tipo_mantenimiento, c.vencimiento, ';
        strquery += 's.nombre as subgerencia, ';
        strquery += 'p.razon_social as proveedor, ';
        strquery += 'datediff(c.vencimiento, now()) as dias ';
        strquery += 'FROM adm_contratos_vto as c ';
        strquery += 'INNER JOIN subgerencia as s ON c.id_subgerencia = s.id_subgerencia ';
        strquery += 'INNER JOIN adm_com_proveedores as p ON c.id_proveedor = p.id ';
        strquery += 'WHERE c.borrado = 0;';

        return $('#vtos').DataTable({
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
                { "data": "subgerencia" },
                { "data": "proveedor" },
                { "data": "tipo_mantenimiento" },
                { "data": "oc" },
                { "data": "dias" },
                { "data": "dias" },
                { "data": "vencimiento" },
                { "data": "dias" },
            ],
            'order': [
                [0, 'asc'],
                [1, 'asc']
            ],
            // 'rowGroup': {
            //     'dataSrc': ['categoria', 'subcategoria']
            // },
            'columnDefs': [
                // {
                //     'targets': [0, 1],
                //     'visible': false
                // },
                // {
                //     'targets': [4, 6, 7],
                //     'className': 'dt-body-right'
                // },
                {
                    'targets': [4],
                    'className': 'dt-body-center',
                    render: function(data) {
                        let abs = Math.abs(data);
                        if (data < 0) {
                            return '<span class="badge bg-red">Vencido</span>';
                        } else if (data < 150) {
                            return '<span class="badge bg-yellow">Renovar</span>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    'targets': [5],
                    'className': 'dt-body-center',
                    render: function(data) {
                        let abs = Math.abs(data);
                        if (data < 0) {
                            return '<span title="' + abs + ' día(s) de vencido">' + abs + '</span>';
                        } else {
                            return '<span title="faltan ' + abs + ' día(s)">' + abs + '</span>';
                        }
                    }
                },
                {
                    'targets': [6],
                    'className': 'dt-body-center',
                    render: function(data) { return moment(data).format('DD/MM/YYYY'); }
                },
                {
                    'targets': [-1],
                    'render': function(data, type, row, meta) {
                        let btns = '<a data-row="' + meta.row + '" data-id="' + row.id + '" ';
                        btns += 'data-subgerencia="' + row.id_subgerencia + '" ';
                        btns += 'data-proveedor="' + row.id_proveedor + '" ';
                        btns += 'data-vencimiento="' + row.vencimiento + '" ';
                        btns += 'data-oc="' + row.oc + '" ';
                        btns += 'data-tipo="' + row.tipo_mantenimiento + '" ';
                        btns += 'title="editar" class="modal-abm-contrato-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit"></i></a>';
                        btns += '<a data-row="' + meta.row + '" data-id="' + row.id + '" title="eliminar" class="modal-abm-contrato-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash" style="color: red;"></i></a>';
                        return btns;
                        // return '<a data-row="' + meta.row + '" data-id="' + row.id + '" data-iditem="' + row.id_costo_item + '" data-idcosto="' + row.id_costo + '" title="editar" class="modal-abm-contrato-btn-edit btn" style="padding: 2px;"><i class="glyphicon glyphicon-edit"></i></a>' +
                        //     '<a data-row="' + meta.row + '" data-id="' + row.id + '" data-descripcion="' + row.descripcion + '" title="eliminar" class="modal-abm-contrato-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash" style="color: red;"></i></a>';
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

    var tbCosteos = createTableContrato();
    tbCosteos.on('draw', function() {
        setAMBTriggers();
    });
    // *******************************************************************************

    $('#modal-abm-contrato-vencimiento').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        daysOfWeekDisabled: [0, 6]
    });

    setAMBTriggers();
});