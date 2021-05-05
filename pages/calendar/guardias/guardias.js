// ========================================================================================================================================================
// MANEJO DE GUARDIAS
// ========================================================================================================================================================

/***************************************************************************************
 * Actualizar guardia
 * @param callback() callback fuanction
 * @author MVGP
 ****************************************************************************************/
const actualizarGuardia = (callback) => {
        return submitGuardia('UPDATE_GUARDIA', callback);
    }
    /***************************************************************************************
     * Eliminar guardia
     * @param callback() callback fuanction
     * @author MVGP
     ****************************************************************************************/
const removeGuardia = (callback) => {
    if (confirm('¿Está seguro que desea eliminar el registro de guardia?')) {
        return submitGuardia('REMOVE_GUARDIA', callback);
    }
}

/***************************************************************************************
 * guardar guardia en DB
 * @param callback() callback fuanction
 * @author MVGP
 ****************************************************************************************/
const submitGuardia = (operacion, callback) => {
    let id = $('#modal-abm-cal-guardias-id').val();
    let id_persona = $('#modal-abm-cal-guardias-id-persona').val();
    let subtipo = $('#modal-abm-cal-guardias-tipo').val();
    let color = $('#modal-abm-cal-guardias-tipo option:selected').data('color');
    let hora_inicio = $('#modal-abm-cal-guardias-tipo option:selected').data('inicio');
    let hora_fin = $('#modal-abm-cal-guardias-tipo option:selected').data('fin');
    let fecha_inicio = $('#modal-abm-cal-guardias-inicio').val().split('/').reverse().join("-") + ' ' + hora_inicio;
    let fecha_fin = $('#modal-abm-cal-guardias-fin').val().split('/').reverse().join("-") + ' ' + hora_fin;
    let descripcion = $('#modal-abm-cal-guardias-tipo option:selected').text();
    let observaciones = $('#modal-abm-cal-guardias-observaciones').val();
    // Ejecuto
    $.ajax({
        type: 'POST',
        url: './calendar/guardias/guardia.controller.php',
        dataType: 'json',
        data: {
            operacion,
            id,
            subtipo,
            id_persona,
            color,
            fecha_inicio,
            fecha_fin,
            descripcion,
            observaciones,
            tipo: 2,
            estado: 1,
            is_all_day: 1,
            is_background: 0,
            is_programmed: 0,
        },
        success: json => {
            $("#modal-abm-cal-guardias").modal("hide");
            if (!json.ok) {
                alert(json.err);
            }
            callback(null);
        },
        error: (xhr, status, error) => {
            callback(error);
        }
    });

}


/***************************************************************************************
 * Guardar guardias multiples
 * @author MVGP
 ****************************************************************************************/
