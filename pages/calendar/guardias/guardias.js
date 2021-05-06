// ========================================================================================================================================================
// MANEJO DE GUARDIAS
// ========================================================================================================================================================


/***************************************************************************************
 * Guardar guardias multiples
 * @author MVGP
 ****************************************************************************************/
const submitGuardiaMultiple = (callback) => {
    let operacion = 'ADD_GUARDIAS_MULTIPLES';
    let id_personas = $('#modal-abm-cal-guardias-mul-personas').val();
    let periodos = $('#modal-abm-cal-guardias-mul-tabla').DataTable().rows().data().toArray();
    let subtipo = $('#modal-abm-cal-guardias-mul-tipo').val();
    let color = $('#modal-abm-cal-guardias-mul-tipo option:selected').data('color');
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
            periodos,
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

    $('#modal-abm-cal-guardias-mul-submit').attr('name', 'ADD_GUARDIAS_MULTIPLES');
    $("#modal-abm-cal-guardias-mul").modal("show");
}

/***************************************************************************************
 * Agregar periodo a la tabla
 * @author MVGP
 ****************************************************************************************/
const agrearPeriodoATabla = (table) => {

    const hora_inicio = $('#modal-abm-cal-guardias-mul-tipo option:selected').data('inicio');
    const hora_fin = $('#modal-abm-cal-guardias-mul-tipo option:selected').data('fin');
    const fecha_inicio = $('#modal-abm-cal-guardias-mul-inicio').val().split('/').reverse().join("-") + ' ' + hora_inicio;
    let m_inicio = moment(fecha_inicio);
    let m_fin = moment(fecha_inicio);
    let dias = $('#modal-abm-cal-guardias-mul-dias').val();
    m_fin.add(dias, 'days');
    let time = moment(hora_fin, 'HH:mm');
    m_fin.set({
        hour: time.get('hour'),
        minute: time.get('minute'),
    });

    let row = table.row.add([m_inicio.format('YYYY-MM-DD HH:mm:ss'), m_fin.format('YYYY-MM-DD HH:mm:ss'), m_inicio.format('DD/MM/YYYY HH:mm:ss'), m_fin.format('DD/MM/YYYY HH:mm:ss'), dias, "<button type='button' class='btn modal-abm-cal-guardias-mul-btn-del-periodo'><i class='fa fa-trash text-danger'></i></button>"]).node();
    table.draw();

    // borro fila
    $(row).on('click', 'button.modal-abm-cal-guardias-mul-btn-del-periodo', () => table.row($(row)).remove().draw());
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const modalGuardiaMultipleLimpiarCampos = () => {

    $('#modal-abm-cal-guardias-mul-tabla').DataTable().clear().destroy();
    let tabla = $('#modal-abm-cal-guardias-mul-tabla').DataTable({
        'language': { 'emptyTable': 'No hay Períodos de guardias' },
        'ordering': false,
        'paging': false,
        'searching': false,
        'info': false,
        'autoWidth': false,
        'columnDefs': [{
            'targets': [0, 1],
            'visible': false
        }, ],
    });
    $('#modal-abm-cal-guardias-mul-id').val(0);
    $('#modal-abm-cal-guardias-mul-dias').val(7);
    $('#modal-abm-cal-guardias-mul-id-persona').val('');
    $('#modal-abm-cal-guardias-mul-tipo').val(1).change();
    $('#modal-abm-cal-guardias-mul-inicio').val('');
    $('#modal-abm-cal-guardias-mul-fin').val('');
    $('#modal-abm-cal-guardias-mul-observaciones').val('');
    $('#modal-abm-cal-guardias-mul-add').off('click').on('click', () => agrearPeriodoATabla(tabla));
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
    $('#modal-abm-cal-guardias-title').html(`${resource.title} <small style="margin-left: 40px;"> Editar registro de guardia</small>`);
    modalGuardiaSimpleLimpiarCampos();
    $('#modal-abm-cal-guardias-id').val(evento.id);
    $('#modal-abm-cal-guardias-id-persona').val(resource.id);
    $('#modal-abm-cal-guardias-observaciones').val(evento.extendedProps.obs);

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
    let descripcion = $('#modal-abm-cal-guardias-tipo option:selected').text();
    let observaciones = $('#modal-abm-cal-guardias-observaciones').val();
    let hora_inicio = $('#modal-abm-cal-guardias-tipo option:selected').data('inicio');
    let hora_fin = $('#modal-abm-cal-guardias-tipo option:selected').data('fin');
    const fecha_inicio = $('#modal-abm-cal-guardias-inicio').val().split('/').reverse().join("-") + ' ' + hora_inicio;
    let m_inicio = moment(fecha_inicio);
    let m_fin = moment(fecha_inicio);
    let dias = $('#modal-abm-cal-guardias-dias').val();
    m_fin.add(dias, 'days');
    let time = moment(hora_fin, 'HH:mm');
    m_fin.set({
        hour: time.get('hour'),
        minute: time.get('minute'),
    });
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
            fecha_inicio: m_inicio.format('YYYY-MM-DD HH:mm:ss'),
            fecha_fin: m_fin.format('YYYY-MM-DD HH:mm:ss'),
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