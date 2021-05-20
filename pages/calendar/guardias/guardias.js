import * as utils from '../utils.js';

// ========================================================================================================================================================
// MANEJO DE GUARDIAS
// ========================================================================================================================================================
var calendar;


const eventsUpdated = eventos => {
    const eventosGuardias = eventos.filter(e => e.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_GUARDIAS);
    createTableGuardias('tbGuardias', eventosGuardias);
}

const createTableGuardias = (id, eventos) => {

    let tbLicencia = $(`#${id}`);
    tbLicencia.DataTable().clear().destroy();
    tbLicencia.DataTable({
        "paging": false,
        "deferRender": true,
        "data": eventos,
        "columns": [
            { data: "id" },
            { data: "icon", render: (data, type, row) => `<i title="${row.subtipo_desc}" class="fa fa-${data}"></i>` },
            { data: "fecha_inicio", render: data => moment(data).format('DD/MM/YYYY') },
            { data: "fecha_fin", render: data => moment(data).format('DD/MM/YYYY') },
            {
                data: "",
                render: (data, type, row) => {
                    const mInicio = moment(row.fecha_inicio);
                    const mFin = moment(row.fecha_fin);
                    const duration = moment.duration(mFin.diff(mInicio));
                    const dias = parseInt(duration.asDays()) + 1;
                    return `${dias}d`;
                }
            },
        ],
        'order': [
            [2, 'desc']
        ],
        'columnDefs': [{
                'targets': [0],
                'visible': false
            },
            {
                'targets': [0, 1, 4],
                orderable: false
            },
        ],
        'dom': 'rtpB',

    });

}

/***************************************************************************************
 * Renderizacion Eventos Guardias
 * @author MVGP
 ****************************************************************************************/
const eventRender = info => {
    const mInicio = moment(info.event.extendedProps.real_start);
    const mFin = moment(info.event.extendedProps.real_end);
    const resource = info.event.getResources()[0];
    $(info.el).popover({
        title: `${info.event.title} <a href="#" class="close" data-dismiss="alert">&times;</a>`,
        placement: 'top',
        html: true,
        trigger: 'hover',
        content: `<strong>${resource.title}:</strong><br>
        <strong>Comienzo:</strong>${mInicio.format('DD/MM/YYYY HH:mm')}<br>
        <strong>Fin:</strong>${mFin.format('DD/MM/YYYY HH:mm')}<br>
        Cantidad de días: ${mFin.diff(mInicio, 'days')+1}`,
        container: 'body'
    }).popover('show');
    $(document).on("click", ".popover .close", () => {
        $(".popover").popover('hide');
    });

    $(info.el).off('click').on('click', () => {
        $(this).popover('hide');
        editarGuardia(info.event);
    })

    $(info.el).addClass([`ar-tipo-${info.event.extendedProps.tipo}`, `subtipo-${info.event.extendedProps.subtipo}`].join(' '))
    $(info.el).css('cursor', 'pointer');
};

/***************************************************************************************
 * Valida nueva definicion de guardia
 * Definición de período de Guardia
 * @typedef PeriodoGuadia
 * @property {string} fecha_inicio - Fecha de incio de la guardia
 * @property {string} fecha_fin - Fecha de fin de la guardia
 * @property {string} fecha_inicio_ISO - Fecha de incio de la guardia ISO
 * @property {string} fecha_fin_ISO - Fecha de fin de la guardia ISO
 * @property {number} días - días
 * 
 * @typedef Resultado
 * @property {boolean} ok: true or false
 * @property {string[]} errores: Array con todos los errores encontrados
 * 
 * @param {number[]} empleadosIDsAsignados: Listado de personas asignadas a los nuevos periodos
 * @param {PeriodoGuadia[][]} nuevosPeriodos: Periodos de guardias a ser agregados
 * @returns {Resultado}: resultado
 * @author MVGP
 ****************************************************************************************/
