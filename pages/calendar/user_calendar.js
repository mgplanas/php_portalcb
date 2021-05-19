import * as registroHoras from './activaciones/registrohoras.js';
import * as guardias from './guardias/guardias.js';
import * as licencias from './licencias/licencias.js';
import * as dnls from './dnls.js';

// ========================================================================================================================================================
// MANEJO DE EVENTOS DEL CALENDARIO
// ========================================================================================================================================================
// Calendar instantiation
var calendarEl = document.getElementById('calendar');
// Default dates range
var today = new Date();


var eventUpdatedSubscribers = [];
const subscribeToEventUpdate = f => eventUpdatedSubscribers.push(f);
const eventsUpdated = events => eventUpdatedSubscribers.forEach(f => f(events));


/***************************************************************************************
 * Obtener los eventos del la persona
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @param number id_persona - area de las personas // FROM DOM
 * @author MVGP
 * @returns {Events[]} - Eventos
 ****************************************************************************************/
const eventSourceFromPerson = {
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
        eventsUpdated(data);
        return events;
    },
};


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
        case "3": // LICENCIAS
            return licencias.eventRender(info);
            break;
        case "4": // REGISTRO HORAS
            return registroHoras.eventRender(info);
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
            eventSourceFromPerson
        ],
        eventRender: (eventInfo) => eventsRender(eventInfo),

        dateClick: (e) => console.log(e),
    });

    calendar.render();
    return calendar;
}




const user_calendar = await initializeCalendar(today, today);
registroHoras.init(user_calendar);
licencias.init(user_calendar);

subscribeToEventUpdate(registroHoras.eventsUpdated);
subscribeToEventUpdate(licencias.eventsUpdated);