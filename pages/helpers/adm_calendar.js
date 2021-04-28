$(function() {

    function getEvents(handleData, inicio, fin) {

        let start = inicio.toISOString().slice(0, 10);
        let end = fin.toISOString().slice(0, 10);
        let sql = `
        SELECT ev.*, per.*
        FROM adm_eventos_cal as ev
        LEFT JOIN persona as per ON ev.id_persona = per.id_persona
        LEFT JOIN subgerencia as sub ON per.subgerencia = sub.id_subgerencia
        LEFT JOIN area as ar ON per.area = ar.id_area
        WHERE NOT (ev.fecha_inicio > '${end}' OR ev.fecha_fin < '${start}')
        AND ev.borrado = 0;`;
        $.getJSON("./helpers/getAsyncDataFromDB.php", { query: sql },
            function(response) {
                let events = [];
                $.each(response.data, (idx, ev) => {
                    const Evento = {
                        id: ev.id,
                        backgroundColor: ev.color,
                        color: ev.color,
                        resourceId: (ev.id_persona || '') + '_1',
                        start: ev.fecha_inicio,
                        end: ev.fecha_fin,
                        allDay: ev.is_all_day === 1,
                        title: ev.descripcion,
                        extendedProps: {
                            obs: ev.observaciones
                        },
                        textEscape: false,
                        classNames: ['modal-abm-licencia-btn-edit']
                    };
                    events.push(Evento);

                    // console.log(this.id_persona)
                });
                handleData(events);
                // calendar.refetchResources();
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }

    function getResources(handleData, inicio, fin) {
        let start = inicio.toISOString().slice(0, 10);
        let end = fin.toISOString().slice(0, 10);
        let sql = `
        SELECT p.id_persona, p.nombre, p.apellido, p.cargo, p.legajo, p.email , sub.nombre as subgerencia, ar.nombre as area
            FROM persona as p 
            LEFT JOIN subgerencia as sub ON p.subgerencia = sub.id_subgerencia
            LEFT JOIN area as ar ON p.area = ar.id_area
        WHERE p.borrado = 0 
        AND p.gerencia IN (1,2)
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

                    // console.log(this.id_persona)
                });
                handleData(res);
                // calendar.refetchResources();
            }
        ).fail(function(jqXHR, errorText) {
            console.log(errorText);
        });
    }


    function editarLicencia(lic) {
        $('#modal-abm-licencia-title').html('Editar Registro');
        // $('#modal-abm-licencia-rowindex').val($(this).parents('tr').index());
        modalAbmlicenciaLimpiarCampos();
        $('#modal-abm-licencia-id').val(lic.id);
        $('#modal-abm-licencia-id-persona').val(lic.idPersona).change();
        $('#modal-abm-licencia-inicio').val(lic.inicio);
        $('#modal-abm-licencia-fin').val(lic.fin);
        $('#modal-abm-licencia-color').val(lic.color);
        $('#modal-abm-licencia-obs').val(lic.obs);
        $('#modal-abm-licencia-estado').val(lic.status).change();
        $('#modal-abm-licencia-delete').show();
        $('#modal-abm-licencia-submit').attr('name', 'M');

        $("#modal-abm-licencia").modal("show");
    }

    function refreshCalendar(day) {

        var calendarEl = document.getElementById('calendar');

        var today = day;
        var inicio = new Date();
        var fin = new Date();
        let start = inicio.setDate(day.getDate() - 15);
        let end = fin.setDate(day.getDate() + 15);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            plugins: ['interaction', 'resourceTimeline'],
            now: today,
            editable: false,
            aspectRatio: 1,
            scrollTime: '00:00',
            height: 'auto',
            header: false,
            locale: 'es',
            defaultView: 'monthview',
            displayEventTime: false,
            //filterResourcesWithEvents: true,
            views: {
                monthview: {
                    type: 'resourceTimeline',
                    //   duration: { months: 3 },
                    buttonText: 'Centrado',
                    visibleRange: {
                        start: start,
                        end: end,
                    },
                    slotLabelFormat: [
                        { month: 'long', year: 'numeric' }, // top level of text
                        // { weekday: 'short', day: 'numeric' } // lower level of text
                        { day: 'numeric' } // lower level of text
                    ]
                }
            },
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
            resources: function(fetchInfo, successCallback, failureCallback) {
                getResources(function(resources, day) {
                    successCallback(resources);
                }, inicio, fin);
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                getEvents(function(events) {
                    successCallback(events);
                }, inicio, fin);
            },
            dateClick: function(e) {
                console.log(e);
                alert(e);
            }
        });

        calendar.render();
    }


    // ==============================================================
    // AUXILIARES
    // ==============================================================

    refreshCalendar(new Date());
});