const validar = (empleadosIDsAsignados, nuevosPeriodos) => {

    const resultado = {
        ok: true,
        errores: []
    }

    // me traigo los eventos de guardia en base a los empleadosAsignados
    empleadosIDsAsignados.forEach(id => {

        // Me traigo el recurso para saber el nombre
        const { title: nombre } = calendar.getResourceById(id);

        let diasDeGuardiaAcumulados = 0;
        let flag_acum = false;

        // Filtro los eventos por tipo de guardias
        const eventosDeLaPersona = calendar.getEvents()
            .filter(evento => evento.extendedProps.id_persona == id);
        const guardias = eventosDeLaPersona
            .filter(evento => evento.extendedProps.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_GUARDIAS); // Tipo guardias

        // Corroboro que los períodos de descanso entre los períodos ingresados vs los existentes se cumplan
        // constato inicio del nuevo contra fin del existente y
        // fin del nuevo contra inicio del existente
        guardias.forEach(guardia => {
            const m_inicio = moment(guardia.start);
            const m_fin = moment(guardia.end);
            diasDeGuardiaAcumulados += (m_fin.diff(m_inicio, 'days'));

            nuevosPeriodos.forEach(periodo => {
                const p_inicio = moment(periodo[0]);
                const p_fin = moment(periodo[1]);

                // verifico que no se solape con un evento de Vacaciones
                if (utils.solapaConLicencia(p_inicio, p_fin, eventosDeLaPersona, utils.RULE_CONSTANTS.SUBTIPOS_LICENCIA.VACACIONES)) {
                    resultado.ok = false;
                    resultado.errores.push(`El período del ${p_inicio.format('DD/MM/YYYY')} al ${p_fin.format('DD/MM/YYYY')} se solapa con un período de vacaciones de ${nombre}.`);
                    return false;
                }

                let periodoDescanzoInicio = (p_inicio.diff(m_fin, 'days'));
                let periodoDescanzoFin = (p_fin.diff(m_inicio, 'days'));
                periodoDescanzoInicio = (periodoDescanzoInicio < 0 ? periodoDescanzoInicio - 1 : periodoDescanzoInicio);
                periodoDescanzoFin = (periodoDescanzoFin < 0 ? periodoDescanzoFin - 1 : periodoDescanzoFin);

                // Contabilizo la cantidad de días sólo la primera vez
                if (!flag_acum) diasDeGuardiaAcumulados += (p_fin.diff(p_inicio, 'days')) + 1;

                if (Math.abs(periodoDescanzoInicio) < 7 || Math.abs(periodoDescanzoFin) < 7) {
                    resultado.ok = false;
                    resultado.errores.push(`El período del ${p_inicio.format('DD/MM/YYYY')} al ${p_fin.format('DD/MM/YYYY')} no respeta los 7 días de descanso entre guardias para ${nombre}.`);
                    return false;
                };
                // console.log('inicio de p con fin del existente ', p_inicio.format('YYYY-MM-DD'), p_fin.format('YYYY-MM-DD'), m_inicio.format('YYYY-MM-DD'), m_fin.format('YYYY-MM-DD'), p_inicio.diff(m_fin, 'days'));
                // console.log('fin de p con inicio del existente ', p_inicio.format('YYYY-MM-DD'), p_fin.format('YYYY-MM-DD'), m_inicio.format('YYYY-MM-DD'), m_fin.format('YYYY-MM-DD'), p_fin.diff(m_inicio, 'days'));
            });
            flag_acum = true;
        });

        if (diasDeGuardiaAcumulados > utils.RULE_CONSTANTS.RULE_CANTIDAD_MAX_DIAS_GUARDIAS) {
            resultado.ok = false;
            resultado.errores.push(`La cantidad de días de guardia asignados a (${diasDeGuardiaAcumulados}) para ${nombre} exeden el máximo permitido (${utils.RULE_CONSTANTS.RULE_CANTIDAD_MAX_DIAS_GUARDIAS}).`);
        }

    });
    resultado.errores = [...new Set(resultado.errores)];
    return resultado;

}

/***************************************************************************************
 * Guardar guardias multiples
 * @author MVGP
 ****************************************************************************************/
const submit = (callback) => {
    let operacion = 'ADD_GUARDIAS_MULTIPLES';
    let id_personas = $('#modal-abm-cal-guardias-mul-personas').val();
    let periodos = $('#modal-abm-cal-guardias-mul-tabla').DataTable().rows().data().toArray();
    let subtipo = $('#modal-abm-cal-guardias-mul-tipo').val();
    let color = $('#modal-abm-cal-guardias-mul-tipo option:selected').data('color');
    let descripcion = $('#modal-abm-cal-guardias-mul-tipo option:selected').text();
    let observaciones = $('#modal-abm-cal-guardias-mul-observaciones').val();

    const validez = validar(id_personas, periodos)
    if (!validez.ok) {
        Swal.fire({
            title: 'Error en la validación',
            html: `<div style="text-align: left;"><li>${validez.errores.join('</li><li>')}</li></div>`,
            icon: 'error',
            confirmButtonText: 'Aceptar',
        });
        return;
    }
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
    $('#modal-abm-cal-guardias-mul-title').html(`Agregar períodos de guardias`);
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

    // Agrego el nuevo registro a la tabla
    let row = table.row.add([m_inicio.format('YYYY-MM-DD HH:mm:ss'), m_fin.format('YYYY-MM-DD HH:mm:ss'), m_inicio.format('DD/MM/YYYY HH:mm:ss'), m_fin.format('DD/MM/YYYY HH:mm:ss'), dias, "<button type='button' class='btn modal-abm-cal-guardias-mul-btn-del-periodo'><i class='fa fa-trash text-danger'></i></button>"]).node();
    table.draw();

    // borro fila event listener
    $(row).on('click', 'button.modal-abm-cal-guardias-mul-btn-del-periodo', () => table.row($(row)).remove().draw());

    // Sumo 7 días a la fecha de inicio para que sea más rápida la auto creación de períodos
    m_fin.add(7, 'days');
    $('#modal-abm-cal-guardias-mul-inicio').val(m_fin.format('DD/MM/YYYY'))
    $('#modal-abm-cal-guardias-mul-inicio').datepicker("setDate", m_fin.toDate());
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const modalGuardiaMultipleLimpiarCampos = () => {

    $('#modal-abm-cal-guardias-mul-tabla').DataTable().clear().destroy();
    let tabla = $('#modal-abm-cal-guardias-mul-tabla').DataTable({
        'language': { 'emptyTable': 'Sin períodos agregados' },
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
const init = (cal) => {
    calendar = cal;
    $('#modal-abm-cal-guardias-mul-submit').on('click', () => submit(() => calendar.refetchEvents()));
    $('#modal-abm-cal-guardias-remove').on('click', () => removeGuardia(() => calendar.refetchEvents()));
    $('#modal-abm-cal-guardias-submit').on('click', () => actualizarGuardia(() => calendar.refetchEvents()));
    $('#modal-abm-guardias-btn-def').on('click', agregarGuardiaMultiple);
}

export { editarGuardia, init, eventRender, eventsUpdated };