const submitGuardiaMultiple = (callback) => {
    let operacion = 'ADD_GUARDIAS_MULTIPLES';
    let id_personas = $('#modal-abm-cal-guardias-mul-personas').val();
    let subtipo = $('#modal-abm-cal-guardias-mul-tipo').val();
    let color = $('#modal-abm-cal-guardias-mul-tipo option:selected').data('color');
    let hora_inicio = $('#modal-abm-cal-guardias-mul-tipo option:selected').data('inicio');
    let hora_fin = $('#modal-abm-cal-guardias-mul-tipo option:selected').data('fin');
    let fecha_inicio = $('#modal-abm-cal-guardias-mul-inicio').val().split('/').reverse().join("-") + ' ' + hora_inicio;
    let fecha_fin = $('#modal-abm-cal-guardias-mul-fin').val().split('/').reverse().join("-") + ' ' + hora_fin;
    let descripcion = $('#modal-abm-cal-guardias-mul-tipo option:selected').text();
    let observaciones = $('#modal-abm-cal-guardias-mul-observaciones').val();
    // Ejecuto
    $.ajax({
        type: 'POST',
        url: './calendar/guardias/guardia.controller.php',
        dataType: 'json',
        data: {
            operacion,
            subtipo,
            id_personas,
            color,
            fecha_inicio,
            fecha_fin,
            descripcion,
            observaciones,
            tipo: 2,
            estado: 1,
            is_all_day: 1,
            is_background: 0,
            is_programmed: 0,
        },
        success: json => {
            $("#modal-abm-cal-guardias-mul").modal("hide");
            if (!json.ok) {
                alert(json.err);
            }
            callback(null);
        },
        error: (xhr, status, error) => {
            callback(error);
        }
    });

}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const modalGuardiaSimpleLimpiarCampos = () => {
    $('#modal-abm-cal-guardias-id').val(0);
    $('#modal-abm-cal-guardias-id-persona').val('');
    $('#modal-abm-cal-guardias-tipo').val(1).change();
    $('#modal-abm-cal-guardias-inicio').val('');
    $('#modal-abm-cal-guardias-fin').val('');
    $('#modal-abm-cal-guardias-observaciones').val('');
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const modalGuardiaMultipleLimpiarCampos = () => {
    $('#modal-abm-cal-guardias-mul-id').val(0);
    $('#modal-abm-cal-guardias-mul-id-persona').val('');
    $('#modal-abm-cal-guardias-mul-tipo').val(1).change();
    $('#modal-abm-cal-guardias-mul-inicio').val('');
    $('#modal-abm-cal-guardias-mul-fin').val('');
    $('#modal-abm-cal-guardias-mul-observaciones').val('');
}

/***************************************************************************************
 * agregar Guardia para un recurso
 * @param Resource resource - Recurso del calendario que representa a la persona
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @author MVGP
 ****************************************************************************************/
const agregarGuardiaSimple = (resource, inicio, fin) => {
    $('#modal-abm-cal-guardias-title').html(`${resource.title} <small>agregar período de guardia</small>`);
    modalGuardiaSimpleLimpiarCampos();
    $('#modal-abm-cal-guardias-id-persona').val(resource.id);
    $('#modal-abm-cal-guardias-inicio').val(inicio.toISOString().slice(0, 10).split('-').reverse().join('/'));
    $('#modal-abm-cal-guardias-fin').val(fin.toISOString().slice(0, 10).split('-').reverse().join('/'));
    $('#modal-abm-cal-guardias-submit').attr('name', 'A');

    $("#modal-abm-cal-guardias").modal("show");
}

/***************************************************************************************
 * editar Guardia para un recurso
 * @param Resource resource - Recurso del calendario que representa a la persona
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @author MVGP
 ****************************************************************************************/
const editarGuardia = (evento) => {

    const resource = evento.getResources()[0];
    const inicio = evento.start;
    const fin = evento.end;
    $('#modal-abm-cal-guardias-title').html(`${resource.title} <small> Editar registro de guardia</small>`);
    modalGuardiaSimpleLimpiarCampos();
    $('#modal-abm-cal-guardias-id').val(evento.id);
    $('#modal-abm-cal-guardias-id-persona').val(resource.id);

    $('#modal-abm-cal-guardias-inicio').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    }).datepicker("setDate", new Date(inicio));
    $('#modal-abm-cal-guardias-fin').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    }).datepicker("setDate", new Date(fin));

    $('#modal-abm-cal-guardias-submit').attr('name', 'M');

    $("#modal-abm-cal-guardias").modal("show");
}

/***************************************************************************************
 * agregar Guardia para multiples recursos
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @author MVGP
 ****************************************************************************************/
const agregarGuardiaMultiple = () => {
    const inicio = new Date();
    const fin = new Date();
    fin.setDate(fin.getDate() + 7);
    $('#modal-abm-cal-guardias-mul-title').html(`Definición de período de guardias`);
    modalGuardiaMultipleLimpiarCampos();

    $('#modal-abm-cal-guardias-mul-inicio').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    }).datepicker("setDate", new Date(inicio));
    $('#modal-abm-cal-guardias-mul-fin').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    }).datepicker("setDate", new Date(fin));
    $('#modal-abm-cal-guardias-mul-submit').attr('name', 'ADD_GUARDIAS_MULTIPLES');

    $("#modal-abm-cal-guardias-mul").modal("show");
}

/***************************************************************************************
 * init: Inicialización de triggers
 * @param Calendar calendario - instancia del fullcalendar
 * @author MVGP
 ****************************************************************************************/
const init = (calendar) => {
    $('#modal-abm-cal-guardias-mul-submit').on('click', () => submitGuardiaMultiple(() => calendar.refetchEvents()));
    $('#modal-abm-cal-guardias-remove').on('click', () => removeGuardia(() => calendar.refetchEvents()));
    $('#modal-abm-cal-guardias-submit').on('click', () => actualizarGuardia(() => calendar.refetchEvents()));
    $('#modal-abm-guardias-btn-def').on('click', agregarGuardiaMultiple);
}

export { editarGuardia, init };