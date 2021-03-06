$(function() {

    // ********************************************************************************************
    // AUXILIARES
    // ********************************************************************************************
    function calcCapacidadAsignable() {
        const capacidad_fisica = $('#modal-abm-storage-capacidad-fisica').val();
        const per_capacidad_recomenda = $('#modal-abm-storage-asignacion-recomendada').val();
        return capacidad_fisica * per_capacidad_recomenda / 100;
    }

    // ********************************************************************************************
    // TRIGGERS DOM
    // ********************************************************************************************
    function setAMBTriggers() {
        // STATS
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-storage-btn-stat').on('click', function() {
            $('#modal-abm-storage-stat-title').html('Resúmen');
            $("#modal-abm-storage-stat").modal("show");
        });
        // ALTA
        // seteo boton trigger para el alta de gerencia
        $('#modal-abm-storage-btn-alta').on('click', function() {
            $('#modal-abm-storage-title').html('Nuevo Equipo de Storage');
            modalAbmLimpiarCampos();
            modalAbmHabilitarCampos($(this).data('role'));
            $('#modal-abm-storage-submit').attr('name', 'A');
            $("#modal-abm-storage").modal("show");
        });

        // EDIT
        // seteo boton trigger para el edit de gerencia
        $('.modal-abm-storage-btn-edit').on('click', function() {
            $('#modal-abm-storage-title').html('Editar Equipo');
            modalAbmLimpiarCampos();
            modalAbmHabilitarCampos($(this).data('role'));

            $('#modal-abm-storage-id').val($(this).data('id'));
            $('#modal-abm-storage-nombre').val($(this).data('nombre'));
            $('#modal-abm-storage-categoria').val($(this).data('categoria'));
            $('#modal-abm-storage-capacidad-fisica').val($(this).data('capacidad-fisica'));
            $('#modal-abm-storage-asignacion-recomendada').val($(this).data('asignacion-recomendada'));
            $('#modal-abm-storage-asignacion-max').val($(this).data('asignacion-max'));
            $('#modal-abm-storage-fisico-ocupado').val($(this).data('fisico-ocupado'));
            $('#modal-abm-storage-asignado').val($(this).data('asignado'));
            $('#modal-abm-storage-capacidad-asignable').val(calcCapacidadAsignable());

            $('#modal-abm-storage-submit').attr('name', 'M');

            $("#modal-abm-storage").modal("show");
        });

        // ON CHANGE
        $('#modal-abm-storage-capacidad-fisica, #modal-abm-storage-asignacion-recomendada').on('keyup', function() {
            $('#modal-abm-storage-capacidad-asignable').val(calcCapacidadAsignable());
        });
    }


    // ==============================================================
    // GUARDAR CLIENTE
    // ==============================================================
    // ejecución de guardado async
    $('#modal-abm-storage-submit').on('click', function() {
        // Recupero datos del formulario
        let op = $(this).attr('name');
        let id = $('#modal-abm-storage-id').val();
        let nombre = $('#modal-abm-storage-nombre').val();
        let categoria = $('#modal-abm-storage-categoria').val();
        let capacidad_fisica = $('#modal-abm-storage-capacidad-fisica').val();
        let asignacion_recomendada = $('#modal-abm-storage-asignacion-recomendada').val();
        let asignacion_max = $('#modal-abm-storage-asignacion-max').val();
        let fisico_ocupado = $('#modal-abm-storage-fisico-ocupado').val();
        let asignado = $('#modal-abm-storage-asignado').val();

        // valido
        if (!nombre) {
            alert('El nombre del equipo no puede estar vacío.');
            return;
        }
        if (!capacidad_fisica || capacidad_fisica <= 0) {
            alert('Debe ingresar la capacidad física del equipo.');
            return;
        }

        // Ejecuto
        $.ajax({
            type: 'POST',
            url: './helpers/sdc_abmstoragedb.php',
            data: {
                operacion: op,
                id: id,
                nombre: nombre,
                categoria: categoria,
                capacidad_fisica: capacidad_fisica,
                asignacion_recomendada: asignacion_recomendada,
                asignacion_max: asignacion_max,
                asignado: asignado,
                fisico_ocupado: fisico_ocupado
            },
            dataType: 'json',
            success: function(json) {
                $("#modal-abm-storage").modal("hide");
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
        $('#modal-abm-storage-id').val(0);
        $('#modal-abm-storage-nombre').val('');
        $('#modal-abm-storage-categoria').first();
        $('#modal-abm-storage-capacidad-fisica').val(0);
        $('#modal-abm-storage-asignacion-recomendada').val(0);
        $('#modal-abm-storage-asignacion-max').val(0);
        $('#modal-abm-storage-fisico-ocupado').val(0);
        $('#modal-abm-storage-asignado').val(0);
    }

    function modalAbmHabilitarCampos(role) {
        $('#modal-abm-storage-id').val(0);
        if (role != 'ALL') {
            $('#modal-abm-storage-nombre').prop("disabled", role === 'OP');
            $('#modal-abm-storage-categoria').prop("disabled", role === 'OP');
            $('#modal-abm-storage-capacidad-fisica').prop("disabled", role === 'OP');
            $('#modal-abm-storage-asignacion-recomendada').prop("disabled", role === 'OP');
            $('#modal-abm-storage-asignacion-max').prop("disabled", role === 'OP');
            $('#modal-abm-storage-fisico-ocupado').prop("disabled", role === 'ADMIN');
            $('#modal-abm-storage-asignado').prop("disabled", role === 'ADMIN');
        }
    }
    // ********************************************************************************************

    setAMBTriggers();

});