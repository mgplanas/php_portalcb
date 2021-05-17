import * as dnls from '../calendar/dnls.js';
import * as guardias from '../calendar/guardias/guardias.js';
import * as registroHoras from '../calendar/activaciones/registrohoras.js';


// Calendar instantiation
var calendarEl = document.getElementById('calendar');
var calendar;
var calResources = [];

// Default dates range
var today = new Date();
var inicio = new Date();
var fin = new Date();

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
        case "4": // Registro de horas
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
 * Obtener los eventos del rango seleccionado
 * @param callback handleData - Callback del calendario
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @param number area - area de las personas
 * @author MVGP
 * @returns {Events[]} - Eventos
 ****************************************************************************************/
const getEvents = (handleData, inicio, fin, area) => {
    let start = inicio.toISOString().slice(0, 10);
    let end = fin.toISOString().slice(0, 10);
    let sql = `
    SELECT ev.*, per.nombre, per.apellido, per.cargo
    FROM adm_eventos_cal as ev
    LEFT JOIN persona as per ON ev.id_persona = per.id_persona AND per.area = ${area}
    LEFT JOIN subgerencia as sub ON per.subgerencia = sub.id_subgerencia
    LEFT JOIN area as ar ON per.area = ar.id_area
    WHERE NOT (ev.fecha_inicio > '${end}' OR ev.fecha_fin < '${start}')
    AND ev.borrado = 0
    ORDER BY ev.tipo, ev.subtipo;`;
    $.getJSON("./helpers/getAsyncDataFromDB.php", { query: sql },
        function(response) {
            let events = [];
            $.each(response.data, (idx, ev) => {

                // SI es un evento de feriado
                // TODO: Hacer con eventSources (https://stackoverflow.com/questions/7778318/multiple-eventsources-with-jquery-fullcalendar)
                if (ev.tipo == 1) {
                    const specificClassName = `ar-tipo-${ev.tipo}-subtipo-${ev.subtipo}`;
                    const Evento = {
                        id: ev.id,
                        start: ev.fecha_inicio,
                        end: ev.fecha_inicio,
                        allDay: true,
                        title: ev.descripcion,
                        textEscape: false,
                        classNames: 'fc-nonbusiness',
                        rendering: 'background',
                        extendedProps: {
                            obs: ev.observaciones,
                            tipo: ev.tipo,
                            subtipo: ev.subtipo,
                        },
                    };
                    events.push(Evento);
                } else {
                    const specificClassName = `ar-tipo-${ev.tipo}-subtipo-${ev.subtipo}`;
                    const Evento = {
                        id: ev.id,
                        start: ev.fecha_inicio,
                        end: ev.fecha_fin,
                        allDay: ev.is_all_day == 1,
                        title: ev.descripcion,
                        textEscape: false,
                        classNames: ['modal-abm-licencia-btn-edit', specificClassName],
                        rendering: (ev.is_background == 1 ? 'background' : 'auto'),
                        extendedProps: {
                            obs: ev.observaciones,
                            tipo: ev.tipo,
                            subtipo: ev.subtipo,
                            real_start: ev.fecha_inicio,
                            real_end: ev.fecha_fin,
                            id_persona: ev.id_persona,
                        },
                    };
                    if (ev.id_persona && ev.id_persona > 0) {
                        Evento.resourceId = ev.id_persona;
                        // Evento.resourceId = ev.id_persona + '_' + ev.tipo;
                    }
                    events.push(Evento);
                }
            });
            handleData(events);
        }
    ).fail(function(jqXHR, errorText) {
        console.log(errorText);
    });
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
                    eventBackgroundColor: 'green',
                    eventBorderColor: 'black',
                    eventTextColor: 'white',
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
            editarLicencia({
                id: info.event.id,
                idPersona: info.event.extendedProps.idPersona,
                inicio: info.event.extendedProps.inicio.split("-").reverse().join("/"),
                fin: info.event.extendedProps.fin.split("-").reverse().join("/"),
                color: info.event.extendedProps.color,
                obs: info.event.extendedProps.obs,
                status: info.event.extendedProps.idstatus
            });

        },
        refetchResourcesOnNavigate: false,
        resourceLabelText: 'Personas',
        resourceAreaWidth: '25%',
        resourceRender: (eventInfo) => resourceRender(eventInfo),
        resourceGroupField: 'area',
        resourceOrder: 'area,title',
        resources: (fetchInfo, successCallback, failureCallback) =>
            getResources((resources, day) =>
                successCallback(resources), 3), //FIXME: Poner area correspondiente
        //events: './helpers/adm_calendardb.php?area=1',
        events: (fetchInfo, successCallback, failureCallback) => {
            let start = fetchInfo.start || inicio;
            let end = fetchInfo.end || fin;
            getEvents(events => successCallback(events), start, end, 3); //FIXME: Poner area correspondiente
        },
        eventRender: (eventInfo) => eventRender(eventInfo),

        dateClick: function(e) {
            console.log(e);
            alert(e);
        },
        selectable: true,
        selectHelper: true,
        selectAllow: selectInfo => selectInfo.resource.id.includes('_2'),
        select: selectInfo => {
            const id_person = selectInfo.resource._resource.parentId;
            const resource = calendar.getTopLevelResources().find(resource => resource.id == id_person);
            agregarGuardiaRecurso(resource, selectInfo.start, selectInfo.end);
            //alert(`¿Está seguro de querer agregar una guardia a ${resource.title} desde ${selectInfo.startStr} hasta el ${selectInfo.endStr}?`);
        },
    });

    calendar.render();
    return calendar;
}

initializeCalendar(inicio, fin)
    .then((cal) => {
        calendar = cal;
        guardias.init(calendar);
    });