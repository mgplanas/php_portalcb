import * as dnls from '../dnls.js';
import * as guardias from '../guardias/guardias.js';
import * as utils from './utils.js'

// Calendar instantiation
var calendarEl = document.getElementById('calendar');
var calendar;

// Default dates range
var today = new Date();

// ========================================================================================================================================================
// MANEJO DE Registro de HORAS
// ========================================================================================================================================================
// TODO: hacer popup de ayuda 
// TODO: hacer edicion
// TODO: validación sobre eventos actuales
// TODO: generacion de eventos que pasan 
// TODO: Aprobacion
// TODO: Hacer anánisis y que devuleva un objeto con todo lo que necesitás


/***************************************************************************************
 * Renderizacion Eventos Registro Horas
 * @author MVGP
 ****************************************************************************************/
const eventRender = info => {
    const mInicio = moment(info.event.extendedProps.real_start);
    const mFin = moment(info.event.extendedProps.real_end);
    const resource = info.event.getResources()[0];
    const duration = moment.duration(mFin.diff(mInicio));
    const dhours = parseInt(duration.asHours());
    const dmin = parseInt(duration.asMinutes()) - (dhours * 60);

    $(info.el).popover({
        title: `<i class='fa fa-${info.event.extendedProps.icon}'></i> ${info.event.extendedProps.subtipo_desc} <a href="#" class="close" data-dismiss="alert">&times;</a>`,
        placement: 'top',
        html: true,
        trigger: 'hover',
        content: `<strong>${resource.title}:</strong><br>
        <strong>Comienzo:</strong>${mInicio.format('DD/MM/YYYY HH:mm')}<br>
        <strong>Fin:</strong>${mFin.format('DD/MM/YYYY HH:mm')}<br>
        <i class="fa fa-clock-o"></i> Duracion: ${dhours} h ${dmin} m
        <hr>
        <div class="text-right"><strong>Estado:</strong> <span class="label label-warning">pendiente aprobación</span></div>`,
        container: 'body'
    }).popover('show');

    $(document).on("click", ".popover .close", () => {
        $(".popover").popover('hide');
    });
    $(info.el).off('click').on('click', () => {
        $(this).popover('hide');

    })

    // agrego estylo
    $(info.el).addClass([`ar-tipo-${info.event.extendedProps.tipo}`, `subtipo-${info.event.extendedProps.subtipo}`, `estado-${info.event.extendedProps.estado}`].join(' '))
        // Agrego el ícono
    $(info.el, "div.fc-content").prepend(`<i class='fa fa-${info.event.extendedProps.icon}'></i>`);
    $(info.el).css('cursor', 'pointer');
};

/***************************************************************************************
 * Valida el registro de horas
 * @param Moment m_inicio - Fecha de inicio del registro de trabajo
 * @param Moment m_fin - Fecha de fin del registro de trabajo
 * @param FullCalendatEvent[] eventosActuales - Eventos de la persona en el período
 * @author MVGP
 ****************************************************************************************/
