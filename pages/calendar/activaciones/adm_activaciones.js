import * as dnls from '../dnls.js';

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
 * Renderizacion Evento Guardia
 * @author MVGP
 ****************************************************************************************/
const eventGuardiasRender = info => {
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
        guardias.editarGuardia(info.event);
    })

    $(info.el).css('cursor', 'pointer');
};

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
            return eventGuardiasRender(info);
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
// TODO: pasar a eventsource
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
                resourceId: ev.tipo,
                extendedProps: {
                    obs: ev.observaciones,
                    tipo: ev.tipo,
                    subtipo: ev.subtipo,
                    real_start: ev.fecha_inicio,
                    real_end: ev.fecha_fin,
                    id_persona: ev.id_persona,
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
        eventRender: (eventInfo) => eventRender(eventInfo),

        dateClick: (e) => console.log(e),
    });

    calendar.render();
    return calendar;
}

initializeCalendar(inicio, fin)
    .then((cal) => {
        calendar = cal;
        // $('#modal-abm-cal-guardias-mul-submit').on('click', () => guardias.submitGuardiaMultiple(() => calendar.refetchEvents()));
        // $('#modal-abm-cal-guardias-remove').on('click', () => guardias.removeGuardia(() => calendar.refetchEvents()));
        // $('#modal-abm-cal-guardias-submit').on('click', () => guardias.actualizarGuardia(() => calendar.refetchEvents()));
        // $('#modal-abm-guardias-btn-def').on('click', guardias.agregarGuardiaMultiple);
        // Cambio el titulo del calendar
        // $('.fc-toolbar > .fc-left').html('<H2>Registro de Horas</H2>');
    });