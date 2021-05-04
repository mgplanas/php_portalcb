// Calendar instantiation
var calendarEl = document.getElementById('calendar');
var calendar;

// Default dates range
var today = new Date();
var inicio = new Date();
var fin = new Date();


/***************************************************************************************
 * Renderizacion Evento Guardia
 * @author MVGP
 ****************************************************************************************/
const renderEvents = info => {
    // TODO hacer un switch por tipo de evento (evento.extendedProps.tipo/subtipo)
    //eventInfo.el.innerHTML = '<div class="ar-guardia">' + eventInfo.event.title + '<br><small>pepepeep</small></div>';
    // console.log($(eventInfo.el));
    // console.log($(eventInfo.el).parent());
    // console.log($(eventInfo.el).parent().parent());
    //$(eventInfo.el).closest('div')
    if (info.event.rendering === 'background') {
        info.el.innerHTML = '<div style="padding: 3px;">' + info.event.title; + '</div>'
    }
    $(info.el).popover({
        title: info.event.title,
        placement: 'top',
        trigger: 'hover',
        content: info.event.title + ":" + info.event.start + " to " + info.event.end,
        container: 'body'
    }).popover('show');
    return;
}


/***************************************************************************************
 * Guardar guardia
 * @author MVGP
 ****************************************************************************************/
const submitGuardia = () => {
    let operacion = $('#modal-abm-cal-guardias-submit').attr('name');
    let id = $('#modal-abm-cal-guardias-id').val();
    let id_persona = $('#modal-abm-cal-guardias-id-persona').val();
    let subtipo = $('#modal-abm-cal-guardias-tipo').val();
    let color = $('#modal-abm-cal-guardias-tipo option:selected').data('color');
    let fecha_inicio = $('#modal-abm-cal-guardias-inicio').val().split('/').reverse().join("-");
    let fecha_fin = $('#modal-abm-cal-guardias-fin').val().split('/').reverse().join("-");
    let descripcion = $('#modal-abm-cal-guardias-tipo option:selected').text();
    let observaciones = $('#modal-abm-cal-guardias-observaciones').val();
    // Ejecuto
    $.ajax({
        type: 'POST',
        url: './helpers/adm_calendar_eventsdb.php',
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
            calendar.refetchEvents();
        },
        error: (xhr, status, error) => {
            alert(xhr.responseText, error);
            calendar.refetchEvents();
        }
    });

}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const modalABMGuardiasLimpiarCampos = () => {
    $('#modal-abm-cal-guardias-id').val(0);
    $('#modal-abm-cal-guardias-id-persona').val('');
    $('#modal-abm-cal-guardias-tipo').val(1).change();
    $('#modal-abm-cal-guardias-inicio').val('');
    $('#modal-abm-cal-guardias-fin').val('');
    $('#modal-abm-cal-guardias-observaciones').val('');
}

/***************************************************************************************
 * agregar Guardia para un recurso
 * @param Resource resource - Recurso del calendario que representa a la persona
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @author MVGP
 ****************************************************************************************/
const agregarGuardiaRecurso = (resource, inicio, fin) => {
    $('#modal-abm-cal-guardias-title').html(`${resource.title} <small>agregar período de guardia</small>`);
    modalABMGuardiasLimpiarCampos();
    $('#modal-abm-cal-guardias-id-persona').val(resource.id);
    $('#modal-abm-cal-guardias-inicio').val(inicio.toISOString().slice(0, 10).split('-').reverse().join('/'));
    $('#modal-abm-cal-guardias-fin').val(fin.toISOString().slice(0, 10).split('-').reverse().join('/'));
    $('#modal-abm-cal-guardias-submit').attr('name', 'A');

    $("#modal-abm-cal-guardias").modal("show");
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
    AND ev.borrado = 0;`;
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
                        },
                    };
                    if (ev.id_persona && ev.id_persona > 0) {
                        // Evento.resourceId = ev.id_persona;
                        Evento.resourceId = ev.id_persona + '_' + ev.tipo;
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
                    children: [
                        { id: resource.id_persona + '_2', title: 'Guardias' },
                        { id: resource.id_persona + '_3', title: 'Activaciones' },
                        { id: resource.id_persona + '_4', title: 'Licencias' },
                    ],
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
const initializeCalendar = (inicio, fin) => {

    // Busco los feriado
    // const dnls = await getDNLs();

    inicio.setDate(today.getDate() - 15);
    fin.setDate(today.getDate() + 15);
    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source', // Licencia Free
        plugins: ['interaction', 'resourceTimeline'], // pluggins
        now: today, // fecha de hoy
        buttonText: { // traducción de texto
            'today': 'Mes actual'
        },
        eventOverlap: true,
        editable: false, // No permito drag
        aspectRatio: 1, // aspecto
        scrollTime: '00:00',
        height: 'auto',
        locale: 'es', // mes en español
        defaultView: 'monthview',
        displayEventTime: false, // sólo días sin hora
        header: { // Configuro los botones del header
            right: 'today prev,next'
        },
        duration: { months: 1 }, // configuro el tamaño de los pasos prev y next
        // dayRender: dateInfo => { // renderizo los días según si es feriado o no
        //     if (dnls.find(dnl => dnl.fecha_inicio.slice(0, 10) === dateInfo.date.toISOString().slice(0, 10))) dateInfo.el.bgColor = '#cccccc';
        // },
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
        // customButtons: {
        //     custButtonPrev: {
        //         icon: 'chevron-left',
        //         click: () => {
        //             calendar.prev();
        //         }
        //     },
        //     custButtonNext: {
        //         icon: 'chevron-right',
        //         click: () => calendar.next()
        //     }
        // },
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
        resourceRender: function(arg) {
            var resource = arg.resource;

            arg.el.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete ' + resource.title + '?')) {
                    resource.remove();
                }
            });
        },
        resourceGroupField: 'area',
        resourceOrder: 'area,id,title',
        resources: (fetchInfo, successCallback, failureCallback) =>
            getResources((resources, day) =>
                successCallback(resources), 7), // VER
        //events: './helpers/adm_calendardb.php?area=1',
        events: (fetchInfo, successCallback, failureCallback) => {
            let start = fetchInfo.start || inicio;
            let end = fetchInfo.end || fin;
            getEvents(events => successCallback(events), start, end, 7);
        },
        eventRender: (eventInfo) => renderEvents(eventInfo),
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

calendar = initializeCalendar(inicio, fin);


$(function() {

    $('#modal-abm-cal-guardias-submit').on('click', submitGuardia);
    $('#modal-abm-licencia-btn-alta').on('click', () => {
        calendar.refetchEvents();
    });


});