const validarRegistroDeHora = (m_inicio, m_fin, es_programada, justificacion, eventosActuales) => {
    const resultado = {
        ok: true,
        errores: []
    }

    // Validacion Campo del form
    // Valido fechas 
    if (!m_inicio.isValid() || !m_fin.isValid()) {
        resultado.ok = false;
        resultado.errores.push(`Las fechas ingresadas no son válidas.`);
        return resultado;
    }

    // Fecha fin > hoy
    if (m_fin.isAfter(moment())) {
        resultado.ok = false;
        resultado.errores.push(`la fecha de fin no puede ser a futuro.`);
        return resultado;
    }

    // Si ña fecha inicio es < a fin
    if (m_inicio.isAfter(m_fin)) {
        resultado.ok = false;
        resultado.errores.push(`la fecha de inicio no puede ser menor a la fecha fin.`);
        return resultado;
    }
    // Valido justificacion 
    if (justificacion === '') {
        resultado.ok = false;
        resultado.errores.push(`El campo Justificación no puede estar vacío.`);
        return resultado;
    }

    // límite de horas de trabajos seguido
    if (m_fin.diff(m_inicio, 'days') >= 1) {
        resultado.ok = false;
        resultado.errores.push(`El límite de horas trabajadas supera el día.`);
        return resultado;
    }

    // Valido de que no se solapen con horarios laborales
    if (utils.solapaConLicencia(m_inicio, m_fin, eventosActuales)) {
        resultado.ok = false;
        resultado.errores.push(`No es posible ingresar horas en un período de licencia.`);
        return resultado;
    }

    // Valido de que no se solapen con horarios laborales
    if (utils.solapaRangoConHorarioLaboral(m_inicio, m_fin, eventosActuales)) {
        resultado.ok = false;
        resultado.errores.push(`El rango ingresado se solapa con horario laboral.`);
        return resultado;
    }

    // Límite de hs acumuladas
    const { excede, totalMinutos } = utils.verificarLimiteAcumuladoMensual(m_inicio, m_fin, eventosActuales);
    if (excede) {
        resultado.ok = false;
        resultado.errores.push(`Se está excediendo el límite de horas mensuales (30hs).`);
        return resultado;
    }

    return resultado;
}

/***************************************************************************************
 * guardar Registro en DB
 * @param callback() callback fuanction
 * @author MVGP
 ****************************************************************************************/
const submitRegistroHoras = (operacion, callback) => {
    let id = $('#modal-abm-cal-registro-id').val();
    let id_persona = $('#modal-abm-cal-registro-id-persona').val();
    let justificacion = $('#modal-abm-cal-registro-justificacion').val();
    let fecha_inicio = $('#modal-abm-cal-registro-inicio').val();
    let fecha_fin = $('#modal-abm-cal-registro-fin').val();
    let es_programada = 0;
    if ($("#modal-abm-cal-registro-programada").is(':checked')) {
        es_programada = 1;
    }

    fecha_inicio = moment(fecha_inicio);
    fecha_fin = moment(fecha_fin);

    // obtengo los eventos para analizar.
    let eventosActuales = calendar.getEvents();

    // Valido nuevo ingreso
    const validez = validarRegistroDeHora(fecha_inicio, fecha_fin, es_programada, justificacion, eventosActuales);
    if (!validez.ok) {
        Swal.fire({
            title: 'Error en la validación',
            html: `<div style="text-align: left;"><li>${validez.errores.join('</li><li>')}</li></div>`,
            icon: 'error',
            confirmButtonText: 'Aceptar',
        });
        return;
    }

    const subtipo = utils.determinarSubtipoRegistroHoras(fecha_inicio, fecha_fin, es_programada, eventosActuales);
    // Ejecuto
    $.ajax({
        type: 'POST',
        url: './calendar/activaciones/registrohoras.controller.php',
        dataType: 'json',
        data: {
            operacion,
            fecha_inicio: fecha_inicio.format('YYYY-MM-DD HH:mm:ss'),
            fecha_fin: fecha_fin.format('YYYY-MM-DD HH:mm:ss'),
            id_persona,
            justificacion,
            tipo: utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS,
            subtipo,
            estado: 1,
            is_all_day: 0,
            is_background: 0,
            is_programmed: es_programada
        },
        success: json => {
            $("#modal-abm-cal-registro").modal("hide");
            if (!json.ok) {
                alert(json.err);
            }
            callback(null);
        },
        error: (xhr, status, error) => {
            alert(error);
            callback(error);
        }
    });

}

/***************************************************************************************
 * Eliminar Registro
 * @param callback() callback fuanction
 * @author MVGP
 ****************************************************************************************/
