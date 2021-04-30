// Calendar instantiation
var calendarEl = document.getElementById('calendar');
var calendar;
var dnls = [];

// Default dates range
var today = new Date();
var inicio = new Date();
var fin = new Date();



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
    SELECT ev.*, per.*
    FROM adm_eventos_cal as ev
    LEFT JOIN persona as per ON ev.id_persona = per.id_persona
    LEFT JOIN subgerencia as sub ON per.subgerencia = sub.id_subgerencia
    LEFT JOIN area as ar ON per.area = ar.id_area
    WHERE NOT (ev.fecha_inicio > '${end}' OR ev.fecha_fin < '${start}')
    AND per.area = ${area}
    AND ev.borrado = 0;`;
    $.getJSON("./helpers/getAsyncDataFromDB.php", { query: sql },
        function(response) {
            let events = [];
            $.each(response.data, (idx, ev) => {
                const Evento = {
                    id: ev.id,
                    backgroundColor: ev.color,
                    color: ev.color,
                    start: ev.fecha_inicio,
                    end: ev.fecha_fin,
                    allDay: ev.is_all_day == 1,
                    title: ev.descripcion,
                    textEscape: false,
                    classNames: ['modal-abm-licencia-btn-edit'],
                    display: (ev.is_background == 1 ? 'background' : 'auto'),
                    extendedProps: {
                        obs: ev.observaciones
                    },
                };
                if (ev.id_persona) {
                    Evento.resourceId = ev.id_persona;
                }
                events.push(Evento);
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
                    children: [
                        { id: resource.id_persona + '_1', title: 'Guardias' },
                        { id: resource.id_persona + '_2', title: 'Activaciones' },
                        { id: resource.id_persona + '_3', title: 'Vacaciones' },
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
 * Obtener los días no laborables desde el año pasado para no tener que ir pidiendo contantemente
 * @author MVGP
 * @returns Date[] - número de días no laborables
 ****************************************************************************************/
const getDNLs = async() => {

    let dnls = [];
    let start = new Date((new Date().getFullYear()) - 1, 0, 1).toISOString().slice(0, 10);
    console.log(start);
    let sql = `
        SELECT fecha_inicio, descripcion
        FROM adm_eventos_cal 
        WHERE fecha_inicio >= '${start}' 
        AND tipo = 1
        AND borrado = 0;`;

    try {
        const { data } = await $.getJSON("./helpers/getAsyncDataFromDB.php", { query: sql });
        data.map(dia => dnls.push(dia));;
        return dnls;

    } catch (error) {
        return dnls;
    }


}

/***************************************************************************************
 * Inicialización de calendario
 * @param Date inicio - inicio del rango 
 * @param Date fin - fin del rango 
 * @author MVGP
 ****************************************************************************************/
const initializeCalendar = async(inicio, fin) => {

    // Busco los feriado
    const dnls = await getDNLs();

    inicio.setDate(today.getDate() - 15);
    fin.setDate(today.getDate() + 15);
    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source', // Licencia Free
        plugins: ['interaction', 'resourceTimeline'], // pluggins
        now: today, // fecha de hoy
        buttonText: { // traducción de texto
            'today': 'Mes actual'
        },
        editable: false, // No permito drag
        aspectRatio: 1, // aspecto
        scrollTime: '00:00',
        height: 'auto',
        locale: 'es', // mes en español
        defaultView: 'monthview',
        displayEventTime: false, // sólo días sin hora
        header: { // Configuro los botones del header
            right: 'today prev,next',
        },
        duration: { months: 1 }, // configuro el tamaño de los pasos prev y next
        dayRender: dateInfo => { // renderizo los días según si es feriado o no
            if (dnls.find(dnl => dnl.fecha_inicio.slice(0, 10) === dateInfo.date.toISOString().slice(0, 10))) dateInfo.el.bgColor = '#cccccc';
        },
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
        dateClick: function(e) {
            console.log(e);
            alert(e);
        },
        selectable: true,
        selectHelper: true,
        selectAllow: selectInfo => selectInfo.resource.id.includes('_1'),
        select: selectInfo => {
            const id_person = selectInfo.resource._resource.parentId;
            const resource = calendar.getTopLevelResources().find(resource => resource.id == id_person);
            alert(`¿Está seguro de querer agregar una guardia a ${resource.title} desde ${selectInfo.startStr} hasta el ${selectInfo.endStr}?`);
        },
    });

    calendar.render();
    return calendar;
}

// getDNLs((dnls) => calendar = initializeCalendar(dnls));
initializeCalendar(inicio, fin);


$(function() {

    // function editarLicencia(lic) {
    //     $('#modal-abm-licencia-title').html('Editar Registro');
    //     // $('#modal-abm-licencia-rowindex').val($(this).parents('tr').index());
    //     modalAbmlicenciaLimpiarCampos();
    //     $('#modal-abm-licencia-id').val(lic.id);
    //     $('#modal-abm-licencia-id-persona').val(lic.idPersona).change();
    //     $('#modal-abm-licencia-inicio').val(lic.inicio);
    //     $('#modal-abm-licencia-fin').val(lic.fin);
    //     $('#modal-abm-licencia-color').val(lic.color);
    //     $('#modal-abm-licencia-obs').val(lic.obs);
    //     $('#modal-abm-licencia-estado').val(lic.status).change();
    //     $('#modal-abm-licencia-delete').show();
    //     $('#modal-abm-licencia-submit').attr('name', 'M');

    //     $("#modal-abm-licencia").modal("show");
    // }

    $('#modal-abm-licencia-btn-alta').on('click', () => {
        calendar.refetchEvents();
    });


});