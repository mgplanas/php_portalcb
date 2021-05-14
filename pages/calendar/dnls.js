const eventSource = {
    url: './calendar/eventController.php',
    method: 'POST',
    extraParams: {
        action: 'DNL'
    },
    error: () => {
        alert('there was an error while fetching shedule!');
    },
    success: ({ data }) => {
        const events = [];
        $.each(data, (idx, ev) => {
            const specificClassName = `ar-tipo-${ev.tipo}-subtipo-${ev.subtipo}`;
            const Evento = {
                id: ev.id,
                start: ev.fecha_inicio,
                end: ev.fecha_inicio,
                allDay: true,
                // title: ev.descripcion,
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
        });
        return events;
    },
};

/***************************************************************************************
 * Renderizacion Evento DNL
 * @author MVGP
 ****************************************************************************************/
const eventRender = info => {
    info.el.innerHTML = '<div style="padding: 3px;">' + info.event.title; + '</div>';
};

export { eventSource, eventRender };