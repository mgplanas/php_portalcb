import * as dnls from './dnls.js';
import * as guardias from './guardias/guardias.js';
import * as registroHoras from './activaciones/registrohoras.js';
import * as licencias from './licencias/licencias.js';


// Calendar instantiation
var calendarEl = document.getElementById('calendar');

// Default dates range
var today = new Date();

// ========================================================================================================================================================
// MANEJO DE EVENTOS DEL CALENDARIO
// ========================================================================================================================================================

/***************************************************************************************
 * Renderizacion Eventos
 * @author MVGP
 ****************************************************************************************/
const eventRender = info => {
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

    // el.addEventListener('click', () => {
    //     console.log(resource);
    //     if (confirm('Are you sure you want to delete ' + resource.title + '?')) {
    //         resource.remove();
    //     }
    // });
    // return;
}

/***************************************************************************************
 * Obtener los eventos del area
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @param number id_persona - area de las personas // FROM DOM
 * @author MVGP
 * @returns {Events[]} - Eventos
 ****************************************************************************************/
const eventSourceByArea = {
    url: './calendar/eventController.php',
    method: 'POST',
    extraParams: {
        action: 'BY_AREA',
        area: $('#per-area').val()
            // area: $('#per_id_persona').val()
    },
    error: () => {
        alert('Hubo un error al obtener los eventos del area!');
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
                rendering: (ev.is_background == 1 ? 'background' : 'auto'),
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
            if (ev.id_persona && ev.id_persona > 0) Evento.resourceId = ev.id_persona;

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
    SELECT p.id_persona, p.nombre, p.apellido, p.cargo, p.legajo, p.email , sub.nombre as subgerencia, ar.nombre as area
        FROM persona as p 
        LEFT JOIN subgerencia as sub ON p.subgerencia = sub.id_subgerencia
        LEFT JOIN area as ar ON p.area = ar.id_area
    WHERE p.borrado = 0 
    AND p.area = ${area}
    ORDER BY p.apellido;`;

    $.getJSON("./helpers/getAsyncDataFromDB.php", { query: sql },
        function(response) {
            let res = [];
            $.each(response.data, (idx, resource) => {
                res.push({
                    id: resource.id_persona,
                    title: resource.apellido + ', ' + resource.nombre,
                    // eventBackgroundColor: 'green',
                    // eventBorderColor: 'black',
                    // eventTextColor: 'white',
                    // children: [
                    //     { id: resource.id_persona + '_2', title: 'Guardias' },
                    //     { id: resource.id_persona + '_3', title: 'Activaciones' },
                    //     { id: resource.id_persona + '_4', title: 'Licencias' },
                    // ],
                    area: (resource.subgerencia ? resource.subgerencia : 'Sin asignar') + (resource.area ? ' - ' + resource.area : '')
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
            info.jsEvent.preventDefault(); // don't let the browser navigate
        },
        refetchResourcesOnNavigate: false,
        resourceLabelText: 'Personas',
        resourceAreaWidth: '25%',
        resourceRender: (eventInfo) => resourceRender(eventInfo),
        resourceGroupField: 'area',
        resourceOrder: 'area,title',
        resources: (fetchInfo, successCallback, failureCallback) =>
            getResources((resources, day) =>
                successCallback(resources), $('#per-area').val()),
        eventSources: [
            dnls.eventSource,
            eventSourceByArea,
        ],
        eventRender: (eventInfo) => eventRender(eventInfo),

        dateClick: function(e) {
            console.log(e);
            alert(e);
        },
    });

    calendar.render();
    return calendar;
}

const adm_calendar = await initializeCalendar(today, today);
guardias.init(adm_calendar);
registroHoras.init(adm_calendar);
licencias.init(adm_calendar);