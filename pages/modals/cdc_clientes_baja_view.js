$(function() {

    let tbClientesBajas = $('#tbbaja');

    // ==============================================================
    // EVENTOS
    // ==============================================================
    // SELECCION GERENCIA
    $('#modal-abm-clientes-baja-view-btn').click(function() {

        let sql = 'SELECT C.id_organismo, C.id, C.razon_social, O.razon_social as organismo, C.cuit, C.nombre_corto, C.sector, C.con_convenio ';
        sql += 'FROM cdc_cliente as C ';
        sql += 'LEFT JOIN cdc_organismo as O ON C.id_organismo = O.id ';
        sql += 'WHERE C.borrado = 1';
        
        tbClientesBajas.DataTable().clear().destroy();
        tbClientesBajas.DataTable({
            "ajax": {
                type: 'POST',
                url: './helpers/getAsyncDataFromDB.php',
                data: { query: sql },
            },
            "dataSrc": function(json) {
                console.log(json);
            },
            "columns": [
                { "data": "organismo" },
                { "data": "razon_social" },
                { "data": "nombre_corto" },
                { "data": "cuit" },
                { "data": "sector" },
            ]
        });

        $("#modal-abm-clientes-baja-view").modal("show");

    });

});