const removeRegistroHoras = (callback) => {
    if (confirm('¿Está seguro que desea eliminar el registro de horas?')) {
        return submitRegistroHoras('REMOVE_REGISTRO_HORAS', callback);
    }
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const modalRegistroHorasLimpiarCampos = () => {
    // let today = new Date();
    // today.setSeconds(0, 0);
    let today = moment();
    $('#modal-abm-cal-registro-id').val(0);
    $('#modal-abm-cal-registro-inicio').val(today.add(-1, 'hours').local().format('YYYY-MM-DDTHH:mm'));
    $('#modal-abm-cal-registro-fin').val(today.add(1, 'hours').local().format('YYYY-MM-DDTHH:mm'));
    $('#modal-abm-cal-registro-fin').attr('max', today.local().format('YYYY-MM-DDTHH:mm'));
    $('#modal-abm-cal-registro-justificacion').val('');
    actualizarDuracion();
}

const actualizarDuracion = () => {
    let fecha_inicio = $('#modal-abm-cal-registro-inicio').val();
    let fecha_fin = $('#modal-abm-cal-registro-fin').val();
    const mInicio = moment(fecha_inicio);
    const mFin = moment(fecha_fin);
    if (mInicio.isValid() && mFin.isValid()) {
        const duration = moment.duration(mFin.diff(mInicio));
        const dhours = parseInt(duration.asHours());
        const dmin = parseInt(duration.asMinutes()) - (dhours * 60);
        $('#modal-abm-cal-registro-duracion').html(`${dhours} h ${dmin} m`);
    } else {
        $('#modal-abm-cal-registro-duracion').html(`Fechas inválidas`);
    }
}

/***************************************************************************************
 * agregar Registro de Hora 
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @author MVGP
 ****************************************************************************************/
const agregarRegistroHoras = () => {
    $('#modal-abm-cal-registro-title').html(`Agregar Registro de horas trabajadas`);
    modalRegistroHorasLimpiarCampos();
    $('#modal-abm-cal-registro-submit').attr('name', 'ADD_REGISTRO_HORAS');
    $("#modal-abm-cal-registro").modal("show");
}



// TODO: Factorizar
// ========================================================================================================================================================
// MANEJO DE EVENTOS DEL CALENDARIO
// ========================================================================================================================================================

/***************************************************************************************
 * Renderizacion Eventos
 * @author MVGP
 ****************************************************************************************/
const eventsRender = info => {
    switch (info.event.extendedProps.tipo) {
        case "1": // DNL
            return dnls.eventRender(info);
            break;
        case "2": // GUARDIAS
            return guardias.eventRender(info);
            break;
        case "4": // REGISTRO HORAS
            return eventRender(info);
            break;

        default:
            break;
    }
    return;
}

/***************************************************************************************
 * Renderizacion de los recursos
 * @author MVGP
 ****************************************************************************************/
const resourceRender = info => {
    const { resource, el } = info;

    el.addEventListener('click', () => {
        console.log(resource);
        if (confirm('Are you sure you want to delete ' + resource.title + '?')) {
            resource.remove();
        }
    });
    return;
}

/***************************************************************************************
 * Obtener los eventos del la persona
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @param number id_persona - area de las personas // FROM DOM
 * @author MVGP
 * @returns {Events[]} - Eventos
 ****************************************************************************************/
const eventsFromPerson = {
    url: './calendar/eventController.php',
    method: 'POST',
    extraParams: {
        action: 'BY_PERSON_ID',
        id: $('#per_id_persona').val()
    },
    error: () => {
        alert('Hubo un error al obtener los eventos de la persona!');
    },
    success: ({ data }) => {
        const events = [];
        $.each(data, (idx, ev) => {
            const Evento = {
                id: ev.id,
                start: ev.fecha_inicio,
                end: ev.fecha_fin,
                allDay: ev.is_all_day == 1,
                title: ev.descripcion,
                textEscape: false,
                //classNames: ['modal-abm-licencia-btn-edit', `ar-tipo-${ev.tipo}`, `subtipo-${ev.subtipo}`, `estado-${ev.estado}`],
                rendering: (ev.is_background == 1 ? 'background' : 'auto'),
                resourceId: ev.tipo,
                extendedProps: {
                    obs: ev.observaciones,
                    tipo: ev.tipo,
                    tipo_desc: ev.tipo_desc,
                    subtipo: ev.subtipo,
                    subtipo_desc: ev.subtipo_desc,
                    icon: ev.icon,
                    real_start: ev.fecha_inicio,
                    real_end: ev.fecha_fin,
                    id_persona: ev.id_persona,
                    justificacion: ev.justificacion,
                    estado: ev.estado
                },
            };
            events.push(Evento);
        });
        return events;
    },
};


/***************************************************************************************
 * Obtiene todos los recursos (personas de un area)
 * @param callback handleData - Callback del calendario
 * @param number area - area de las personas
 * @author MVGP
 * @returns {Resources[]} - Eventos
 ****************************************************************************************/
const getResources = (handleData, area) => {

    let sql = `
    SELECT id, descripcion FROM adm_eventos_tipos
    WHERE borrado = 0 
    AND id IN (2,3,4)
    ORDER BY orden;`;

    $.getJSON("./helpers/getAsyncDataFromDB.php", { query: sql },
        function(response) {
            let res = [];
            $.each(response.data, (idx, resource) => {
                res.push({
                    id: resource.id,
                    title: resource.descripcion,
                });
            });
            handleData(res);
        }
    ).fail(function(jqXHR, errorText) {
        console.log(errorText);
    });
}

/***************************************************************************************
 * Inicialización de calendario
 * @param Date inicio - inicio del rango 
 * @param Date fin - fin del rango 
 * @author MVGP
 ****************************************************************************************/
const initializeCalendar = async(inicio, fin) => {

    // Busco los feriado
    // const dnls = await getDNLs();

    inicio.setDate(today.getDate() - 15);
    fin.setDate(today.getDate() + 15);
    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source', // Licencia Free
        plugins: ['interaction', 'resourceTimeline'], // pluggins
        themeSystem: 'bootstrap',
        now: today, // fecha de hoy
        buttonText: { // traducción de texto
            'today': 'Mes actual'
        },
        eventOverlap: true,
        editable: false, // No permito drag
        aspectRatio: 1, // aspecto
        height: 'auto',
        locale: 'es', // mes en español
        defaultView: 'monthview',
        displayEventTime: false, // sólo días sin hora
        displayEventEnd: false, // sólo días sin hora
        header: { // Configuro los botones del header
            left: 'title',
            right: 'today prev next'
        },
        duration: { months: 1 }, // configuro el tamaño de los pasos prev y next
        views: {
            monthview: {
                type: 'resourceTimeline',
                visibleRange: {
                    start: inicio, // start,
                    end: fin // end,
                },
                slotLabelFormat: [
                    { month: 'long', year: 'numeric' }, // top level of text
                    { day: 'numeric' } // lower level of text
                ],

            }
        },
        // customButtons: customButtons,
        //filterResourcesWithEvents: true,
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigat
        },
        refetchResourcesOnNavigate: false,
        resourceLabelText: 'Eventos',
        resourceAreaWidth: '12%',
        resourceRender: (eventInfo) => resourceRender(eventInfo),
        resources: (fetchInfo, successCallback, failureCallback) =>
            getResources((resources, day) =>
                successCallback(resources), 7), // VER
        eventSources: [
            dnls.eventSource,
            eventsFromPerson
        ],
        eventRender: (eventInfo) => eventsRender(eventInfo),

        dateClick: (e) => console.log(e),
    });

    calendar.render();
    return calendar;
}

const init = (inicio, fin) => {
    initializeCalendar(inicio, fin)
        .then((cal) => {
            calendar = cal;
            $('#modal-abm-cal-registro-submit').on('click', () => submitRegistroHoras('ADD_REGISTRO_HS', () => calendar.refetchEvents()));
            $('#modal-abm-cal-registro-remove').on('click', () => removeRegistroHoras(() => calendar.refetchEvents()));
            $('#modal-abm-registro-btn-add').on('click', agregarRegistroHoras);
            $('#modal-abm-cal-registro-inicio,#modal-abm-cal-registro-fin').on('change', actualizarDuracion)
        });
}

export { init, eventRender }